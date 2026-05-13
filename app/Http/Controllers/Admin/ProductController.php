<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. SHOW ALL PRODUCTS (GET /admin/products)
    public function index()
    {
        $products = Product::query()
            ->withSum('orderItems as sold_quantity', 'quantity')
            ->latest()
            ->get();

        $topSoldQuantity = $products->max('sold_quantity') ?? 0;

        return view('admin.products.index', [
            'products' => $products,
            'topSoldQuantity' => $topSoldQuantity,
        ]);
    }

    // 2. SHOW CREATE PRODUCT FORM (GET /admin/products/create)
    public function create()
    {
        // Return the form to create a new product
        return view('admin.products.create');
    }

    // 3. STORE NEW PRODUCT (POST /admin/products)
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store image in storage/app/public/products
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Create the product in database
        Product::create($validated);

        // Redirect back with success message
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    // 4. SHOW EDIT PRODUCT FORM (GET /admin/products/{id}/edit)
    public function edit(Product $product)
    {
        // Return the edit form with the product data
        return view('admin.products.edit', ['product' => $product]);
    }

    // 5. UPDATE PRODUCT (PUT /admin/products/{id})
    public function update(Request $request, Product $product)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload (delete old one if new image uploaded)
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Store new image
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Update the product
        $product->update($validated);

        // Redirect back with success message
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    // 6. DELETE PRODUCT (DELETE /admin/products/{id})
    public function destroy(Product $product)
    {
        // Delete the image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete the product from database
        $product->delete();

        // Redirect back with success message
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
