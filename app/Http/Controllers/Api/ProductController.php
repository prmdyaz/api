<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => ProductResource::collection(Product::all()->where('is_delete', false))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'img' => 'required|file|mimes:png,jpg',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $file = $request->file('img');
        $file_name = time() . '.' . $file->getClientOriginalExtension();
        $file->move('product/img', $file_name);

        $product = Product::create([
            'name' => $request->name,
            'img' => $file_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'sold' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success add new product',
            'data' => new ProductResource($product)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if ($product->is_delete == true) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if ($product->is_delete == true) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes',
            'img' => 'sometimes|mimes:png,jpg',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|numeric',
        ]);

        // check if request not passing any data to updated
        if (!$request->name && !$request->img && !$request->fees) {
            return response()->json([
                'success' => false,
                'message' => 'No one data is updated'
            ], 202);
        }

        // Update all except img
        $product->update($request->only(['name', 'price', 'stock']));

        // Update img only
        if ($request->hasFile('img')) {
            File::delete("product/img/$product->img");
            $file = $request->file('img');
            $file->move('product/img', $product->img);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success update Product',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->is_delete == true) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found',
            ], 404);
        }

        // delete image first before delete data in database
        // File::delete("product/img/$product->img");
        $product->update([
            'is_delete' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success delete product'
        ]);
    }
}
