<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safe_space_photos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->nullable();
            $table->string('image_path');
            $table->dateTime('event_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safe_space_photos');
    }
};
