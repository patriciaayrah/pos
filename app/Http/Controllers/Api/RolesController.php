<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     * List all roles
     */
    public function index()
    {
        return response()->json(Role::all(), 200);
    }

    /**
     * Create Roles
     * 
     */
    public function store(Request $request){

        return $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        return response()->json($role, 201);
        
    }
}
