<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // User information
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_role')->nullable();

            // Activity information
            $table->string('action'); // create, update, delete, login, logout, view, etc.
            $table->string('action_type')->nullable(); // create, update, delete, auth, system
            $table->string('module')->nullable(); // course, user, quiz, category, etc.

            // Model information
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_name')->nullable();

            // Data changes
            $table->longText('old_data')->nullable();
            $table->longText('new_data')->nullable();
            $table->text('description')->nullable();

            // Request information
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            $table->string('url')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet

            // Additional metadata
            $table->json('metadata')->nullable();
            $table->boolean('is_error')->default(false);
            $table->text('error_message')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes for better performance
            $table->index('user_id');
            $table->index('action');
            $table->index('action_type');
            $table->index('module');
            $table->index('model_type');
            $table->index('model_id');
            $table->index('created_at');
            $table->index('ip_address');
            $table->index('is_error');

            // Composite indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
            $table->index(['module', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
