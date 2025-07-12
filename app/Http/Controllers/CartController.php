<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session('cart', []);
        $cart = [];
        $total = 0;
        foreach ($cartItems as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $originalPrice = $product->price;
                $salePrice = ($product->sale_price && $product->sale_price > 0) ? $product->sale_price : $originalPrice;
                $cart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'original_price' => $originalPrice,
                    'sale_price' => $salePrice,
                    'subtotal' => $salePrice * $quantity
                ];
                $total += $salePrice * $quantity;
            }
        }
        return view('cart.index', compact('cart', 'total'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = session('cart', []);
        $productId = $request->product_id;
        
        if (isset($cart[$productId])) {
            $cart[$productId] += $request->quantity;
        } else {
            $cart[$productId] = $request->quantity;
        }
        
        session(['cart' => $cart]);
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = session('cart', []);
        $productId = $request->product_id;
        
        if ($request->quantity > 0) {
            $cart[$productId] = $request->quantity;
        } else {
            unset($cart[$productId]);
        }
        
        session(['cart' => $cart]);
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        
        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);
        
        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }
} 