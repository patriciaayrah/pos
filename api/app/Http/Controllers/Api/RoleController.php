<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{

    public function create(Request $request){
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'cashier']);
    }
   
    public function assignRole(Request $request){

        $user = User::find(1);
        $user->assignRole('admin');

    }
}
