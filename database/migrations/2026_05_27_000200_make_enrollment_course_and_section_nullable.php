<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE enrollments MODIFY COLUMN course_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE enrollments MODIFY COLUMN section_id BIGINT UNSIGNED NULL');
        } else {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->foreignId('course_id')->nullable()->change();
                $table->foreignId('section_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE enrollments MODIFY COLUMN course_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE enrollments MODIFY COLUMN section_id BIGINT UNSIGNED NOT NULL');
        } else {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->foreignId('course_id')->nullable(false)->change();
                $table->foreignId('section_id')->nullable(false)->change();
            });
        }
    }
};
