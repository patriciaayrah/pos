<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\InventoryStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InventoryStockController extends Controller
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
        
        return response()->json(InventoryStock::all(), 200);
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new stock
     */
    public function store(Request $request)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        $validated = $request->validate([
            'item_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'purchased_date' => 'required',
            'expiration_date' => 'required',
        ]);

        $stock = InventoryStock::create([
            'item_id' => $validated['item_id'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'purchased_date' => $validated['purchased_date'],
            'expiration_date' => $validated['expiration_date'],
        ]);

        return response()->json($stock, 201);
    }

     /**
     * Display the specified resource.
     * Show single stock
     */
    public function show($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $stock = InventoryStock::find($id);
            return $stock
                ? response()->json($stock, 200)
                : response()->json(['message' => 'stock not found'], 404);
    }

     /**
     * Update the specified resource in storage.
     * Update specific stock
     */
    public function update(Request $request, $id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $stock = InventoryStock::find($id);
        if(!$stock) return response()->json(['message' => 'stock not found'], 404);

        $stock->update($request->only(['item_id', 'price', 'quantity', 'purchased_date', 'expiration_date']));
        return response()->json($stock, 200);
    }

    /**
     * Remove the specified resource from storage.
     * Delete specific stock
     */
    public function destroy($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $stock = InventoryStock::find($id);
        if(!$stock) return response()->json(['message' => 'stock not found'], 400);

        $stock->delete();
        return response()->json(['message' => 'stock deleted'], 200);
    }
}
