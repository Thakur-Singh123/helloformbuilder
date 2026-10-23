<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Make sure User model is imported
use App\Models\Attendance; 
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Employee Dashboard
     */
    public function index()
    {
        return view('employee.dashboard');
    }

    /**
     * Employee Attendance
     */
     public function attendance()
    {
        $employee = currentUser(); // Aapka helper function
        $today = Carbon::today();

        // Aaj ki attendance nikalein
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $employee->id, 'date' => $today],
            ['status' => 'not_started']
        );

        // Working Time Calculation (Lunch Excluded)
        $workingMinutes = 0;

        if ($attendance->check_in) {
            $endTime = $attendance->check_out ?? now();
            $totalMinutes = $attendance->check_in->diffInMinutes($endTime);

            $lunchMinutes = 0;
            if ($attendance->lunch_start) {
                $lunchEnd = $attendance->lunch_end ?? now();
                $lunchMinutes = $attendance->lunch_start->diffInMinutes($lunchEnd);
            }

            // Total time mein se lunch time minus kiya
            $workingMinutes = max(0, $totalMinutes - $lunchMinutes); 
        }

        $workingHours = floor($workingMinutes / 60);
        $workingMins = $workingMinutes % 60;
        $calculatedTime = sprintf("%02dh %02dm", $workingHours, $workingMins);

        // Team members ki aaj ki attendance fetch karo (Sirf dikhane ke liye)
        $query = User::where('user_type', 'employee');
        if ($employee->admin_id) {
            $query->where('admin_id', $employee->admin_id);
        }
        $teamMembers = $query->with(['attendances' => function($q) use ($today) {
            $q->where('date', $today);
        }])->get();

        return view('employee.attendance.attendance', compact('employee', 'attendance', 'calculatedTime', 'teamMembers'));
    }

    // Action Handle karne ke liye naya method
    public function action(Request $request)
    {
        $employee = currentUser();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $employee->id)->where('date', $today)->first();
        $action = $request->action;

        if ($action == 'start') {
            $attendance->update(['check_in' => now(), 'status' => 'working']);
        } elseif ($action == 'lunch_start') {
            $attendance->update(['lunch_start' => now(), 'status' => 'on_lunch']);
        } elseif ($action == 'lunch_end') {
            $attendance->update(['lunch_end' => now(), 'status' => 'working']);
        } elseif ($action == 'checkout') {
            $attendance->update(['check_out' => now(), 'status' => 'completed']);
        }

        return back()->with('success', 'Status Updated!');
    }

    /**
     * Employee Profile
     */
    public function profile()
    {
        $employee = currentUser();
        return view('employee.profile', compact('employee'));
    }

    /**
     * Employee Account
     */
    public function account()
    {
        $employee = currentUser();
        return view('employee.account', compact('employee'));
    }

    /**
     * Show Assigned Tasks to Employee
     */
    public function tasks()
    {
        $employee = currentUser();

        // Employee ke tasks fetch karein aur due date ke hisaab se sort karein
        $tasks = \App\Models\Task::where('user_id', $employee->id)
                    ->orderByRaw("CASE WHEN status = 'completed' THEN 2 ELSE 1 END") // Completed tasks sabse neeche
                    ->orderBy('due_date', 'asc') // Jo date pass hai wo upar
                    ->paginate(10);

        return view('employee.tasks.index', compact('tasks'));
    }
}