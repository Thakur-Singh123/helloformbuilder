<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('whatsapp_message')->nullable();
            $table->string('email')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_name')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('currency_sign')->nullable();
            $table->string('logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('icon')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('google')->nullable();
            $table->string('skype')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_tag')->nullable();
            $table->timestamps();
        });

        // Insert default row so submission emails use sobiamaan9@gmail.com
        DB::table('settings')->insert([
            'id' => 1,
            'site_title' => 'Lead Matrix',
            'sender_email' => 'sobiamaan9@gmail.com',
            'sender_name' => 'Lead Matrix Services',
            'email' => 'sobiamaan9@gmail.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
