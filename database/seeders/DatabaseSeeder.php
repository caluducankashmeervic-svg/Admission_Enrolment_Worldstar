<?php

namespace Database\Seeders;

use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@school.test'],
            ['name' => 'System Admin', 'password' => Hash::make('password'),
             'role' => User::ROLE_ADMIN, 'is_active' => true],
        );
        User::updateOrCreate(
            ['email' => 'registrar@school.test'],
            ['name' => 'Jane Registrar', 'password' => Hash::make('password'),
             'role' => User::ROLE_REGISTRAR, 'is_active' => true],
        );

        $term = AcademicTerm::updateOrCreate(
            ['school_year' => '2026-2027', 'semester' => '1st'],
            ['start_date' => '2026-08-01', 'end_date' => '2026-12-20', 'is_active' => true],
        );

        $courses = [
            ['BSIT', 'BS Information Technology',  'CCS', 80],
            ['BSCS', 'BS Computer Science',        'CCS', 60],
            ['BSBA', 'BS Business Administration', 'CBA', 100],
            ['BSED', 'BS Secondary Education',     'COE', 80],
        ];
        foreach ($courses as [$code, $name, $dept, $quota]) {
            $course = Course::updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'department' => $dept,
                 'quota' => $quota, 'duration_years' => 4, 'is_active' => true],
            );

            foreach (['A', 'B'] as $letter) {
                Section::updateOrCreate(
                    ['course_id' => $course->id, 'academic_term_id' => $term->id,
                     'name' => "{$code}-1{$letter}"],
                    ['year_level' => 1,
                     'capacity'   => config('enrollment.default_section_capacity', 40),
                     'is_open'    => true],
                );
            }
        }
    }
}
