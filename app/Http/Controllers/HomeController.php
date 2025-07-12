<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Offer;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::where('featured', true)->take(8)->get();
        $bestSellers = Product::bestSellers(8)->get();
        $offers = Offer::where('active', true)->get();
        
        return view('home', compact('categories', 'featuredProducts', 'bestSellers', 'offers'));
    }
} 