<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\CustomForm;
use App\Models\FormSubmission;
use App\Policies\FormPolicy;
use App\Policies\SubmissionPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        CustomForm::class => FormPolicy::class,
        FormSubmission::class => SubmissionPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(CustomForm::class, FormPolicy::class);
        Gate::policy(FormSubmission::class, SubmissionPolicy::class);
        
        view()->composer('*',function($settings){
			
             // $settings->with('settings', DB::table('settings')->where('id',1)->first());
			  
		});
    }
}
