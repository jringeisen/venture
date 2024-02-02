<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Landing/Index', [
            'blogCount' => BlogPost::count(),
        ]);
    }
}
