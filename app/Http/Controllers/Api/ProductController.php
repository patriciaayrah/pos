<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    public function permission(array|string $roles){
        
        $user = request()->user();

        if (! $user || ! $user->hasRole($roles)) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        return true; // returns true if allowed

    }

    /**
     * Display a listing of the resource.
     * List all users
     */
    
    public function index()
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        return response()->json(Product::all(), 200);
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new product
     */
    public function store(Request $request)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required',
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'sub_category_id' => $request->sub_category_id,
            'price' => $request->price,
        ]);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     * Show single product
     */
    public function show($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $product = Product::find($id);
            return $product
                ? response()->json($product, 200)
                : response()->json(['message' => 'Product not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     * Update specific user
     */
    public function update(Request $request, $id)
    {
         $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $product = Product::find($id);
        if(!$product) return response()->json(['message' => 'product not found'], 404);

        $product->update($request->only(['name', 'category_id', 'sub_category_id', 'price']));
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     * Delete specific product
     */
    public function destroy($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $product = Product::find($id);
        if(!$product) return response()->json(['message' => 'product not found'], 400);

        $product->delete();
        return response()->json(['message' => 'product deleted'], 200);
    }
}
