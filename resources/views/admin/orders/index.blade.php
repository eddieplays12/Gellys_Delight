@extends('admin.layout')

@section('title', 'Orders')

@section('content')
<div class="page-header">
    <h2>Customer Orders</h2>
</div>

@if($orders->count() > 0)
    @foreach($orders as $order)
        <div class="card">
            <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="margin-bottom: 0.5rem;">Order #{{ $order->id }}</h3>
                    <p><strong>Status:</strong> {{ $order->status }}</p>
                    <p><strong>Total:</strong> PHP {{ number_format($order->total, 2) }}</p>
                    <p><strong>Placed:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div style="min-width: 280px;">
                    <h4 style="margin-bottom: 0.75rem;">Customer Details</h4>
                    <p><strong>Name:</strong> {{ $order->delivery_name }}</p>
                    <p><strong>Phone:</strong> {{ $order->delivery_phone }}</p>
                    <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
                    <p><strong>Account:</strong> {{ optional($order->user)->username ?? 'Unknown' }}</p>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="margin-bottom: 0.75rem;">Ordered Products</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ optional($item->product)->name ?? 'Deleted product' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>PHP {{ number_format($item->price, 2) }}</td>
                                <td>PHP {{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <form method="POST" action="{{ route('admin.orders.status', $order) }}" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
                @csrf
                @method('PUT')
                <div class="form-group" style="min-width: 240px; margin-bottom: 0;">
                    <label for="status-{{ $order->id }}">Update Status</label>
                    <select id="status-{{ $order->id }}" name="status" required>
                        @foreach(['Pending', 'Preparing', 'Out for Delivery', 'Delivered', 'Cancelled'] as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Status</button>
            </form>
        </div>
    @endforeach
@else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="font-size: 1.1rem; color: #999;">No customer orders yet.</p>
    </div>
@endif
@endsection
