<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WelcomeController extends Controller
{
    /**
     * Welcome / landing page with dynamic content.
     */
    public function index()
    {
        $appName = config('app.name', 'Lead Matrix');
        $tagline = 'Build forms once. Embed anywhere. Collect leads effortlessly.';
        $heroSubtext = 'Create custom forms, generate embed codes for any website or CMS, and manage all submissions in one place.';
        $features = [
            [
                'icon' => 'bi-code-square',
                'title' => 'Universal Form Builder',
                'description' => 'Create contact, survey, or reservation forms with a simple builder. Embed on any site with one shortcode.',
            ],
            [
                'icon' => 'bi-envelope-check',
                'title' => 'Instant Notifications',
                'description' => 'Get email alerts on new submissions. All data is stored securely and viewable in your dashboard.',
            ],
            [
                'icon' => 'bi-shield-check',
                'title' => 'Multi-tenant & Secure',
                'description' => 'Role-based access, form versioning, and data isolation. Built for teams and agencies.',
            ],
        ];
        $stats = [
            ['value' => 'Embed anywhere', 'label' => 'HTML, React, WordPress, Shopify'],
            ['value' => 'Unlimited forms', 'label' => 'Create as many as you need'],
            ['value' => 'Real-time', 'label' => 'Submissions in your inbox'],
        ];

        $subscribeTitle = 'Stay in the loop';
        $subscribeDescription = 'Tips, updates, and product news. No spam.';

        $plans = [
            [
                'name' => 'Silver',
                'price' => '29',
                'period' => '/month',
                'description' => 'Perfect for small projects and personal use.',
                'features' => ['Up to 5 forms', '1,000 submissions/month', 'Email support', 'Basic analytics'],
                'highlighted' => false,
                'cta' => 'Get started',
            ],
            [
                'name' => 'Gold',
                'price' => '79',
                'period' => '/month',
                'description' => 'Best for growing teams and agencies.',
                'features' => ['Up to 25 forms', '10,000 submissions/month', 'Priority support', 'Advanced analytics', 'Multi-user access'],
                'highlighted' => true,
                'cta' => 'Choose Gold',
            ],
            [
                'name' => 'Platinum',
                'price' => '199',
                'period' => '/month',
                'description' => 'For large organizations with high volume.',
                'features' => ['Unlimited forms', 'Unlimited submissions', 'Dedicated support', 'Custom integrations', 'API access', 'SSO'],
                'highlighted' => false,
                'cta' => 'Contact sales',
            ],
        ];

        return view('welcome', compact('appName', 'tagline', 'heroSubtext', 'features', 'stats', 'subscribeTitle', 'subscribeDescription', 'plans'));
    }

    /**
     * Handle newsletter / subscription signup.
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->input('email');
        NewsletterSubscriber::firstOrCreate(
            ['email' => $email],
            ['subscribed_at' => now()]
        );

        return response()->json(['success' => true, 'message' => 'Thank you for subscribing!']);
    }

    //Function for pricing
    public function pricing() {
        return view('pricing');
    }

    //Function for why us
    public function why_us() {
        return view('why-us');
    }

    //Function for about
    public function about() {
        return view('about');
    }
}