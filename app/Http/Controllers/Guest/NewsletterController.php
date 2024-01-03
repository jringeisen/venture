<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterRequest;
use App\Models\NewsletterList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterRequest $request): RedirectResponse
    {
        NewsletterList::create(['email' => $request->email]);

        return to_route('planner');
    }

    public function unsubscribe(Request $request, NewsletterList $newsletterList): Response
    {
        $newsletterList->update(['is_subscribed' => false]);

        return Inertia::render('Guest/Newsletter/Unsubscribe');
    }
}
