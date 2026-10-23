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
        if (!Schema::hasTable('custom_form_fields')) {
            Schema::create('custom_form_fields', function (Blueprint $table) {
                $table->id();
                $table->integer('custom_form_id'); // Reference to custom_forms table
                $table->string('field_name'); // Display name of the field
                $table->string('field_key'); // Unique key for the field
                $table->string('field_type'); // Input, Text Area, Checkbox, Radio, etc.
                $table->boolean('is_required')->default(false); // Whether field is required
                $table->text('metadata')->nullable(); // JSON metadata for field options
                $table->integer('order')->default(0); // Display order
                $table->timestamps();
                
                // Indexes
                $table->index('custom_form_id');
                $table->index('order');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_form_fields');
    }
};
