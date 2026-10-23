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
        if (!Schema::hasTable('custom_forms')) {
            Schema::create('custom_forms', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id'); // User who created the form
                $table->integer('admin_id')->nullable(); // Admin (subscriber) who owns this form
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('status')->default('active'); // active, inactive
                $table->timestamps();
                
                // Indexes
                $table->index('user_id');
                $table->index('admin_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_forms');
    }
};
