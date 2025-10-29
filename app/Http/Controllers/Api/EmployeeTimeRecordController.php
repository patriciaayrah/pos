<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployeeTimeRecord;
use Illuminate\Http\Request;
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

        $remarks = $this->check_remarks($request->record_type);

        $time_record = EmployeeTimeRecord::find($id);
        if(!$time_record) return response()->json(['message' => 'Time record not found'], 404);

          //Compute the total hours worked
        $total_hours = "";
        if($request->record_type == "time_out"){
            $total_hours = $this->total_hours($time_record);
        }
        

        $time_record->update([
            $request->record_type => now(),
            'remarks' => $time_record->remarks.", ".$remarks,
            'hours_worked' => $total_hours
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
        $time_in_schedule = "";
        $time_out_schedule = "";

        if ($employee_schedule === 'morning') {
            //TIME IN
            $time_in_hr = "10";
            $time_in_min = "0";
            $time_in_sec = "0";
            //TIME OUT
            $time_out_hr = "20";
            $time_out_min = "0";
            $time_out_sec = "0";
            //BREAK OUT
            $break_out_hr = "18";
            $break_out_min = "30";
            $break_out_sec = "0";
        }else if($employee_schedule === 'night'){
            //TIME IN
            $time_in_hr = "15";
            $time_in_min = "0";
            $time_in_sec = "0";
            //TIME OUT
            $time_out_hr = "0";
            $time_out_min = "0";
            $time_out_sec = "0";
            //BREAK OUT
            $break_out_hr = "19";
            $break_out_min = "30";
            $break_out_sec = "0";
        }

            if ($data === 'time_in') {
                // Morning shift start time (10:00 AM)
                $shift_start = Carbon::today()->setTime($time_in_hr, $time_in_min, $time_in_sec);

                // Grace period: +10 mins
                $grace_period = $shift_start->copy()->addMinutes(10);

                $remarks = $timestamp->lessThanOrEqualTo($grace_period)
                    ? 'Not late'
                    : '';

            } elseif ($data === 'time_out') {
                // Morning shift end time (8:00 PM)
                $shift_end = Carbon::today()->setTime($time_out_hr, $time_out_min, $time_out_sec);

                $remarks = $timestamp->lessThan($shift_end)
                    ? 'Early out'
                    : '';

            } elseif ($data === 'break_out') {
                // Break should end by 6:30 PM
                $break_limit = Carbon::today()->setTime($break_out_hr, $break_out_min, $break_out_sec);

                $remarks = $timestamp->greaterThan($break_limit)
                    ? 'Overbreak'
                    : '';
            }

        return $remarks;
        
    }

    public function total_hours($data){

        $time_in = Carbon::parse($data->time_in);
        $time_out = Carbon::parse($data->time_out);
        $break_in = Carbon::parse($data->break_in);
        $break_out = Carbon::parse($data->break_out);
        $total_hours = "";

        // Compute durations
        $total_minutes_worked = $time_in->diffInMinutes($time_out);
        $break_minutes = $break_out->diffInMinutes($break_in);

        $net_minutes = $total_minutes_worked - $break_minutes;

        $total_hours = floor($net_minutes / 60);
        $total_minutes = $net_minutes % 60;

        $total_time_worked = sprintf('%02d:%02d', $total_hours, $total_minutes);

        return $total_time_worked;
    }

}
