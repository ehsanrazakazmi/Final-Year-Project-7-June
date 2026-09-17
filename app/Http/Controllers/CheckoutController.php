<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function stripeCheckout(Request $request)
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'pending',
        ]);

        foreach (session()->get('cart') as $item) {
            $order->items()->create([
                'service_id' => $item['product']['id'],
                'availability_id' => $item['availability']['id'],
                'category_id' => $item['category_id'],
                'quantity' => $item['quantity'],

            ]);
        }

        session()->forget('cart');

        return view('pages.success', ['order' => $order]);
    }
}
