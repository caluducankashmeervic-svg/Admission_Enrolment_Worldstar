<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill: every existing pre_registered applicant gets auto-assigned to the
     * earliest open exam batch (FCFS by exam_datetime). Once assigned they move
     * to "exam_scheduled". Applicants left unassigned (no open slots) remain
     * pre_registered and will be picked up by a registrar approval later.
     */
    public function up(): void
    {
        $applicants = DB::table('applicants')
            ->where('status', 'pre_registered')
            ->orderBy('created_at')
            ->get();

        foreach ($applicants as $a) {
            DB::transaction(function () use ($a) {
                $batch = DB::table('exam_schedules')
                    ->whereColumn('assigned_count', '<', 'capacity')
                    ->orderBy('exam_datetime')
                    ->lockForUpdate()
                    ->first();

                if (! $batch) {
                    return;
                }

                $alreadyAssigned = DB::table('exam_results')
                    ->where('applicant_id', $a->id)
                    ->exists();
                if ($alreadyAssigned) {
                    return;
                }

                DB::table('exam_results')->insert([
                    'applicant_id'     => $a->id,
                    'exam_schedule_id' => $batch->id,
                    'result'           => 'pending',
                    'sms_status'       => 'not_sent', // legacy column, removed in a later migration
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                DB::table('exam_schedules')
                    ->where('id', $batch->id)
                    ->increment('assigned_count');

                DB::table('applicants')
                    ->where('id', $a->id)
                    ->update([
                        'status'     => 'exam_scheduled',
                        'updated_at' => now(),
                    ]);
            });
        }
    }

    public function down(): void
    {
        // Non-reversible historical backfill.
    }
};
