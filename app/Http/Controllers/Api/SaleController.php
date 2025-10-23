<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class SaleController extends Controller
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
     * List all sale
     */
    
    public function index()
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        return response()->json(Sale::all(), 200);
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new sale
     */
    public function store(Request $request)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin', 'cashier']);
        if($check !== true) { return $check; }
        
        $validated = $request->validate([
            'subtotal' => 'required',
            'total' => 'required',
            'payment_type' => 'required',
            'amount_tendered' => 'required',
            'change_due' => 'required',
            'status' => 'required',
        ]);

        $validated = array_merge($validated, [
            'discount' => $request->discount,
            'tax' => $request->tax,
            'notes' => $request->notes,
            'invoice_number' => "1",
            'user_id' => $userId = Auth::id(),
        ]);

        $sale = Sale::create($validated);

        $itemValidated = $request->validate([
            'items.*.product_id' => 'required',
            'items.*.product_name' => 'required',
            'items.*.unit_price' => 'required',
            'items.*.quantity' => 'required',
            'items.*.total_price' => 'required',
            'items.*.discount' => 'nullable|numeric'
        ]);

        // Loop through each validated item and add sale_id
        $saleItem = array_map(function ($item) use ($sale) {
            return array_merge($item, ['sale_id' => $sale->id]);
        }, $itemValidated['items']);

        $saleItem = SaleItem::insert($saleItem);

            return response()->json([
                'message' => 'Sale created successfully',
                'sale' => [
                    'sale' => $sale,
                    'item' => $saleItem,
                ]
                ], 201);
    }

    /**
     * Display the specified resource.
     * Show single sale
     */
    public function show($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin', 'cashier']);
        if($check !== true) { return $check; }

        $sale = Sale::find($id);
            return $sale
                ? response()->json($sale, 200)
                : response()->json(['message' => 'sale not found'], 404);
    }

     /**
     * Update the specified resource in storage.
     * Update specific sale
     */
    public function update(Request $request, $id)
    {
         $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $sale = Sale::find($id);
        if(!$sale) return response()->json(['message' => 'sale not found'], 404);

        $sale->update($request->only(['name', 'email']));
        return response()->json($sale, 200);
    }
}
