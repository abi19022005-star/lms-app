<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->integer('progress')->default(0);
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Unique constraint untuk mencegah double enroll
            $table->unique(['user_id', 'course_id']);

            // Index untuk optimasi
            $table->index('user_id');
            $table->index('course_id');
            $table->index('status');
            $table->index('progress');
            $table->index('enrolled_at');

            // Composite index untuk query progress
            $table->index(['user_id', 'status', 'progress']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
