<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DownloadPlannerController extends Controller
{
    public function __invoke()
    {
        $file = Storage::disk('s3')->temporaryUrl('/downloads/student-planner.zip', now()->addHours(1));

        return response()->download($file);
    }
}
