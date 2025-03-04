<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
{
    $user_id = Auth::id();
    $categories = Category::where('user_id', $user_id)->orderBy('name')->get();
    
    return view('categories.index', compact('categories'));
}

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'color' => 'required|string|size:7|starts_with:#',
        'description' => 'nullable|string',
    ]);

    // Add user_id to validated data
    $validated['user_id'] = Auth::id();

    // Create category
    Category::create($validated);

    return redirect()->route('categories.index')
        ->with('success', 'Category created successfully.');
}

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        // Check if the category belongs to the user
        if (Auth::id() !== $category->user_id) {
            abort(403);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Check if the category belongs to the user
        if (Auth::id() !== $category->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|size:7|starts_with:#',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if the category belongs to the user
        if (Auth::id() !== $category->user_id) {
            abort(403);
        }

        // Delete the category (related tasks will have their category_id set to null)
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}