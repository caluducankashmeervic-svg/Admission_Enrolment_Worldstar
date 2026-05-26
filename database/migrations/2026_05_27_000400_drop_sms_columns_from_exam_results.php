<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            if (Schema::hasColumn('exam_results', 'sms_status')) {
                $table->dropColumn('sms_status');
            }
            if (Schema::hasColumn('exam_results', 'sms_sent_at')) {
                $table->dropColumn('sms_sent_at');
            }
            if (Schema::hasColumn('exam_results', 'sms_provider_ref')) {
                $table->dropColumn('sms_provider_ref');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->enum('sms_status', ['queued', 'sent', 'failed', 'not_sent'])
                  ->default('not_sent');
            $table->timestamp('sms_sent_at')->nullable();
            $table->string('sms_provider_ref')->nullable();
        });
    }
};
