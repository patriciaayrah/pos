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

        $permissions = $request->permission;

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo($permissions);

        return response()->json($adminRole, 201);
        
    }
}
