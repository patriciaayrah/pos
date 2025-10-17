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
        
        return response()->json(ProductIngredient::all(), 200);
        
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
            'product_id' => 'required',
            'item_id' => 'required',
            'qty_used' => 'required',
        ]);

        $productIngredient = ProductIngredient::create([
            'product_id' => $validated['product_id'],
            'item_id' => $validated['item_id'],
            'qty_used' => $validated['qty_used']
        ]);

        return response()->json($productIngredient, 201);
    }

    /**
     * Display the specified resource.
     * Show single product ingredient
     */
    public function show($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $productIngredient = ProductIngredient::find($id);
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
