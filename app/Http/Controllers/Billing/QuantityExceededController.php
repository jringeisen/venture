<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class QuantityExceededController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Teachers/Billing/QuantityExceeded');
    }
}
