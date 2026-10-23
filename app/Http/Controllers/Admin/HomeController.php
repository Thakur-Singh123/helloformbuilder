<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Redirect to same dashboard as user (sidebar layout).
     */
    public function index()
    {
        return redirect()->route('user_dashboard');
    }

    public function my_profile()
    {
        return view('admin.my_profile');
    }

    public function change_password()
    {
        return view('admin.change_password');
    }

    public function update_password(Request $request)
    {
        // Placeholder - implement as needed
        return back()->with('success', 'Password updated.');
    }

    public function update_admin_profile(Request $request)
    {
        // Placeholder - implement as needed
        return back()->with('success', 'Profile updated.');
    }
}
