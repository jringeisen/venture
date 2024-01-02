<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PlannerController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Guest/Planner');
    }
}
