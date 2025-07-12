<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Category;

class OfferController extends Controller
{
    public function index() {
        $offers = Offer::with(['product', 'category'])->get();
        return view('admin.offers.index', compact('offers'));
    }

    public function create() {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.offers.create', compact('products', 'categories'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:offers,code',
            'discount_type' => 'required|in:percentage,amount',
            'discount_value' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percentage' && ($value < 0 || $value > 100)) {
                        $fail('Percentage discount must be between 0 and 100.');
                    }
                    if ($request->discount_type === 'amount' && $value < 0) {
                        $fail('Amount discount must be at least 0.');
                    }
                }
            ],
            'scope' => 'required|in:category,product',
            'category_id' => 'nullable|required_if:scope,category|exists:categories,id',
            'product_id' => 'nullable|required_if:scope,product|exists:products,id',
            'minimum_order_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'active' => 'boolean',
        ]);

        $data['active'] = $request->has('active');
        Offer::create($data);
        return redirect()->route('admin.offers.index')->with('status', 'Offer created successfully!');
    }

    public function edit(Offer $offer) {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.offers.edit', compact('offer', 'products', 'categories'));
    }

    public function update(Request $request, Offer $offer) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:offers,code,' . $offer->id,
            'discount_type' => 'required|in:percentage,amount',
            'discount_value' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percentage' && ($value < 0 || $value > 100)) {
                        $fail('Percentage discount must be between 0 and 100.');
                    }
                    if ($request->discount_type === 'amount' && $value < 0) {
                        $fail('Amount discount must be at least 0.');
                    }
                }
            ],
            'scope' => 'required|in:category,product',
            'category_id' => 'nullable|required_if:scope,category|exists:categories,id',
            'product_id' => 'nullable|required_if:scope,product|exists:products,id',
            'minimum_order_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'active' => 'boolean',
        ]);

        $data['active'] = $request->has('active');
        $offer->update($data);
        return redirect()->route('admin.offers.index')->with('status', 'Offer updated successfully!');
    }

    public function destroy(Offer $offer) {
        $offer->delete();
        return redirect()->route('admin.offers.index')->with('status', 'Offer deleted successfully!');
    }
} 