<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/BlogCategories/Index', [
            'categories' => BlogCategory::withCount('posts')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/BlogCategories/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        BlogCategory::create($validated);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $blogCategory): Response
    {
        return Inertia::render('Admin/BlogCategories/Edit', [
            'category' => $blogCategory,
        ]);
    }

    public function update(Request $request, BlogCategory $blogCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $blogCategory->update($validated);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory): RedirectResponse
    {
        $blogCategory->delete();

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
