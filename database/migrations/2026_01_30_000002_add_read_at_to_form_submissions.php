<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add read_at to track when a submission was viewed (for "NEW" badge and notification counter).
     */
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable()->after('user_agent');
        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn('read_at');
        });
    }
};
