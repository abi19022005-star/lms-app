<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->float('total_score', 8, 2)->default(0);
            $table->boolean('is_graded')->default(false);
            $table->timestamps();

            // Unique constraint untuk satu attempt per user per quiz
            $table->unique(['quiz_id', 'user_id']);

            // Index untuk optimasi
            $table->index('quiz_id');
            $table->index('user_id');
            $table->index('submitted_at');
            $table->index('total_score');
            $table->index('is_graded');

            // Composite indexes
            $table->index(['quiz_id', 'user_id', 'submitted_at']);
            $table->index(['user_id', 'is_graded']);
            $table->index(['quiz_id', 'is_graded']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
