<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->enum('tipe', ['multiple_choice', 'essay']);
            $table->text('pertanyaan');
            $table->json('opsi')->nullable();
            $table->string('jawaban_benar')->nullable();
            $table->timestamps();

            // Index untuk optimasi
            $table->index('quiz_id');
            $table->index('tipe');

            // Composite index
            $table->index(['quiz_id', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
