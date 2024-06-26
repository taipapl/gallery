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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->string('title');
            $table->text('post');
            $table->integer('likes')->default(0);
            $table->boolean('active')->default(1);
            $table->timestamps();
        });

        Schema::create('posts_photos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->boolean('first')->default(0);
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('photo_id')->constrained('photos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_photos');
        Schema::dropIfExists('posts');
    }
};
