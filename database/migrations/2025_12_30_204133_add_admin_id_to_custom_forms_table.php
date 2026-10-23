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
        // Only run if custom_forms table exists
        if (Schema::hasTable('custom_forms')) {
            Schema::table('custom_forms', function (Blueprint $table) {
                // Add admin_id to track which Admin (subscriber) owns this form
                if (!Schema::hasColumn('custom_forms', 'admin_id')) {
                    $table->integer('admin_id')->nullable()->after('user_id');
                }
                
                // Add index for performance
                if (Schema::hasColumn('custom_forms', 'admin_id')) {
                    $table->index('admin_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_forms', function (Blueprint $table) {
            if (Schema::hasColumn('custom_forms', 'admin_id')) {
                $table->dropColumn('admin_id');
            }
        });
    }
};
