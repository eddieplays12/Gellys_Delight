<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->latest()
            ->get();

        return response()->json($products)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With');
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:100',
            'image' => 'nullable|string',
        ]);

        // 2. Create a new product with the validated data
        $product = Product::create($validated);

        // 3. Return the created product as JSON
        return response()->json(
            ['message' => 'Product created successfully', 'product' => $product],
            201
        )->header('Access-Control-Allow-Origin', '*');
    }
}
