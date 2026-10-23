<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TeamsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Allow only Admin users.
     */
    private function authorizeAdmin(): void
    {
        $user = currentUser();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Only Admin can manage team members.');
        }
    }

    /**
     * Get site settings.
     */
    private function getSettings()
    {
        try {
            $settings = DB::table('settings')->first();
        } catch (\Exception $e) {
            $settings = null;
        }

        return $settings ?: (object)[
            'site_title' => config('app.name'),
            'icon' => 'favicon.ico',
        ];
    }

    /**
     * Team listing.
     */
    public function index()
    {
        $this->authorizeAdmin();

        $admin = currentUser();

        $employees = User::with('tasks')->where('admin_id', $admin->id)
                    ->where('user_type', 'employee')
                    ->orderBy('first_name')
                    ->paginate(10);

        $settings = $this->getSettings();

        return view('admin.teams.index', compact('employees', 'settings'));
    }

    /**
     * Add Employee page.
     */
    public function create()
    {
        $this->authorizeAdmin();

        $settings = $this->getSettings();

        return view('admin.teams.create', compact('settings'));
    }

    /**
     * Save Employee.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $admin = currentUser();

        //Check if the email already exists
        $IsEmailExists = User::where('email', $request->email)->exists();
        if($IsEmailExists) {
            return back()->with('unsuccess', 'This Email already used. Please try with a new email.');
        }

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'different:email',
            ],
        ], [
            'password.different' => 'Password cannot be same as email.',
        ]);

        $employee = new User();

        $employee->first_name = $request->first_name ?: $request->name;
        $employee->last_name = $request->last_name ?: '';
        $employee->name = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
        $employee->email = $request->email;
        $employee->password = $request->password;
        $employee->user_type = 'employee';
        $employee->admin_id = $admin->id;
        $employee->profile = $request->profile;
        $employee->is_active = true;
        $employee->email_verified_at = now();

        $employee->save();

        /**
         * Welcome Email
         */
        try {

            $settings = $this->getSettings();

            $mailData = [
                'employee_name' => $employee->name,
                'employee_email' => $employee->email,
                'password' => $request->password,
                'login_url' => route('login'),
                'site_name' => $settings->site_title,
            ];

            Mail::send('email.employee_welcome', $mailData, function ($message) use ($employee) {

                $message->to($employee->email, $employee->name)
                    ->subject('Your Employee Account')
                    ->from(config('mail.from.address'), config('mail.from.name'));

            });

        } catch (\Exception $e) {

            \Log::error('Employee email failed: ' . $e->getMessage());

        }

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Employee created successfully.');

    }

    /**
     * Edit Employee page.
     */
    public function edit($id)
    {
        $this->authorizeAdmin();

        $admin = currentUser();

        $employee = User::where('id', $id)
            ->where('admin_id', $admin->id)
            ->where('user_type', 'employee')
            ->firstOrFail();

        $settings = $this->getSettings();

        return view('admin.teams.edit', compact('settings', 'employee'));
    }

    /**
     * Update Employee.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $admin = currentUser();

        $employee = User::where('id', $id)
            ->where('admin_id', $admin->id)
            ->where('user_type', 'employee')
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($employee->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
                'different:email',
            ],
        ]);

        $employee->name = $request->name;
        $employee->first_name = $request->first_name ?: $request->name;
        $employee->last_name = $request->last_name ?: '';
        $employee->email = $request->email;
        $employee->profile = $request->profile;

        if ($request->filled('password')) {
            $employee->password = $request->password;
        }

        $employee->save();

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Employee updated successfully.');

    }

    /**
     * Delete Employee.
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();

        $admin = currentUser();

        $employee = User::where('id', $id)
            ->where('admin_id', $admin->id)
            ->where('user_type', 'employee')
            ->firstOrFail();

        $employee->delete();

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Employee deleted successfully.');
    }

     public function tasks_create($employee_id)
    {
        $admin = currentUser(); // Assuming this is your helper
        
        // Ensure employee belongs to this admin
        $employee = User::where('id', $employee_id)
            ->where('admin_id', $admin->id)
            ->where('user_type', 'employee')
            ->firstOrFail();

        return view('admin.teams.tasks.create', compact('employee'));
    }

    public function tasks_store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $admin = currentUser();

        // 1. Task Create Karein
        $task = Task::create([
            'admin_id' => $admin->id,
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => 'pending'
        ]);

        // 2. Employee ko find karein jisko task assign hua hai
        $employee = User::find($request->user_id);

        /**
         * 3. Task Assignment Email Bhejein
         */
        try {
            $settings = $this->getSettings();

            $mailData = [
                'employee_name' => $employee->name,
                'task_title'    => $task->title,
                'task_desc'     => $task->description,
                'due_date'      => $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M, Y') : 'No Date Set',
                'login_url'     => route('login'), // Ya phir sidha route('employee.tasks') agar defined ho
                'site_name'     => $settings->site_title,
            ];

            // email.task_assigned view ka use karke mail bhejein
            Mail::send('email.task_assigned', $mailData, function ($message) use ($employee, $task) {
                $message->to($employee->email, $employee->name)
                    ->subject('New Task Assigned: ' . $task->title)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            // Agar mail fail ho jaye toh application crash na ho
            \Log::error('Task assignment email failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.teams.index')
            ->with('success', 'Task assigned successfully and email sent to the employee.');
    }

    /**
     * Edit Task page.
     */
    public function tasks_edit($task_id)
    {
        $admin = currentUser();

        // Ensure task belongs to this admin
        $task = Task::where('id', $task_id)
            ->where('admin_id', $admin->id)
            ->firstOrFail();

        $employee = User::where('id', $task->user_id)->first();

        return view('admin.teams.tasks.edit', compact('task', 'employee'));
    }

    /**
     * Update Task.
     */
    public function tasks_update(Request $request, $task_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|string|in:pending,in_progress,completed' // Add statuses as per your DB
        ]);

        $admin = currentUser();

        $task = Task::where('id', $task_id)
            ->where('admin_id', $admin->id)
            ->firstOrFail();

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status
        ]);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Show task list for a specific employee on a separate page.
     */
    public function tasks_list($employee_id)
    {
        $admin = currentUser();

        $employee = User::where('id', $employee_id)
            ->where('admin_id', $admin->id)
            ->where('user_type', 'employee')
            ->firstOrFail();

        $tasks = Task::where('user_id', $employee->id)
            ->where('admin_id', $admin->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.teams.tasks.index', compact('employee', 'tasks'));
    }

    /**
 * Delete Task.
 */
    public function tasks_destroy($task_id)
    {
        $this->authorizeAdmin();

        $admin = currentUser();

        // Ensure task belongs to this admin
        $task = Task::where('id', $task_id)
            ->where('admin_id', $admin->id)
            ->firstOrFail();

        $task->delete();

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Task deleted successfully.');
    }
}