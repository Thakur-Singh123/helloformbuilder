<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CustomForm;
use App\Models\CustomFormField;

class GlobalTemplatesSeeder extends Seeder
{
    /**
     * Seed global form templates (same for all admins in "Choose template").
     * Templates: Car Reservation (step form), Contact Form, Feedback Form.
     */
    public function run(): void
    {
        $owner = User::where('user_type', 'superadmin')->first() ?? User::first();
        $userId = $owner ? $owner->id : 1;

        $templates = [
            [
                'name' => 'Car Reservation',
                'description' => 'Multi-step car reservation form: contact details, dates & vehicle, and special requests.',
                'fields' => [
                    ['field_name' => 'Full Name', 'field_key' => 'full_name', 'field_type' => 'Input', 'is_required' => 1, 'order' => 1, 'metadata' => ['step' => 1]],
                    ['field_name' => 'Email', 'field_key' => 'email', 'field_type' => 'Input', 'is_required' => 1, 'order' => 2, 'metadata' => ['step' => 1]],
                    ['field_name' => 'Phone', 'field_key' => 'phone', 'field_type' => 'Input', 'is_required' => 1, 'order' => 3, 'metadata' => ['step' => 1]],
                    ['field_name' => 'Pick-up Date', 'field_key' => 'pickup_date', 'field_type' => 'Date', 'is_required' => 1, 'order' => 4, 'metadata' => ['step' => 2]],
                    ['field_name' => 'Return Date', 'field_key' => 'return_date', 'field_type' => 'Date', 'is_required' => 1, 'order' => 5, 'metadata' => ['step' => 2]],
                    ['field_name' => 'Car Type / Vehicle', 'field_key' => 'car_type', 'field_type' => 'Input', 'is_required' => 1, 'order' => 6, 'metadata' => ['step' => 2]],
                    ['field_name' => 'Special Requests', 'field_key' => 'special_requests', 'field_type' => 'Text Area', 'is_required' => 0, 'order' => 7, 'metadata' => ['step' => 3]],
                ],
            ],
            [
                'name' => 'Contact Form',
                'description' => 'Simple contact form: name, email, subject, and message.',
                'fields' => [
                    ['field_name' => 'Name', 'field_key' => 'name', 'field_type' => 'Input', 'is_required' => 1, 'order' => 1, 'metadata' => []],
                    ['field_name' => 'Email', 'field_key' => 'email', 'field_type' => 'Input', 'is_required' => 1, 'order' => 2, 'metadata' => []],
                    ['field_name' => 'Subject', 'field_key' => 'subject', 'field_type' => 'Input', 'is_required' => 1, 'order' => 3, 'metadata' => []],
                    ['field_name' => 'Message', 'field_key' => 'message', 'field_type' => 'Text Area', 'is_required' => 1, 'order' => 4, 'metadata' => []],
                ],
            ],
            [
                'name' => 'Feedback Form',
                'description' => 'Collect feedback: name, email, rating, and comments.',
                'fields' => [
                    ['field_name' => 'Name', 'field_key' => 'name', 'field_type' => 'Input', 'is_required' => 1, 'order' => 1, 'metadata' => []],
                    ['field_name' => 'Email', 'field_key' => 'email', 'field_type' => 'Input', 'is_required' => 1, 'order' => 2, 'metadata' => []],
                    ['field_name' => 'Rating (1-5)', 'field_key' => 'rating', 'field_type' => 'Input', 'is_required' => 1, 'order' => 3, 'metadata' => []],
                    ['field_name' => 'Comments', 'field_key' => 'comments', 'field_type' => 'Text Area', 'is_required' => 0, 'order' => 4, 'metadata' => []],
                ],
            ],
        ];

        foreach ($templates as $t) {
            $form = CustomForm::firstOrCreate(
                ['name' => $t['name'], 'is_global' => true],
                [
                    'user_id' => $userId,
                    'admin_id' => null,
                    'description' => $t['description'],
                    'status' => 'active',
                    'is_global' => true,
                ]
            );

            // Replace fields so we don't duplicate on re-run
            CustomFormField::where('custom_form_id', $form->id)->delete();
            foreach ($t['fields'] as $f) {
                $meta = $f['metadata'] ?? [];
                CustomFormField::create([
                    'custom_form_id' => $form->id,
                    'field_name' => $f['field_name'],
                    'field_key' => $f['field_key'],
                    'field_type' => $f['field_type'],
                    'is_required' => (bool) ($f['is_required'] ?? false),
                    'metadata' => json_encode($meta),
                    'order' => $f['order'],
                ]);
            }

            $form->load('fields');
            if ($form->versions()->count() === 0) {
                $form->createVersion($userId, 'Initial template');
            }
        }
    }
}
