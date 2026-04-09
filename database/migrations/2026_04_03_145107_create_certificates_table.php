<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('kode_unik')->unique();
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamps();

            // Unique constraint untuk satu sertifikat per user per course
            $table->unique(['user_id', 'course_id']);

            // Index untuk optimasi
            $table->index('user_id');
            $table->index('course_id');
            $table->index('kode_unik');
            $table->index('issued_at');

            // Composite indexes
            $table->index(['user_id', 'course_id']);
            $table->index(['user_id', 'issued_at']);
            $table->index(['course_id', 'issued_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
