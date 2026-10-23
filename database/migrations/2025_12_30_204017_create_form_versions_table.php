<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_versions', function (Blueprint $table) {
            $table->id();
            $table->integer('custom_form_id'); // Reference to the form
            $table->integer('version_number')->default(1); // Version number (1, 2, 3, etc.)
            $table->integer('created_by_user_id'); // User who created this version
            $table->json('form_data'); // Snapshot of form fields at this version
            $table->text('description')->nullable(); // Optional description of changes
            $table->boolean('is_active')->default(true); // Whether this version is currently active
            $table->timestamps();
            
            // Indexes for performance
            $table->index('custom_form_id');
            $table->index('created_by_user_id');
            $table->index(['custom_form_id', 'version_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_versions');
    }
};
