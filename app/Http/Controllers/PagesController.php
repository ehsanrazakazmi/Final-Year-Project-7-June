<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    public function home()
    {
        $pproducts = Services::with('category','availabilities')->orderBy('created_at', 'desc')->get();
        return view('pages.home', ['products'=> $pproducts]);
    }
    // public function public()
    // {
    //     return view('public');
    // }

    public function cart()
    {
       // dd(session()->get('cart'));
       return view('pages/cart');
    }
    public function success()
    {
       // dd(session()->get('cart'));
       return view('pages/success');
    }
    public function wishlist()
    {
        $products = Auth::User()->wishlist;
        return view('pages.wishlist', ['products' => $products ]);
    }
    public function account()
    {
        return view('pages.account');
    }

    public function checkout()
    {
        
        return view('pages.checkout');
    }
    
    public function product($id)
    {
        $product =Services::with('category', 'availabilities')->findOrFail($id);
        return view('pages.product', ['product' => $product]);
    }
}
