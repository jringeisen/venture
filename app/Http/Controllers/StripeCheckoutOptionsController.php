<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class StripeCheckoutOptionsController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('StripeCheckout/StripeCheckoutOptions');
    }
}
