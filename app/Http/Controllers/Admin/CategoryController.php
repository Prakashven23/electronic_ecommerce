<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }
    public function create() {
        return view('admin.categories.create');
    }
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('status', 'Category created!');
    }
    public function edit(Category $category) {
        return view('admin.categories.edit', compact('category'));
    }
    public function update(Request $request, Category $category) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('status', 'Category updated!');
    }
    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('status', 'Category deleted!');
    }
} 