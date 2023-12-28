<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeCheckoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $price = $request->price;
        $quantity = $request->quantity;

        return $request->user()
            ->newSubscription('default', $price)
            ->quantity($quantity)
            ->allowPromotionCodes()
            ->checkout([
                'success_url' => 'https://venture.test/success',
                'cancel_url' => route('dashboard'),
            ]);
    }
}
