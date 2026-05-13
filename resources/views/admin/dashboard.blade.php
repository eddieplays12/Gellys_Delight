@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h2>Dashboard</h2>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Products</h3>
        <div class="value">{{ \App\Models\Product::count() }}</div>
    </div>

    <div class="stat-card">
        <h3>Total Orders</h3>
        <div class="value">{{ \App\Models\Order::count() }}</div>
    </div>

    <div class="stat-card">
        <h3>Total Users</h3>
        <div class="value">{{ \App\Models\User::count() }}</div>
    </div>

    <div class="stat-card">
        <h3>Total Revenue</h3>
        <div class="value">PHP {{ number_format(\App\Models\Order::sum('total'), 2) }}</div>
    </div>

    <div class="stat-card">
        <h3>Pending Orders</h3>
        <div class="value">{{ \App\Models\Order::where('status', 'Pending')->count() }}</div>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 1rem;">Welcome to Gelly's Delights Admin Panel</h2>
    <p style="margin-bottom: 1rem; line-height: 1.6;">
        Use this admin panel to manage your store and track customer deliveries.
    </p>
    <ul style="margin-left: 1.5rem; margin-bottom: 1.5rem; line-height: 1.8;">
        <li><strong>Add Products:</strong> Create new coffee, cakes, pastries, and drinks</li>
        <li><strong>Upload Images:</strong> Add product photos for the menu</li>
        <li><strong>Edit Products:</strong> Update names, prices, and details anytime</li>
        <li><strong>Track Orders:</strong> View customer name, phone, address, and ordered items</li>
        <li><strong>Update Status:</strong> Mark orders as Pending, Preparing, Out for Delivery, Delivered, or Cancelled</li>
    </ul>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Manage Products</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">View Orders</a>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.5rem;">Product Categories</h3>
    @php
        $categories = \App\Models\Product::select('category')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('category')
            ->get();
    @endphp

    @if($categories->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                    <tr>
                        <td>{{ $cat->category }}</td>
                        <td>{{ $cat->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #999; padding: 2rem; text-align: center;">
            No products yet. <a href="{{ route('admin.products.create') }}">Create the first one</a>
        </p>
    @endif
</div>
@endsection
