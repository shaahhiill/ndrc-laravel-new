<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::orderBy('category')->orderBy('name')->get();
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category' => 'required|in:Dairy,Beverages,Noodles,Confectionery,Culinary',
            'unit' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }
}
