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
        Schema::table('shortcodes', function (Blueprint $table) {
            // Add form_version_id to link shortcode to specific form version
            if (!Schema::hasColumn('shortcodes', 'form_version_id')) {
                $table->integer('form_version_id')->nullable()->after('custom_form_id');
            }
            
            // Keep custom_form_id for backward compatibility and quick access
            // Add index for performance
            if (Schema::hasColumn('shortcodes', 'form_version_id')) {
                $table->index('form_version_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shortcodes', function (Blueprint $table) {
            if (Schema::hasColumn('shortcodes', 'form_version_id')) {
                $table->dropColumn('form_version_id');
            }
        });
    }
};
