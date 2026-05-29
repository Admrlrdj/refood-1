<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deliveries - RE-FOOD Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
    <style>
        :root { --green:#2e7d32; --green-dark:#1b5e20; --sidebar-width:200px; --topbar-height:64px; --bg:#f4f6f8; --border:#e4e8ed; --text:#1a2332; --muted:#6b7a8d; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--bg); color:var(--text); display:flex; min-height:100vh; }

        /* SIDEBAR */
        .sidebar { width:var(--sidebar-width); background:#2e7d32; display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:100; }
        .sidebar-logo { padding:16px 18px; display:flex; align-items:center; border-bottom:1px solid rgba(255,255,255,0.12); min-height:var(--topbar-height); }
        .logo-box { background:#fff; color:#2e7d32; font-weight:800; font-size:0.9rem; line-height:1.15; padding:7px 10px; border-radius:8px; text-align:center; }
        .logo-box span { display:block; font-size:0.62rem; font-weight:600; letter-spacing:1px; color:#2e7d32; }
        .sidebar-nav { flex:1; padding:10px 0; overflow-y:auto; }
        .nav-item { display:flex; align-items:center; gap:12px; padding:11px 18px; color:rgba(255,255,255,0.85); text-decoration:none; font-size:0.87rem; font-weight:500; border-left:3px solid transparent; transition:background .15s; }
        .nav-item:hover { background:rgba(0,0,0,0.15); color:#fff; }
        .nav-item.active { background:#1b5e20; color:#fff; border-left-color:#a5d6a7; }
        .nav-item svg { width:19px; height:19px; flex-shrink:0; }

        /* MAIN */
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

        .content { padding:24px 28px; flex:1; display:flex; flex-direction:column; gap:20px; }

        /* STAT CARDS — 3 kolom sesuai gambar: Completed(biru), Delivered(hijau), On Delivery(orange) */
        .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        .stat-card { border-radius:12px; padding:20px 22px; color:#fff; position:relative; overflow:hidden; border:3px solid transparent; }
        .stat-card.blue   { background:#2563eb; border-color:#1d4ed8; }
        .stat-card.green  { background:#2e7d32; border-color:#1b5e20; }
        .stat-card.orange { background:#f59e0b; border-color:#d97706; }
        .stat-top { display:flex; align-items:center; gap:10px; margin-bottom:10px; }
        .stat-top svg { width:22px; height:22px; opacity:.95; }
        .stat-top-label { font-size:1rem; font-weight:700; }
        .stat-number { font-size:2.8rem; font-weight:800; line-height:1; letter-spacing:-2px; }

        /* TOOLBAR */
        .toolbar { display:flex; align-items:center; gap:12px; background:#fff; border:1px solid var(--border); border-radius:10px; padding:10px 16px; }
        .search-box { display:flex; align-items:center; gap:8px; flex:1; }
        .search-box svg { width:16px; height:16px; color:var(--muted); flex-shrink:0; }
        .search-box input { border:none; outline:none; font-family:inherit; font-size:0.87rem; color:var(--text); width:100%; background:none; }
        .search-box input::placeholder { color:var(--muted); }
        .toolbar-divider { width:1px; height:24px; background:var(--border); }
        .sort-label { font-size:0.84rem; color:var(--muted); white-space:nowrap; }
        .sort-select { border:none; outline:none; font-family:inherit; font-size:0.84rem; font-weight:600; color:var(--text); cursor:pointer; background:none; }
        .btn-add { display:inline-flex; align-items:center; gap:7px; background:#2e7d32; color:#fff; font-family:inherit; font-size:0.85rem; font-weight:600; padding:9px 16px; border-radius:8px; border:none; cursor:pointer; text-decoration:none; transition:background .15s; white-space:nowrap; }
        .btn-add:hover { background:#1b5e20; }
        .btn-add svg { width:15px; height:15px; }

        /* DELIVERY CARDS GRID — 4 kolom sesuai gambar */
        .cards-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }

        .dcard { background:#fff; border-radius:12px; border:1px solid var(--border); overflow:hidden; transition:box-shadow .2s, transform .15s; cursor:pointer; display:flex; flex-direction:column; }
        .dcard:hover { box-shadow:0 6px 20px rgba(0,0,0,0.1); transform:translateY(-2px); }

        /* Status header bar */
        .dcard-status { padding:10px 14px; display:flex; align-items:center; gap:8px; font-size:0.8rem; font-weight:700; }
        .dcard-status svg { width:16px; height:16px; }
        .ds-delivered   { background:#2e7d32; color:#fff; }
        .ds-on_delivery { background:#f59e0b; color:#fff; }
        .ds-completed   { background:#2563eb; color:#fff; }
        .ds-pending     { background:#ca8a04; color:#fff; }
        .ds-failed      { background:#dc2626; color:#fff; }

        .dcard-body { padding:14px; flex:1; display:flex; flex-direction:column; gap:7px; }
        .dcard-name { font-size:1rem; font-weight:800; color:var(--text); margin-bottom:2px; }
        .dcard-row { display:flex; align-items:center; gap:7px; font-size:0.79rem; color:var(--muted); }
        .dcard-row svg { width:13px; height:13px; flex-shrink:0; color:#9ca3af; }
        .dcard-row span { color:var(--text); font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:130px; display:block; }
        .dcard-dist { display:flex; align-items:center; gap:5px; font-size:0.78rem; color:#2e7d32; font-weight:600; }
        .dcard-dist svg { width:13px; height:13px; }

        /* Mini MAP — OpenStreetMap static via tile */
        .dcard-map { height:110px; background:#e8f5e9; position:relative; overflow:hidden; border-top:1px solid var(--border); }
        .dcard-map iframe { width:100%; height:100%; border:none; pointer-events:none; }
        .map-static { width:100%; height:100%; object-fit:cover; }
        /* SVG map placeholder yang mirip OpenStreetMap */
        .map-mock { width:100%; height:100%; position:relative; background:#f0ebe3; }
        .map-mock-grid { position:absolute; inset:0; }
        .map-route-line { position:absolute; top:0; left:0; width:100%; height:100%; }

        /* PAGINATION */
        .pagination-wrap { display:flex; justify-content:center; align-items:center; gap:6px; }
        .page-btn { width:34px; height:34px; border-radius:8px; border:1px solid var(--border); background:#fff; font-family:inherit; font-size:0.85rem; font-weight:600; color:var(--text); cursor:pointer; display:flex; align-items:center; justify-content:center; text-decoration:none; transition:all .15s; }
        .page-btn:hover { background:#f4f6f8; }
        .page-btn.active { background:#2e7d32; color:#fff; border-color:#2e7d32; }
        .page-btn.arrow { color:var(--muted); }
        .page-dots { color:var(--muted); font-size:0.85rem; padding:0 2px; }

        .alert { padding:12px 16px; border-radius:8px; font-size:0.87rem; font-weight:500; display:flex; align-items:center; gap:10px; }
        .alert-success { background:#dcfce7; color:#16a34a; border:1px solid #bbf7d0; }
        .alert svg { width:16px; height:16px; }
        .empty-state { text-align:center; padding:60px 20px; color:var(--muted); grid-column:1/-1; }
        .empty-state svg { width:48px; height:48px; margin:0 auto 12px; display:block; opacity:.35; }
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

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo"><div class="logo-box"><strong>RE</strong><span>food</span></div></div>
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard
        </a>
        <a href="{{ route('admin.foods.index') }}" class="nav-item {{ request()->routeIs('admin.foods.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>Foods
        </a>
        <a href="{{ route('admin.deliveries.index') }}" class="nav-item {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Deliveries
        </a>
        <a href="{{ route('admin.donors.index') }}" class="nav-item {{ request()->routeIs('admin.donors.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Donors
        </a>
        <a href="{{ route('admin.receivers.index') }}" class="nav-item {{ request()->routeIs('admin.receivers.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Receivers
        </a>
        <a href="{{ route('admin.volunteers.index') }}" class="nav-item {{ request()->routeIs('admin.volunteers.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>Volunteers
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Reports
        </a>
        <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Settings
        </a>
    </nav>
</aside>

<!-- MAIN -->
<div class="main">
    <header class="topbar">
        <h1 class="topbar-title">Deliveries</h1>
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

        @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <!-- 3 STAT CARDS: Completed, Delivered, On Delivery -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-top">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="stat-top-label">Completed</span>
                </div>
                <div class="stat-number">{{ $stats['completed'] }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-top">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    <span class="stat-top-label">Delivered</span>
                </div>
                <div class="stat-number">{{ $stats['delivered'] }}</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-top">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    <span class="stat-top-label">On Delivery</span>
                </div>
                <div class="stat-number">{{ $stats['on_delivery'] }}</div>
            </div>
        </div>

        <!-- TOOLBAR -->
        <div class="toolbar">
            <form method="GET" action="{{ route('admin.deliveries.index') }}" style="display:contents;" id="searchForm">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" placeholder="Search for food / status from {{ $totalDeliveries }} deliveries" value="{{ request('search') }}">
                </div>
                <div class="toolbar-divider"></div>
                <span class="sort-label">Sort by</span>
                <select name="sort" class="sort-select" onchange="document.getElementById('searchForm').submit()">
                    <option value="date"   {{ request('sort','date')=='date'   ? 'selected':'' }}>Date</option>
                    <option value="status" {{ request('sort')=='status' ? 'selected':'' }}>Status</option>
                    <option value="name"   {{ request('sort')=='name'   ? 'selected':'' }}>Name</option>
                </select>
            </form>
        </div>

        <!-- DELIVERY CARDS — 4 kolom dengan mini map -->
        <div class="cards-grid">
            @forelse($deliveries as $d)
            @php
                $statusClass = match($d->status) {
                    'delivered'   => 'ds-delivered',
                    'on_delivery' => 'ds-on_delivery',
                    'completed'   => 'ds-completed',
                    'pending'     => 'ds-pending',
                    'failed'      => 'ds-failed',
                    default       => 'ds-pending',
                };
                $statusLabel = match($d->status) {
                    'delivered'   => 'Delivered',
                    'on_delivery' => 'On Delivery',
                    'completed'   => 'Completed',
                    'pending'     => 'Pending',
                    'failed'      => 'Failed',
                    default       => ucfirst($d->status),
                };
                // Random distance 1-10 km untuk display (bisa diganti kalau ada kolom distance)
                $dist = $d->distance_km ?? rand(1, 10);
            @endphp
            <div class="dcard" onclick="window.location='{{ route('admin.deliveries.show', $d) }}'">
                <!-- Status header -->
                <div class="dcard-status {{ $statusClass }}">
                    @if(in_array($d->status, ['delivered','completed']))
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    @elseif($d->status === 'on_delivery')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    @elseif($d->status === 'failed')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    @endif
                    {{ $statusLabel }}
                </div>

                <!-- Body info -->
                <div class="dcard-body">
                    <div class="dcard-name">{{ $d->food->name ?? 'Unknown Food' }}</div>
                    <div class="dcard-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>{{ $d->donor->name ?? '-' }}</span>
                    </div>
                    <div class="dcard-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        <span>{{ $d->receiver->name ?? '-' }}</span>
                    </div>
                    <div class="dcard-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span>{{ $d->pickup_time ? \Carbon\Carbon::parse($d->pickup_time)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="dcard-dist">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $dist }} km
                    </div>
                </div>

                <!-- Mini Map — OpenStreetMap embed -->
                <div class="dcard-map">
                    @php
                        // Default ke Jakarta jika tidak ada koordinat
                        $lat = $d->lat ?? (-6.2 + (rand(-50,50)/1000));
                        $lng = $d->lng ?? (106.8 + (rand(-50,50)/1000));
                        $zoom = 14;
                        $mapUrl = "https://www.openstreetmap.org/export/embed.html?bbox=" . ($lng-0.02) . "%2C" . ($lat-0.02) . "%2C" . ($lng+0.02) . "%2C" . ($lat+0.02) . "&layer=mapnik&marker=" . $lat . "%2C" . $lng;
                    @endphp
                    <iframe
                        src="{{ $mapUrl }}"
                        loading="lazy"
                        title="Map delivery #{{ $d->id }}"
                        style="pointer-events:none;">
                    </iframe>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                <p>Belum ada data delivery</p>
            </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if($deliveries->hasPages())
        <div class="pagination-wrap">
            @if($deliveries->onFirstPage())
                <span class="page-btn arrow" style="opacity:.4;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><polyline points="15 18 9 12 15 6"/></svg></span>
            @else
                <a href="{{ $deliveries->previousPageUrl() }}" class="page-btn arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><polyline points="15 18 9 12 15 6"/></svg></a>
            @endif

            @foreach($deliveries->getUrlRange(1, $deliveries->lastPage()) as $page => $url)
                @if($page == $deliveries->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @elseif($page == 1 || $page == $deliveries->lastPage() || abs($page - $deliveries->currentPage()) <= 1)
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @elseif(abs($page - $deliveries->currentPage()) == 2)
                    <span class="page-dots">...</span>
                @endif
            @endforeach

            @if($deliveries->hasMorePages())
                <a href="{{ $deliveries->nextPageUrl() }}" class="page-btn arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><polyline points="9 18 15 12 9 6"/></svg></a>
            @else
                <span class="page-btn arrow" style="opacity:.4;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="15" height="15"><polyline points="9 18 15 12 9 6"/></svg></span>
            @endif
        </div>
        @endif

    </div>
</div>

<script>
function toggleDropdown() { document.getElementById('adminDropdown').classList.toggle('open'); }
document.addEventListener('click', function(e) {
    const btn = document.getElementById('adminBtn'), dd = document.getElementById('adminDropdown');
    if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('open');
});
</script>
</body>
</html>
