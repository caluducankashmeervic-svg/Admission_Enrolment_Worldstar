<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_term_id')->constrained()->cascadeOnDelete();
            $table->string('name', 50);
            $table->unsignedSmallInteger('year_level')->default(1);
            $table->unsignedInteger('capacity')->default(40);
            $table->unsignedInteger('enrolled_count')->default(0);
            $table->boolean('is_open')->default(true);
            $table->timestamps();

            $table->unique(['course_id', 'academic_term_id', 'name']);
            $table->index(['is_open', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
