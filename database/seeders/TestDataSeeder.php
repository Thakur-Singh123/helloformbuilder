<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CustomForm;
use App\Models\CustomFormField;
use App\Models\FormVersion;
use App\Models\Shortcode;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@leadmatrix.com'],
            [
                'name' => 'Super Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password123'),
                'user_type' => 'superadmin',
                'admin_id' => null,
                'is_active' => true,
            ]
        );

        // Create or update Admin (Subscriber)
        $admin = User::updateOrCreate(
            ['email' => 'admin@leadmatrix.com'],
            [
                'name' => 'Admin User',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password123'),
                'user_type' => 'admin',
                'admin_id' => null,
                'is_active' => true,
            ]
        );

        // Create or update Client (Website Owner, under Admin)
        $client = User::updateOrCreate(
            ['email' => 'client@leadmatrix.com'],
            [
                'name' => 'Test Client',
                'first_name' => 'Test',
                'last_name' => 'Client',
                'password' => Hash::make('password123'),
                'user_type' => 'client',
                'admin_id' => $admin->id,
                'is_active' => true,
            ]
        );

        // Create or update Regular User (under Admin)
        $user = User::updateOrCreate(
            ['email' => 'user@leadmatrix.com'],
            [
                'name' => 'Test User',
                'first_name' => 'Test',
                'last_name' => 'User',
                'password' => Hash::make('password123'),
                'user_type' => 'user',
                'admin_id' => $admin->id,
                'is_active' => true,
            ]
        );

        // Create or get Contact Us form for the Admin (avoids duplicates on re-seed)
        $form = CustomForm::firstOrCreate(
            [
                'admin_id' => $admin->id,
                'name' => 'Contact Us Form',
            ],
            [
                'user_id' => $admin->id,
                'description' => 'A sample contact form for testing purposes',
                'status' => 'active',
            ]
        );

        // Contact Us form fields (with extra fields)
        $fields = [
            ['field_name' => 'Full Name', 'field_key' => 'full_name', 'field_type' => 'Input', 'is_required' => true, 'metadata' => '{}', 'order' => 1],
            ['field_name' => 'Email Address', 'field_key' => 'email', 'field_type' => 'Input', 'is_required' => true, 'metadata' => '{}', 'order' => 2],
            ['field_name' => 'Phone Number', 'field_key' => 'phone', 'field_type' => 'Input', 'is_required' => false, 'metadata' => '{}', 'order' => 3],
            ['field_name' => 'Company', 'field_key' => 'company', 'field_type' => 'Input', 'is_required' => false, 'metadata' => '{}', 'order' => 4],
            ['field_name' => 'Subject', 'field_key' => 'subject', 'field_type' => 'Input', 'is_required' => true, 'metadata' => '{}', 'order' => 5],
            ['field_name' => 'Message', 'field_key' => 'message', 'field_type' => 'Text Area', 'is_required' => true, 'metadata' => '{}', 'order' => 6],
        ];

        foreach ($fields as $fieldData) {
            CustomFormField::firstOrCreate(
                ['custom_form_id' => $form->id, 'field_key' => $fieldData['field_key']],
                [
                    'field_name' => $fieldData['field_name'],
                    'field_type' => $fieldData['field_type'],
                    'is_required' => $fieldData['is_required'],
                    'metadata' => $fieldData['metadata'],
                    'order' => $fieldData['order'],
                ]
            );
        }

        $form->load('fields');
        // Create initial form version
        $formData = [
            'name' => $form->name,
            'description' => $form->description,
            'status' => $form->status,
            'fields' => $form->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'field_name' => $field->field_name,
                    'field_key' => $field->field_key,
                    'field_type' => $field->field_type,
                    'is_required' => $field->is_required,
                    'metadata' => $field->metadata,
                    'order' => $field->order,
                ];
            })->toArray(),
        ];

        $version = FormVersion::firstOrCreate(
            ['custom_form_id' => $form->id, 'version_number' => 1],
            [
                'created_by_user_id' => $admin->id,
                'form_data' => $formData,
                'description' => 'Initial version',
                'is_active' => true,
            ]
        );

        $shortcode = Shortcode::firstOrCreate(
            ['code' => 'test1234'],
            [
                'user_id' => $admin->id,
                'custom_form_id' => $form->id,
                'form_version_id' => $version->id,
                'settings' => null,
            ]
        );

        // Create a sample submission
        $submissionData = [
            'full_name' => [
                'field_name' => 'Full Name',
                'field_type' => 'Input',
                'value' => 'John Doe',
            ],
            'email' => [
                'field_name' => 'Email Address',
                'field_type' => 'Input',
                'value' => 'john.doe@example.com',
            ],
            'phone' => [
                'field_name' => 'Phone Number',
                'field_type' => 'Input',
                'value' => '+1234567890',
            ],
            'message' => [
                'field_name' => 'Message',
                'field_type' => 'Text Area',
                'value' => 'This is a test submission to verify the system is working correctly.',
            ],
        ];

        FormSubmission::create([
            'admin_id' => $admin->id,
            'user_id' => $admin->id,
            'shortcode_id' => $shortcode->id,
            'custom_form_id' => $form->id,
            'form_version_id' => $version->id,
            'submission_data' => $submissionData,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Test Browser)',
        ]);

        // Create or get Feedback form for the regular user (avoids duplicates on re-seed)
        $userForm = CustomForm::firstOrCreate(
            [
                'admin_id' => $admin->id,
                'name' => 'Feedback Form',
            ],
            [
                'user_id' => $user->id,
                'description' => 'Collect user feedback',
                'status' => 'active',
            ]
        );

        $userFormFields = [
            ['field_name' => 'Rating', 'field_key' => 'rating', 'field_type' => 'Input', 'is_required' => true, 'metadata' => '{}', 'order' => 1],
            ['field_name' => 'Comments', 'field_key' => 'comments', 'field_type' => 'Text Area', 'is_required' => false, 'metadata' => '{}', 'order' => 2],
        ];

        foreach ($userFormFields as $fieldData) {
            CustomFormField::firstOrCreate(
                ['custom_form_id' => $userForm->id, 'field_key' => $fieldData['field_key']],
                [
                    'field_name' => $fieldData['field_name'],
                    'field_type' => $fieldData['field_type'],
                    'is_required' => $fieldData['is_required'],
                    'metadata' => $fieldData['metadata'],
                    'order' => $fieldData['order'],
                ]
            );
        }

        $userForm->load('fields');
        // Create version for user form
        $userFormData = [
            'name' => $userForm->name,
            'description' => $userForm->description,
            'status' => $userForm->status,
            'fields' => $userForm->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'field_name' => $field->field_name,
                    'field_key' => $field->field_key,
                    'field_type' => $field->field_type,
                    'is_required' => $field->is_required,
                    'metadata' => $field->metadata,
                    'order' => $field->order,
                ];
            })->toArray(),
        ];

        $userVersion = FormVersion::firstOrCreate(
            ['custom_form_id' => $userForm->id, 'version_number' => 1],
            [
                'created_by_user_id' => $user->id,
                'form_data' => $userFormData,
                'description' => 'Initial version',
                'is_active' => true,
            ]
        );

        Shortcode::firstOrCreate(
            ['code' => 'user1234'],
            [
                'user_id' => $user->id,
                'custom_form_id' => $userForm->id,
                'form_version_id' => $userVersion->id,
                'settings' => null,
            ]
        );

        // Car Reservation form template (multi-step, similar to NYC wheelchair reservation)
        $reservationForm = CustomForm::firstOrCreate(
            ['admin_id' => $admin->id, 'name' => 'Car Reservation'],
            [
                'user_id' => $admin->id,
                'description' => 'Book a car or wheelchair-accessible vehicle. Complete pickup, drop-off, and contact details in a few steps.',
                'status' => 'active',
            ]
        );

        $reservationFields = [
            // Step 1: Pickup
            ['field_name' => 'Pickup Date', 'field_key' => 'pickup_date', 'field_type' => 'Date', 'is_required' => true, 'metadata' => json_encode(['step' => 1]), 'order' => 1],
            ['field_name' => 'Pickup Time', 'field_key' => 'pickup_time', 'field_type' => 'Input', 'is_required' => true, 'metadata' => json_encode(['step' => 1]), 'order' => 2],
            ['field_name' => 'Pickup Location / Address', 'field_key' => 'pickup_address', 'field_type' => 'Input', 'is_required' => true, 'metadata' => json_encode(['step' => 1]), 'order' => 3],
            ['field_name' => 'Pickup Type', 'field_key' => 'pickup_type', 'field_type' => 'Input', 'is_required' => false, 'metadata' => json_encode(['step' => 1]), 'order' => 4],
            // Step 2: Drop-off
            ['field_name' => 'Drop-off Date', 'field_key' => 'dropoff_date', 'field_type' => 'Date', 'is_required' => true, 'metadata' => json_encode(['step' => 2]), 'order' => 5],
            ['field_name' => 'Drop-off Time', 'field_key' => 'dropoff_time', 'field_type' => 'Input', 'is_required' => true, 'metadata' => json_encode(['step' => 2]), 'order' => 6],
            ['field_name' => 'Drop-off Location / Address', 'field_key' => 'dropoff_address', 'field_type' => 'Input', 'is_required' => true, 'metadata' => json_encode(['step' => 2]), 'order' => 7],
            // Step 3: Passenger & contact
            ['field_name' => 'Full Name', 'field_key' => 'full_name', 'field_type' => 'Input', 'is_required' => true, 'metadata' => json_encode(['step' => 3]), 'order' => 8],
            ['field_name' => 'Phone Number', 'field_key' => 'phone', 'field_type' => 'Input', 'is_required' => true, 'metadata' => json_encode(['step' => 3]), 'order' => 9],
            ['field_name' => 'Email', 'field_key' => 'email', 'field_type' => 'Input', 'is_required' => true, 'metadata' => json_encode(['step' => 3]), 'order' => 10],
            ['field_name' => 'Number of Passengers', 'field_key' => 'num_passengers', 'field_type' => 'Number', 'is_required' => false, 'metadata' => json_encode(['step' => 3]), 'order' => 11],
            ['field_name' => 'Special Requirements', 'field_key' => 'special_requirements', 'field_type' => 'Text Area', 'is_required' => false, 'metadata' => json_encode(['step' => 3]), 'order' => 12],
        ];

        foreach ($reservationFields as $fieldData) {
            CustomFormField::firstOrCreate(
                ['custom_form_id' => $reservationForm->id, 'field_key' => $fieldData['field_key']],
                [
                    'field_name' => $fieldData['field_name'],
                    'field_type' => $fieldData['field_type'],
                    'is_required' => $fieldData['is_required'],
                    'metadata' => $fieldData['metadata'],
                    'order' => $fieldData['order'],
                ]
            );
        }

        $reservationForm->load('fields');
        $reservationFormData = [
            'name' => $reservationForm->name,
            'description' => $reservationForm->description,
            'status' => $reservationForm->status,
            'fields' => $reservationForm->fields->map(function ($field) {
                $meta = is_string($field->metadata) ? (json_decode($field->metadata, true) ?: []) : (array) $field->metadata;
                return [
                    'id' => $field->id,
                    'field_name' => $field->field_name,
                    'field_key' => $field->field_key,
                    'field_type' => $field->field_type,
                    'is_required' => $field->is_required,
                    'metadata' => $field->metadata,
                    'order' => $field->order,
                ];
            })->toArray(),
        ];

        $reservationVersion = FormVersion::firstOrCreate(
            ['custom_form_id' => $reservationForm->id, 'version_number' => 1],
            [
                'created_by_user_id' => $admin->id,
                'form_data' => $reservationFormData,
                'description' => 'Initial version',
                'is_active' => true,
            ]
        );

        Shortcode::firstOrCreate(
            ['code' => 'reservation1'],
            [
                'user_id' => $admin->id,
                'custom_form_id' => $reservationForm->id,
                'form_version_id' => $reservationVersion->id,
                'settings' => null,
            ]
        );

        $this->command->info('✅ Test data seeded successfully!');
        $this->command->info('');
        $this->command->info('📧 Login Credentials:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Super Admin:');
        $this->command->info('  Email: superadmin@leadmatrix.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('Admin (Subscriber):');
        $this->command->info('  Email: admin@leadmatrix.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('Regular User:');
        $this->command->info('  Email: user@leadmatrix.com');
        $this->command->info('  Password: password123');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
        $this->command->info('🔗 Test Shortcodes:');
        $this->command->info('  Admin Form: [shortcode=test1234]');
        $this->command->info('  User Form: [shortcode=user1234]');
        $this->command->info('');
        $this->command->info('📝 Test Data Created:');
        $this->command->info('  - 1 Super Admin account');
        $this->command->info('  - 1 Admin account');
        $this->command->info('  - 1 Regular User account');
        $this->command->info('  - 3 Forms (Contact Us, Feedback, Car Reservation)');
        $this->command->info('  - 3 Shortcodes (test1234, user1234, reservation1)');
        $this->command->info('  - 1 Sample Submission');
        $this->command->info('  - Car Reservation: multi-step template, shortcode=reservation1');
    }
}
