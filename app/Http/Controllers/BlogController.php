<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Guest/Blog/Index', [
            'posts' => BlogPost::with('user', 'category')
                ->published()
                ->when($request->category, function ($query) use ($request) {
                    $query->whereHas('category', function ($query) use ($request) {
                        $query->where('slug', $request->category);
                    });
                })
                ->latest()
                ->get(),
            'categories' => BlogCategory::hasPublishedPosts()->get(),
        ]);
    }

    public function show(BlogPost $blogPost)
    {
        return Inertia::render('Guest/Blog/Show', [
            'post' => $blogPost->load('user', 'category'),
        ]);
    }
}
