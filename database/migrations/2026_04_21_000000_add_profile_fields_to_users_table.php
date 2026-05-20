<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('email');
            $table->string('firstname')->nullable()->after('username');
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('middlename')->nullable()->after('lastname');
            $table->string('contact_no', 20)->nullable()->after('middlename');
            $table->string('student_no', 20)->nullable()->unique()->after('contact_no');
            $table->string('profile_photo_path')->nullable()->after('student_no');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropUnique(['student_no']);
            $table->dropColumn([
                'username', 'firstname', 'lastname', 'middlename',
                'contact_no', 'student_no', 'profile_photo_path',
            ]);
        });
    }
};
