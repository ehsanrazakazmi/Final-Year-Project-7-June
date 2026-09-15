<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')-> orderBy('created_at', 'desc')->get();
        return view('laravel-examples/orders/index', ['orders' => $orders]);
    }

    public function view($id)
    {
        $states = ['pending', 'processing', 'shipped', 'cancelled'];
        $order = Order::with('user', 'items', 'items.services', 'items.color')-> findOrFail($id);
        return view('laravel-examples/orders/view', ['order' => $order, 'states' => $states]);
    }
    public function updateStatus($id, Request $request)
    {
        Order::findOrFail($id)->update(['status' => $request->status]);
        // return back()->with('success', 'Products Updated!');
        return redirect('/orders/')->with('success', 'Products Updated!');

    }

}
