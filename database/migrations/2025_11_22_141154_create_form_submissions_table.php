<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     * Creates the form_submissions table to store data submitted through shortcode forms.
     */
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('shortcode_id'); // Reference to the shortcode used
            $table->integer('custom_form_id'); // Reference to the form (matches int(100) in custom_forms)
            $table->json('submission_data'); // JSON field storing all form field values
            $table->string('ip_address')->nullable(); // IP address of the submitter
            $table->string('user_agent')->nullable(); // User agent/browser info
            $table->timestamps(); // created_at and updated_at timestamps
            
            // Note: Foreign keys are not added due to type mismatches with existing tables
            // Relationships are maintained at the application level through Eloquent
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
