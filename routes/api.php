<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryItemController;
use App\Http\Controllers\Api\InventoryStockController;
use Spatie\Permission\Models\Role;


use App\Http\Controllers\Api\RolesController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::middleware('role:admin|owner|superadmin')->apiResource('users', UserController::class);
    Route::middleware('role:superadmin')->apiResource('roles', RolesController::class);
    Route::post('/logout', [AuthController::class, 'logout']);

    //INVENTORY MODULE
    Route::middleware('role:admin|owner|superadmin')->apiResource('inventory-items', InventoryItemController::class);
    Route::middleware('role:admin|owner|superadmin')->apiResource('inventory-stocks', InventoryStockController::class);

});
