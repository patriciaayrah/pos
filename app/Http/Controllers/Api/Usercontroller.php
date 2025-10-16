<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;



class Usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     * List all users
     */
    public function index()
    {
        
        $user = request()->user();

        if($user->hasRole('admin')){
            return response()->json(User::all(), 200);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new user
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     * Show single user
     */
    public function show($id)
    {
        $user = User::find($id);
        return $user
            ? response()->json($user, 200)
            : response()->json(['message' => 'User not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     * Update specific user
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user) return response()->json(['message' => 'User not found'], 404);

        $user->update($request->only(['name', 'email']));
        $user->assignRole($request->role);
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     * Delete specific user
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(!$user) return response()->json(['message' => 'User not found'], 400);

        $user->delete();
        return response()->json(['message', 'User deleted'], 200);
    }
}
