<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('shortcodes') && !Schema::hasColumn('shortcodes', 'website')) {
            Schema::table('shortcodes', function (Blueprint $table) {
                $table->string('website', 500)->nullable()->after('client_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('shortcodes') && Schema::hasColumn('shortcodes', 'website')) {
            Schema::table('shortcodes', function (Blueprint $table) {
                $table->dropColumn('website');
            });
        }
    }
};
