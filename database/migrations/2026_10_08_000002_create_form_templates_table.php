<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description', 500)->nullable();
            $table->json('fields');                       // ordered array of field definitions
            $table->unsignedSmallInteger('fields_count')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('uses_count')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'is_popular']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_templates');
    }
};
