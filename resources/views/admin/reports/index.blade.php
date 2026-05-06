<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - RE-FOOD Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        :root { --green:#2e7d32; --green-dark:#1b5e20; --sidebar-width:200px; --topbar-height:64px; --bg:#f4f6f8; --border:#e4e8ed; --text:#1a2332; --muted:#6b7a8d; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--bg); color:var(--text); display:flex; min-height:100vh; }
        .sidebar { width:var(--sidebar-width); background:#2e7d32; display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:100; }
        .sidebar-logo { padding:16px 18px; display:flex; align-items:center; border-bottom:1px solid rgba(255,255,255,0.12); min-height:var(--topbar-height); }
        .logo-box { background:#fff; color:#2e7d32; font-weight:800; font-size:0.9rem; line-height:1.15; padding:7px 10px; border-radius:8px; text-align:center; }
        .logo-box span { display:block; font-size:0.62rem; font-weight:600; letter-spacing:1px; color:#2e7d32; }
        .sidebar-nav { flex:1; padding:10px 0; overflow-y:auto; }
        .nav-item { display:flex; align-items:center; gap:12px; padding:11px 18px; color:rgba(255,255,255,0.85); text-decoration:none; font-size:0.87rem; font-weight:500; border-left:3px solid transparent; transition:background .15s; }
        .nav-item:hover { background:rgba(0,0,0,0.15); color:#fff; }
        .nav-item.active { background:#1b5e20; color:#fff; border-left-color:#a5d6a7; }
        .nav-item svg { width:19px; height:19px; flex-shrink:0; }
        .main { margin-left:var(--sidebar-width); flex:1; display:flex; flex-direction:column; }
        .topbar { height:var(--topbar-height); background:#fff; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; padding:0 28px; position:sticky; top:0; z-index:50; }
        .topbar-title { font-size:1.5rem; font-weight:800; }
        .admin-badge { display:flex; align-items:center; gap:8px; background:#f0f2f5; border:1px solid var(--border); border-radius:50px; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer; position:relative; }
        .admin-badge svg { width:17px; height:17px; color:#2e7d32; }
        .admin-dropdown { display:none; position:absolute; top:calc(100% + 8px); right:0; background:#fff; border:1px solid var(--border); border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.1); min-width:160px; z-index:200; overflow:hidden; }
        .admin-dropdown.open { display:block; }
        .dropdown-item { display:flex; align-items:center; gap:10px; padding:11px 16px; font-size:0.84rem; font-weight:500; color:var(--text); text-decoration:none; transition:background .12s; cursor:pointer; border:none; background:none; width:100%; font-family:inherit; }
        .dropdown-item:hover { background:#f4f6f8; }
        .dropdown-item.danger { color:#dc2626; }
        .dropdown-item svg { width:15px; height:15px; }
        .dropdown-divider { height:1px; background:var(--border); }
        .content { padding:24px 28px; flex:1; display:flex; flex-direction:column; gap:18px; }

        /* CHARTS GRID */
        .charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
        .chart-card { background:#fff; border-radius:12px; border:1px solid var(--border); padding:20px; }
        .chart-title { font-size:0.88rem; font-weight:700; color:var(--text); margin-bottom:16px; }
        .chart-wrap { position:relative; height:200px; }

        /* Donut legend */
        .donut-wrap { display:grid; grid-template-columns:1fr 1fr; gap:16px; align-items:center; height:200px; }
        .donut-chart { position:relative; height:160px; width:160px; }
        .donut-legends { display:flex; flex-direction:column; gap:10px; }
        .legend-item { display:flex; align-items:center; gap:8px; font-size:0.82rem; }
        .legend-dot { width:12px; height:12px; border-radius:3px; flex-shrink:0; }
        .legend-label { color:var(--text); font-weight:600; }
        .legend-pct { color:var(--muted); font-size:0.78rem; }
    </style>
    <script>
        // Apply appearance & font dari localStorage sebelum render (cegah flash)
        (function() {
            var app  = localStorage.getItem('refood_appearance') || 'light';
            var font = localStorage.getItem('refood_font')       || 'medium';
            var html = document.documentElement;
            if (app === 'dark') html.classList.add('dark');
            html.classList.add('font-' + font);
        })();
    </script>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo"><div class="logo-box"><strong>RE</strong><span>food</span></div></div>
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
        <a href="{{ route('admin.foods.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>Foods</a>
        <a href="{{ route('admin.deliveries.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Deliveries</a>
        <a href="{{ route('admin.donors.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Donors</a>
        <a href="{{ route('admin.receivers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Receivers</a>
        <a href="{{ route('admin.volunteers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>Volunteers</a>
        <a href="{{ route('admin.reports.index') }}" class="nav-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Reports</a>
        <a href="{{ route('admin.settings') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Settings</a>
    </nav>
</aside>
<div class="main">
    <header class="topbar">
        <h1 class="topbar-title">Reports</h1>
        <div style="position:relative;">
            <div class="admin-badge" id="adminBtn" onclick="toggleDropdown()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
            </div>
            <div class="admin-dropdown" id="adminDropdown">
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Logout
                    </button>
                </form>
            </div>
        </div>
    </header>
    <div class="content">
        <div class="charts-grid">
            <!-- Bar: Food Distribution per hari -->
            <div class="chart-card">
                <div class="chart-title">Food Distribution</div>
                <div class="chart-wrap">
                    <canvas id="foodDistChart"></canvas>
                </div>
            </div>

            <!-- Donut: Delivery Status -->
            <div class="chart-card">
                <div class="chart-title">Delivery Status</div>
                <div class="donut-wrap">
                    <div class="donut-chart">
                        <canvas id="deliveryDonut"></canvas>
                    </div>
                    <div class="donut-legends">
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#2e7d32;"></div>
                            <div>
                                <div class="legend-label">Completed</div>
                                <div class="legend-pct">{{ $deliveryStats['completed_pct'] }}%</div>
                            </div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#f59e0b;"></div>
                            <div>
                                <div class="legend-label">Delivered</div>
                                <div class="legend-pct">{{ $deliveryStats['delivered_pct'] }}%</div>
                            </div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot" style="background:#ea580c;"></div>
                            <div>
                                <div class="legend-label">On Delivery</div>
                                <div class="legend-pct">{{ $deliveryStats['on_delivery_pct'] }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Line: Donor Growth -->
            <div class="chart-card">
                <div class="chart-title">Donor Growth</div>
                <div class="chart-wrap">
                    <canvas id="donorGrowthChart"></canvas>
                </div>
            </div>

            <!-- Line: Delivery Trend -->
            <div class="chart-card">
                <div class="chart-title">Delivery Status</div>
                <div class="chart-wrap">
                    <canvas id="deliveryTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDropdown() { document.getElementById('adminDropdown').classList.toggle('open'); }
document.addEventListener('click', function(e) {
    const btn = document.getElementById('adminBtn'), dd = document.getElementById('adminDropdown');
    if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('open');
});

const chartDefaults = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
};

// 1. Bar — Food Distribution
new Chart(document.getElementById('foodDistChart'), {
    type: 'bar',
    data: {
        labels: @json($foodDist['labels']),
        datasets: [{
            data: @json($foodDist['data']),
            backgroundColor: '#2e7d32',
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        ...chartDefaults,
        scales: {
            x: { grid: { display:false }, ticks: { font:{ size:11 } } },
            y: { grid: { color:'#f0f2f5' }, ticks: { font:{ size:11 } }, beginAtZero:true }
        }
    }
});

// 2. Donut — Delivery Status
new Chart(document.getElementById('deliveryDonut'), {
    type: 'doughnut',
    data: {
        labels: ['Completed','Delivered','On Delivery'],
        datasets: [{
            data: [{{ $deliveryStats['completed'] }}, {{ $deliveryStats['delivered'] }}, {{ $deliveryStats['on_delivery'] }}],
            backgroundColor: ['#2e7d32','#f59e0b','#ea580c'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display:false } },
        cutout: '68%',
    }
});

// 3. Line — Donor Growth
new Chart(document.getElementById('donorGrowthChart'), {
    type: 'line',
    data: {
        labels: @json($donorGrowth['labels']),
        datasets: [{
            data: @json($donorGrowth['data']),
            borderColor: '#ea580c',
            backgroundColor: 'rgba(234,88,12,0.08)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#ea580c',
            pointRadius: 4,
        }]
    },
    options: {
        ...chartDefaults,
        scales: {
            x: { grid:{ display:false }, ticks:{ font:{ size:11 } } },
            y: { grid:{ color:'#f0f2f5' }, ticks:{ font:{ size:11 } }, beginAtZero:true }
        }
    }
});

// 4. Line — Delivery Trend
new Chart(document.getElementById('deliveryTrendChart'), {
    type: 'line',
    data: {
        labels: @json($deliveryTrend['labels']),
        datasets: [{
            data: @json($deliveryTrend['data']),
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.07)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#2563eb',
            pointRadius: 4,
        }]
    },
    options: {
        ...chartDefaults,
        scales: {
            x: { grid:{ display:false }, ticks:{ font:{ size:11 } } },
            y: { grid:{ color:'#f0f2f5' }, ticks:{ font:{ size:11 } }, beginAtZero:true }
        }
    }
});
</script>
</body>
</html>
