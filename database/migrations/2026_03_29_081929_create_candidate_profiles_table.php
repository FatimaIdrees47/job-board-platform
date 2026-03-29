<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->unsignedTinyInteger('profile_completion')->default(0);
            $table->enum('visibility', ['public', 'private', 'employers'])->default('public');
            $table->boolean('is_open_to_work')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};