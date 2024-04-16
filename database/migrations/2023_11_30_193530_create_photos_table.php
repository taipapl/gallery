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
        Schema::create('photos', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('uuid')->unique()->index();
            $table->string('label')->nullable();
            $table->string('path');
            $table->string('video_path')->nullable();
            $table->boolean('is_video')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_favorite')->default(false);
            $table->text('meta')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('photo_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
