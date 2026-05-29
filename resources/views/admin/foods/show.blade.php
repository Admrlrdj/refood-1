<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foods Monitoring - RE-FOOD Admin</title>
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
        .btn-back { display:flex; align-items:center; gap:5px; font-size:0.87rem; font-weight:600; color:var(--muted); text-decoration:none; transition:color .15s; }
        .btn-back:hover { color:var(--text); }
        .btn-back svg { width:16px; height:16px; }
        .topbar-title { font-size:1.2rem; font-weight:800; }
        .admin-badge { display:flex; align-items:center; gap:8px; background:#f0f2f5; border:1px solid var(--border); border-radius:50px; padding:8px 16px; font-size:0.85rem; font-weight:600; }
        .admin-badge svg { width:17px; height:17px; color:#2e7d32; }
        .content { padding:24px 28px; flex:1; }

        /* MAIN CARD */
        .detail-card { background:#fff; border-radius:14px; border:1px solid var(--border); overflow:hidden; max-width:820px; }
        .detail-card-header { padding:18px 24px; border-bottom:1px solid var(--border); }
        .detail-card-header h2 { font-size:1.25rem; font-weight:800; }

        /* TOP SECTION: Photo + Addresses */
        .top-section { display:grid; grid-template-columns:180px 1fr; gap:0; border-bottom:1px solid var(--border); }
        .photo-col { padding:20px; border-right:1px solid var(--border); display:flex; flex-direction:column; gap:8px; }
        .photo-label { font-size:0.72rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; }
        .photo-box { width:140px; height:110px; border-radius:10px; border:2px dashed var(--border); background:#f8f9fb; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .photo-box img { width:100%; height:100%; object-fit:cover; border-radius:8px; }
        .photo-box svg { width:36px; height:36px; color:#d1d5db; }
        .address-col { padding:20px; display:flex; flex-direction:column; gap:14px; }
        .address-group { display:flex; flex-direction:column; gap:5px; }
        .address-group label { font-size:0.72rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; }
        .address-value { font-size:0.88rem; color:var(--text); font-weight:500; padding:8px 12px; background:#f8f9fb; border:1px solid var(--border); border-radius:8px; min-height:38px; }

        /* DETAILS ROW */
        .details-section { padding:20px 24px; border-bottom:1px solid var(--border); }
        .details-section h4 { font-size:0.75rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:12px; }
        .details-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
        .detail-item { display:flex; flex-direction:column; gap:4px; }
        .detail-item-label { font-size:0.7rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; }
        .detail-item-value { font-size:0.9rem; font-weight:700; color:var(--text); }

        .sp { display:inline-flex; align-items:center; gap:5px; font-size:0.8rem; font-weight:700; padding:4px 12px; border-radius:20px; }
        .sp-delivered  { background:#dcfce7; color:#16a34a; }
        .sp-on_delivery{ background:#ffedd5; color:#ea580c; }
        .sp-available  { background:#dbeafe; color:#2563eb; }
        .sp-taken      { background:#f3e8ff; color:#7c3aed; }
        .sp-invalid    { background:#fee2e2; color:#dc2626; }
        .sp-dot { width:6px; height:6px; border-radius:50%; background:currentColor; }

        /* NOTE SECTION */
        .note-section { padding:20px 24px; border-bottom:1px solid var(--border); }
        .note-section h4 { font-size:0.75rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:10px; }
        .note-box { background:#f8f9fb; border:1px solid var(--border); border-radius:8px; padding:12px 14px; font-size:0.87rem; color:var(--text); min-height:60px; line-height:1.6; }
        .note-box.empty { color:var(--muted); font-style:italic; }

        /* ACTIONS */
        .actions-section { padding:16px 24px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
        .actions-left { display:flex; gap:10px; }
        .btn-edit { display:inline-flex; align-items:center; gap:7px; background:#f4f6f8; border:1px solid var(--border); color:var(--text); font-family:inherit; font-size:0.85rem; font-weight:600; padding:10px 18px; border-radius:8px; cursor:pointer; text-decoration:none; transition:background .15s; }
        .btn-edit:hover { background:#e8eaed; }
        .btn-edit svg { width:15px; height:15px; }
        .btn-invalid { display:inline-flex; align-items:center; gap:7px; background:#dc2626; color:#fff; font-family:inherit; font-size:0.85rem; font-weight:700; padding:10px 18px; border-radius:8px; border:none; cursor:pointer; transition:background .15s; }
        .btn-invalid:hover { background:#b91c1c; }
        .btn-invalid svg { width:15px; height:15px; }
        .btn-delete { display:inline-flex; align-items:center; gap:7px; background:#fff; border:1px solid #fecaca; color:#dc2626; font-family:inherit; font-size:0.85rem; font-weight:600; padding:10px 18px; border-radius:8px; cursor:pointer; transition:all .15s; }
        .btn-delete:hover { background:#fee2e2; }
        .btn-delete svg { width:15px; height:15px; }

        /* MODAL CONFIRM */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:500; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal { background:#fff; border-radius:14px; padding:28px; max-width:400px; width:90%; text-align:center; }
        .modal-icon { width:56px; height:56px; border-radius:50%; background:#fee2e2; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; }
        .modal-icon svg { width:26px; height:26px; color:#dc2626; }
        .modal h3 { font-size:1.05rem; font-weight:800; margin-bottom:8px; }
        .modal p { font-size:0.87rem; color:var(--muted); margin-bottom:22px; line-height:1.6; }
        .modal-btns { display:flex; gap:10px; justify-content:center; }
        .btn-confirm-cancel { padding:10px 22px; border-radius:8px; border:1px solid var(--border); background:#fff; font-family:inherit; font-size:0.87rem; font-weight:600; cursor:pointer; }
        .btn-confirm-ok { padding:10px 22px; border-radius:8px; border:none; background:#dc2626; color:#fff; font-family:inherit; font-size:0.87rem; font-weight:700; cursor:pointer; }

        .alert { padding:12px 16px; border-radius:8px; font-size:0.87rem; font-weight:500; display:flex; align-items:center; gap:10px; margin-bottom:16px; }
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
        <a href="{{ route('admin.foods.index') }}" class="nav-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>Foods</a>
        <a href="{{ route('admin.deliveries.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Deliveries</a>
        <a href="{{ route('admin.donors.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Donors</a>
        <a href="{{ route('admin.receivers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Receivers</a>
        <a href="{{ route('admin.volunteers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>Volunteers</a>
        <a href="{{ route('admin.reports.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Reports</a>
        <a href="{{ route('admin.settings') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Settings</a>
    </nav>
</aside>
<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <a href="{{ route('admin.foods.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Foods Monitoring
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
            <!-- HEADER: Nama makanan -->
            <div class="detail-card-header">
                <h2>{{ $food->name }}</h2>
            </div>

            <!-- TOP: Photo + Addresses -->
            <div class="top-section">
                <div class="photo-col">
                    <div class="photo-label">Food Photo</div>
                    <div class="photo-box">
                        @if($food->photo)
                            <img src="{{ asset('storage/'.$food->photo) }}" alt="{{ $food->name }}">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        @endif
                    </div>
                </div>
                <div class="address-col">
                    <div class="address-group">
                        <label>Donor Address:</label>
                        <div class="address-value {{ !$food->donor?->address ? 'empty' : '' }}">
                            {{ $food->donor?->address ?? '' }}
                        </div>
                    </div>
                    <div class="address-group">
                        <label>Receiver Address:</label>
                        <div class="address-value {{ !$food->receiver?->address ? 'empty' : '' }}">
                            {{ $food->receiver?->address ?? '' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAILS ROW: Portion, Collection Date, Status -->
            <div class="details-section">
                <h4>Details</h4>
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-item-label">Portion</div>
                        <div class="detail-item-value">{{ $food->portion ?? '-' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-label">Collection Date</div>
                        <div class="detail-item-value">
                            {{ $food->collection_date ? $food->collection_date->format('d M Y, H:i') : '-' }}
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-item-label">Status</div>
                        <div>
                            @php
                                $sc = match($food->status) {
                                    'available' => 'sp-available',
                                    'taken'     => 'sp-taken',
                                    'delivered' => 'sp-delivered',
                                    'invalid'   => 'sp-invalid',
                                    default     => 'sp-available'
                                };
                                $sl = match($food->status) {
                                    'available' => 'Available',
                                    'taken'     => 'Taken',
                                    'delivered' => 'Delivered',
                                    'invalid'   => 'Invalid',
                                    default     => ucfirst($food->status)
                                };
                            @endphp
                            <span class="sp {{ $sc }}"><span class="sp-dot"></span>{{ $sl }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NOTE -->
            <div class="note-section">
                <h4>Note</h4>
                <div class="note-box {{ !$food->note ? 'empty' : '' }}">
                    {{ $food->note ?? 'Tidak ada catatan.' }}
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="actions-section">
                @if($food->status !== 'invalid')
                <button class="btn-invalid" onclick="document.getElementById('confirmModal').classList.add('open')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Mark as Invalid ›
                </button>
                @else
                <span style="font-size:0.82rem;color:#dc2626;font-weight:600;">⚠ Makanan ini sudah ditandai Invalid</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- CONFIRM MODAL -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal">
        <div class="modal-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <h3>Tandai sebagai Invalid?</h3>
        <p>Makanan <strong>{{ $food->name }}</strong> akan ditandai sebagai invalid dan tidak bisa digunakan lagi. Tindakan ini tidak bisa dibatalkan.</p>
        <div class="modal-btns">
            <button class="btn-confirm-cancel" onclick="document.getElementById('confirmModal').classList.remove('open')">Batal</button>
            <form method="POST" action="{{ route('admin.foods.mark-invalid', $food) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-confirm-ok">Ya, Tandai Invalid</button>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if(e.target === this) this.classList.remove('open');
});
</script>
</body>
</html>
