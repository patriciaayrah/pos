<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryItemController;
use App\Http\Controllers\Api\InventoryStockController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductIngredientController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductSubCategoryController;
use App\Http\Controllers\Api\SaleController;
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

    //PRODUCT MODULE
    Route::middleware('role:admin|owner|superadmin')->apiResource('product-categories', ProductCategoryController::class);
    Route::middleware('role:admin|owner|superadmin')->apiResource('product-sub-categories', ProductSubCategoryController::class);
    Route::middleware('role:admin|owner|superadmin')->apiResource('products', ProductController::class);
    Route::middleware('role:admin|owner|superadmin')->apiResource('product-ingredients', ProductIngredientController::class);
    Route::middleware('role:admin|owner|superadmin|cashier')->apiResource('sales', SaleController::class);

});
