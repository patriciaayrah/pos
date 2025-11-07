<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProductIngredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProductIngredientController extends Controller
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
        
        $productIngredient = ProductIngredient::with(['product', 'inventoryItem'])->get();
        return response()->json($productIngredient, 200);
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new ingredient
     */
    public function store(Request $request)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        $validated = $request->validate([
            'ingredients' => 'required|array',
            'ingredients.*.product_id' => 'required',
            'ingredients.*.item_id' => 'required',
            'ingredients.*.qty_used' => 'required',
        ]);

        $productIngredient = ProductIngredient::insert($validated['ingredients']);

        return response()->json(['message' => 'Products added successfully!'], 201);
    }

    /**
     * Display the specified resource.
     * Show single product ingredient
     */
    public function show($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $productIngredient = ProductIngredient::with(['product', 'inventoryItem'])->find($id);
            return $productIngredient
                ? response()->json($productIngredient, 200)
                : response()->json(['message' => 'product ingredient not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     * Update specific ingredient
     */
    public function update(Request $request, $id)
    {
         $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $productIngredient = ProductIngredient::find($id);
        if(!$productIngredient) return response()->json(['message' => 'product ingredient not found'], 404);

        $productIngredient->update($request->only(['product_id', 'item_id', 'qty_used']));
        return response()->json($productIngredient, 200);
    }

    /**
     * Remove the specified resource from storage.
     * Delete specific ingredient
     */
    public function destroy($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $productIngredient = ProductIngredient::find($id);
        if(!$productIngredient) return response()->json(['message' => 'product ingredient not found'], 400);

        $productIngredient->delete();
        return response()->json(['message' => 'product ingredient deleted'], 200);
    }
}
