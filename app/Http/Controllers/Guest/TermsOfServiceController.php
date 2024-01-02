<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class TermsOfServiceController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Guest/TermsOfService');
    }
}
