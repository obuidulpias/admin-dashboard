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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable(); // Model class name (e.g., App\Models\User)
            $table->unsignedBigInteger('user_id')->nullable(); // ID of the user who performed the action
            $table->string('event'); // created, updated, deleted
            $table->string('auditable_type'); // Model class name that was changed
            $table->unsignedBigInteger('auditable_id'); // ID of the model that was changed
            $table->text('old_values')->nullable(); // JSON of old values
            $table->text('new_values')->nullable(); // JSON of new values
            $table->text('url')->nullable(); // URL where the action occurred
            $table->ipAddress('ip_address')->nullable(); // IP address
            $table->string('user_agent')->nullable(); // User agent
            $table->text('tags')->nullable(); // Additional tags for filtering
            $table->timestamps();
            
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['user_type', 'user_id']);
            $table->index('event');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
