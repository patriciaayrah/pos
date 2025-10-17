<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\Hash;

class InventoryItemController extends Controller
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
     * List all items
     */
    
    public function index()
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        return response()->json(InventoryItem::all(), 200);
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new item
     */
    public function store(Request $request)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        $validated = $request->validate([
            'name' => 'required|unique:inventory_items',
            'weight' => 'required',
            'unit' => 'required|min:2',
        ]);

        $item = InventoryItem::create([
            'name' => $validated['name'],
            'weight' => $validated['weight'],
            'unit' => $validated['unit']
        ]);

        return response()->json($item, 201);
    }


    /**
     * Display the specified resource.
     * Show single item
     */
    public function show($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $item = InventoryItem::find($id);
            return $item
                ? response()->json($item, 200)
                : response()->json(['message' => 'User not found'], 404);
    }

     /**
     * Update the specified resource in storage.
     * Update specific item
     */
    public function update(Request $request, $id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $item = InventoryItem::find($id);
        if(!$item) return response()->json(['message' => 'item not found'], 404);

        $item->update($request->only(['name', 'weight', 'unit']));
        return response()->json($item, 200);
    }

    /**
     * Remove the specified resource from storage.
     * Delete specific item
     */
    public function destroy($id)
    {
        $check = $this->permission(['admin', 'owner']);
        if($check !== true) { return $check; }

        $item = InventoryItem::find($id);
        if(!$item) return response()->json(['message' => 'item not found'], 400);

        $item->delete();
        return response()->json(['message' => 'item deleted'], 200);
    }
}
