<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    public function index(Request $request) {
        $query = User::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Get all customers with order count
        $customers = $query->withCount('orders')
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
        
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer) {
        $orders = $customer->orders()->with('items.product')->orderBy('created_at', 'desc')->get();
        return view('admin.customers.show', compact('customer', 'orders'));
    }

    public function edit(User $customer) {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ]);
        
        $customer->update($data);
        return redirect()->route('admin.customers.show', $customer)->with('status', 'Customer updated successfully!');
    }

    public function destroy(User $customer) {
        try {
            $customer->delete();
            return redirect()->route('admin.customers.index')->with('status', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('error', 'Cannot delete customer. They may have associated orders.');
        }
    }
} 