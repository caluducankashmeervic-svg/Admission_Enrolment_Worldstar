<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_schedule_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 6, 2)->nullable();
            $table->decimal('percentile', 5, 2)->nullable();
            $table->enum('result', ['pending', 'passed', 'failed'])->default('pending');
            $table->enum('sms_status', ['queued', 'sent', 'failed', 'not_sent'])->default('not_sent');
            $table->timestamp('sms_sent_at')->nullable();
            $table->string('sms_provider_ref')->nullable();
            $table->timestamps();

            $table->unique(['applicant_id', 'exam_schedule_id']);
            $table->index('result');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
