<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adminLogin(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('admin_id', $validated['admin_id'])->first();

        if (!$admin || !Hash::check($validated['password'], $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401)
                ->header('Access-Control-Allow-Origin', '*');
        }

        $token = $admin->createToken('admin-token')->plainTextToken;
        
        return response()->json(['token' => $token, 'message' => 'Login successful'])
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function getProducts()
    {
        $products = Product::all();

        return response()->json($products)
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function createProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json(['product' => $product, 'message' => 'Product created'], 201)
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function updateProduct(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404)
                ->header('Access-Control-Allow-Origin', '*');
        }

        $product->update($request->all());

        return response()->json(['product' => $product, 'message' => 'Product updated'])
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404)
                ->header('Access-Control-Allow-Origin', '*');
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted'])
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function getOrders()
    {
        $orders = Order::with('user', 'items.product')->get();

        return response()->json($orders)
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function getRatings()
    {
        $ratings = Rating::with(['user', 'product'])->latest()->get();

        return response()->json($ratings)
            ->header('Access-Control-Allow-Origin', '*');
    }
}