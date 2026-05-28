@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<section class="relative overflow-hidden rounded-xl shadow-lg text-white">
    <div class="absolute inset-0" style="background: linear-gradient(135deg, #0b1c96 0%, #1a38d6 45%, #0d7364 100%);"></div>
    <div class="absolute inset-0 opacity-[0.07]" style="background-image: repeating-linear-gradient(45deg, #fff 0px, #fff 1px, transparent 1px, transparent 24px);"></div>
    <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full blur-3xl" style="background: rgba(52,211,153,0.20);"></div>
    <div class="absolute -bottom-16 -left-8 w-56 h-56 rounded-full blur-3xl" style="background: rgba(99,102,241,0.20);"></div>
    <div class="relative flex items-center gap-5 p-6">
        <div class="w-20 h-20 rounded-full bg-white shadow-xl flex items-center justify-center p-2 shrink-0 ring-4 ring-white/20">
            <img src="{{ asset('images/image.png') }}" alt="Worldstar Logo" class="w-full h-full object-contain">
        </div>
        <div class="flex-1 min-w-0">
            <p class="uppercase tracking-[0.3em] text-amber-300 text-[10px] font-bold">Administrator Dashboard</p>
            <h1 class="text-xl md:text-3xl font-extrabold leading-tight mt-0.5">
                Worldstar College of Science and Technology, Inc.
            </h1>
            <div class="flex flex-wrap items-center gap-x-5 gap-y-1 mt-2 text-xs text-white/80">
                @if($term)
                    <span class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Active: <strong class="text-white ml-1">{{ $term->school_year }} — {{ $term->semester }} Sem</strong>
                    </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-300"></span>
                    Viewing: <strong class="text-amber-300 ml-1">{{ $selectedTermLabel }}</strong>
                </span>
            </div>
        </div>
        <div class="hidden lg:flex divide-x divide-white/20 shrink-0">
            <div class="text-center px-5">
                <p class="text-3xl font-black text-amber-300 leading-none">{{ number_format($summary['total_applicants']) }}</p>
                <p class="text-[10px] uppercase tracking-widest text-white/60 mt-1">Applicants</p>
            </div>
            <div class="text-center px-5">
                <p class="text-3xl font-black text-emerald-300 leading-none">{{ number_format($summary['total_enrolled']) }}</p>
                <p class="text-[10px] uppercase tracking-widest text-white/60 mt-1">Enrolled</p>
            </div>
            <div class="text-center px-5">
                <p class="text-3xl font-black text-white leading-none">{{ number_format($summary['total_courses']) }}</p>
                <p class="text-[10px] uppercase tracking-widest text-white/60 mt-1">Courses</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white border border-slate-200 rounded-lg shadow-sm p-4 mt-4">
    <form method="GET" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="text-xs uppercase tracking-wide text-slate-500">Dashboard Term Filter</label>
            <select name="term" class="mt-1 border rounded px-3 py-2 min-w-[220px]">
                <option value="active" @selected(request('term', 'active') === 'active')>Active Term</option>
                <option value="all" @selected(request('term') === 'all')>All Terms</option>
                @foreach($terms as $t)
                    <option value="{{ $t->id }}" @selected((string) request('term') === (string) $t->id)>
                        {{ $t->school_year }} {{ $t->semester }}{{ $t->is_active ? ' (Active)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Apply</button>
        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-slate-600 hover:underline">Reset</a>
    </form>
</section>

<section class="grid md:grid-cols-4 gap-4 mt-5">
    @foreach ([
        ['Applicants',  $summary['total_applicants'], 'indigo',  route('registrar.applicants.index')],
        ['Enrolled',    $summary['total_enrolled'],   'emerald', route('registrar.enrollments.index')],
        ['Courses',     $summary['total_courses'],    'amber',   route('admin.courses.index')],
        ['Sections',    $summary['total_sections'],   'rose',    route('admin.sections.index')],
    ] as [$label, $val, $color, $href])
        <a href="{{ $href }}" class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm hover:shadow-md hover:border-{{ $color }}-300 transition">
            <p class="text-xs uppercase tracking-wider text-slate-500">{{ $label }}</p>
            <p class="text-3xl font-bold text-{{ $color }}-600 mt-1">{{ number_format($val) }}</p>
        </a>
    @endforeach
</section>

<section class="grid md:grid-cols-3 lg:grid-cols-6 gap-3 mt-4">
    @foreach ([
        ['Pending Pre-Reg', $summary['pending_pre_reg'], 'bg-amber-50 text-amber-700 border-amber-200', route('registrar.applicants.index', ['status' => 'pre_registered'])],
        ['Exam Scheduled',  $summary['exam_scheduled'],  'bg-sky-50 text-sky-700 border-sky-200',       route('exam.schedule.index')],
        ['Exam Completed',  $summary['exam_completed'],  'bg-blue-50 text-blue-700 border-blue-200',    route('registrar.applicants.index', ['status' => 'exam_completed'])],
        ['Verified',        $summary['verified'],        'bg-indigo-50 text-indigo-700 border-indigo-200', route('registrar.applicants.index', ['status' => 'verified'])],
        ['Rejected',        $summary['rejected'],        'bg-rose-50 text-rose-700 border-rose-200',    route('admin.trash.index')],
        ['Sections Full',   $summary['sections_full'],   'bg-slate-100 text-slate-700 border-slate-200', route('admin.sections.index')],
    ] as [$label, $val, $cls, $href])
        <a href="{{ $href }}" class="border rounded-lg px-4 py-3 {{ $cls }} hover:shadow transition block">
            <p class="text-[10px] uppercase tracking-wider font-semibold opacity-80">{{ $label }}</p>
            <p class="text-2xl font-bold mt-0.5">{{ number_format($val) }}</p>
        </a>
    @endforeach
</section>

@php
    $enrollmentRate = $summary['total_applicants'] > 0
        ? round($summary['total_enrolled'] / $summary['total_applicants'] * 100, 1)
        : 0;
@endphp
<section class="grid sm:grid-cols-3 gap-3 mt-3">
    <div class="bg-white border border-slate-200 rounded-lg px-4 py-3 shadow-sm flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-wider text-slate-500">Enrollment Rate</p>
            <p class="text-xl font-bold text-indigo-600">{{ $enrollmentRate }}%</p>
            <p class="text-[10px] text-slate-400">{{ $summary['total_enrolled'] }} of {{ $summary['total_applicants'] }} applicants</p>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-lg px-4 py-3 shadow-sm flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-wider text-slate-500">Active Announcements</p>
            <p class="text-xl font-bold text-amber-600">{{ $announcementCount }}</p>
            <a href="{{ route('admin.announcements.index') }}" class="text-[10px] text-blue-500 hover:underline">Manage →</a>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-lg px-4 py-3 shadow-sm flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-wider text-slate-500">Data As Of</p>
            <p class="text-sm font-semibold text-slate-700">{{ now()->format('M d, Y') }}</p>
            <p class="text-[10px] text-slate-400">{{ now()->format('g:i A') }}</p>
        </div>
    </div>
</section>

<section class="grid md:grid-cols-2 gap-3 mt-3">
    <div class="bg-white border border-slate-200 rounded-lg px-4 py-3 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[11px] uppercase tracking-wider text-slate-500">New Applicants Today</p>
            <p class="text-xl font-bold text-emerald-600 mt-0.5">{{ number_format($summary['new_today']) }}</p>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-wider text-slate-500 text-right">Last 7 Days</p>
            <p class="text-xl font-bold text-blue-600 mt-0.5 text-right">{{ number_format($summary['new_this_week']) }}</p>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-lg px-4 py-3 shadow-sm flex items-center gap-3 flex-wrap">
        <span class="text-[11px] uppercase tracking-wider text-slate-500">Quick Actions:</span>
        <a href="{{ route('admin.terms.index') }}" class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700">+ Term</a>
        <a href="{{ route('admin.courses.index') }}" class="text-xs bg-amber-600 text-white px-3 py-1.5 rounded hover:bg-amber-700">+ Course</a>
        <a href="{{ route('admin.sections.index') }}" class="text-xs bg-rose-600 text-white px-3 py-1.5 rounded hover:bg-rose-700">+ Section</a>
        <a href="{{ route('exam.schedule.index') }}" class="text-xs bg-emerald-600 text-white px-3 py-1.5 rounded hover:bg-emerald-700">Exams</a>
    </div>
</section>

<section class="grid lg:grid-cols-2 gap-5 mt-6">
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
        <h2 class="font-semibold mb-3">Applicants vs Enrolled (by month)</h2>
        <canvas id="trendsChart" height="120"></canvas>
    </div>
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
        <h2 class="font-semibold mb-3">Course Capacity vs Enrolled</h2>
        <canvas id="capacityChart" height="120"></canvas>
    </div>
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
        <h2 class="font-semibold mb-3">Application Funnel</h2>
        <canvas id="funnelChart" height="140"></canvas>
    </div>
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
        <h2 class="font-semibold mb-3">Top 5 Courses by Applicants</h2>
        <canvas id="topCoursesChart" height="140"></canvas>
    </div>
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
        <h2 class="font-semibold mb-3">Gender Distribution</h2>
        <canvas id="genderChart" height="160"></canvas>
    </div>
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
        <h2 class="font-semibold mb-3">Age Group</h2>
        <canvas id="ageChart" height="160"></canvas>
    </div>
</section>

<section class="grid lg:grid-cols-2 gap-5 mt-5">
    {{-- Upcoming Exams --}}
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-slate-800">Upcoming Exams</h2>
            <a href="{{ route('exam.schedule.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
        </div>
        @if($upcomingExams->isEmpty())
            <div class="py-6 text-center text-slate-400">
                <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-sm">No upcoming exams scheduled.</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($upcomingExams as $exam)
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-50 border border-slate-100 hover:border-blue-200 transition">
                        <div class="shrink-0 text-center w-10">
                            <p class="text-[10px] font-black text-blue-600 uppercase">{{ $exam->exam_datetime->format('M') }}</p>
                            <p class="text-xl font-black text-slate-800 leading-none">{{ $exam->exam_datetime->format('d') }}</p>
                        </div>
                        <div class="flex-1 min-w-0 border-l border-slate-200 pl-3">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $exam->batch_code }}</p>
                            <p class="text-xs text-slate-500">{{ $exam->exam_datetime->format('g:i A') }} · {{ $exam->venue }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-xs font-bold {{ $exam->assigned_count >= $exam->capacity ? 'text-rose-500' : 'text-emerald-600' }}">
                                {{ $exam->assigned_count }}/{{ $exam->capacity }}
                            </p>
                            <p class="text-[10px] text-slate-400">slots</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recent Applications --}}
    <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-slate-800">Recent Applications</h2>
            <a href="{{ route('registrar.applicants.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
        </div>
        @if($recentActivity->isEmpty())
            <div class="py-6 text-center text-slate-400">
                <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-sm">No applications yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <th class="pb-2 text-left font-semibold">Applicant</th>
                            <th class="pb-2 text-left font-semibold">Course</th>
                            <th class="pb-2 text-left font-semibold">Status</th>
                            <th class="pb-2 text-right font-semibold">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentActivity as $app)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-2 font-medium text-slate-800">{{ $app->first_name }} {{ $app->last_name }}</td>
                                <td class="py-2 text-slate-500">{{ $app->preferredCourse?->code ?? '—' }}</td>
                                <td class="py-2">
                                    @php
                                        $sc = match($app->status) {
                                            'enrolled'       => 'bg-emerald-100 text-emerald-700',
                                            'verified'       => 'bg-indigo-100 text-indigo-700',
                                            'exam_completed' => 'bg-blue-100 text-blue-700',
                                            'exam_scheduled' => 'bg-sky-100 text-sky-700',
                                            'rejected'       => 'bg-rose-100 text-rose-700',
                                            default          => 'bg-amber-100 text-amber-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $sc }}">
                                        {{ ucwords(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                </td>
                                <td class="py-2 text-slate-500 text-right">{{ $app->created_at->format('M d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
async function j(url){ const r = await fetch(url); return r.json(); }

Chart.register({
    id: 'noDataText',
    afterDraw(chart) {
        const total = chart.data.datasets.reduce(
            (s, ds) => s + ds.data.reduce((a, v) => a + (Number(v) || 0), 0), 0
        );
        if (total === 0) {
            const { ctx, width, height } = chart;
            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = '600 13px system-ui, sans-serif';
            ctx.fillStyle = '#94a3b8';
            ctx.fillText('No data for this term', width / 2, height / 2);
            ctx.restore();
        }
    }
});

const params = new URLSearchParams(window.location.search);
const termParam = params.get('term') || 'active';
const analyticsQs = '?term=' + encodeURIComponent(termParam);

(async () => {
    const [trends, caps, demo, top, funnel] = await Promise.all([
        j('{{ route('admin.analytics.trends') }}' + analyticsQs),
        j('{{ route('admin.analytics.capacities') }}' + analyticsQs),
        j('{{ route('admin.analytics.demographics') }}' + analyticsQs),
        j('{{ route('admin.analytics.top-courses') }}' + analyticsQs),
        j('{{ route('admin.analytics.funnel') }}' + analyticsQs),
    ]);

    new Chart(document.getElementById('trendsChart'), {
        type: 'line',
        data: { labels: trends.labels, datasets: [
            { label: 'Applicants', data: trends.applicants, borderColor: '#6366f1', fill: false },
            { label: 'Enrolled',   data: trends.enrolled,   borderColor: '#10b981', fill: false },
        ]},
        options: { responsive: true }
    });

    new Chart(document.getElementById('capacityChart'), {
        type: 'bar',
        data: { labels: caps.labels, datasets: [
            { label: 'Quota',    data: caps.quota,    backgroundColor: '#cbd5e1' },
            { label: 'Enrolled', data: caps.enrolled, backgroundColor: '#6366f1' },
        ]},
        options: { responsive: true }
    });

    new Chart(document.getElementById('funnelChart'), {
        type: 'bar',
        data: { labels: funnel.labels, datasets: [
            { label: 'Applicants', data: funnel.data,
              backgroundColor: ['#f59e0b','#0ea5e9','#3b82f6','#6366f1','#10b981'] }
        ]},
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('topCoursesChart'), {
        type: 'bar',
        data: { labels: top.labels, datasets: [
            { label: 'Applicants', data: top.data, backgroundColor: '#0ea5e9' }
        ]},
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: { labels: demo.gender.labels,
                datasets: [{ data: demo.gender.data,
                             backgroundColor: ['#6366f1','#ec4899','#94a3b8'] }] }
    });

    new Chart(document.getElementById('ageChart'), {
        type: 'bar',
        data: { labels: demo.age.labels,
                datasets: [{ label: 'Applicants', data: demo.age.data,
                             backgroundColor: '#10b981' }] }
    });
})();
</script>
@endpush
@endsection
