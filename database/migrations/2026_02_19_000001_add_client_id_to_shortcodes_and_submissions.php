<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Client = website owner under an Admin. Shortcodes can be assigned to a client;
     * submissions store client_id so the client can see submissions from their site.
     */
    public function up(): void
    {
        Schema::table('shortcodes', function (Blueprint $table) {
            if (!Schema::hasColumn('shortcodes', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('user_id');
                $table->index('client_id');
            }
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('form_submissions', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('admin_id');
                $table->index('client_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shortcodes', function (Blueprint $table) {
            if (Schema::hasColumn('shortcodes', 'client_id')) {
                $table->dropIndex(['client_id']);
                $table->dropColumn('client_id');
            }
        });
        Schema::table('form_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('form_submissions', 'client_id')) {
                $table->dropIndex(['client_id']);
                $table->dropColumn('client_id');
            }
        });
    }
};
