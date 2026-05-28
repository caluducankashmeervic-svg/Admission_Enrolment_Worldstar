<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Recalculate exam_schedules.assigned_count from the actual exam_results rows.
     * This fixes stale counts that occur when applicants are deleted directly
     * without going through the remove-applicant flow.
     */
    public function up(): void
    {
        // Works on both SQLite and MySQL
        foreach (DB::table('exam_schedules')->get(['id']) as $schedule) {
            $actual = DB::table('exam_results')
                ->where('exam_schedule_id', $schedule->id)
                ->count();

            DB::table('exam_schedules')
                ->where('id', $schedule->id)
                ->update(['assigned_count' => $actual]);
        }
    }

    public function down(): void
    {
        // Irreversible data correction — no rollback needed
    }
};
