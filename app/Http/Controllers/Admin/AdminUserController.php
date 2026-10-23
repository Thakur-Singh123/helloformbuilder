<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\CustomForm;
use App\Models\CustomFormField;
use App\Models\FormSubmission;
use App\Models\FormVersion;
use App\Models\Shortcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use DB;

/**
 * Super Admin only: list and view all Admins (subscribers) using the software.
 */
class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ensure only Super Admin can access.
     */
    private function authorizeSuperAdmin(): void
    {
        $user = currentUser();
        if (!$user || $user->user_type !== 'superadmin') {
            abort(403, 'Only Super Admin can manage admins.');
        }
    }

    /**
     * List all Admins (user_type = 'admin').
     */
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $settings = null;
        try {
            $settings = DB::table('settings')->first();
        } catch (\Exception $e) {
            //
        }
        if (!$settings) {
            $settings = (object) ['site_title' => config('app.name', 'LeadMatrix'), 'icon' => 'favicon.ico'];
        }

        $admins = User::where('user_type', 'admin')
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%' . $request->search . '%';
                $q->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->through(function (User $admin) {
                return [
                    'id' => $admin->id,
                    'name' => $admin->name ?? trim($admin->first_name . ' ' . $admin->last_name) ?: $admin->email,
                    'first_name' => $admin->first_name,
                    'last_name' => $admin->last_name,
                    'email' => $admin->email,
                    'is_active' => $admin->is_active,
                    'created_at' => $admin->created_at,
                    'users_count' => User::where('admin_id', $admin->id)->count(),
                    'clients_count' => Client::where('admin_id', $admin->id)->count(),
                    'forms_count' => CustomForm::where('admin_id', $admin->id)->count(),
                    'submissions_count' => FormSubmission::where('admin_id', $admin->id)->count(),
                ];
            });

        return view('admin.admins.index', compact('settings', 'admins'));
    }

    /**
     * Show one Admin's details (forms, submissions, users under them).
     */
    public function show(Request $request, $id)
    {
        $this->authorizeSuperAdmin();

        $admin = User::where('user_type', 'admin')->findOrFail($id);

        $settings = null;
        try {
            $settings = DB::table('settings')->first();
        } catch (\Exception $e) {
            //
        }
        if (!$settings) {
            $settings = (object) ['site_title' => config('app.name', 'LeadMatrix'), 'icon' => 'favicon.ico'];
        }

        $usersCount = User::where('admin_id', $admin->id)->count();
        $forms = CustomForm::where('admin_id', $admin->id)->latest()->limit(20)->get();
        $formsCount = CustomForm::where('admin_id', $admin->id)->count();
        $submissionsCount = FormSubmission::where('admin_id', $admin->id)->count();
        $recentSubmissions = FormSubmission::where('admin_id', $admin->id)
            ->with(['customForm', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.admins.show', compact(
            'settings', 'admin', 'usersCount', 'forms', 'formsCount', 'submissionsCount', 'recentSubmissions'
        ));
    }

    /**
     * Show form to create a new Admin (company).
     */
    public function create()
    {
        $this->authorizeSuperAdmin();
        $settings = $this->getSettings();
        return view('admin.admins.create', compact('settings'));
    }

    /**
     * Store a new Admin (company).
     */
    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->first_name = $request->first_name ?? $request->name;
        $user->last_name = $request->last_name ?? '';
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_type = 'admin';
        $user->admin_id = null;
        $user->is_active = true;
        $user->save();
        return redirect()->route('admin.admins.index')->with('success', 'Admin (company) created successfully.');
    }

    /**
     * Show form to edit an Admin.
     */
    public function edit($id)
    {
        $this->authorizeSuperAdmin();
        $admin = User::where('user_type', 'admin')->findOrFail($id);
        $settings = $this->getSettings();
        return view('admin.admins.edit', compact('settings', 'admin'));
    }

    /**
     * Update an Admin.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeSuperAdmin();
        $admin = User::where('user_type', 'admin')->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($admin->id)],
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $admin->name = $request->name;
        $admin->first_name = $request->first_name ?? $request->name;
        $admin->last_name = $request->last_name ?? '';
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();
        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    /**
     * Deactivate an Admin (soft: set is_active = false).
     */
    public function deactivate($id)
    {
        $this->authorizeSuperAdmin();
        $admin = User::where('user_type', 'admin')->findOrFail($id);
        $admin->is_active = false;
        $admin->save();
        return redirect()->route('admin.admins.index')->with('success', 'Admin deactivated.');
    }

    /**
     * Reactivate an Admin.
     */
    public function activate($id)
    {
        $this->authorizeSuperAdmin();
        $admin = User::where('user_type', 'admin')->findOrFail($id);
        $admin->is_active = true;
        $admin->save();
        return redirect()->route('admin.admins.index')->with('success', 'Admin activated.');
    }

    /**
     * Permanently delete an Admin and all related data (submissions, shortcodes, forms, form versions/fields, clients, users under them).
     */
    public function destroy($id)
    {
        $this->authorizeSuperAdmin();
        $admin = User::where('user_type', 'admin')->findOrFail($id);

        DB::transaction(function () use ($admin) {
            $formIds = CustomForm::where('admin_id', $admin->id)->pluck('id');
            $clientIds = Client::where('admin_id', $admin->id)->pluck('id');

            FormSubmission::where('admin_id', $admin->id)->delete();
            if ($formIds->isNotEmpty() || $clientIds->isNotEmpty()) {
                Shortcode::where(function ($q) use ($formIds, $clientIds) {
                    if ($formIds->isNotEmpty()) $q->whereIn('custom_form_id', $formIds);
                    if ($clientIds->isNotEmpty()) $q->orWhereIn('client_id', $clientIds);
                })->delete();
            }
            if ($formIds->isNotEmpty()) {
                FormVersion::whereIn('custom_form_id', $formIds)->delete();
                CustomFormField::whereIn('custom_form_id', $formIds)->delete();
            }
            CustomForm::where('admin_id', $admin->id)->delete();
            Client::where('admin_id', $admin->id)->delete();
            User::where('admin_id', $admin->id)->delete();
            $admin->delete();
        });

        return redirect()->route('admin.admins.index')->with('success', 'Admin and all related data deleted permanently.');
    }

    private function getSettings()
    {
        try {
            $s = DB::table('settings')->first();
        } catch (\Exception $e) {
            $s = null;
        }
        return $s ?: (object) ['site_title' => config('app.name', 'LeadMatrix'), 'icon' => 'favicon.ico'];
    }
}
