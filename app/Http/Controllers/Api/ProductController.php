<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::when($request->category, function ($query) use ($request) {
            $query->where('category', $request->category);
        })->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Data Product',
            'data' => $products
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $filename = time() . '.' . $request->photo->extension();
            // Gunakan disk 'public' dan simpan path lengkap agar konsisten dengan Web Controller
            $data['photo'] = $request->photo->storeAs('products', $filename, 'public');
        }

        $product = Product::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Product Created Successfully',
            'data' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Product',
            'data' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
                'data' => null
            ], 404);
        }

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $filename = time() . '.' . $request->photo->extension();
            // Gunakan disk 'public' dan simpan path lengkap
            $data['photo'] = $request->photo->storeAs('products', $filename, 'public');
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product Updated Successfully',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
                'data' => null
            ], 404);
        }

        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product Deleted Successfully',
            'data' => null
        ]);
    }
}