<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Assign the active academic term to any applicants that were pre-registered
     * before the auto-assign fix, leaving them with academic_term_id = NULL.
     */
    public function up(): void
    {
        $activeTermId = DB::table('academic_terms')
            ->where('is_active', true)
            ->orderByDesc('school_year')
            ->value('id');

        if (! $activeTermId) {
            return; // No active term — nothing to assign
        }

        DB::table('applicants')
            ->whereNull('academic_term_id')
            ->update(['academic_term_id' => $activeTermId]);
    }

    public function down(): void
    {
        // Not reversible — do not null out term assignments
    }
};
