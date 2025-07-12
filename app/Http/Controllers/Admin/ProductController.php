<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index() {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }
    public function create() {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'image' => 'nullable|image',
            'category_id' => 'required|exists:categories,id',
            'featured' => 'boolean',
        ]);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        // Handle featured checkbox
        $data['featured'] = $request->has('featured');
        
        Product::create($data);
        return redirect()->route('admin.products.index')->with('status', 'Product created!');
    }
    public function edit(Product $product) {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }
    public function update(Request $request, Product $product) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'image' => 'nullable|image',
            'category_id' => 'required|exists:categories,id',
            'featured' => 'boolean',
        ]);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        // Handle featured checkbox
        $data['featured'] = $request->has('featured');
        
        $product->update($data);
        return redirect()->route('admin.products.index')->with('status', 'Product updated!');
    }
    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('admin.products.index')->with('status', 'Product deleted!');
    }
} 