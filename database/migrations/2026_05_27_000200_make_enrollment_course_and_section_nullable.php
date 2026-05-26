<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Section and course are no longer required to finalize enrollment
     * (out-of-scope per business decision). SHS/TESDA applicants also do
     * not carry a preferred_course_id, so course_id must be nullable too.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->change();
            $table->foreignId('section_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable(false)->change();
            $table->foreignId('section_id')->nullable(false)->change();
        });
    }
};
