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
        ['Applicants',  $summary['total_applicants'], 'indigo'],
        ['Enrolled',    $summary['total_enrolled'],   'emerald'],
        ['Courses',     $summary['total_courses'],    'amber'],
        ['Sections',    $summary['total_sections'],   'rose'],
    ] as [$label, $val, $color])
        <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wider text-slate-500">{{ $label }}</p>
            <p class="text-3xl font-bold text-{{ $color }}-600 mt-1">{{ number_format($val) }}</p>
        </div>
    @endforeach
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
    const [trends, caps, demo] = await Promise.all([
        j('{{ route('admin.analytics.trends') }}' + analyticsQs),
        j('{{ route('admin.analytics.capacities') }}' + analyticsQs),
        j('{{ route('admin.analytics.demographics') }}' + analyticsQs),
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
