<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')
                  ->constrained('employer_profiles')
                  ->cascadeOnDelete();
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', [
                'full-time',
                'part-time',
                'remote',
                'contract',
                'internship',
                'freelance',
            ])->default('full-time');
            $table->string('location')->nullable();
            $table->boolean('is_remote')->default(false);
            $table->boolean('is_hybrid')->default(false);
            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->string('salary_currency', 10)->default('PKR');
            $table->enum('salary_period', ['monthly', 'yearly'])->default('monthly');
            $table->boolean('salary_negotiable')->default(false);
            $table->boolean('show_salary')->default(true);
            $table->enum('experience_level', [
                'entry',
                'mid',
                'senior',
                'lead',
                'executive',
            ])->default('mid');
            $table->longText('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->enum('application_method', ['platform', 'external'])->default('platform');
            $table->string('external_url')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', [
                'draft',
                'active',
                'paused',
                'expired',
                'closed',
            ])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_until')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('applications_count')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};