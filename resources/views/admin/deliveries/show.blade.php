<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Delivery #RF{{ str_pad($delivery->id, 4, '0', STR_PAD_LEFT) }} - RE-FOOD Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
    <style>
        /* BASE STYLE (Sama persis dengan index.blade.php) */
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

        .main { margin-left:var(--sidebar-width); flex:1; display:flex; flex-direction:column; min-width:0; }
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

        .content { padding:24px 28px; flex:1; display:flex; flex-direction:column; gap:22px; }

        /* CUSTOM STYLE UNTUK HALAMAN DETAIL */
        .detail-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: var(--muted); text-decoration: none; padding: 8px 16px; background: #fff; border: 1px solid var(--border); border-radius: 8px; transition: 0.2s; }
        .btn-back:hover { background: #e4e8ed; color: var(--text); }
        .btn-back svg { width: 16px; height: 16px; }

        .status-badge { display:inline-flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:700; padding:6px 14px; border-radius:20px; }
        .status-badge.delivered  { background:#dcfce7; color:#16a34a; border: 1px solid #bbf7d0;}
        .status-badge.on_delivery { background:#ffedd5; color:#ea580c; border: 1px solid #fed7aa;}
        .status-badge.pending    { background:#fef9c3; color:#ca8a04; border: 1px solid #fef08a;}
        .status-badge.failed     { background:#fee2e2; color:#dc2626; border: 1px solid #fecaca;}

        .detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        .card { background: #fff; border-radius: 12px; border: 1px solid var(--border); padding: 24px; }
        .card-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
        
        .info-group { margin-bottom: 20px; }
        .info-label { font-size: 0.75rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .info-value { font-size: 0.95rem; font-weight: 600; color: var(--text); }
        
        .entity-box { display: flex; align-items: center; gap: 16px; padding: 16px; background: #f8f9fb; border-radius: 10px; border: 1px solid var(--border); margin-bottom: 16px; }
        .entity-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
        .entity-icon.donor { background: #e0f2fe; color: #0284c7; }
        .entity-icon.receiver { background: #fef08a; color: #ca8a04; }
        .entity-icon.volunteer { background: #dcfce7; color: #16a34a; }
        .entity-icon svg { width: 24px; height: 24px; }
        .entity-details h4 { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
        .entity-details p { font-size: 0.85rem; color: var(--muted); }

        .note-box { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 4px 8px 8px 4px; font-size: 0.85rem; color: #92400e; margin-top: 20px; }
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
        <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard
        </a>
        <a href="{{ route('admin.foods.index') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>Foods
        </a>
        <a href="{{ route('admin.deliveries.index') }}" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Deliveries
        </a>
        <a href="{{ route('admin.donors.index') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Donors
        </a>
        <a href="{{ route('admin.receivers.index') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Receivers
        </a>
        <a href="{{ route('admin.volunteers.index') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>Volunteers
        </a>
    </nav>
</aside>

<div class="main">
    <header class="topbar">
        <h1 class="topbar-title">Delivery Details</h1>
        <div style="position:relative;">
            <div class="admin-badge" id="adminBtn" onclick="toggleDropdown()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
            </div>
            <div class="admin-dropdown" id="adminDropdown">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item danger">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="detail-header">
            <a href="{{ route('admin.deliveries.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Deliveries
            </a>

            @php
                $statusClass = match($delivery->status) {
                    'delivered' => 'delivered',
                    'on_delivery' => 'on_delivery',
                    'pending' => 'pending',
                    'failed' => 'failed',
                    default => 'pending'
                };
            @endphp
            <div class="status-badge {{ $statusClass }}">
                Status: {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
            </div>
        </div>

        <div class="detail-grid">
            <div class="card">
                <div class="card-title">Informasi Pengiriman #RF{{ str_pad($delivery->id, 4, '0', STR_PAD_LEFT) }}</div>
                
                <div class="info-group">
                    <div class="info-label">Makanan yang Didonasikan</div>
                    <div class="info-value" style="font-size: 1.2rem; color: var(--green);">{{ $delivery->food->name ?? 'Makanan Tidak Diketahui' }}</div>
                </div>

                <div class="entity-box">
                    <div class="entity-icon donor">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    </div>
                    <div class="entity-details">
                        <div class="info-label">Donatur (Asal)</div>
                        <h4>{{ $delivery->donor->name ?? 'Donatur Tidak Diketahui' }}</h4>
                        <p>ID Donatur: #{{ $delivery->donor_id }}</p>
                    </div>
                </div>

                <div class="entity-box">
                    <div class="entity-icon receiver">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="entity-details">
                        <div class="info-label">Penerima (Tujuan)</div>
                        <h4>{{ $delivery->receiver->name ?? 'Penerima Tidak Diketahui' }}</h4>
                        <p>ID Penerima: #{{ $delivery->receiver_id }}</p>
                    </div>
                </div>

                <div class="entity-box">
                    <div class="entity-icon volunteer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>
                    </div>
                    <div class="entity-details">
                        <div class="info-label">Kurir / Relawan</div>
                        <h4>{{ $delivery->volunteer->name ?? 'Belum ada relawan yang ditugaskan' }}</h4>
                        <p>ID Relawan: {{ $delivery->volunteer_id ? '#'.$delivery->volunteer_id : '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Timeline & Catatan</div>
                
                <div class="info-group">
                    <div class="info-label">Waktu Pickup (Penjemputan)</div>
                    <div class="info-value">
                        {{ $delivery->pickup_time ? \Carbon\Carbon::parse($delivery->pickup_time)->format('d F Y, H:i') : 'Belum diatur' }}
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label">Estimasi Waktu Tiba (ETA)</div>
                    <div class="info-value">
                        {{ $delivery->eta_minutes ? $delivery->eta_minutes . ' Menit' : '-' }}
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-label">Dibuat Pada</div>
                    <div class="info-value">
                        {{ $delivery->created_at ? \Carbon\Carbon::parse($delivery->created_at)->format('d M Y, H:i') : '-' }}
                    </div>
                </div>

                @if($delivery->is_expiring)
                <div class="note-box" style="background:#fee2e2; border-left-color:#dc2626; color:#991b1b;">
                    <strong>Peringatan!</strong> Makanan ini mendekati masa kedaluwarsa, harus segera dikirimkan.
                </div>
                @endif

                @if($delivery->note)
                <div class="note-box">
                    <strong>Catatan Pengiriman:</strong><br>
                    {{ $delivery->note }}
                </div>
                @endif
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
</script>
</body>
</html>