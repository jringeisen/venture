<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeCheckoutController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->plan === 'monthly') {
            $price = config('app.stripe.prices.monthly');
        } else {
            $price = config('app.stripe.prices.annual');
        }

        return $request->user()
            ->newSubscription('default', $price)
            ->quantity($request->student_count)
            ->allowPromotionCodes()
            ->checkout([
                'success_url' => route('subscription.checkout.success'),
                'cancel_url' => route('subscription.checkout.options'),
            ]);
    }
}
