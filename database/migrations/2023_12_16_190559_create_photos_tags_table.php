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

        Schema::create('photos_tags', function (Blueprint $table) {
            $table->id();
            $table->uuid('photo_id')->references('photos')->on('id')->onDelete('cascade');
            $table->uuid('tag_id')->references('tags')->on('id')->onDelete('cascade');
            $table->foreignId('user_id')->references('users')->on('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos_tags');
    }
};
