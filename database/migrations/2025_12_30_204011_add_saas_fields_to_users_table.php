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
        Schema::table('users', function (Blueprint $table) {
            // Add first_name and last_name if they don't exist
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            
            // Add admin_id for multi-tenant architecture (Users belong to an Admin)
            // NULL for Super Admin and Admin users, set for regular Users
            if (!Schema::hasColumn('users', 'admin_id')) {
                $table->integer('admin_id')->nullable()->after('user_type');
            }
            
            // Add is_active for account management
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('admin_id');
            }
            
            // Ensure user_type exists and add index
            if (Schema::hasColumn('users', 'user_type')) {
                $table->index('user_type');
            }
            if (Schema::hasColumn('users', 'admin_id')) {
                $table->index('admin_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'first_name')) {
                $table->dropColumn('first_name');
            }
            if (Schema::hasColumn('users', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('users', 'admin_id')) {
                $table->dropColumn('admin_id');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
