<?php

use App\Models\Shortcode;
use Illuminate\Support\Facades\Auth;

/**
 * Return the currently authenticated user or client (from web or client guard).
 *
 * @return \App\Models\User|\App\Models\Client|null
 */
if (!function_exists('currentUser')) {
    function currentUser()
    {
        return Auth::guard('web')->user() ?? Auth::guard('client')->user();
    }
}

/**
 * Parse shortcodes in content and replace them with rendered forms.
 * 
 * This function searches for shortcode patterns like [shortcode=abc123] in content
 * and replaces them with the actual form HTML.
 * 
 * @param string $content The content containing shortcodes
 * @return string The content with shortcodes replaced by form HTML
 */
if (!function_exists('parse_shortcodes')) {
    function parse_shortcodes($content)
    {
        // Pattern to match [shortcode=code] format
        $pattern = '/\[shortcode=([a-zA-Z0-9]+)\]/';

        // Replace each shortcode match with the rendered form
        $content = preg_replace_callback($pattern, function ($matches) {
            $code = $matches[1]; // Extract the shortcode from the match

            try {
                // Find the shortcode in the database
                $shortcode = Shortcode::where('code', $code)
                    ->with(['customForm.fields' => function($query) {
                        $query->orderBy('order', 'asc');
                    }])
                    ->first();

                // If shortcode doesn't exist, return a placeholder or empty string
                if (!$shortcode) {
                    return '<p class="shortcode-error">Shortcode not found: ' . htmlspecialchars($code) . '</p>';
                }

                $form = $shortcode->customForm;

                // If form doesn't exist or is inactive, return error message
                if (!$form || $form->status !== 'active') {
                    return '<p class="shortcode-error">Form is not available.</p>';
                }

                // Render the form using the view
                return view('user.shortcode.embed', compact('shortcode', 'form'))->render();
            } catch (\Exception $e) {
                // If there's an error, return an error message
                return '<p class="shortcode-error">Error rendering shortcode: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        }, $content);

        return $content;
    }
}

