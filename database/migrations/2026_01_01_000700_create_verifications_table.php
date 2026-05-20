<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('registrar_id')->constrained('users')->cascadeOnDelete();

            $table->boolean('doc_form_137')->default(false);
            $table->boolean('doc_psa_birth_cert')->default(false);
            $table->boolean('doc_good_moral')->default(false);
            $table->boolean('doc_id_photos')->default(false);
            $table->boolean('doc_medical_cert')->default(false);
            $table->boolean('doc_diploma')->default(false);

            $table->enum('status', ['pending', 'incomplete', 'verified', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
