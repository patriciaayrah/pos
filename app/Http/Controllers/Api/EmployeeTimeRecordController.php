<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeeTimeRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class EmployeeTimeRecordController extends Controller
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
     * List all employee time records
     */
    
    public function index()
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }
        
        $time_record = EmployeeTimeRecord::with(['employee'])->get();

        return response()->json($time_record, 200);
        
    }

    /**
     * Store a newly created resource in storage.
     * Create a new employee time records
     */
    public function store(Request $request)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin', 'barista', 'cashier']);
        if($check !== true) { return $check; }

        $remarks = $this->check_remarks($request->record_type);
        $time_record = EmployeeTimeRecord::create([
            'user_id' => Auth::id(),
            'time_in' => now(),
            'remarks' => $remarks,
        ]);

        return response()->json($time_record, 201);
    }

     /**
     * Display the specified resource.
     * Show single employee time record
     */
    public function show($userId)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $employee_record = EmployeeTimeRecord::where('user_id', $userId)->get();
            return $employee_record
                ? response()->json($employee_record, 200)
                : response()->json(['message' => 'Employee time record not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     * Update specific employee time record
     */
    public function update(Request $request, $id)
    {
         $check = $this->permission(['admin', 'owner', 'superadmin', 'cahsier', 'barista']);
        if($check !== true) { return $check; }

        $time_record = EmployeeTimeRecord::find($id);
        
        if(!$time_record) return response()->json(['message' => 'Time record not found'], 404);
       
        $time_record->update([
            $request->record_type => now(),
        ]);

        return response()->json($time_record, 200);
    }

    /**
     * Remove the specified resource from storage.
     * Delete specific employee time record
     */
    public function destroy($id)
    {
        $check = $this->permission(['admin', 'owner', 'superadmin']);
        if($check !== true) { return $check; }

        $user = User::find($id);
        if(!$user) return response()->json(['message' => 'User not found'], 400);

        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }

    public function check_remarks($data){

        $user = Auth::user();
        $employee_schedule = $user->employee_schedule;
        $remarks = "";
        $timestamp = now(); // current time of login

        if($employee_schedule == "morning"){

            if ($data == "time_in") {

                // Define morning shift start time (10:00 AM today)
                $morning_shift = Carbon::today()->setTime(10, 0, 0);

                // Add 10-minute grace period
                $grace_period = $morning_shift->copy()->addMinutes(10);

                // Check if time_in is within or beyond grace period
                if ($timestamp->lessThanOrEqualTo($grace_period)) {
                    $remarks = "Not late";
                } else {
                    $remarks = "Late";
                }
            }else if($data = "time_out"){
                // Define morning shift start time (10:00 AM today)
                $morning_shift = Carbon::today()->setTime(10, 0, 0);

                // Add 10-minute grace period
                $grace_period = $morning_shift->copy()->addMinutes(10);

                // Check if time_in is within or beyond grace period
                if ($timestamp->lessThanOrEqualTo($grace_period)) {
                    $remarks = "Not late";
                } else {
                    $remarks = "Late";
                }
            }
            
        }

        return $remarks;
        
    }

}
