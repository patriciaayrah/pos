<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
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
     * List all product category
     */
    
    public function index()
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        return response()->json(ProductCategory::all(), 200);
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new product category
     */
    public function store(Request $request)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $category = ProductCategory::create([
            'name' => $validated['name'],
        ]);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     * Show single product category
     */
    public function show($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $category = ProductCategory::find($id);
            return $category
                ? response()->json($category, 200)
                : response()->json(['message' => 'category not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     * Update specific product category
     */
    public function update(Request $request, $id)
    {
         $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $category = ProductCategory::find($id);
        if(!$category) return response()->json(['message' => 'category not found'], 404);

        $category->update($request->only(['name', 'email']));
        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     * Delete specific product category
     */
    public function destroy($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $category = ProductCategory::find($id);
        if(!$category) return response()->json(['message' => 'category not found'], 400);

        $category->delete();
        return response()->json(['message' => 'category deleted'], 200);
    }

}
