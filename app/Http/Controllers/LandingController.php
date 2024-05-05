<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Donation;
use Illuminate\Support\Number;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function __invoke()
    {
        $donations = Donation::sumAmountByPaymentLink();

        return Inertia::render('Landing/Index', [
            'blogCount' => BlogPost::count(),
            'paymentLinks' => config('services.stripe.payment_links'),
            'totalSumDonations' => Number::currency(Donation::totalSum() / 100),
            'causes' => collect(config('causes'))->map(function ($cause) use ($donations) {
                return array_merge($cause, [
                    'raised' => Number::currency($donations->where('payment_link', $cause['plink'])->first()?->amount / 100 ?? 0),
                    'progress' => Number::percentage((($donations->where('payment_link', $cause['plink'])->first()?->amount / 100) / $cause['goalValue']) * 100),
                ]);
            }),
        ]);
    }
}
