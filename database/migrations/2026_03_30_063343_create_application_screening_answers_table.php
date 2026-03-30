<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_screening_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('question_id')
                  ->constrained('job_screening_questions')
                  ->cascadeOnDelete();
            $table->text('answer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_screening_answers');
    }
};