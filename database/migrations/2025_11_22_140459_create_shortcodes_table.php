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
     * Creates the shortcodes table to store user-generated shortcodes
     * that embed submission forms anywhere in the portal.
     */
    public function up(): void
    {
        Schema::create('shortcodes', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('code')->unique(); // Unique shortcode identifier (e.g., 'abc123')
            $table->unsignedBigInteger('user_id'); // Reference to the user who created the shortcode
            $table->integer('custom_form_id'); // Reference to the form to display (matches int(100) in custom_forms)
            $table->json('settings')->nullable(); // JSON field for storing additional settings (e.g., styling, redirect URL)
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
        Schema::dropIfExists('shortcodes');
    }
};
