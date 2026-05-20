<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_term_id')->constrained()->cascadeOnDelete();
            $table->string('batch_code', 30)->unique();
            $table->dateTime('exam_datetime');
            $table->string('venue');
            $table->unsignedInteger('capacity')->default(100);
            $table->unsignedInteger('assigned_count')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('exam_datetime');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_schedules');
    }
};
