<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('jawaban_text');
            $table->boolean('is_correct')->default(false);
            $table->float('score', 8, 2)->nullable();
            $table->timestamps();

            // Unique constraint untuk mencegah double answer per question
            $table->unique(['attempt_id', 'question_id']);

            // Index untuk optimasi
            $table->index('attempt_id');
            $table->index('question_id');
            $table->index('is_correct');
            $table->index('score');

            // Composite indexes
            $table->index(['attempt_id', 'question_id']);
            $table->index(['question_id', 'is_correct']);
            $table->index(['attempt_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
