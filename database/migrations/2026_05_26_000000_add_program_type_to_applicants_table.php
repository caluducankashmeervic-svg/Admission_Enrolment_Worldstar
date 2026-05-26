<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('applicant_type', 20)->nullable()->after('reference_code')
                  ->index(); // 'shs' | 'tesda'
            $table->json('profile_data')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['applicant_type', 'profile_data']);
        });
    }
};
