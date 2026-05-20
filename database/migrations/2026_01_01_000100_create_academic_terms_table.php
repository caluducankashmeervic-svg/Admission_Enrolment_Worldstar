<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->id();
            $table->string('school_year', 9);
            $table->enum('semester', ['1st', '2nd', 'Summer']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['school_year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
