@extends('admin.layout')

@section('title', 'Ratings')

@section('content')
<div class="page-header">
    <h2>Customer Ratings</h2>
</div>

@if($ratings->count() > 0)
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Stars</th>
                    <th>Comment</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ratings as $rating)
                    <tr>
                        <td>
                            <strong>{{ optional($rating->user)->username ?? 'Unknown User' }}</strong><br>
                            <small>{{ optional($rating->user)->email ?? 'No email' }}</small>
                        </td>
                        <td>{{ optional($rating->product)->name ?? 'Deleted product' }}</td>
                        <td style="color: #ffb400; font-size: 1.1rem;">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $rating->rating ? '★' : '☆' }}
                            @endfor
                            <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">
                                {{ $rating->rating }}/5
                            </div>
                        </td>
                        <td>{{ $rating->comment ?: 'No comment provided.' }}</td>
                        <td>{{ $rating->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="font-size: 1.1rem; color: #999;">No ratings yet.</p>
    </div>
@endif
@endsection
