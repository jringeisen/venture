<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadPlannerController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->download(storage_path('app/public/student-planner.zip'));
    }
}
