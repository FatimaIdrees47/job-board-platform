@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('sidebar')
    @include('admin._sidebar')
@endsection

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <div>
            <h2 style="margin-bottom:4px;">Admin Dashboard</h2>
            <p style="font-size:13px;">Platform overview — last updated now.</p>
        </div>
        @if($stats['pending_approval'] > 0)
            <a href="{{ route('admin.jobs.index', ['approval' => 'pending']) }}" class="btn btn-primary">
                ⚠ {{ $stats['pending_approval'] }} pending approval
            </a>
        @endif
    </div>

    {{-- Stats Grid --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;">
        @php
            $cards = [
                ['label' => 'Total Jobs',       'value' => $stats['total_jobs'],         'trend' => '+' . $stats['new_jobs_this_week'] . ' this week',  'color' => 'var(--accent-bright)'],
                ['label' => 'Active Jobs',       'value' => $stats['active_jobs'],        'trend' => $stats['pending_approval'] . ' pending',            'color' => 'var(--success)'],
                ['label' => 'Candidates',        'value' => $stats['total_candidates'],   'trend' => '+' . $stats['new_users_this_week'] . ' this week', 'color' => 'var(--spark)'],
                ['label' => 'Employers',         'value' => $stats['total_employers'],    'trend' => $stats['verified_employers'] . ' verified',         'color' => 'var(--info)'],
                ['label' => 'Applications',      'value' => $stats['total_applications'], 'trend' => '+' . $stats['new_apps_this_week'] . ' this week',  'color' => 'var(--accent-pop)'],
                ['label' => 'Messages',          'value' => $stats['total_messages'],     'trend' => 'total sent',                                       'color' => 'var(--warning)'],
                ['label' => 'Verified Employers','value' => $stats['verified_employers'], 'trend' => 'of ' . $stats['total_employers'] . ' total',       'color' => 'var(--success)'],
                ['label' => 'Pending Approval',  'value' => $stats['pending_approval'],   'trend' => 'needs review',                                     'color' => 'var(--danger)'],
            ];
        @endphp
        @foreach($cards as $card)
            <div class="card" style="padding:18px;">
                <div style="font-family:var(--font-display);font-size:28px;font-weight:800;
                            color:{{ $card['color'] }};margin-bottom:4px;">
                    {{ number_format($card['value']) }}
                </div>
                <div style="font-size:13px;color:var(--text-secondary);margin-bottom:4px;">
                    {{ $card['label'] }}
                </div>
                <div style="font-size:11px;color:var(--text-tertiary);">{{ $card['trend'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px;">

        <div class="card" style="padding:24px;">
            <h4 style="margin-bottom:16px;font-size:15px;">User Signups — Last 30 Days</h4>
            <canvas id="signupChart" height="120"></canvas>
        </div>

        <div class="card" style="padding:24px;">
            <h4 style="margin-bottom:16px;font-size:15px;">Applications — Last 30 Days</h4>
            <canvas id="applicationChart" height="120"></canvas>
        </div>

    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- Pending Jobs --}}
        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                <h4>Pending Approval</h4>
                <a href="{{ route('admin.jobs.index', ['approval' => 'pending']) }}" style="font-size:13px;">View all →</a>
            </div>
            @if($pendingJobs->isEmpty())
                <div class="card" style="padding:24px;text-align:center;">
                    <p style="font-size:14px;color:var(--success);">✓ No jobs pending review</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($pendingJobs as $job)
                        <div class="card" style="padding:14px 16px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                                <div style="min-width:0;">
                                    <div style="font-size:13px;font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $job->title }}
                                    </div>
                                    <div style="font-size:12px;color:var(--text-tertiary);">
                                        {{ $job->employer->company_name }} · {{ $job->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div style="display:flex;gap:6px;flex-shrink:0;">
                                    <form method="POST" action="{{ route('admin.jobs.approve', $job) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm"
                                                style="background:rgba(16,185,129,0.15);color:var(--success);border:1px solid rgba(16,185,129,0.3);">
                                            ✓
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.jobs.reject', $job) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm">✕</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Activity Feed --}}
        <div>
            <h4 style="margin-bottom:14px;">Recent Activity</h4>
            <div class="card" style="padding:20px;">
                <div style="position:relative;padding-left:20px;">
                    <div style="position:absolute;left:6px;top:8px;bottom:8px;width:2px;background:var(--bg-muted);"></div>
                    @foreach($recentActivity as $activity)
                        <div style="position:relative;margin-bottom:14px;">
                            <div style="position:absolute;left:-17px;top:4px;width:8px;height:8px;
                                        border-radius:50%;background:{{ $activity['color'] }};
                                        border:2px solid var(--bg-base);flex-shrink:0;"></div>
                            <div style="font-size:13px;color:var(--text-secondary);line-height:1.5;">
                                {{ $activity['message'] }}
                            </div>
                            <div style="font-size:11px;color:var(--text-tertiary);margin-top:2px;">
                                {{ $activity['time']->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = @json($labels);
const signups = @json($signups);
const applications = @json($applications);

const chartDefaults = {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
        x: {
            ticks: {
                color: 'rgba(160,156,181,0.6)',
                font: { size: 10 },
                maxTicksLimit: 8,
            },
            grid: { color: 'rgba(46,46,62,0.5)' }
        },
        y: {
            ticks: { color: 'rgba(160,156,181,0.6)', font: { size: 10 } },
            grid: { color: 'rgba(46,46,62,0.5)' },
            beginAtZero: true,
        }
    }
};

new Chart(document.getElementById('signupChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            data: signups,
            borderColor: '#A78BFA',
            backgroundColor: 'rgba(167,139,250,0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 2,
        }]
    },
    options: chartDefaults
});

new Chart(document.getElementById('applicationChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            data: applications,
            backgroundColor: 'rgba(34,211,238,0.3)',
            borderColor: '#22D3EE',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: chartDefaults
});
</script>
@endsection