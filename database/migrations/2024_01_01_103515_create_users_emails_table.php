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
	    Schema::disableForeignKeyConstraints();
        Schema::create('users_emails', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nikname')->nullable();
            $table->foreignId('email_id')->constrained('emails')->onDelete('cascade')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->boolean('share_blog')->default(false);
            $table->integer('count')->default(0);
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_emails');
    }
};
