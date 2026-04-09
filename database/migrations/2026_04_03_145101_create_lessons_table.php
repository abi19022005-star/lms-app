<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('judul');
            $table->text('konten_teks')->nullable();
            $table->enum('tipe', ['video', 'teks', 'pdf']);
            $table->string('url_video')->nullable();
            $table->string('file_pdf')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            // Index untuk optimasi
            $table->index('course_id');
            $table->index('order');
            $table->index('tipe');

            // Composite index untuk sorting
            $table->index(['course_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
