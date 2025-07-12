<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::with('user')->orderByDesc('created_at')->get();
        return view('admin.orders.index', compact('orders'));
    }
    public function show(Order $order) {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }
    public function update(Request $request, Order $order) {
        $data = $request->validate([
            'status' => 'required|string',
        ]);
        $order->update($data);
        return redirect()->route('admin.orders.show', $order)->with('status', 'Order status updated!');
    }
} 