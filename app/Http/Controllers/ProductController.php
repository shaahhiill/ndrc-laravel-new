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
        if (auth()->user()->role !== 'nestle') {
            abort(403, 'Only Nestlé administrators can add products.');
        }

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

        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect()->route('nestle.products')->with('success', "Product '{$product->name}' added successfully to the unified catalogue!");
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }
}
