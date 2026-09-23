<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TechOrderNotification;
use Illuminate\Support\Facades\Notification;

class TechnicianController extends Controller
{
    public function intro()
    {
        return view('technician.pages.introduction');
    }
    public function dashboard()
    {
        $categ_id = auth()->user()->category_id; // Replace with your variable value
        // $order = DB::select('SELECT * from orders WHERE status = ? AND category_id = ?', ['shipped', $categ_id]);

        $order = DB::select('SELECT o.* FROM orders AS o
        INNER JOIN items AS i ON o.id = i.order_id
        WHERE o.status = ? AND i.category_id = ?', ['shipped', $categ_id]);

        return view('technician.pages.dashboard')->with(['order' => $order]);
    }
    /**
     * A technician may only open a job in their own trade.
     *
     * The dashboard lists their category, but the id came straight off the
     * URL with no check, so changing the number in the address bar showed
     * any other trade's job - including the resident's name, phone and
     * address. ownedOrder() is the single place that check lives.
     */
    public function view($id)
    {
        $states = ['accepted'];
        $order = $this->ownedOrder($id);

        return view('technician.pages.view', ['order' => $order, 'states' => $states]);
    }

    /**
     * Load an order, or 404 if it is not in the signed-in technician's trade.
     *
     * 404 rather than 403 on purpose: a technician has no business learning
     * whether an order id they cannot act on exists.
     */
    protected function ownedOrder($id): Order
    {
        $order = Order::with('user', 'items', 'items.services', 'items.availability')
            ->findOrFail($id);

        $inTrade = $order->items->contains(
            fn ($item) => (int) $item->category_id === (int) auth()->user()->category_id
        );

        abort_unless($inTrade, 404);

        return $order;
    }
    /*
     * store() lived here. It was reachable at POST /technicianpanel but was
     * broken three ways:
     *
     *   - it called TechOrderNotification($request->id) with an int, while
     *     that notification takes (Order, User) - a TypeError, so a 500;
     *   - it sent that notification to User::all(), pushing one resident's
     *     order into every other resident's and technician's bell;
     *   - it never actually changed the order's status.
     *
     * The only markup that posted to it sits inside the commented-out block
     * in technician/pages/dashboard.blade.php. updateStatus() below is the
     * live path. Removed rather than repaired.
     */

    public function confirmed()
    {
        $order = order::all();
        return view('technician.pages.confirmed')->with(['order' => $order]);
    }
    public function updateStatus($id)
    {
        $order = $this->ownedOrder($id);
        $order->update(['status' => 'accepted']);

        // Notify administrators only. The old call in store() used User::all(),
        // which pushed the notification to residents and technicians too.
        $admins = User::role('admin')->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new TechOrderNotification($order, Auth::user()));
        }

        return back()->with('success', 'Order accepted. The admin has been notified.');
    }

    public function chat()
    {
        return view('technician.pages.chat');
    }
}
