<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('jobs')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:categories'],
            'icon'  => ['nullable', 'string', 'max:10'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        Category::create([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'icon'  => $request->icon ?? '💼',
            'color' => $request->color ?? '#7B5EA7',
        ]);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'icon'  => ['nullable', 'string', 'max:10'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $category->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'icon'  => $request->icon,
            'color' => $request->color,
        ]);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        abort_if($category->jobs()->count() > 0, 422, 'Cannot delete category with active jobs.');
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}