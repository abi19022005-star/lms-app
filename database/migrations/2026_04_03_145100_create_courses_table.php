<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('categories')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('thumbnail')->nullable();
            $table->decimal('harga', 10, 2)->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();

            // Index untuk optimasi query
            $table->index('guru_id');
            $table->index('kategori_id');
            $table->index('status');
            $table->index('harga');
            $table->fullText(['judul', 'deskripsi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
