<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'delivery_name' => 'required|string',
            'delivery_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        $userId = $request->session()->get('user_id');

        $order = Order::create([
            'user_id' => $userId,
            'delivery_name' => $validated['delivery_name'],
            'delivery_phone' => $validated['delivery_phone'],
            'delivery_address' => $validated['delivery_address'],
            'total' => collect($validated['items'])->sum(fn ($item) => $item['price'] * $item['quantity']),
        ]);

        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json([
            'order' => $order->load('items'),
            'message' => 'Order created successfully',
        ], 201);
    }

    public function getMyOrders(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->session()->get('user_id'))
            ->with('items.product')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function updateOrderStatus(Request $request, $orderId): JsonResponse
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Preparing,Out for Delivery,Delivered,Cancelled',
        ]);

        $order->update($validated);

        return response()->json([
            'order' => $order,
            'message' => 'Order updated',
        ]);
    }
}