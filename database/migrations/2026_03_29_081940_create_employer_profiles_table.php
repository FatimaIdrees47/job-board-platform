<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('company_slug')->unique();
            $table->string('industry')->nullable();
            $table->string('size_range')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_remote_friendly')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->decimal('overall_rating', 3, 2)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_profiles');
    }
};