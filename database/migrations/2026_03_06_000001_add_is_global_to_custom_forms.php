<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * is_global: true = shared template (same for all admins in "Choose template"); false = custom per admin.
     */
    public function up(): void
    {
        if (Schema::hasTable('custom_forms') && !Schema::hasColumn('custom_forms', 'is_global')) {
            Schema::table('custom_forms', function (Blueprint $table) {
                $table->boolean('is_global')->default(false)->after('admin_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('custom_forms') && Schema::hasColumn('custom_forms', 'is_global')) {
            Schema::table('custom_forms', function (Blueprint $table) {
                $table->dropColumn('is_global');
            });
        }
    }
};
