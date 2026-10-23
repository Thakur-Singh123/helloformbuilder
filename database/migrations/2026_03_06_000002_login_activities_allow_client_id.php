<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * login_activities.user_id stores either users.id or clients.id (see user_type).
     * Drop FK to users so client logins can be recorded.
     */
    public function up(): void
    {
        if (!Schema::hasTable('login_activities')) {
            return;
        }
        Schema::table('login_activities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('login_activities')) {
            return;
        }
        Schema::table('login_activities', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
