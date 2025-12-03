<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    // PUBLIC: List Products
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return ProductResource::collection($products)
                ->additional(['message' => 'Products retrieved successfully']);
    }

    // PUBLIC: Single Product
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    // ADMIN: Create Product
    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return (new ProductResource($product))
                ->additional(['message' => 'Product created successfully']);
    }

    // ADMIN: Update Product
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }

            $data['image_url'] = $request->file('image')
                ->store('products', 'public');
        }

        $product->update($data);

        return (new ProductResource($product))
                ->additional(['message' => 'Product updated successfully']);
    }


    // ADMIN: Delete Product
    public function destroy(Product $product)
    {
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
