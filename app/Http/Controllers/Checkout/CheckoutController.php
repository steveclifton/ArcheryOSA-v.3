<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function getCheckout()
    {
        $cart = Auth::user()->getcart();

        $cartitems = $cart->getcartitems();

        dd($cartitems);

        return view('checkout.checkout', compact('cart', 'cartitems'));
    }
}
