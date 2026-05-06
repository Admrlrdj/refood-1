<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($delivery) ? 'Edit' : 'Tambah' }} Delivery - RE-FOOD Admin</title>
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
        .topbar-left { display:flex; align-items:center; gap:12px; }
        .btn-back { display:flex; align-items:center; gap:6px; padding:8px 14px; border:1px solid var(--border); border-radius:8px; background:#fff; font-family:inherit; font-size:0.84rem; font-weight:600; color:var(--muted); cursor:pointer; text-decoration:none; transition:all .15s; }
        .btn-back:hover { background:#f4f6f8; color:var(--text); }
        .btn-back svg { width:15px; height:15px; }
        .topbar-title { font-size:1.3rem; font-weight:800; }
        .admin-badge { display:flex; align-items:center; gap:8px; background:#f0f2f5; border:1px solid var(--border); border-radius:50px; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer; }
        .admin-badge svg { width:17px; height:17px; color:#2e7d32; }

        .content { padding:28px; flex:1; }

        .form-wrapper { max-width:760px; margin:0 auto; display:flex; flex-direction:column; gap:20px; }

        .form-card { background:#fff; border-radius:14px; border:1px solid var(--border); overflow:hidden; }
        .form-card-header { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:10px; }
        .form-card-header svg { width:18px; height:18px; color:#2e7d32; }
        .form-card-header h3 { font-size:0.95rem; font-weight:700; }
        .form-card-body { padding:22px; display:flex; flex-direction:column; gap:16px; }

        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group.full { grid-column:1/-1; }
        .form-group label { font-size:0.75rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; }
        .form-group label .req { color:#ef4444; }
        .form-group input, .form-group select, .form-group textarea {
            width:100%; background:#f8f9fb; border:1.5px solid var(--border); border-radius:9px;
            padding:11px 14px; font-family:inherit; font-size:0.88rem; color:var(--text);
            outline:none; transition:border-color .15s, background .15s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:#2e7d32; background:#fff; }
        .form-group textarea { resize:vertical; min-height:80px; }
        .form-group .hint { font-size:0.74rem; color:var(--muted); }

        /* Status select dengan warna */
        select[name="status"] option[value="pending"]     { color:#d97706; }
        select[name="status"] option[value="on_delivery"] { color:#ea580c; }
        select[name="status"] option[value="delivered"]   { color:#16a34a; }
        select[name="status"] option[value="failed"]      { color:#dc2626; }

        .status-preview { display:inline-flex; align-items:center; gap:6px; font-size:0.8rem; font-weight:700; padding:5px 12px; border-radius:20px; margin-top:4px; }
        .sp-pending    { background:#fef9c3; color:#ca8a04; }
        .sp-on_delivery{ background:#ffedd5; color:#ea580c; }
        .sp-delivered  { background:#dcfce7; color:#16a34a; }
        .sp-failed     { background:#fee2e2; color:#dc2626; }

        /* Form actions */
        .form-actions { display:flex; align-items:center; justify-content:flex-end; gap:12px; padding:20px 22px; border-top:1px solid var(--border); background:#f8f9fb; }
        .btn-cancel { padding:10px 22px; border-radius:9px; border:1px solid var(--border); background:#fff; font-family:inherit; font-size:0.87rem; font-weight:600; color:var(--text); cursor:pointer; text-decoration:none; transition:background .15s; }
        .btn-cancel:hover { background:#f4f6f8; }
        .btn-submit { display:inline-flex; align-items:center; gap:8px; padding:11px 24px; border-radius:9px; border:none; background:#2e7d32; color:#fff; font-family:inherit; font-size:0.87rem; font-weight:700; cursor:pointer; transition:background .15s; }
        .btn-submit:hover { background:#1b5e20; }
        .btn-submit svg { width:16px; height:16px; }

        .alert-error { padding:10px 16px; background:#fee2e2; border:1px solid #fecaca; border-radius:8px; font-size:0.84rem; color:#dc2626; display:flex; align-items:center; gap:8px; }
        .alert-error svg { width:15px; height:15px; flex-shrink:0; }
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
        <a href="{{ route('admin.dashboard') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
        <a href="{{ route('admin.foods.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>Foods</a>
        <a href="{{ route('admin.deliveries.index') }}" class="nav-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="m16 8 5 3v5h-5V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Deliveries</a>
        <a href="{{ route('admin.donors.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Donors</a>
        <a href="{{ route('admin.receivers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Receivers</a>
        <a href="{{ route('admin.volunteers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/></svg>Volunteers</a>
        <a href="{{ route('admin.reports.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Reports</a>
        <a href="{{ route('admin.settings') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Settings</a>
    </nav>
</aside>

<!-- MAIN -->
<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <a href="{{ route('admin.deliveries.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali
            </a>
            <h1 class="topbar-title">{{ isset($delivery) ? 'Edit Delivery' : 'Tambah Delivery Baru' }}</h1>
        </div>
        <div class="admin-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
        </div>
    </header>

    <div class="content">
        <div class="form-wrapper">

            @if($errors->any())
            <div class="alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            <form method="POST" action="{{ isset($delivery) ? route('admin.deliveries.update', $delivery) : route('admin.deliveries.store') }}">
                @csrf
                @if(isset($delivery)) @method('PUT') @endif

                <!-- FOOD & RELASI -->
                <div class="form-card">
                    <div class="form-card-header">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                        <h3>Informasi Makanan & Relasi</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group full">
                            <label>Pilih Makanan <span class="req">*</span></label>
                            <select name="food_id" required>
                                <option value="">-- Pilih makanan --</option>
                                @foreach($foods as $food)
                                <option value="{{ $food->id }}" {{ old('food_id', $delivery->food_id ?? '') == $food->id ? 'selected' : '' }}>
                                    {{ $food->name }} ({{ ucfirst($food->status) }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Donor</label>
                                <select name="donor_id">
                                    <option value="">-- Pilih donor (opsional) --</option>
                                    @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}" {{ old('donor_id', $delivery->donor_id ?? '') == $donor->id ? 'selected' : '' }}>
                                        {{ $donor->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Receiver</label>
                                <select name="receiver_id">
                                    <option value="">-- Pilih receiver (opsional) --</option>
                                    @foreach($receivers as $receiver)
                                    <option value="{{ $receiver->id }}" {{ old('receiver_id', $delivery->receiver_id ?? '') == $receiver->id ? 'selected' : '' }}>
                                        {{ $receiver->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group full">
                            <label>Volunteer / Kurir</label>
                            <select name="volunteer_id">
                                <option value="">-- Pilih volunteer (opsional) --</option>
                                @foreach($volunteers as $vol)
                                <option value="{{ $vol->id }}" {{ old('volunteer_id', $delivery->volunteer_id ?? '') == $vol->id ? 'selected' : '' }}>
                                    {{ $vol->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- STATUS & WAKTU -->
                <div class="form-card">
                    <div class="form-card-header">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <h3>Status & Waktu Pengiriman</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Status <span class="req">*</span></label>
                                <select name="status" id="statusSelect" required onchange="updateStatusPreview()">
                                    <option value="pending"     {{ old('status', $delivery->status ?? 'pending') == 'pending'     ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="on_delivery" {{ old('status', $delivery->status ?? '') == 'on_delivery' ? 'selected' : '' }}>🚚 On Delivery</option>
                                    <option value="delivered"   {{ old('status', $delivery->status ?? '') == 'delivered'   ? 'selected' : '' }}>✅ Delivered</option>
                                    <option value="failed"      {{ old('status', $delivery->status ?? '') == 'failed'      ? 'selected' : '' }}>❌ Failed</option>
                                </select>
                                <div id="statusPreview" class="status-preview sp-pending">⏳ Pending</div>
                            </div>
                            <div class="form-group">
                                <label>ETA (menit)</label>
                                <input type="number" name="eta_minutes" min="1" max="999" placeholder="Contoh: 30" value="{{ old('eta_minutes', $delivery->eta_minutes ?? '') }}">
                                <span class="hint">Estimasi waktu pengiriman</span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Waktu Pickup</label>
                                <input type="datetime-local" name="pickup_time" value="{{ old('pickup_time', isset($delivery->pickup_time) ? $delivery->pickup_time->format('Y-m-d\TH:i') : '') }}">
                            </div>
                            <div class="form-group">
                                <label>Hampir Expired?</label>
                                <select name="is_expiring">
                                    <option value="0" {{ old('is_expiring', $delivery->is_expiring ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
                                    <option value="1" {{ old('is_expiring', $delivery->is_expiring ?? 0) == 1 ? 'selected' : '' }}>Ya — Hampir Expired</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LOKASI / KOORDINAT MAP -->
                <div class="form-card">
                    <div class="form-card-header">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <h3>Lokasi Pengiriman (untuk Map)</h3>
                    </div>
                    <div class="form-card-body">
                        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:12px 14px;font-size:0.82rem;color:#92400e;line-height:1.6;margin-bottom:4px;">
                            <strong>📍 Cara isi koordinat:</strong><br>
                            1. Buka <a href="https://maps.google.com" target="_blank" style="color:#2563eb;font-weight:600;">Google Maps</a> di tab baru<br>
                            2. Klik lokasi tujuan pengiriman (rumah receiver)<br>
                            3. Koordinat muncul di bawah layar, contoh: <strong>-6.2088, 106.8456</strong><br>
                            4. Angka pertama = Latitude, angka kedua = Longitude
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Latitude</label>
                                <input type="text" name="lat" id="lat_input"
                                    placeholder="Contoh: -6.2088"
                                    value="{{ old('lat', $delivery->lat ?? '') }}"
                                    oninput="updateMapPreview()">
                                <span class="hint">Garis lintang (angka negatif = selatan)</span>
                            </div>
                            <div class="form-group">
                                <label>Longitude</label>
                                <input type="text" name="lng" id="lng_input"
                                    placeholder="Contoh: 106.8456"
                                    value="{{ old('lng', $delivery->lng ?? '') }}"
                                    oninput="updateMapPreview()">
                                <span class="hint">Garis bujur (angka positif = timur)</span>
                            </div>
                        </div>
                        <!-- Preview map langsung -->
                        <div class="form-group" id="mapPreviewWrap" style="display:{{ (old('lat',$delivery->lat??'') ? 'block' : 'none') }};">
                            <label>Preview Lokasi</label>
                            <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border);height:180px;">
                                <iframe id="mapPreviewFrame" src="" style="width:100%;height:200px;border:none;display:block;margin-top:-10px;" loading="lazy"></iframe>
                            </div>
                        </div>
                        <button type="button" onclick="openGoogleMaps()" style="display:inline-flex;align-items:center;gap:7px;background:#2563eb;color:#fff;font-family:inherit;font-size:0.84rem;font-weight:600;padding:9px 16px;border-radius:8px;border:none;cursor:pointer;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Buka Google Maps
                        </button>
                    </div>
                </div>

                <!-- CATATAN -->
                <div class="form-card">
                    <div class="form-card-header">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <h3>Catatan</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group">
                            <label>Catatan Tambahan</label>
                            <textarea name="note" placeholder="Catatan pengiriman, kondisi makanan, instruksi khusus...">{{ old('note', $delivery->note ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <a href="{{ route('admin.deliveries.index') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            {{ isset($delivery) ? 'Update Delivery' : 'Simpan Delivery' }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
const statusConfig = {
    'pending':     { class:'sp-pending',     label:'⏳ Pending' },
    'on_delivery': { class:'sp-on_delivery', label:'🚚 On Delivery' },
    'delivered':   { class:'sp-delivered',   label:'✅ Delivered' },
    'failed':      { class:'sp-failed',      label:'❌ Failed' },
};
function updateStatusPreview() {
    const val = document.getElementById('statusSelect').value;
    const cfg = statusConfig[val] || statusConfig['pending'];
    const el = document.getElementById('statusPreview');
    el.className = 'status-preview ' + cfg.class;
    el.textContent = cfg.label;
}
updateStatusPreview();

// Live map preview saat admin isi lat/lng
function updateMapPreview() {
    const lat = parseFloat(document.getElementById('lat_input').value);
    const lng = parseFloat(document.getElementById('lng_input').value);
    const wrap = document.getElementById('mapPreviewWrap');
    const frame = document.getElementById('mapPreviewFrame');

    if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
        const url = "https://www.openstreetmap.org/export/embed.html"
            + "?bbox=" + (lng-0.015) + "%2C" + (lat-0.015)
            + "%2C"    + (lng+0.015) + "%2C" + (lat+0.015)
            + "&layer=mapnik&marker=" + lat + "%2C" + lng;
        frame.src = url;
        wrap.style.display = 'block';
    } else {
        wrap.style.display = 'none';
    }
}

// Buka Google Maps di tab baru
function openGoogleMaps() {
    window.open('https://maps.google.com', '_blank');
}

// Init preview jika edit (udah ada nilai)
window.addEventListener('load', function() {
    const lat = document.getElementById('lat_input').value;
    const lng = document.getElementById('lng_input').value;
    if (lat && lng) updateMapPreview();
});
</script>
</body>
</html>