<?php

namespace Database\Seeders;

use App\Models\FormTemplate;
use App\Models\TemplateCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TemplateSeeder extends Seeder
{
    /** Field helper: f('text', 'Full name', required: true, options: [...]) */
    private function f(string $type, string $label, bool $required = false, array $options = [], ?string $placeholder = null): array
    {
        return array_filter([
            'type'        => $type,
            'label'       => $label,
            'name'        => Str::snake($label),
            'required'    => $required,
            'options'     => $options ?: null,
            'placeholder' => $placeholder,
        ], fn ($v) => $v !== null);
    }

    public function run(): void
    {
        $cats = [
            ['Business', 'BU'], ['Trades & Field Services', 'TR'], ['Feedback', 'FE'], ['Survey', 'SU'],
            ['Events', 'EV'], ['Healthcare', 'HE'], ['Education', 'ED'], ['HR', 'HR'], ['Marketing', 'MA'],
            ['Real Estate', 'RE'], ['Financial', 'FI'], ['E-commerce', 'EC'], ['Legal', 'LE'],
            ['Technology', 'TE'], ['Entertainment', 'EN'], ['Food & Beverage', 'FO'], ['Travel', 'TV'],
            ['Sports', 'SP'], ['Non-profit', 'NO'], ['Government', 'GO'], ['Manufacturing', 'MF'],
            ['Logistics', 'LO'], ['Agriculture', 'AG'], ['Automotive', 'AU'], ['Beauty', 'BE'],
            ['Waivers', 'WA'], ['Other', 'OT'],
        ];

        $map = [];
        foreach ($cats as $i => [$name, $code]) {
            $map[$name] = TemplateCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'code' => $code, 'sort_order' => $i]
            );
        }

        $f = fn (...$a) => $this->f(...$a);
        $svc = ['Excellent', 'Good', 'Average', 'Poor'];

        $templates = [
            // ---------- Business ----------
            ['Business', 'Contact Form', 'Simple contact form with name, email, and message fields', [
                $f('text', 'Full name', true), $f('email', 'Email address', true),
                $f('text', 'Subject'), $f('textarea', 'Message', true),
            ]],
            ['Business', 'Business Inquiry', 'Professional business inquiry form for B2B communications', [
                $f('text', 'Contact name', true), $f('text', 'Company name', true), $f('email', 'Work email', true),
                $f('phone', 'Phone number'), $f('select', 'Inquiry type', true, ['Partnership', 'Vendor', 'Sales', 'Other']),
                $f('select', 'Budget range', false, ['Under $5k', '$5k–$25k', '$25k–$100k', '$100k+']),
                $f('textarea', 'Describe your needs', true), $f('file', 'Attachment'),
            ]],
            ['Business', 'Appointment Booking', 'Schedule appointments with date, time, and service selection', [
                $f('text', 'Full name', true), $f('email', 'Email address', true), $f('phone', 'Phone number', true),
                $f('select', 'Service', true, ['Consultation', 'Follow-up', 'Standard session', 'Extended session']),
                $f('date', 'Preferred date', true), $f('time', 'Preferred time', true), $f('textarea', 'Notes'),
            ]],
            ['Business', 'Quote Request', 'Service or product pricing quote request form', [
                $f('text', 'Full name', true), $f('email', 'Email address', true), $f('phone', 'Phone number'),
                $f('text', 'Company'), $f('select', 'Service needed', true, ['Product', 'Service', 'Custom project']),
                $f('textarea', 'Project details', true), $f('date', 'Needed by'), $f('file', 'Reference files'),
            ]],
            ['Business', 'Technical Support Request', 'IT helpdesk ticket submission and problem reporting form', [
                $f('text', 'Full name', true), $f('email', 'Email address', true), $f('text', 'Device or system'),
                $f('select', 'Category', true, ['Hardware', 'Software', 'Network', 'Account access', 'Other']),
                $f('radio', 'Urgency', true, ['Low', 'Medium', 'High', 'Critical']),
                $f('text', 'Issue summary', true), $f('textarea', 'Steps to reproduce'),
                $f('file', 'Screenshot'), $f('checkbox', 'Contact me by phone', false, ['Yes']),
            ]],

            // ---------- Events ----------
            ['Events', 'Event Registration', 'Event registration form with attendee details and preferences', [
                $f('text', 'Full name', true), $f('email', 'Email address', true), $f('phone', 'Phone number'),
                $f('select', 'Ticket type', true, ['General', 'VIP', 'Student']),
                $f('select', 'Meal preference', false, ['No preference', 'Vegetarian', 'Vegan', 'Gluten-free']),
                $f('number', 'Number of guests'), $f('textarea', 'Special requirements'),
            ]],
            ['Events', 'Workshop Signup', 'Registration form for workshops and training sessions', [
                $f('text', 'Full name', true), $f('email', 'Email address', true),
                $f('select', 'Session', true, ['Morning', 'Afternoon', 'Full day']),
                $f('radio', 'Experience level', true, ['Beginner', 'Intermediate', 'Advanced']),
                $f('textarea', 'What do you hope to learn?'), $f('checkbox', 'Send me reminders', false, ['Yes']),
            ]],
            ['Events', 'Conference Registration', 'Professional conference and summit registration form', [
                $f('text', 'Full name', true), $f('email', 'Email address', true), $f('text', 'Organization'),
                $f('text', 'Job title'), $f('select', 'Pass type', true, ['Standard', 'Premium', 'Virtual']),
                $f('checkbox', 'Sessions of interest', false, ['Keynote', 'Workshops', 'Panels', 'Networking']),
                $f('select', 'Dietary needs', false, ['None', 'Vegetarian', 'Vegan', 'Allergy']),
                $f('address', 'Billing address'), $f('checkbox', 'I agree to the code of conduct', true, ['Agree']),
            ]],
            ['Events', 'Volunteer Registration', 'Community volunteer opportunity signup and skills assessment', [
                $f('text', 'Full name', true), $f('email', 'Email address', true), $f('phone', 'Phone number'),
                $f('checkbox', 'Areas of interest', true, ['Setup', 'Registration desk', 'Outreach', 'Cleanup']),
                $f('checkbox', 'Availability', true, ['Weekday mornings', 'Weekday evenings', 'Weekends']),
                $f('textarea', 'Skills and experience'), $f('text', 'Emergency contact'),
            ]],

            // ---------- Feedback ----------
            ['Feedback', 'Product Feedback', 'Collect detailed product feedback with ratings and improvement suggestions', [
                $f('text', 'Product name', true), $f('rating', 'Overall rating', true),
                $f('rating', 'Ease of use'), $f('rating', 'Value for money'),
                $f('textarea', 'What do you like most?'), $f('textarea', 'What should we improve?'),
                $f('radio', 'Would you recommend us?', true, ['Yes', 'Maybe', 'No']), $f('email', 'Email (optional)'),
            ]],
            ['Feedback', 'Service Feedback', 'Customer service experience feedback and rating form', [
                $f('text', 'Name'), $f('email', 'Email address'), $f('date', 'Date of service'),
                $f('rating', 'Overall experience', true), $f('radio', 'Staff friendliness', true, $svc),
                $f('radio', 'Response time', true, $svc), $f('radio', 'Issue resolved?', true, ['Yes', 'Partly', 'No']),
                $f('textarea', 'Comments'), $f('checkbox', 'You may contact me about this feedback', false, ['Yes']),
            ]],

            // ---------- HR ----------
            ['HR', 'Job Application', 'Professional job application form with resume upload and experience details', [
                $f('text', 'Full name', true), $f('email', 'Email address', true), $f('phone', 'Phone number', true),
                $f('select', 'Position', true, ['Position 1', 'Position 2', 'Position 3']),
                $f('number', 'Years of experience'), $f('url', 'LinkedIn or portfolio'),
                $f('file', 'Resume', true), $f('textarea', 'Cover letter'), $f('date', 'Available from'),
            ]],
            ['HR', 'Employee Feedback', 'Anonymous employee feedback and workplace satisfaction survey', [
                $f('select', 'Department', false, ['Operations', 'Sales', 'Engineering', 'Support', 'Other']),
                $f('scale', 'Overall satisfaction', true), $f('scale', 'Work-life balance', true),
                $f('scale', 'Management support', true), $f('radio', 'Would you recommend us as an employer?', true, ['Yes', 'No', 'Unsure']),
                $f('textarea', 'What is working well?'), $f('textarea', 'What should change?'),
            ]],

            // ---------- Marketing ----------
            ['Marketing', 'Newsletter Signup', 'Simple newsletter subscription form with preferences', [
                $f('text', 'First name'), $f('email', 'Email address', true),
                $f('checkbox', 'Topics', false, ['News', 'Tips', 'Offers', 'Events']),
                $f('radio', 'Frequency', false, ['Weekly', 'Monthly']),
            ]],
            ['Marketing', 'Lead Generation', 'Capture high-quality leads with detailed qualification questions', [
                $f('text', 'Full name', true), $f('email', 'Work email', true), $f('phone', 'Phone number'),
                $f('text', 'Company', true), $f('select', 'Company size', false, ['1–10', '11–50', '51–200', '200+']),
                $f('select', 'Interested in', true, ['Product demo', 'Pricing', 'Partnership', 'General']),
                $f('select', 'Timeline', false, ['Now', '1–3 months', '3–6 months', 'Just exploring']),
                $f('textarea', 'Anything else?'),
            ]],
            ['Marketing', 'Webinar Registration', 'Online webinar and virtual event registration form', [
                $f('text', 'Full name', true), $f('email', 'Email address', true), $f('text', 'Company'),
                $f('text', 'Job title'), $f('select', 'How did you hear about us?', false, ['Email', 'Social', 'Search', 'Friend']),
                $f('textarea', 'Questions for the speaker'), $f('checkbox', 'Send me the recording', false, ['Yes']),
                $f('checkbox', 'I agree to receive emails', true, ['Agree']),
            ]],
            ['Marketing', 'Content Download', 'Lead capture form for downloadable content and resources', [
                $f('text', 'First name', true), $f('text', 'Last name', true), $f('email', 'Work email', true),
                $f('text', 'Company'), $f('select', 'Role', false, ['Owner', 'Manager', 'Specialist', 'Other']),
                $f('checkbox', 'I agree to receive related emails', false, ['Agree']),
            ]],

            // ---------- Survey ----------
            ['Survey', 'Customer Survey', 'Comprehensive customer satisfaction survey with ratings and feedback', [
                $f('rating', 'Overall satisfaction', true), $f('scale', 'How likely are you to recommend us? (0–10)', true),
                $f('radio', 'How often do you use us?', true, ['Daily', 'Weekly', 'Monthly', 'Rarely']),
                $f('checkbox', 'Features you use', false, ['Feature A', 'Feature B', 'Feature C']),
                $f('textarea', 'What could we do better?'), $f('email', 'Email (optional)'),
            ]],
            ['Survey', 'Market Research Survey', 'Comprehensive market research form for product and brand insights', [
                $f('select', 'Age group', false, ['18–24', '25–34', '35–44', '45–54', '55+']),
                $f('radio', 'Familiar with our brand?', true, ['Very', 'Somewhat', 'Not at all']),
                $f('checkbox', 'What matters most when buying?', true, ['Price', 'Quality', 'Brand', 'Reviews', 'Support']),
                $f('select', 'Monthly spend in this category', false, ['< $50', '$50–$150', '$150–$500', '$500+']),
                $f('text', 'Brands you use today'), $f('textarea', 'What is missing from current options?'),
                $f('email', 'Email for follow-up (optional)'),
            ]],
        ];

        $popular = collect($templates)->pluck(1)->all();

        foreach ($templates as [$cat, $title, $desc, $fields]) {
            FormTemplate::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'template_category_id' => $map[$cat]->id,
                    'title'       => $title,
                    'description' => $desc,
                    'fields'      => $fields,
                    'is_popular'  => in_array($title, $popular, true),
                    'is_active'   => true,
                ]
            );
        }
    }
}
