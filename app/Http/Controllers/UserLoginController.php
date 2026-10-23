<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User;
use App\Models\Client;
use App\Models\LoginActivity;
use DB;  
use Str;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;  
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Input; 
use App\Helpers\Common; 
use Session;
use Mail; 
class UserLoginController extends Controller {  

    public function __construct() { 
    } 
	 
     public function login(Request $request) {  
          $settings = $this->getSettingsForView();  
          return view('login/login', compact('settings')); 
     }
  
    public function user_login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Try User (admin, superadmin, user) first
        $user = User::where('email', $credentials['email'])->where('is_active', true)->first();

        if ($user) {
            if (!$user->email_verified_at) {
                return back()->with('error', 'Your account is not verified yet. We sent a verification link to your email when you signed up—please check your inbox (and spam folder) and click that link to verify your account. You can then log in here.')
                    ->with('unverified_email', $user->email);
            }
            if ($user->user_type === 'superadmin') {
                return back()->with('error', 'Super Admin accounts must use the Super Admin login at ' . route('superadmin_login') . '.');
            }
            if (Auth::guard('web')->attempt($credentials, $remember)) {
                $loggedIn = Auth::guard('web')->user();

                LoginActivity::create([
                    'user_id'    => $loggedIn->id,
                    'user_type'  => $loggedIn->user_type,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'logged_at'  => now(),
                ]);

                if ($loggedIn->user_type === 'employee') {
                    return redirect()
                        ->route('employee.dashboard')
                        ->with('success', 'Welcome back!');
                }

                if ($loggedIn->user_type === 'client') {
                    return redirect()
                        ->intended(route('templates.index'))
                        ->with('success', 'Welcome back!');
                }

                return redirect()
                    ->route('user_dashboard')
                    ->with('success', 'Welcome back!');
            }
        }

        // Try Client (separate clients table) — may have multiple accounts (same email, different admins)
        $clients = Client::where('email', $credentials['email'])->where('is_active', true)->with('admin')->get();
        $matchingClients = $clients->filter(function ($c) use ($credentials) {
            return Hash::check($credentials['password'], $c->password);
        });
        if ($matchingClients->isEmpty()) {
            if ($clients->isNotEmpty()) {
                return back()->with('error', 'Invalid email or password. Please try again.');
            }
        } else {
            $first = $matchingClients->first();
            if (!$first->email_verified_at) {
                return back()->with('error', 'Your account is not verified yet. We sent a verification link to your email when you signed up—please check your inbox (and spam folder) and click that link to verify your account. You can then log in here.')
                    ->with('unverified_email', $first->email);
            }
            if ($matchingClients->count() === 1) {
                Auth::guard('client')->login($first, $remember);
                LoginActivity::create([
                    'user_id'    => $first->id,
                    'user_type'  => 'client',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'logged_at'  => now(),
                ]);
                return redirect()->route('user_dashboard')->with('success', 'Welcome back!');
            }
            // Multiple client accounts with same email + password — let user choose which to open
            $request->session()->put('login_pending_client_ids', $matchingClients->pluck('id')->values()->all());
            $request->session()->put('login_pending_remember', $remember);
            return redirect()->route('login.select_account');
        }

