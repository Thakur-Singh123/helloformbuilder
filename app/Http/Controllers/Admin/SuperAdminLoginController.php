<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginActivity;
use DB;

/**
 * Separate URL and login form for Super Admin only.
 * Admins use /admin; Super Admins use /superadmin.
 */
class SuperAdminLoginController extends Controller
{
    /**
     * Show Super Admin login page.
     */
    public function index()
    {
        try {
            $settings = DB::table('settings')->first();
        } catch (\Exception $e) {
            $settings = null;
        }
        if (!$settings) {
            $settings = (object) [
                'site_title' => config('app.name', 'LeadMatrix'),
                'icon' => 'favicon.ico',
            ];
        }
        return view('admin.superadmin.login', compact('settings'));
    }

    /**
     * Attempt Super Admin login. Only user_type === 'superadmin' can log in here.
     */
    public function attempt_login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|max:255',
        ]);

        $email = $request->email;
        $password = $request->password;

        if (!Auth::attempt(['email' => $email, 'password' => $password, 'is_active' => true])) {
            return back()->with('danger', 'Credentials do not match our records.');
        }

        $user = Auth::user();
        if ($user->user_type !== 'superadmin') {
            Auth::logout();
            return back()->with('danger', 'This login is for Super Admin only. Use the Admin login for your account.');
        }

        LoginActivity::create([
            'user_id'    => $user->id,
            'user_type'  => $user->user_type,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'logged_at'  => now(),
        ]);

        return redirect()->route('user_dashboard')->with('success', 'Welcome back.');
    }

    /**
     * Logout Super Admin and redirect to Super Admin login page.
     */
    public function logout(Request $request)
    {
        if (Auth::user()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return redirect()->route('superadmin_login');
    }
}
