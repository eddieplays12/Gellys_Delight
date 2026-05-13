@extends('admin.layout')

@section('title', 'Products')

@section('content')
<div class="page-header">
    <h2>Manage Products</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
</div>

@if($products->count() > 0)
    <div class="admin-product-grid">
        @foreach($products as $product)
            @php
                $soldQuantity = (int) ($product->sold_quantity ?? 0);
                $isBestseller = $soldQuantity > 0 && $soldQuantity === (int) $topSoldQuantity;
            @endphp
            <article class="admin-product-card {{ $isBestseller ? 'is-bestseller' : '' }}">
                <div class="admin-product-image-wrap">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="admin-product-image">
                    <span class="admin-product-badge">{{ strtoupper($product->category) }}</span>
                    @if($isBestseller)
                        <span class="admin-bestseller-badge">Bestseller</span>
                    @endif
                </div>

                <div class="admin-product-body">
                    <h3>{{ $product->name }}</h3>
                    <p class="admin-product-description">{{ Str::limit($product->description, 70) }}</p>

                    <div class="admin-product-meta">
                        <div>
                            <span class="admin-product-label">Price</span>
                            <strong>PHP {{ number_format($product->price, 2) }}</strong>
                        </div>
                        <div>
                            <span class="admin-product-label">Sold</span>
                            <strong>{{ $soldQuantity }} units</strong>
                        </div>
                    </div>

                    <div class="admin-product-actions">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-small">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure? This cannot be undone!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-small">Delete</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="font-size: 1.1rem; color: #999; margin-bottom: 1.5rem;">
            No products yet. Create your first product.
        </p>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add First Product</a>
    </div>
@endif
@endsection
