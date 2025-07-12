@extends('layouts.admin')

@section('content')
<h3>Edit Offer</h3>
<form method="POST" action="{{ route('admin.offers.update', $offer) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $offer->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="code" class="form-label">Code</label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $offer->code) }}" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="discount_type" class="form-label">Discount Type</label>
        <select class="form-control @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required>
            <option value="percentage" {{ old('discount_type', $offer->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
            <option value="amount" {{ old('discount_type', $offer->discount_type) == 'amount' ? 'selected' : '' }}>Amount (₹)</option>
        </select>
        @error('discount_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="discount_value" class="form-label">Discount Value</label>
        <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" name="discount_value" value="{{ old('discount_value', $offer->discount_value) }}" required>
        @error('discount_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="scope" class="form-label">Scope</label>
        <select class="form-control @error('scope') is-invalid @enderror" id="scope" name="scope" required onchange="toggleScopeSelectors()">
            <option value="category" {{ old('scope', $offer->scope) == 'category' ? 'selected' : '' }}>Category-wise</option>
            <option value="product" {{ old('scope', $offer->scope) == 'product' ? 'selected' : '' }}>Product-wise</option>
        </select>
        @error('scope')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3" id="category_selector" style="display: {{ old('scope', $offer->scope) == 'category' ? 'block' : 'none' }};">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $offer->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3" id="product_selector" style="display: {{ old('scope', $offer->scope) == 'product' ? 'block' : 'none' }};">
        <label for="product_id" class="form-label">Product</label>
        <select class="form-control @error('product_id') is-invalid @enderror" id="product_id" name="product_id">
            <option value="">Select Product</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id', $offer->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
            @endforeach
        </select>
        @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="minimum_order_value" class="form-label">Minimum Order Value</label>
        <input type="number" step="0.01" class="form-control @error('minimum_order_value') is-invalid @enderror" id="minimum_order_value" name="minimum_order_value" value="{{ old('minimum_order_value', $offer->minimum_order_value) }}" required>
        @error('minimum_order_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $offer->start_date) }}" required>
        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $offer->end_date) }}" required>
        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $offer->active) ? 'checked' : '' }}>
        <label class="form-check-label" for="active">Active</label>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.offers.index') }}" class="btn btn-secondary">Cancel</a>
</form>
<script>
function toggleScopeSelectors() {
    var scope = document.getElementById('scope').value;
    document.getElementById('category_selector').style.display = (scope === 'category') ? 'block' : 'none';
    document.getElementById('product_selector').style.display = (scope === 'product') ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleScopeSelectors);
</script>
@endsection 