@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<section class="bg-gradient-to-r from-blue-700 via-blue-600 to-emerald-600 text-white rounded-xl shadow-lg p-6 flex items-center gap-5">
    <div class="w-20 h-20 rounded-full bg-white shadow-md flex items-center justify-center p-2 shrink-0">
        <img src="{{ asset('images/image.png') }}" alt="Worldstar Logo" class="w-full h-full object-contain">
    </div>
    <div class="flex-1">
        <p class="uppercase tracking-widest text-amber-300 text-[11px] font-semibold">Administrator Dashboard</p>
        <h1 class="text-2xl md:text-3xl font-extrabold leading-tight">
            Worldstar College of Science and Technology, Inc.
        </h1>
        @if($term)
            <p class="text-sm text-white/85 mt-1">
                Active term: <strong>{{ $term->school_year }} — {{ $term->semester }} Sem</strong>
            </p>
        @endif
        <p class="text-sm text-white/85 mt-1">
            Viewing: <strong>{{ $selectedTermLabel }}</strong>
        </p>
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
        ['Exam Scheduled',  $summary['exam_scheduled'],  'bg-sky-50 text-sky-700 border-sky-200',       route('registrar.applicants.index', ['status' => 'exam_scheduled'])],
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

@push('scripts')
<script>
async function j(url){ const r = await fetch(url); return r.json(); }

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
