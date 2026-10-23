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
        Schema::table('form_submissions', function (Blueprint $table) {
            // Add admin_id to track which Admin (subscriber) owns this submission
            if (!Schema::hasColumn('form_submissions', 'admin_id')) {
                $table->integer('admin_id')->nullable()->after('id');
            }
            
            // Add user_id to track which User created/edited the form
            if (!Schema::hasColumn('form_submissions', 'user_id')) {
                $table->integer('user_id')->nullable()->after('admin_id');
            }
            
            // Add form_version_id to track which version of the form was submitted
            if (!Schema::hasColumn('form_submissions', 'form_version_id')) {
                $table->integer('form_version_id')->nullable()->after('custom_form_id');
            }
            
            // Add indexes for performance
            if (Schema::hasColumn('form_submissions', 'admin_id')) {
                $table->index('admin_id');
            }
            if (Schema::hasColumn('form_submissions', 'user_id')) {
                $table->index('user_id');
            }
            if (Schema::hasColumn('form_submissions', 'form_version_id')) {
                $table->index('form_version_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('form_submissions', 'admin_id')) {
                $table->dropColumn('admin_id');
            }
            if (Schema::hasColumn('form_submissions', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('form_submissions', 'form_version_id')) {
                $table->dropColumn('form_version_id');
            }
        });
    }
};