        return back()->with('error', 'Invalid email or password. Please try again.');
    }

    /**
     * Show "select which account" when multiple client accounts exist for the same email.
     */
    public function showSelectAccount(Request $request)
    {
        $ids = $request->session()->get('login_pending_client_ids', []);
        if (empty($ids)) {
            return redirect()->route('login')->with('error', 'Please sign in with your email and password first.');
        }
        $clients = Client::whereIn('id', $ids)->with('admin')->get();
        if ($clients->count() !== count($ids)) {
            $request->session()->forget(['login_pending_client_ids', 'login_pending_remember']);
            return redirect()->route('login')->with('error', 'Session expired. Please sign in again.');
        }
        $settings = $this->getSettingsForView();
        return view('login.select_account', compact('clients', 'settings'));
    }

    // app/Http/Controllers/Auth/LoginController.php
        protected function authenticated(Request $request, $user)
        {
            session(['login_time' => now()]);
        }

    /**
     * Log in as the chosen client account (after select-account step).
     */
    public function selectClient(Request $request)
    {
        $request->validate(['client_id' => 'required|integer']);
        $ids = $request->session()->get('login_pending_client_ids', []);
        $remember = $request->session()->get('login_pending_remember', false);
        if (!in_array((int) $request->client_id, $ids, true)) {
            $request->session()->forget(['login_pending_client_ids', 'login_pending_remember']);
            return redirect()->route('login')->with('error', 'Invalid selection. Please sign in again.');
        }
        $client = Client::find($request->client_id);
        if (!$client || !$client->is_active) {
            $request->session()->forget(['login_pending_client_ids', 'login_pending_remember']);
            return redirect()->route('login')->with('error', 'Account not available. Please sign in again.');
        }
        $request->session()->forget(['login_pending_client_ids', 'login_pending_remember']);
        Auth::guard('client')->login($client, $remember);
        LoginActivity::create([
            'user_id'    => $client->id,
            'user_type'  => 'client',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'logged_at'  => now(),
        ]);
        return redirect()->route('user_dashboard')->with('success', 'Welcome back!');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('client')->logout();
        $request->session()->flush();
        return redirect('/');
    }
 
    public function sign_up() {  
        $settings = $this->getSettingsForView();
        return view('login/sign_up', compact('settings')); 
    }


     public function user_register(Request $request)
    {
        $request->validate([
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:6',
            'confirm_password'=> 'required|min:6|same:password',
        ], [
            'email.unique' => 'Please use another email. This email is already in use.',
        ]);

        $user = new User();
        $user->first_name               = $request->first_name;
        $user->last_name                = $request->last_name;
        $user->name                    = trim($request->first_name . ' ' . ($request->last_name ?? '')) ?: $request->email;
        $user->email                    = $request->email;
        $user->password                 = Hash::make($request->password);
        $user->user_type                = 'client';
        $user->email_verified_at        = null;
        $user->email_verification_token = Str::random(64);
        $user->is_active                = true;
        $user->save();

        $this->sendVerificationEmail($user);

        return redirect()->route('verification.check_email')->with('registered_email', $user->email);
    }

    /**
     * Show the "check your email" page after sign-up.
     * Always shows a message; uses session email if available.
     */
    public function check_email_page(Request $request)
    {
        $email = null;
        try {
            $email = $request->session()->get('registered_email');
            if (!is_string($email) || $email === '') {
                $email = null;
            }
        } catch (\Throwable $e) {
            // Session may be unavailable; show generic message
        }
        return response()->view('login.check_email', [
            'email' => $email,
            'loginUrl' => url('login'),
        ]);
    }

    /**
     * Send email verification link to the user.
     */
    protected function sendVerificationEmail(User $user)
    {
        $settings = DB::table('settings')->first();
        $verifyUrl = url('/verify-email?token=' . urlencode($user->email_verification_token) . '&email=' . urlencode($user->email));

        $name = trim($user->first_name . ' ' . ($user->last_name ?? ''));
        if ($name === '') {
            $name = $user->email;
        }

        $mailData = [
            'name'     => $name,
            'verifyUrl'=> $verifyUrl,
            'siteName' => $settings->site_title ?? config('app.name'),
        ];

        Mail::send('email.verify_email', $mailData, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verify your email address - ' . config('app.name'))
                ->from(config('mail.from.address'), config('mail.from.name'));
        });
    }


    /**
     * Verify email via link sent to user (token + email in query).
     */
    public function verify_email(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('login')->with('error', 'Invalid or missing verification link.');
        }

        $user = User::where('email', $email)
            ->where('email_verification_token', $token)
            ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification link. You can request a new one from the login page.');
        }

        $user->email_verified_at        = now();
        $user->email_verification_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Your email has been verified. You can now log in to your account.');
    }

    /**
     * Resend verification email (e.g. from login page when user is not verified).
     */
    public function resend_verification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'We couldn\'t find an account with that email.');
        }

        if ($user->email_verified_at) {
            return back()->with('info', 'This account is already verified. You can log in.');
        }

        $user->email_verification_token = Str::random(64);
        $user->save();

        $this->sendVerificationEmail($user);

        return back()->with('success', 'A new verification link has been sent to your email.');
    }

