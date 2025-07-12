<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Events\OrderPlaced;

class ShopController extends Controller
{
    public function home() {
        $categories = Category::all();
        $products = Product::latest()->take(8)->get();
        return view('shop.home', compact('categories', 'products'));
    }
    public function category($id) {
        $category = Category::findOrFail($id);
        $products = $category->products()->get();
        return view('shop.category', compact('category', 'products'));
    }
    public function product($id) {
        $product = Product::findOrFail($id);
        return view('shop.product', compact('product'));
    }
    public function cart() {
        $cartItems = $this->getCartItems();
        return view('shop.cart', compact('cartItems'));
    }
    public function addToCart(Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $userId = Auth::id();
        $sessionId = session()->getId();
        $cart = Cart::firstOrNew([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $request->product_id,
        ]);
        $cart->quantity += $request->quantity;
        $cart->save();
        return back()->with('status', 'Added to cart!');
    }
    public function updateCart(Request $request) {
        $request->validate([
            'cart_id' => 'required|exists:cart,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = Cart::findOrFail($request->cart_id);
        $cart->quantity = $request->quantity;
        $cart->save();
        return back()->with('status', 'Cart updated!');
    }
    public function removeFromCart(Request $request) {
        $request->validate([
            'cart_id' => 'required|exists:cart,id',
        ]);
        $cart = Cart::findOrFail($request->cart_id);
        $cart->delete();
        return back()->with('status', 'Removed from cart!');
    }
    private function getCartItems() {
        $userId = Auth::id();
        $sessionId = session()->getId();
        return Cart::with('product')
            ->where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->get();
    }
    public function checkout() {
        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('status', 'Your cart is empty.');
        }
        return view('shop.checkout', compact('cartItems'));
    }
    public function processCheckout(Request $request) {
        $cartItems = $this->getCartItems();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('status', 'Your cart is empty.');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'address' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $cartItems->sum(function($item) {
                    return ($item->product->sale_price ?: $item->product->price) * $item->quantity;
                }),
                'status' => 'pending',
            ]);
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->sale_price ?: $item->product->price,
                ]);
            }
            // Clear cart
            $userId = Auth::id();
            $sessionId = session()->getId();
            Cart::where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->delete();
            DB::commit();
            event(new OrderPlaced($order));
            // TODO: Integrate Razorpay payment and WhatsApp confirmation
            return redirect()->route('thankyou');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('status', 'Order failed: ' . $e->getMessage());
        }
    }
    public function thankYou() { return view('shop.thankyou'); }
} 