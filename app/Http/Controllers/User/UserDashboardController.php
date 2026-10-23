<?php

namespace App\Http\Controllers\User;

use App\Models\Client;
use App\Models\CustomForm;
use App\Models\FormSubmission;
use App\Models\LoginActivity;
use App\Models\Shortcode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserDashboardController
{
   public function user_dashboard() {
          $user = currentUser();
          
          // Get statistics based on user role
          if ($user->isSuperAdmin()) {
              // Super Admin sees everything
              $activeForms = CustomForm::where('status', 'active')->count();
              $totalSubmissions = FormSubmission::count();
              $totalShortcodes = Shortcode::count();
              $recentSubmissions = FormSubmission::with(['customForm', 'user', 'admin'])
                  ->latest()
                  ->limit(5)
                  ->get();
              $recentForms = CustomForm::with(['user', 'admin'])
                  ->latest()
                  ->limit(5)
                  ->get();
              $recentLoginActivities = LoginActivity::with('user')
                  ->latest('logged_at')
                  ->limit(20)
                  ->get();
          } elseif ($user->isAdmin()) {
              // Admin sees their own data
              $activeForms = CustomForm::where('admin_id', $user->id)
                  ->where('status', 'active')
                  ->count();
              $totalSubmissions = FormSubmission::where('admin_id', $user->id)->count();
              $totalShortcodes = Shortcode::whereHas('customForm', function($query) use ($user) {
                  $query->where('admin_id', $user->id);
              })->count();
              $recentSubmissions = FormSubmission::where('admin_id', $user->id)
                  ->with(['customForm', 'user', 'admin'])
                  ->latest()
                  ->limit(5)
                  ->get();
              $recentForms = CustomForm::where('admin_id', $user->id)
                  ->with(['user', 'admin'])
                  ->latest()
                  ->limit(5)
                  ->get();
          } elseif ($user->isClient()) {
              // Client (website owner) sees submissions from their website only
              $activeForms = Shortcode::where('client_id', $user->id)->count();
              $totalSubmissions = FormSubmission::where('client_id', $user->id)->count();
              $totalShortcodes = Shortcode::where('client_id', $user->id)->count();
              $recentSubmissions = FormSubmission::where('client_id', $user->id)
                  ->with(['customForm', 'user', 'admin'])
                  ->latest()
                  ->limit(5)
                  ->get();
              $recentForms = CustomForm::whereHas('submissions', fn($q) => $q->where('client_id', $user->id))
                  ->with(['user', 'admin'])
                  ->latest()
                  ->limit(5)
                  ->get();
          } else {
              // Regular user sees their own data
              $activeForms = CustomForm::where('user_id', $user->id)
                  ->where('status', 'active')
                  ->count();
              $totalSubmissions = FormSubmission::where('user_id', $user->id)->count();
              $totalShortcodes = Shortcode::where('user_id', $user->id)->count();
              $recentSubmissions = FormSubmission::where('user_id', $user->id)
                  ->with(['customForm', 'user', 'admin'])
                  ->latest()
                  ->limit(5)
                  ->get();
              $recentForms = CustomForm::where('user_id', $user->id)
                  ->with(['user', 'admin'])
                  ->latest()
                  ->limit(5)
                  ->get();
          }
          
          // Get submissions from last 7 days
          $submissionsLast7Days = FormSubmission::when($user->isAdmin(), function($query) use ($user) {
                  $query->where('admin_id', $user->id);
              })
              ->when($user->isClient(), function($query) use ($user) {
                  $query->where('client_id', $user->id);
              })
              ->when($user->isUser(), function($query) use ($user) {
                  $query->where('user_id', $user->id);
              })
              ->where('created_at', '>=', now()->subDays(7))
              ->count();
          
          $recentLoginActivities = $user->isSuperAdmin()
              ? ($recentLoginActivities ?? collect())
              : collect();

          return view('user.dashboard', compact(
              'activeForms',
              'totalSubmissions',
              'totalShortcodes',
              'submissionsLast7Days',
              'recentSubmissions',
              'recentForms',
              'recentLoginActivities'
          )); 
     }

    
     public function edit_new_form() {
          return view('user.form.edit');
     }

    /**
     * Profile (account) page – works for both User and Client.
     */
    public function user_account()
    {
        $user = currentUser();
        return view('user.account', compact('user'));
    }

    /**
     * Update profile – works for both User and Client.
     */
    public function update_user_profile(Request $request)
    {
        $user = currentUser();

        if ($user->isClient()) {
            $request->validate([
                'name'       => 'required|string|max:255',
                'first_name' => 'nullable|string|max:255',
                'last_name'  => 'nullable|string|max:255',
                'website'    => 'nullable|string|url|max:255',
            ]);
            $user->update($request->only(['name', 'first_name', 'last_name', 'website']));
        } else {
            $request->validate([
                'name'       => 'required|string|max:255',
                'first_name' => 'nullable|string|max:255',
                'last_name'  => 'nullable|string|max:255',
            ]);
            $user->update($request->only(['name', 'first_name', 'last_name']));
        }

        return redirect()->route('user_account')->with('success', 'Profile updated successfully.');
    }

    /**
     * Change password page – works for both User and Client.
     */
    public function change_user_password()
    {
        $user = currentUser();
        return view('user.password', compact('user'));
    }

    /**
     * Update password – works for both User and Client.
     */
    public function update_user_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = currentUser();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        if (strtolower($request->password) === strtolower($user->email)) {
            return back()->withErrors(['password' => 'Password must be different from your email.'])->withInput();
        }

        $user->update(['password' => $request->password]);

        return redirect()->route('change_user_password')->with('success', 'Password changed successfully.');
    }
}