public function verify_account($id){
             if (DB::table('users')->where('email_token','=',$id)->exists()) {
                        DB::table('users')->where('email_token','=',$id)
                        ->update(['isactive'=>'1','email_verified_at'=>date('Y-m-d H:i:s'),'email_verify'=>'yes']);
                    $data['success'] = 'true';
                    $data['title'] = 'Email verification completed.';
                    $data['message'] = 'Congratulations!!!your account has been activated.';
                    $data['image'] = url('public/uploads/email-success.png');
            }else{
                    $data['success'] = 'false';
                    $data['title'] = 'Sorry!!! Account verification fail.';
                    $data['message'] = 'Please check your email for verify account!!!';
                    $data['image'] = url('public/uploads/email-error.png');
            }
            return view('login/email_verify',$data);
} 

 

public function forgot_username() {   
    return view('login/forgot_username'); 
}
 
public function reset_username(Request $request) {  
    
     $type = $request->type;
    $email = $request->email; 
    // Determine the user type and fetch user data
    if ($type == 'vendor') {
        $user = Vendor::where('email',$email)->first();
    } elseif ($type == 'client') {
        $user = Client::where('email',$email)->first();
    } else {
        $user = User::where('email',$email)->where('user_type',$type)->first();
    }
    if (empty($user)) {
        return back()->with('error', "We couldn't find the account you're looking for. Kindly request a new one or reach out to support.");
   }

   if ($user->status != 'active') {
        return back()->with('error', 'Your account has been banned, please contact the support team.');
    } 

    $settings  = DB::table("settings")->first();
    $array['subject'] = 'Forgot Username Request Confirmation';
    $array['name'] = $user->title . ' ' . $user->first_name . ' ' . $user->last_name;
    $array['email'] = $user->email;
    $array['username'] = $user->username;
    $mail =  Mail::send('email.reset_username_mail', $array, function($message) use ($array) {
        $message->to($array['email'])
                ->subject($array['subject'])
                ->from(config('mail.from.address'), config('mail.from.name'));
    }); 
    $email = $user->email;
    $maskedEmail = preg_replace('/(?<=.{3}).(?=.*@)/', '*', $email);
    return back()->with('success', 'Username has been sent to your registered email address (' . $maskedEmail . '). Please check your inbox.');
    

    
}
 



public function forgot_password() {
    $settings = $this->getSettingsForView();
    return view('login/forgot_password', compact('settings'));
}
 
public function sendResetLink(Request $request) {
    $request->validate(['email' => 'required|email']);
    $email = $request->email;

    // 1) Try users table (admin, user, superadmin)
    $user = User::where('email', $email)->first();
    if ($user) {
        if (!$user->is_active) {
            return back()->with('error', 'Your account is inactive. Please contact support.');
        }
        $type = $user->user_type; // 'admin', 'user', or 'superadmin'
        $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->name;
        $this->sendPasswordResetEmail($email, $type, $name);
        $maskedEmail = preg_replace('/(?<=.{3}).(?=.*@)/', '*', $email);
        return back()->with('success', "A password reset link has been sent to your email ($maskedEmail).");
    }

    // 2) Try clients table (client)
    $client = Client::where('email', $email)->first();
    if ($client) {
        if (!$client->is_active) {
            return back()->with('error', 'Your account is inactive. Please contact support.');
        }
        $type = 'client';
        $name = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? '')) ?: $client->name;
        $this->sendPasswordResetEmail($email, $type, $name);
        $maskedEmail = preg_replace('/(?<=.{3}).(?=.*@)/', '*', $email);
        return back()->with('success', "A password reset link has been sent to your email ($maskedEmail).");
    }

    return back()->with('error', "We couldn't find an account with that email.");
}

