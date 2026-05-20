<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // TECH-PRO TRACK
            ['code' => 'TP-AUTO',  'name' => 'Automotive and Small Engine Technologies',                                                                         'department' => 'Tech-Pro Track',     'duration_years' => 2],
            ['code' => 'TP-BHT',   'name' => 'Business, Hospitality, and Tourism Bundle (Bookkeeping NCIII & Events Management NCIII)',                          'department' => 'Tech-Pro Track',     'duration_years' => 2],
            ['code' => 'TP-CADT',  'name' => 'Creative Arts and Design Technologies Bundle (Animation NCII, Visual Graphic Design NCIII, & Web Development NCIII)', 'department' => 'Tech-Pro Track',  'duration_years' => 2],
            ['code' => 'TP-ICT',   'name' => 'ICT Support and Computer Programming Technologies Bundle (Computer Systems Servicing NC & Technical Drafting NCI)', 'department' => 'Tech-Pro Track',  'duration_years' => 2],
            ['code' => 'TP-IA',    'name' => 'Industrial Arts Bundle (Electronic Products Assembly and Servicing NCII & Electrical Installation and Maintenance NCII)', 'department' => 'Tech-Pro Track', 'duration_years' => 2],

            // 3-YEAR DIPLOMA COURSES
            ['code' => 'CST',      'name' => 'Computer Science Technology',           'department' => '3-Year Diploma Course', 'duration_years' => 3],
            ['code' => 'CET',      'name' => 'Computer Engineering Technology',       'department' => '3-Year Diploma Course', 'duration_years' => 3],
            ['code' => 'EET',      'name' => 'Electronics Engineering Technology',    'department' => '3-Year Diploma Course', 'duration_years' => 3],
            ['code' => 'ICT',      'name' => 'Information and Communications Technology', 'department' => '3-Year Diploma Course', 'duration_years' => 3],
        ];

        foreach ($courses as $c) {
            Course::updateOrCreate(
                ['code' => $c['code']],
                array_merge($c, ['quota' => 0, 'is_active' => true]),
            );
        }
    }
}
