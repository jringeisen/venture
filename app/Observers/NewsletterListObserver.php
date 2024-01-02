<?php

namespace App\Observers;

use App\Mail\OptInMail;
use App\Models\NewsletterList;
use Illuminate\Support\Facades\Mail;

class NewsletterListObserver
{
    public function created(NewsletterList $newsletterList): void
    {
        Mail::to($newsletterList->email)->queue(new OptInMail($newsletterList));
    }
}