/**
 * Send password reset email and store token. Used for both User (admin/user/superadmin) and Client.
 */
protected function sendPasswordResetEmail(string $email, string $type, string $name): void
{
    $token = Str::random(64);
    DB::table('password_resets')->updateOrInsert(
        ['email' => $email, 'user_type' => $type],
        ['token' => bcrypt($token), 'created_at' => now()]
    );
    $settings = DB::table('settings')->first();
    $resetUrl = url("/reset-password-form?token=$token&email=" . urlencode($email) . "&type=" . urlencode($type));
    $mailData = [
        'name' => $name ?: $email,
        'email' => $email,
        'subject' => 'Password Reset Request',
        'resetUrl' => $resetUrl,
        'sender_name' => $settings->sender_name ?? config('mail.from.name'),
    ];
    Mail::send('email.reset_password_mail', $mailData, function ($message) use ($mailData) {
        $message->to($mailData['email'])
            ->subject($mailData['subject'])
            ->from(config('mail.from.address'), $mailData['sender_name']);
    });
}


public function showNewPasswordForm(Request $request){
    $email = $request->query('email');
    $token = $request->query('token');
    $type = $request->query('type');
    $settings = $this->getSettingsForView();
    return view('login.reset-password-form', compact('email', 'token', 'type', 'settings'));
}

/**
 * Return settings object for auth views (forgot password, reset form). Safe when table missing or empty.
 */
protected function getSettingsForView()
{
    try {
        $settings = DB::table('settings')->first();
    } catch (\Throwable $e) {
        $settings = null;
    }
    if (!$settings) {
        return (object) [
            'seo_title' => config('app.name'),
            'seo_tag' => '',
            'seo_description' => '',
            'site_name' => config('app.name'),
            'site_title' => config('app.name'),
            'logo' => '',
        ];
    }
    if (!isset($settings->site_name)) {
        $settings->site_name = $settings->site_title ?? config('app.name');
    }
    return $settings;
}

public function resetPassword(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'token' => 'required|string',
        'type' => 'required|string|in:admin,user,superadmin,client,employee',
        'password' => ['required', 'min:6', 'confirmed', 'different:email'],
    ], [
        'password.different' => 'The password cannot be the same as your email address. Please choose a different password for security.',
    ]);
    $email = $request->email;
    $type = $request->type;

    $record = DB::table('password_resets')->where('email', $email)->where('user_type', $type)->first();
    if (!$record || !Hash::check($request->token, $record->token)) {
        return back()->with('error', 'Invalid or expired reset token.');
    }

    if ($type === 'client') {
        $client = Client::where('email', $email)->first();
        if (!$client) {
            return back()->with('error', 'Account not found.');
        }
        $client->password = Hash::make($request->password);
        $client->save();
    } else {
        $user = User::where('email', $email)->where('user_type', $type)->first();
        if (!$user) {
            return back()->with('error', 'Account not found.');
        }
        $user->password = Hash::make($request->password);
        $user->save();
    }

    DB::table('password_resets')->where('email', $email)->where('user_type', $type)->delete();
    return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now log in.');
}


public function gate()
{
    return view('login.gate');
}

public function gateCheck(Request $request)
{
    $request->validate([
        'email'        => 'required|email',
        'template_url' => 'nullable|string|max:500',
    ]);
    $target = $request->input('template_url');
    if ($target && \Illuminate\Support\Str::startsWith($target, url('/templates/'))) {
        session(['url.intended' => $target]);
    }

    $email = $request->email;

    if (User::where('email', $email)->exists()) {
        return redirect()->route('login')
            ->withInput(['email' => $email])
            ->with('info', 'Welcome back! Please log in to continue with your template.');
    }

    return redirect()->route('sign_up')
        ->withInput(['email' => $email])
        ->with('info', 'Create your account to continue with your template.');
}

}

?>