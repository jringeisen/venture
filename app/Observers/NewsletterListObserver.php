<?php

namespace App\Observers;

use App\Mail\OptInMail;
use App\Models\NewsletterList;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NewsletterListObserver
{
    public function created(NewsletterList $newsletterList): void
    {
        $temporaryUrl = Storage::temporaryUrl('/downloads/student-planner.zip', now()->addHours(48));

        Mail::to($newsletterList->email)->queue(new OptInMail($newsletterList, $temporaryUrl));
    }
}
