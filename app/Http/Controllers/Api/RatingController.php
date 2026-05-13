<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function createRating(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => $request->session()->get('user_id'),
                'product_id' => $validated['product_id'],
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ],
        );

        return response()->json([
            'rating' => $rating,
            'message' => 'Rating saved successfully',
        ], 201);
    }

    public function getProductRatings($productId): JsonResponse
    {
        $ratings = Rating::where('product_id', $productId)
            ->with('user:id,username')
            ->latest()
            ->get();

        return response()->json($ratings);
    }
}
