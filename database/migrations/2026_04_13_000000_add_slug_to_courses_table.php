<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if slug column already exists
        if (!Schema::hasColumn('courses', 'slug')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('judul');
            });
        }

        // Generate slug untuk courses yang sudah ada dan slug-nya kosong
        $courses = DB::table('courses')->whereNull('slug')->orWhere('slug', '')->get();
        foreach ($courses as $course) {
            DB::table('courses')
                ->where('id', $course->id)
                ->update(['slug' => Str::slug($course->judul)]);
        }

        // Sekarang buat slug unique dan tidak nullable
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};


