<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->change();
            $table->date('birth_date')->nullable()->change();
            $table->string('mobile', 20)->nullable()->change();
            $table->string('address_line')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('province')->nullable()->change();
            $table->string('last_school_attended')->nullable()->change();
            $table->string('guardian_name')->nullable()->change();
            $table->string('guardian_relationship', 50)->nullable()->change();
            $table->string('guardian_contact', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Reverting NOT NULL on rows containing nulls would fail, so leave nullable.
    }
};
