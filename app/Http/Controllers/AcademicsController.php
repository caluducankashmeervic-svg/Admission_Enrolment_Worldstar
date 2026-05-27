<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcademicsController extends Controller
{
    /**
     * Static data for all programs, keyed by slug.
     */
    private static array $programs = [

        /* ── SHS Academic Track ─────────────────────────────────────── */

        'arts-social-sciences-humanities' => [
            'category'    => 'SHS Academic Track',
            'title'       => 'Arts, Social Sciences, and Humanities',
            'tagline'     => 'Develop creative expression and critical social thinking.',
            'description' => 'The Arts, Social Sciences, and Humanities strand is designed for students who have a strong interest in the arts, culture, society, and human behavior. This strand exposes learners to disciplines such as literature, communication arts, philosophy, performing arts, and social sciences. It builds skills in critical analysis, research, creative writing, and cultural appreciation. Graduates are well-prepared for college programs in Communication, Journalism, Political Science, Psychology, Fine Arts, Education, and other humanities-related fields.',
            'highlights'  => [
                'Core subjects in Social Sciences, Philosophy, and Literature',
                'Exposure to performing and visual arts',
                'Research and communication skills development',
                'Pathways to college: Communication, Journalism, Education, Fine Arts',
            ],
        ],

        'business-and-entrepreneurship' => [
            'category'    => 'SHS Academic Track',
            'title'       => 'Business and Entrepreneurship',
            'tagline'     => 'Build the foundation for business, finance, and enterprise.',
            'description' => 'The Business and Entrepreneurship strand equips students with the fundamental knowledge and practical skills needed to thrive in the world of commerce and industry. Learners are introduced to core business concepts including accounting, marketing, economics, and business management. The strand emphasizes an entrepreneurial mindset, preparing graduates to become either competent business professionals or innovative entrepreneurs. It serves as a strong foundation for college programs in Business Administration, Accountancy, Marketing, Management, and related fields.',
            'highlights'  => [
                'Subjects covering Accounting, Marketing, and Economics',
                'Entrepreneurship and business planning modules',
                'Practical case studies and simulations',
                'Pathways to college: Business Administration, Accountancy, Marketing',
            ],
        ],

        'science-technology-engineering-mathematics' => [
            'category'    => 'SHS Academic Track',
            'title'       => 'Science, Technology, Engineering & Mathematics',
            'tagline'     => 'Explore STEM for health and non-health pathways.',
            'description' => 'The Science, Technology, Engineering, and Mathematics (STEM) strand prepares students for careers in the natural sciences, engineering, technology, and health-related professions. This strand covers advanced studies in Biology, Chemistry, Physics, and Mathematics, with options for both health and non-health orientations. Students develop analytical and problem-solving skills essential for higher education in Engineering, Medicine, Computer Science, Architecture, and other technical fields. WCST offers STEM with both Health and Non-Health specialization tracks to accommodate diverse interests.',
            'highlights'  => [
                'Advanced subjects in Biology, Chemistry, Physics, and Calculus',
                'Health and Non-Health specialization options',
                'Laboratory and research-based learning',
                'Pathways: Engineering, Medicine, Computer Science, Architecture',
            ],
        ],

        /* ── SHS Tech-Pro Track ─────────────────────────────────────── */

        'automotive-small-engine-technologies' => [
            'category'    => 'SHS Tech-Pro Track',
            'title'       => 'Automotive and Small Engine Technologies',
            'tagline'     => 'Hands-on training in automotive and engine systems.',
            'description' => 'The Automotive and Small Engine Technologies strand provides students with practical and theoretical knowledge in the operation, maintenance, and repair of vehicles and small engine machinery. This tech-vocational strand integrates TESDA competency-based standards, giving students the opportunity to earn a National Certificate (NC) upon assessment. Learners develop technical skills that are highly in demand in the automotive and mechanical industries, both locally and abroad.',
            'highlights'  => [
                'Engine diagnostics, maintenance, and repair',
                'Alignment with TESDA NC II competency standards',
                'Workshop-based practical training',
                'Career paths: Mechanic, Automotive Technician, Service Advisor',
            ],
        ],

        'business-hospitality-tourism' => [
            'category'    => 'SHS Tech-Pro Track',
            'title'       => 'Business, Hospitality, and Tourism Bundle',
            'tagline'     => 'Prepare for careers in hospitality, tourism, and service industries.',
            'description' => 'The Business, Hospitality, and Tourism strand combines business fundamentals with specialized training in the hospitality and tourism sectors. Students learn front office operations, food and beverage services, housekeeping, travel operations, and customer service excellence. The program is aligned with industry standards and TESDA competencies, making graduates job-ready for hotels, resorts, airlines, travel agencies, and food service establishments.',
            'highlights'  => [
                'Hotel and restaurant operations training',
                'Customer service and front office management',
                'TESDA NC alignment for early certification',
                'Career paths: Hotel Staff, Tour Guide, Travel Agent, Restaurant Crew',
            ],
        ],

        'creative-arts-design-technologies' => [
            'category'    => 'SHS Tech-Pro Track',
            'title'       => 'Creative Arts and Design Technologies Bundle',
            'tagline'     => 'Fuse creativity with technology in design and media.',
            'description' => 'The Creative Arts and Design Technologies strand is for students passionate about visual communication, digital design, and the arts. It covers graphic design principles, digital media production, photography, and applied arts. Students gain proficiency in design software and develop a portfolio of works that prepares them for creative industries and arts-related college programs. The strand encourages innovation and artistic expression while grounding students in technical design skills.',
            'highlights'  => [
                'Graphic design and digital media production',
                'Portfolio development and creative projects',
                'Introduction to design software and tools',
                'Career paths: Graphic Designer, Multimedia Artist, Digital Content Creator',
            ],
        ],

        'ict-computer-programming-technologies' => [
            'category'    => 'SHS Tech-Pro Track',
            'title'       => 'ICT and Computer Programming Technologies Bundle',
            'tagline'     => 'Build technical skills in computing, networks, and programming.',
            'description' => 'The ICT and Computer Programming Technologies strand prepares students for careers in information technology, software development, and computer systems. Students learn programming fundamentals, web development, computer hardware servicing, and network administration. This strand is aligned with TESDA competency standards, and graduates may earn a National Certificate (NC II) in Computer Systems Servicing. It also serves as an excellent foundation for college programs in Computer Science, Information Technology, and Computer Engineering.',
            'highlights'  => [
                'Programming fundamentals and web development',
                'Computer hardware servicing (TESDA NC II pathway)',
                'Network installation and administration basics',
                'Career paths: Programmer, IT Support, Web Developer, Network Technician',
            ],
        ],

        'industrial-arts' => [
            'category'    => 'SHS Tech-Pro Track',
            'title'       => 'Industrial Arts Bundle',
            'tagline'     => 'Practical training in trades, construction, and industrial skills.',
            'description' => 'The Industrial Arts strand develops students\' technical capabilities in construction, electrical installation, plumbing, welding, and other skilled trades. This hands-on program is aligned with TESDA competency-based training, preparing students to take National Certificate (NC) assessments in their chosen trade area. Graduates are equipped to enter the workforce immediately in construction, utilities, manufacturing, and related industries, or to pursue technical-vocational courses at the college level.',
            'highlights'  => [
                'Electrical installation and maintenance training',
                'Welding, plumbing, and carpentry fundamentals',
                'TESDA NC I/II alignment per trade area',
                'Career paths: Electrician, Welder, Plumber, Construction Tradesman',
            ],
        ],

        /* ── TESDA Diploma Courses ──────────────────────────────────── */

        'cst-computer-science-technology' => [
            'category'    => 'TESDA Diploma Course',
            'title'       => 'CST — Computer Science Technology',
            'tagline'     => 'A diploma program covering core computing and system technologies.',
            'description' => 'The Computer Science Technology (CST) diploma program provides students with a comprehensive education in computing fundamentals, systems analysis, database management, programming, and network infrastructure. This TESDA-accredited program is designed for graduates who wish to enter the IT industry quickly with a recognized qualification, or to continue toward a full bachelor\'s degree. CST prepares students for roles in software development, system administration, and technical support.',
            'highlights'  => [
                'Programming, database, and systems analysis',
                'Networking and cybersecurity fundamentals',
                'TESDA-accredited diploma qualification',
                'Career paths: System Administrator, Programmer, IT Support Specialist',
            ],
        ],

        'cet-computer-engineering-technology' => [
            'category'    => 'TESDA Diploma Course',
            'title'       => 'CET — Computer Engineering Technology',
            'tagline'     => 'Bridges hardware engineering with software systems.',
            'description' => 'The Computer Engineering Technology (CET) diploma program combines electronics, hardware systems, and computer science fundamentals. Students learn circuit design, microprocessor technology, embedded systems, and computer architecture alongside programming and software integration. This program is ideal for students aiming for technical roles in hardware manufacturing, electronics repair, embedded systems, and computer assembly. Graduates may also advance to a full engineering degree.',
            'highlights'  => [
                'Electronics and circuit theory applied to computing',
                'Microprocessors, embedded systems, and hardware assembly',
                'Integration of software programming with hardware systems',
                'Career paths: Hardware Technician, Electronics Engineer, Embedded Systems Developer',
            ],
        ],

        'eet-electronics-engineering-technology' => [
            'category'    => 'TESDA Diploma Course',
            'title'       => 'EET — Electronics Engineering Technology',
            'tagline'     => 'Focused training in electronics, communications, and instrumentation.',
            'description' => 'The Electronics Engineering Technology (EET) diploma program prepares students for technical roles in electronics, telecommunications, and instrumentation. The curriculum covers electronic circuit theory, analog and digital systems, communications technology, and industrial electronics. Students gain practical laboratory experience and are prepared for TESDA competency assessments. EET graduates are in high demand across the electronics manufacturing, telecommunications, and energy sectors.',
            'highlights'  => [
                'Analog and digital circuit design and troubleshooting',
                'Telecommunications and signal systems',
                'Industrial electronics and instrumentation',
                'Career paths: Electronics Technician, Telecommunications Specialist, Instrumentation Engineer',
            ],
        ],

        'ict-information-communications-technology' => [
            'category'    => 'TESDA Diploma Course',
            'title'       => 'ICT — Information and Communications Technology',
            'tagline'     => 'Comprehensive ICT training for the digital economy.',
            'description' => 'The Information and Communications Technology (ICT) diploma program equips students with broad and practical knowledge in computing, digital communications, and network systems. The program covers web technologies, data management, telecommunications systems, computer hardware servicing, and ICT project management. This TESDA-accredited diploma provides a recognized qualification that opens doors to employment in BPO, IT services, telecommunications, and government technology sectors, while also serving as a bridge to a bachelor\'s degree.',
            'highlights'  => [
                'Web development, data management, and network systems',
                'Computer hardware servicing and troubleshooting',
                'TESDA accreditation and NC II pathway',
                'Career paths: ICT Specialist, Network Technician, BPO Technical Support',
            ],
        ],
    ];

    public function show(string $slug)
    {
        $program = self::$programs[$slug] ?? null;

        if (! $program) {
            abort(404);
        }

        return view('academics.program', compact('program', 'slug'));
    }
}
