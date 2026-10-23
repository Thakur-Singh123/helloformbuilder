<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('clients') && !Schema::hasColumn('clients', 'website')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('website', 500)->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('clients') && Schema::hasColumn('clients', 'website')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('website');
            });
        }
    }
};
