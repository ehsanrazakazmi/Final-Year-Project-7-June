<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Services;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {

        $product = Services::findOrFail($id);
        $availability = Availability::findOrFail($request->availability);

        $item = [
            'product' => $product,
            'quantity' => $request->quantity,
            'availability' => $availability,
            'category_id' => $product->category_id,
        ];

        if (session()->has('cart')) {
            //already exists, increment the quantity
            $cart = session()->get('cart');
            $key = $this->checkItemInCart($item);

            if ($key != -1) {
                $cart[$key]['quantity'] += $request->quantity;
                session()->put('cart', $cart);
            } else {
                session()->push('cart', $item);
            }
        } else {
            session()->push('cart', $item);
        }
        return back()->with('addedToCart', 'Success! Order has been added to cart');
    }

    public function checkItemInCart($item)
    {
        foreach (session()->get('cart') as $key => $val) {
            if ($val['product']['id'] == $item['product']['id'] && $val['availability']['id'] == $item['availability']['id']) {
                return $key;
            }
        }
        return -1;
    }






    public function removeFromCart($key)
    {



        if (session()->has('cart')) {
            //already exists, increment the quantity
            $cart = session()->get('cart');
            array_splice($cart, $key, 1);
            session()->put('cart', $cart);
            return back()->with('success', 'Success! Order rempved from cart');
        }

        return back();
    }
}
