<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Donation;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Landing/Index', [
            'blogCount' => BlogPost::count(),
            'paymentLinks' => config('services.stripe.payment_links'),
            'sumDonations' => (int) Donation::sum('amount'),
        ]);
    }
}
