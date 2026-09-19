<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function stripeCheckout(Request $request)
    {
        $cart = session()->get('cart', []);

        // Checking out an empty cart used to create an order with no items and
        // a zero total - 9 of the first 11 orders were created that way.
        if (empty($cart)) {
            return redirect()->route('cart')
                ->with('error', 'Your cart is empty.');
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'pending',
        ]);

        $total = 0;

        foreach ($cart as $item) {
            $quantity = (int) $item['quantity'];
            $price = (float) ($item['product']['price'] ?? 0);

            $order->items()->create([
                'service_id' => $item['product']['id'],
                'availability_id' => $item['availability']['id'],
                'category_id' => $item['category_id'],
                'quantity' => $quantity,
            ]);

            $total += $price * $quantity;
        }

        // Stored at checkout rather than derived on read, so the figure stays
        // what the resident actually ordered even if the service price changes
        // later. `orders.total` was previously never written at all.
        $order->update(['total' => $total]);

        session()->forget('cart');

        return view('pages.success', ['order' => $order]);
    }
}
