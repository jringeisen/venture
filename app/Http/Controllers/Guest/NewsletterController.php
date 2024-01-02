<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterRequest;
use App\Models\NewsletterList;

class NewsletterController extends Controller
{
    public function store(NewsletterRequest $request)
    {
        NewsletterList::create(['email' => $request->email]);

        return to_route('planner');
    }
}
