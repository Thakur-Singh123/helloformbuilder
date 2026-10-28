<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WelcomeController;  
use App\Http\Controllers\CommonController;
use App\Http\Controllers\UserLoginController;

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SuperAdminLoginController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\TeamsController;

use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserCustomFormController;
use App\Http\Controllers\User\ShortcodeController;
use App\Http\Controllers\User\SubmissionController;
use App\Http\Controllers\User\InvoiceController;

use App\Http\Controllers\Employee\EmployeeController;

use App\Http\Controllers\FormController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\User\TempFormsController;
use App\Http\Controllers\Admin\TemplateFormsController;
use App\Http\Controllers\Admin\TemplatesController;
use App\Http\Controllers\PlanController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
    /* Cache Clear Start*/
		Route::get('/cache', function(){
			Artisan::call('cache:clear');
			Artisan::call('config:clear');
			Artisan::call('route:clear');
			Artisan::call('view:clear');
			 return redirect('/');
		});
    /* Cache Clear End*/  

    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
    Route::post('/subscribe', [WelcomeController::class, 'subscribe'])->name('newsletter.subscribe');

	Route::get('why-us', [WelcomeController::class, 'why_us']);
	Route::get('pricing', [WelcomeController::class, 'pricing'])->name('pricing');
	Route::get('about', [WelcomeController::class, 'about']);

	Route::get('login', [UserLoginController::class, 'login'])->name('login');
	Route::post('user_login', [UserLoginController::class, 'user_login'])->name('user_login');
	Route::get('login/select-account', [UserLoginController::class, 'showSelectAccount'])->name('login.select_account');
	Route::post('login/select-client', [UserLoginController::class, 'selectClient'])->name('login.select_client');

	Route::get('sign-up', [UserLoginController::class, 'sign_up'])->name('sign_up');
	Route::post('user_register', [UserLoginController::class, 'user_register'])->name('user_register');

	Route::get('register/check-email', [UserLoginController::class, 'check_email_page'])->name('verification.check_email');
	Route::get('verify-email', [UserLoginController::class, 'verify_email'])->name('verification.verify');
	Route::post('resend-verification', [UserLoginController::class, 'resend_verification'])->name('verification.resend');

	Route::get('forgot-password', [UserLoginController::class, 'forgot_password'])->name('forgot_password');
	Route::post('sendResetLink', [UserLoginController::class, 'sendResetLink'])->name('sendResetLink');
	
    Route::get('reset-password-form', [UserLoginController::class, 'showNewPasswordForm'])->name('showNewPasswordForm');
	Route::post('resetPassword', [UserLoginController::class, 'resetPassword'])->name('resetPassword');

    Route::match(['get', 'post'], 'logout', [UserLoginController::class, 'logout'])->name('logout');

	// Public shortcode routes - accessible without authentication
	Route::get('/shortcode/{code}', [ShortcodeController::class, 'render'])->name('shortcode.render');
	Route::post('/shortcode/{code}/submit', [ShortcodeController::class, 'submit'])->name('shortcode.submit');

    //Auth
	Route::get('/start',  [UserLoginController::class, 'gate'])->name('template.gate');
    Route::post('/start', [UserLoginController::class, 'gateCheck'])->name('template.gate.check');

	//Template gallery
	Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
	Route::get('/templates/{template}', [TemplateController::class, 'show'])->name('templates.show');
	// Route::match(['get', 'post'], '/templates/{template}/use', [TemplateController::class, 'use'])->middleware('auth')->name('templates.use');
	Route::match(['get', 'post'], '/templates/{template}/use', [TemplateController::class, 'use'])->middleware(['auth', 'template.limit'])->name('templates.use');

	//Form editor
	Route::middleware('auth')->group(function () {
		Route::get('/forms/{form}/edit', [FormController::class, 'edit'])->name('forms.edit');
		Route::put('/forms/{form}', [FormController::class, 'update'])->name('forms.update');
		Route::post('/forms/{form}/publish', [FormController::class, 'publish'])->name('forms.publish');
		//Plan
		Route::get('/plans/upgrade', [PlanController::class, 'upgrade'])->name('plans.upgrade');
		Route::post('/plans/checkout', [PlanController::class, 'checkout'])->name('plans.checkout');
		Route::get('/plans/checkout', [PlanController::class, 'checkoutPage'])->name('plans.checkout.page');
		Route::get('/plans/success', [PlanController::class, 'success'])->name('plans.success');
	});

	//Public form
	Route::get('/f/{slug}', [FormController::class, 'showPublic'])->name('forms.public');
	Route::post('/f/{slug}', [FormController::class, 'submit'])->name('forms.submit');
	
	//API routes for embedding
	Route::get('/api/shortcode/{code}/data', [\App\Http\Controllers\Api\ShortcodeApiController::class, 'getFormData'])->name('api.shortcode.data');
	Route::get('/api/shortcode/{code}/html', [\App\Http\Controllers\Api\ShortcodeApiController::class, 'getFormHtml'])->name('api.shortcode.html');

	Route::group(['middleware' => ['auth_user_or_client', 'user']], function(){
			Route::group(['namespace'=>'User','prefix'=>'user'], function(){

	          Route::get('/dashboard', [UserDashboardController::class, 'user_dashboard'])->name('user_dashboard');
			  Route::get('/build-form', [UserCustomFormController::class, 'buildFormLanding'])->name('build_form');
			  Route::get('/list-view', [UserCustomFormController::class, 'list_view'])->name('list_view');

			  Route::get('/add-new-form', [UserCustomFormController::class, 'add_new_form'])->name('add_new_form');
			  Route::get('/edit-new-form/{form}', [UserCustomFormController::class, 'edit_form'])->name('edit_form');
			  // Specific path must come before parameterized route so "store" is not matched as {form}
			  Route::post('/custom-form/store', [UserCustomFormController::class, 'custom_form_save'])->name('custom_form_save');
			  Route::post('/custom-form/{form}/status', [UserCustomFormController::class, 'update_form_status'])->name('custom_form_status');
			  Route::post('/custom-form/{form}', [UserCustomFormController::class, 'custom_form_update'])->name('custom_form_update');

			  // Shortcode routes - only accessible to logged-in users
			  Route::get('/shortcodes', [ShortcodeController::class, 'index'])->name('shortcodes.index');
			  Route::get('/shortcodes/create', [ShortcodeController::class, 'create'])->name('shortcodes.create');
			  Route::post('/shortcodes/store', [ShortcodeController::class, 'store'])->name('shortcodes.store');
			  Route::put('/shortcodes/{shortcode}', [ShortcodeController::class, 'update'])->name('shortcodes.update');
			  Route::delete('/shortcodes/{shortcode}', [ShortcodeController::class, 'destroy'])->name('shortcodes.destroy');

			  // Submission routes - view form submissions
			  Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
			  Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
			  Route::post('/submissions/{submission}/hire', [SubmissionController::class, 'hire'])->name('submissions.hire');
			  Route::post('/submissions/{submission}/cancel', [SubmissionController::class, 'cancel'])->name('submissions.cancel');
			  Route::post('/submissions/{submission}/reply', [SubmissionController::class, 'reply'])->name('submissions.reply');
			  Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');

			  // Invoice routes - draft auto-created when a lead is hired
			  Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
			  Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
			  Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
			  Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');

			  // Delete form route
			  Route::delete('/form/{form}', [UserCustomFormController::class, 'delete_form'])->name('delete_form');

			  Route::get('orders', [UserDashboardController::class, 'user_orders'])->name('user_orders');
	          
			  Route::get('address', [UserDashboardController::class, 'user_address'])->name('user_address'); 
			  Route::post('update_user_address', [UserDashboardController::class, 'update_user_address'])->name('update_user_address');

			  Route::get('account', [UserDashboardController::class, 'user_account'])->name('user_account');
			  Route::post('update_user_profile', [UserDashboardController::class, 'update_user_profile'])->name('update_user_profile');
 
	          Route::get('password', [UserDashboardController::class, 'change_user_password'])->name('change_user_password');
	          Route::post('update_user_password', [UserDashboardController::class, 'update_user_password'])->name('update_user_password');

			   //Tempform
			   Route::get('/forms', [TempFormsController::class, 'index'])->name('forms.index');
               Route::get('/forms/{form}/responses', [TempFormsController::class, 'responses'])->name('forms.responses');

		 });
	 });

	// Super Admin: separate URL and login form (only superadmin can log in here)
	Route::get('/superadmin', [SuperAdminLoginController::class, 'index'])->name('superadmin_login');
	Route::post('/superadmin/login', [SuperAdminLoginController::class, 'attempt_login'])->name('superadmin_attempt_login');
	Route::match(['get', 'post'], '/superadmin/logout', [SuperAdminLoginController::class, 'logout'])->name('superadmin_logout');

  	Route::get('/admin', [LoginController::class, 'index'])->name('admin_login');
	Route::post('/admin/login', [LoginController::class, 'attempt_login'])->name('attempt_login');
	Route::match(['get', 'post'], 'admin/logout', [LoginController::class, 'logout'])->name('admin_logout');
	  
	Route::group(['middleware' => 'auth', 'admin'], function(){ 
		Route::group(['namespace'=>'Admin','prefix'=>'admin'], function(){
											
					Route::get('dashboard', [HomeController::class, 'index'])->name('admin_dashboard');
					Route::get('my-profile', [HomeController::class, 'my_profile'])->name('my_profile');
					
					Route::get('change-password', [HomeController::class, 'change_password'])->name('change_password');
					Route::post('update_password', [HomeController::class, 'update_password'])->name('update_password');
					Route::post('update_admin_profile', [HomeController::class, 'update_admin_profile'])->name('update_admin_profile');

					// Super Admin only: Admins (companies) CRUD + system settings
					Route::get('admins', [AdminUserController::class, 'index'])->name('admin.admins.index');
					Route::get('admins/create', [AdminUserController::class, 'create'])->name('admin.admins.create');
					Route::post('admins', [AdminUserController::class, 'store'])->name('admin.admins.store');
					Route::get('admins/{id}', [AdminUserController::class, 'show'])->name('admin.admins.show');
					Route::get('admins/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.admins.edit');
					Route::put('admins/{id}', [AdminUserController::class, 'update'])->name('admin.admins.update');
					Route::post('admins/{id}/deactivate', [AdminUserController::class, 'deactivate'])->name('admin.admins.deactivate');
					Route::post('admins/{id}/activate', [AdminUserController::class, 'activate'])->name('admin.admins.activate');
					Route::delete('admins/{id}', [AdminUserController::class, 'destroy'])->name('admin.admins.destroy');

						// Teams routes - draft auto-created when a lead is hired
					Route::get('/teams', [TeamsController::class, 'index'])->name('admin.teams.index');
					Route::get('/add-team-member', [TeamsController::class, 'create'])->name('admin.teams.create');
					Route::post('/add-team-member', [TeamsController::class, 'store'])->name('admin.teams.store');
					Route::get('/team-member/{id}/edit', [TeamsController::class, 'edit'])->name('admin.teams.edit');
					Route::put('/team-member/{id}', [TeamsController::class, 'update'])->name('admin.teams.update');
					Route::delete('/team-member/{id}', [TeamsController::class, 'destroy'])->name('admin.teams.destroy');

					Route::get('/tasks/assign/{employee}', [TeamsController::class, 'tasks_create'])->name('tasks.create');
    		  		Route::post('/tasks/store', [TeamsController::class, 'tasks_store'])->name('tasks.store');

					Route::get('tasks/{task_id}/edit', [TeamsController::class, 'tasks_edit'])->name('tasks.edit');
					Route::put('tasks/{task_id}', [TeamsController::class, 'tasks_update'])->name('tasks.update');
					Route::delete('tasks/{task_id}', [TeamsController::class, 'tasks_destroy'])->name('tasks.destroy');
					Route::get('teams/{employee_id}/tasks', [TeamsController::class, 'tasks_list'])->name('teams.tasks');
 
					// Admin (company) only: Clients (website owners) - basic info only
					Route::get('clients', [ClientController::class, 'index'])->name('admin.clients.index');
					Route::get('clients/create', [ClientController::class, 'create'])->name('admin.clients.create');
					Route::post('clients', [ClientController::class, 'store'])->name('admin.clients.store');
					Route::get('clients/{id}', [ClientController::class, 'show'])->name('admin.clients.show');
					Route::get('clients/{id}/edit', [ClientController::class, 'edit'])->name('admin.clients.edit');
					Route::put('clients/{id}', [ClientController::class, 'update'])->name('admin.clients.update');
					Route::delete('clients/{id}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');
					//Templates management
					Route::get('/manage-templates', [TemplatesController::class, 'index'])->name('admin_templates.index');
					Route::get('/manage-templates/create', [TemplatesController::class, 'create'])->name('admin_templates.create');
					Route::post('/manage-templates', [TemplatesController::class, 'store'])->name('admin_templates.store');
					Route::get('/manage-templates/{id}/edit', [TemplatesController::class, 'edit'])->name('admin_templates.edit');
					Route::put('/manage-templates/{id}', [TemplatesController::class, 'update'])->name('admin_templates.update');
					Route::post('/manage-templates/{id}/toggle', [TemplatesController::class, 'toggle'])->name('admin_templates.toggle');
					Route::delete('/manage-templates/{id}', [TemplatesController::class, 'destroy'])->name('admin_templates.destroy');
					
					//Templates form
					Route::get('/client-forms', [TemplateFormsController::class, 'index'])->name('admin_forms.index');
                    Route::get('/client-forms/{form}/responses', [TemplateFormsController::class, 'responses'])->name('admin_forms.responses');
	});

	Route::middleware(['auth', 'employee'])->prefix('employee')->name('employee.')->group(function () {

			Route::get('/dashboard', [EmployeeController::class, 'index'])
				->name('dashboard');

			  Route::get('/attendance', [EmployeeController::class, 'attendance'])->name('attendance');
    		  Route::post('/attendance/action', [EmployeeController::class, 'action'])->name('attendance.action'); 

			  Route::get('/tasks', [EmployeeController::class, 'tasks'])->name('tasks'); 
			
		});
		
	 });

 