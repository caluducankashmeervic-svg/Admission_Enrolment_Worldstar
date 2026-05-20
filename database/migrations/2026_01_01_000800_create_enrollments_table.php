<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('enrollment_no', 20)->unique();
            $table->foreignId('applicant_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_term_id')->constrained()->cascadeOnDelete();
            $table->foreignId('processed_by')->constrained('users');

            $table->enum('status', ['finalized', 'cancelled'])->default('finalized');
            $table->string('cor_path')->nullable();
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamps();

            $table->index(['academic_term_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
