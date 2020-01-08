<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function getCheckout()
    {
        $cart = null;

        return view('checkout.checkout', compact('cart'));
    }
}
