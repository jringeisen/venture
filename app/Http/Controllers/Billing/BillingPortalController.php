<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingPortalController extends Controller
{
    public function __invoke(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('parent.dashboard'));
    }
}
