<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;
class LoginController extends Controller
{
    public function __construct() {
        
    }
	//show admin page
	public function index(){
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
		return view('admin/login', compact('settings'));
	}
	//function for submit admin login form
    public function attempt_login(Request $request){
        request()->validate([
            'email' => 'required', 'email',
            'password' => 'required', 'string', 'max:255'
        ]);
  
	   	$email = $request->email;
    	$password = $request->password;
    	// Check validation
		// Admin login: only Admin (not Super Admin) — Super Admin uses /superadmin
		if (Auth::attempt(['email' => $email, 'password' => $password, 'is_active' => true])) {
	        $user = Auth::user();
	        if ($user->user_type === 'superadmin') {
	            Auth::logout();
	            return redirect()->route('superadmin_login')->with('info', 'Use the Super Admin login at /superadmin.');
	        }
	        if ($user->user_type === 'admin') {
	            return redirect()->route('user_dashboard');
	        }
	        Auth::logout();
	        return redirect('/');
		}
		return back()->with('danger', 'Credentials do not match our records.');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function logout(Request $request)
    {
        if (!Auth::user()) {
            return redirect('/admin');
        }
        $user = Auth::user();

		Auth::logout();
		if ($user->user_type === 'superadmin') {
        	return redirect()->route('superadmin_login');
		}
		if ($user->user_type === 'admin') {
        	return redirect('/admin');
		}
		return redirect('/');
    }
}
