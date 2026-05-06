<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receiver Detail - RE-FOOD Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
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
        .topbar-left { display:flex; align-items:center; gap:10px; }
        .btn-back { display:flex; align-items:center; gap:5px; font-size:0.9rem; font-weight:700; color:var(--text); text-decoration:none; }
        .btn-back:hover { opacity:.7; }
        .btn-back svg { width:18px; height:18px; }
        .admin-badge { display:flex; align-items:center; gap:8px; background:#f0f2f5; border:1px solid var(--border); border-radius:50px; padding:8px 16px; font-size:0.85rem; font-weight:600; }
        .admin-badge svg { width:17px; height:17px; color:#2e7d32; }
        .content { padding:24px 28px; flex:1; display:flex; flex-direction:column; gap:18px; }

        .detail-card { background:#fff; border-radius:14px; border:1px solid var(--border); overflow:hidden; max-width:860px; }
        .detail-header { padding:18px 22px; border-bottom:1px solid var(--border); }
        .detail-header h2 { font-size:1.3rem; font-weight:800; }

        /* Foto + info grid */
        .top-section { display:grid; grid-template-columns:160px 1fr; border-bottom:1px solid var(--border); }
        .photo-col { padding:20px; border-right:1px solid var(--border); display:flex; flex-direction:column; align-items:center; gap:8px; }
        .photo-box { width:120px; height:100px; border-radius:10px; border:2px dashed var(--border); background:#f8f9fb; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .photo-box img { width:100%; height:100%; object-fit:cover; }
        .photo-box svg { width:36px; height:36px; color:#d1d5db; }
        .info-col { padding:20px 24px; }

        /* Type badge */
        .type-badge { display:inline-flex; align-items:center; gap:6px; font-size:0.8rem; font-weight:700; padding:5px 12px; border-radius:8px; margin-bottom:10px; }
        .type-orphanage  { background:#dbeafe; color:#1d4ed8; }
        .type-foundation { background:#f0fdf4; color:#16a34a; }
        .type-community  { background:#faf5ff; color:#7c3aed; }
        .type-school     { background:#fff7ed; color:#c2410c; }
        .type-other      { background:#f4f6f8; color:var(--muted); }

        /* Info grid sesuai gambar */
        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; border:1px solid var(--border); border-radius:10px; overflow:hidden; }
        .info-cell { padding:10px 14px; border-bottom:1px solid var(--border); border-right:1px solid var(--border); font-size:0.84rem; }
        .info-cell:nth-child(even) { border-right:none; }
        .info-cell:nth-last-child(-n+2) { border-bottom:none; }
        .info-cell.full { grid-column:1/-1; border-right:none; }
        .info-cell.full:last-child { border-bottom:none; }
        .info-cell-label { font-size:0.72rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.3px; margin-bottom:3px; }
        .info-cell-value { font-weight:600; color:var(--text); }
        .badge-active { display:inline-flex; align-items:center; gap:5px; background:#dcfce7; color:#16a34a; font-size:0.75rem; font-weight:700; padding:3px 10px; border-radius:20px; }
        .badge-dot { width:6px; height:6px; border-radius:50%; background:currentColor; }

        /* Foods Received Table */
        .foods-section { padding:20px 22px; }
        .foods-section h3 { font-size:0.95rem; font-weight:700; margin-bottom:14px; }
        table { width:100%; border-collapse:collapse; }
        thead th { padding:9px 14px; text-align:left; font-size:0.72rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; border-bottom:1px solid var(--border); background:#f8f9fb; }
        tbody tr { border-bottom:1px solid var(--border); }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover { background:#fafbfc; }
        tbody td { padding:10px 14px; font-size:0.84rem; }
        .td-food { font-weight:700; }
        .td-muted { color:var(--muted); font-size:0.82rem; }

        .sp { display:inline-flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; padding:3px 10px; border-radius:20px; }
        .sp-delivered  { background:#dcfce7; color:#16a34a; }
        .sp-on_delivery{ background:#ffedd5; color:#ea580c; }
        .sp-pending    { background:#fef9c3; color:#ca8a04; }
        .sp-failed     { background:#fee2e2; color:#dc2626; }
        .sp-dot { width:6px; height:6px; border-radius:50%; background:currentColor; }

        .detail-footer { padding:14px 22px; border-top:1px solid var(--border); display:flex; gap:10px; justify-content:flex-end; background:#f8f9fb; }
        .btn-back-footer { display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--border); color:var(--text); font-family:inherit; font-size:0.84rem; font-weight:600; padding:9px 16px; border-radius:8px; cursor:pointer; text-decoration:none; }
        .btn-del { display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid #fecaca; color:#dc2626; font-family:inherit; font-size:0.84rem; font-weight:600; padding:9px 16px; border-radius:8px; cursor:pointer; }
        .btn-back-footer:hover { background:#f4f6f8; }
        .btn-del:hover { background:#fee2e2; }
        .btn-back-footer svg, .btn-del svg { width:14px; height:14px; }

        .alert { padding:12px 16px; border-radius:8px; font-size:0.87rem; font-weight:500; display:flex; align-items:center; gap:10px; }
        .alert-success { background:#dcfce7; color:#16a34a; border:1px solid #bbf7d0; }
        .alert svg { width:16px; height:16px; }
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
        <a href="{{ route('admin.receivers.index') }}" class="nav-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Receivers</a>
        <a href="{{ route('admin.volunteers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>Volunteers</a>
        <a href="{{ route('admin.reports.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Reports</a>
        <a href="{{ route('admin.settings') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Settings</a>
    </nav>
</aside>

<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <a href="{{ route('admin.receivers.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Receiver Detail
            </a>
        </div>
        <div class="admin-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
        </div>
    </header>

    <div class="content">
        @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="detail-card">
            <div class="detail-header">
                <h2>{{ $receiver->name }}</h2>
            </div>

            <div class="top-section">
                <div class="photo-col">
                    <div class="photo-box">
                        @if($receiver->photo)
                            <img src="{{ asset('storage/'.$receiver->photo) }}" alt="{{ $receiver->name }}">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        @endif
                    </div>
                </div>
                <div class="info-col">
                    @php
                        $typeClass = match($receiver->type) {
                            'orphanage'  => 'type-orphanage',
                            'foundation' => 'type-foundation',
                            'community'  => 'type-community',
                            'school'     => 'type-school',
                            default      => 'type-other',
                        };
                        $typeLabel = match($receiver->type) {
                            'orphanage'  => 'Orphanage',
                            'foundation' => 'Foundation',
                            'community'  => 'Community',
                            'school'     => 'School',
                            default      => 'Other',
                        };
                    @endphp
                    <div class="type-badge {{ $typeClass }}">{{ $typeLabel }}</div>
                    @if($receiver->address)
                    <div style="font-size:0.82rem;color:var(--muted);margin-bottom:10px;">Address: {{ $receiver->address }}</div>
                    @endif
                    <div class="info-grid">
                        <div class="info-cell">
                            <div class="info-cell-label">Total Foods Received</div>
                            <div class="info-cell-value">{{ $receiver->deliveries_count ?? $receiver->deliveries->count() }}</div>
                        </div>
                        <div class="info-cell">
                            <div class="info-cell-label">Last Received</div>
                            <div class="info-cell-value">{{ $receiver->deliveries->last()?->created_at?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="info-cell">
                            <div class="info-cell-label">Status</div>
                            <div class="info-cell-value">
                                <span class="badge-active"><span class="badge-dot"></span>Active</span>
                            </div>
                        </div>
                        <div class="info-cell">
                            <div class="info-cell-label">No</div>
                            <div class="info-cell-value">{{ $receiver->phone ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOODS RECEIVED TABLE -->
            <div class="foods-section">
                <h3>Foods Received</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Food Name</th>
                            <th>Donor</th>
                            <th>Portion</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receiver->deliveries as $delivery)
                        @php
                            $sc = match($delivery->status) {
                                'delivered'   => 'sp-delivered',
                                'on_delivery' => 'sp-on_delivery',
                                'failed'      => 'sp-failed',
                                default       => 'sp-pending',
                            };
                            $sl = match($delivery->status) {
                                'delivered'   => 'Delivered',
                                'on_delivery' => 'On Delivery',
                                'failed'      => 'Failed',
                                default       => 'Pending',
                            };
                        @endphp
                        <tr>
                            <td class="td-food">{{ $delivery->food->name ?? '-' }}</td>
                            <td>{{ $delivery->donor->name ?? '-' }}</td>
                            <td>{{ $delivery->food->portion ?? '-' }}</td>
                            <td class="td-muted">{{ $delivery->created_at->format('d M Y') }}</td>
                            <td><span class="sp {{ $sc }}"><span class="sp-dot"></span>{{ $sl }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted);">Belum ada makanan yang diterima</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="detail-footer">
                <a href="{{ route('admin.receivers.index') }}" class="btn-back-footer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                    Kembali
                </a>
                <form method="POST" action="{{ route('admin.receivers.destroy', $receiver) }}" onsubmit="return confirm('Yakin hapus receiver ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-del">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                        Hapus Receiver
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
