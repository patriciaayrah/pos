<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProductSubCategoryController extends Controller
{
    public function permission(array|string $roles){
        
        $user = request()->user();

        if (! $user || ! $user->hasRole($roles)) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        return true; // returns true if allowed

    }

     public function index()
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        return response()->json(ProductSubCategory::all(), 200);
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new product sub category
     */
    public function store(Request $request)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
        ]);

        $subCategory = ProductSubCategory::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
        ]);

        return response()->json($subCategory, 201);
    }

    /**
     * Display the specified resource.
     * Show single product sub category
     */
    public function show($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $subCategory = ProductSubCategory::find($id);
            return $subCategory
                ? response()->json($subCategory, 200)
                : response()->json(['message' => 'sub-category not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     * Update specific product sub category
     */
    public function update(Request $request, $id)
    {
         $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $subCategory = ProductSubCategory::find($id);
        if(!$subCategory) return response()->json(['message' => 'sub-category not found'], 404);

        $subCategory->update($request->only(['name', 'category_id']));
        return response()->json($subCategory, 200);
    }

    /**
     * Remove the specified resource from storage.
     * Delete specific product sub category
     */
    public function destroy($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $subCategory = ProductSubCategory::find($id);
        if(!$subCategory) return response()->json(['message' => 'sub-category not found'], 400);

        $subCategory->delete();
        return response()->json(['message' => 'sub-category deleted'], 200);
    }
}
