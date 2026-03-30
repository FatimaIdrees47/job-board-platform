<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')
                  ->constrained('job_listings')
                  ->cascadeOnDelete();
            $table->foreignId('candidate_id')
                  ->constrained('candidate_profiles')
                  ->cascadeOnDelete();
            $table->string('cv_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->enum('status', [
                'applied',
                'reviewing',
                'shortlisted',
                'interview',
                'offered',
                'rejected',
                'withdrawn',
            ])->default('applied');
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('withdrawn_at')->nullable();
            $table->unique(['job_id', 'candidate_id']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};