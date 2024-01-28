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
        Schema::create('users_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('tag_id')->constrained('tags');
            $table->foreignId('email_id')->constrained('emails')->nullable();
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->text('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_tags');
    }
};
