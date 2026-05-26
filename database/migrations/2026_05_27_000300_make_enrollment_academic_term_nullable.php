<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SHS / TESDA applicants do not always carry an academic_term_id at
     * enrollment time. Allow it to be null so the finalize step can succeed.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable(false)->change();
        });
    }
};
