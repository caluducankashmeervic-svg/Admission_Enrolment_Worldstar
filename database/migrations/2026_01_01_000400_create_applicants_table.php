<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code', 12)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('preferred_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('academic_term_id')->nullable()->constrained()->nullOnDelete();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix', 10)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('birth_date');
            $table->string('civil_status', 20)->default('Single');
            $table->string('nationality')->default('Filipino');
            $table->string('religion')->nullable();

            $table->string('email')->nullable();
            $table->string('mobile', 20);
            $table->string('address_line');
            $table->string('city');
            $table->string('province');
            $table->string('zip', 10)->nullable();

            $table->string('last_school_attended');
            $table->string('last_school_address')->nullable();
            $table->string('strand_track')->nullable();
            $table->year('year_graduated')->nullable();
            $table->decimal('gwa', 5, 2)->nullable();

            $table->string('guardian_name');
            $table->string('guardian_relationship', 50);
            $table->string('guardian_contact', 20);

            $table->enum('status', [
                'pre_registered',
                'exam_scheduled',
                'exam_completed',
                'verified',
                'enrolled',
                'rejected',
            ])->default('pre_registered');

            $table->timestamps();

            $table->index(['status', 'academic_term_id']);
            $table->index('preferred_course_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
