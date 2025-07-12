<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Offer;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $cart = [];
        $subtotal = 0;
        $total = 0;
        $discount = 0;
        // Get all active, non-expired offers, latest first
        $offers = Offer::where('active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderByDesc('created_at')
            ->get();
        foreach ($cartItems as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product) continue;
            $originalPrice = $product->price;
            $salePrice = ($product->sale_price && $product->sale_price > 0) ? $product->sale_price : $originalPrice;
            $appliedOffer = null;
            $discountValue = 0;
            // Find latest applicable offer (product-wise first, then category-wise)
            $offer = $offers->first(function($o) use ($product) {
                return $o->scope === 'product' && $o->product_id == $product->id;
            });
            if (!$offer && $product->category_id) {
                $offer = $offers->first(function($o) use ($product) {
                    return $o->scope === 'category' && $o->category_id == $product->category_id;
                });
            }
            if ($offer) {
                if ($salePrice * $quantity >= $offer->minimum_order_value) {
                    if ($offer->discount_type === 'percentage') {
                        $discountValue = ($salePrice * $offer->discount_value) / 100;
                        $discountValue = min($discountValue, $salePrice);
                        $salePrice = $salePrice - $discountValue;
                        $discount += $discountValue * $quantity;
                        $appliedOffer = $offer;
                    } elseif ($offer->discount_type === 'amount' && $salePrice > $offer->discount_value) {
                        $discountValue = $offer->discount_value;
                        $salePrice = $salePrice - $discountValue;
                        $discount += $discountValue * $quantity;
                        $appliedOffer = $offer;
                    }
                }
            }
            $subtotal += $originalPrice * $quantity;
            $total += $salePrice * $quantity;
            $cart[] = [
                'product' => $product,
                'quantity' => $quantity,
                'original_price' => $originalPrice,
                'sale_price' => $salePrice,
                'subtotal' => $originalPrice * $quantity,
                'total' => $salePrice * $quantity,
                'applied_offer' => $appliedOffer,
                'discount_value' => $discountValue,
            ];
        }
        $discount = $subtotal - $total;
        return view('checkout.index', compact('cart', 'subtotal', 'total', 'discount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
        ]);
        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $subtotal = 0;
        $total = 0;
        $discount = 0;
        $orderItems = [];
        $offerId = null;
        $appliedOffers = [];
        // Get all active, non-expired offers, latest first
        $offers = Offer::where('active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderByDesc('created_at')
            ->get();
        foreach ($cartItems as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product) continue;
            $originalPrice = $product->price;
            $salePrice = ($product->sale_price && $product->sale_price > 0) ? $product->sale_price : $originalPrice;
            $appliedOffer = null;
            $discountValue = 0;
            // Find latest applicable offer (product-wise first, then category-wise)
            $offer = $offers->first(function($o) use ($product) {
                return $o->scope === 'product' && $o->product_id == $product->id;
            });
            if (!$offer && $product->category_id) {
                $offer = $offers->first(function($o) use ($product) {
                    return $o->scope === 'category' && $o->category_id == $product->category_id;
                });
            }
            if ($offer) {
                if ($salePrice * $quantity >= $offer->minimum_order_value) {
                    if ($offer->discount_type === 'percentage') {
                        $discountValue = ($salePrice * $offer->discount_value) / 100;
                        $discountValue = min($discountValue, $salePrice);
                        $salePrice = $salePrice - $discountValue;
                        $discount += $discountValue * $quantity;
                        $appliedOffer = $offer->id;
                    } elseif ($offer->discount_type === 'amount' && $salePrice > $offer->discount_value) {
                        $discountValue = $offer->discount_value;
                        $salePrice = $salePrice - $discountValue;
                        $discount += $discountValue * $quantity;
                        $appliedOffer = $offer->id;
                    }
                }
            }
            $subtotal += $originalPrice * $quantity;
            $total += $salePrice * $quantity;
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $salePrice,
            ];
            if ($appliedOffer) {
                $appliedOffers[] = $appliedOffer;
            }
        }
        $discount = $subtotal - $total;
        $offerId = count($appliedOffers) ? $appliedOffers[0] : null;
        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'status' => 'pending',
            'offer_id' => $offerId
        ]);
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        session()->forget('cart');
        return redirect()->route('payment.process', $order)->with('success', 'Order placed successfully!');
    }
} 