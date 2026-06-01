<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Detail - RE-FOOD Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
    <style>
        :root {
            --green: #2e7d32;
            --green-dark: #1b5e20;
            --sidebar-width: 200px;
            --topbar-height: 64px;
            --bg: #f4f6f8;
            --border: #e4e8ed;
            --text: #1a2332;
            --muted: #6b7a8d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: #2e7d32;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 16px 18px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            min-height: var(--topbar-height);
        }

        .logo-box {
            background: #fff;
            color: #2e7d32;
            font-weight: 800;
            font-size: 0.9rem;
            line-height: 1.15;
            padding: 7px 10px;
            border-radius: 8px;
            text-align: center;
        }

        .logo-box span {
            display: block;
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 1px;
            color: #2e7d32;
        }

        .sidebar-nav {
            flex: 1;
            padding: 10px 0;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 18px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.87rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: background .15s;
        }

        .nav-item:hover {
            background: rgba(0, 0, 0, 0.15);
            color: #fff;
        }

        .nav-item.active {
            background: #1b5e20;
            color: #fff;
            border-left-color: #a5d6a7;
        }

        .nav-item svg {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
        }

        .main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
        }

        .btn-back:hover {
            opacity: .7;
        }

        .btn-back svg {
            width: 18px;
            height: 18px;
        }

        .admin-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0f2f5;
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .admin-badge svg {
            width: 17px;
            height: 17px;
            color: #2e7d32;
        }

        .content {
            padding: 24px 28px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .detail-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid var(--border);
            overflow: hidden;
            max-width: 860px;
        }

        .detail-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
        }

        .detail-header h2 {
            font-size: 1.3rem;
            font-weight: 800;
        }

        /* Foto + info grid */
        .top-section {
            display: grid;
            grid-template-columns: 160px 1fr;
            border-bottom: 1px solid var(--border);
        }

        .photo-col {
            padding: 20px;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .photo-box {
            width: 120px;
            height: 100px;
            border-radius: 10px;
            border: 2px dashed var(--border);
            background: #f8f9fb;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .photo-box svg {
            width: 36px;
            height: 36px;
            color: #d1d5db;
        }

        .info-col {
            padding: 20px 24px;
        }

        .vehicle-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .vehicle-badge svg {
            width: 14px;
            height: 14px;
        }

        .vehicle-plat {
            font-size: 0.78rem;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        .info-cell {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            border-right: 1px solid var(--border);
            font-size: 0.84rem;
        }

        .info-cell:nth-child(even) {
            border-right: none;
        }

        .info-cell:nth-last-child(-n+2) {
            border-bottom: none;
        }

        .info-cell-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-bottom: 3px;
        }

        .info-cell-value {
            font-weight: 600;
            color: var(--text);
        }

        .badge-active {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #dcfce7;
            color: #16a34a;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Deliveries table */
        .deliveries-section {
            padding: 20px 22px;
        }

        .deliveries-section h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 9px 14px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .4px;
            border-bottom: 1px solid var(--border);
            background: #f8f9fb;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fafbfc;
        }

        tbody td {
            padding: 10px 14px;
            font-size: 0.84rem;
        }

        .td-food {
            font-weight: 700;
        }

        .td-muted {
            color: var(--muted);
            font-size: 0.82rem;
        }

        .td-truncate {
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sp {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .sp-delivered {
            background: #dcfce7;
            color: #16a34a;
        }

        .sp-on_delivery {
            background: #ffedd5;
            color: #ea580c;
        }

        .sp-pending {
            background: #fef9c3;
            color: #ca8a04;
        }

        .sp-failed {
            background: #fee2e2;
            color: #dc2626;
        }

        .sp-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .detail-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            background: #f8f9fb;
        }

        .btn-back-f {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text);
            font-family: inherit;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 9px 16px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-del {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-family: inherit;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 9px 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-back-f:hover {
            background: #f4f6f8;
        }

        .btn-del:hover {
            background: #fee2e2;
        }

        .btn-back-f svg,
        .btn-del svg {
            width: 14px;
            height: 14px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.87rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .alert svg {
            width: 16px;
            height: 16px;
        }
    </style>
    <script>
        // Apply appearance & font dari localStorage sebelum render (cegah flash)
        (function() {
            var app = localStorage.getItem('refood_appearance') || 'light';
            var font = localStorage.getItem('refood_font') || 'medium';
            var html = document.documentElement;
            if (app === 'dark') html.classList.add('dark');
            html.classList.add('font-' + font);
        })();
    </script>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-box"><strong>RE</strong><span>food</span></div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                    <rect x="14" y="14" width="7" height="7" rx="1" />
                </svg>Dashboard</a>
            <a href="{{ route('admin.foods.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                    <line x1="6" y1="1" x2="6" y2="4" />
                    <line x1="10" y1="1" x2="10" y2="4" />
                    <line x1="14" y1="1" x2="14" y2="4" />
                </svg>Foods</a>
            <a href="{{ route('admin.deliveries.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
                    <rect x="1" y="3" width="15" height="13" rx="1" />
                    <path d="m16 8 5 3v5h-5V8z" />
                    <circle cx="5.5" cy="18.5" r="2.5" />
                    <circle cx="18.5" cy="18.5" r="2.5" />
                </svg>Deliveries</a>
            <a href="{{ route('admin.donors.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>Donors</a>
            <a href="{{ route('admin.receivers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>Receivers</a>
            <a href="{{ route('admin.volunteers.index') }}" class="nav-item active"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10" />
                    <path
                        d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32" />
                </svg>Volunteers</a>
            <a href="{{ route('admin.reports.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                </svg>Reports</a>
            <a href="{{ route('admin.settings') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="3" />
                    <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                </svg>Settings</a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <a href="{{ route('admin.volunteers.index') }}" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Volunteers Detail
                </a>
            </div>
            <div class="admin-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
            </div>
        </header>

        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h2 style="font-size: 1.25rem; font-weight: 800;">Detail Relawan (Kurir)</h2>
                <a href="{{ route('admin.volunteers.index') }}" class="btn-cancel-modal"
                    style="text-decoration: none;">&larr; Kembali</a>
            </div>

            <!-- PROFILE CARD -->
            <div class="table-card"
                style="padding: 24px; display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
                <div style="text-align: center; padding-right: 24px; border-right: 1px solid var(--border);">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($volunteer->name) }}&background=10B981&color=fff&size=100"
                        style="border-radius: 50%; margin-bottom: 16px;">
                    <h3 style="font-size: 1.2rem; font-weight: 800;">{{ $volunteer->name }}</h3>
                    <p style="color: var(--muted); font-size: 0.85rem; margin-bottom: 16px;">
                        {{ '@' . $volunteer->username }}</p>

                    <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 20px;">
                        @if ($volunteer->is_verified)
                            <span class="badge-active"><span class="badge-dot"></span>Terverifikasi</span>
                        @else
                            <span class="badge-inactive" style="color: #dc2626; background: #fee2e2;"><span
                                    class="badge-dot"></span>Belum Verifikasi</span>
                        @endif

                        @if ($volunteer->status === 'aktif' || $volunteer->status === 'online')
                            <span class="badge-active" style="background: #e0f2fe; color: #2563eb;"><span
                                    class="badge-dot"></span>ON</span>
                        @else
                            <span class="badge-inactive"><span class="badge-dot"></span>OFF</span>
                        @endif
                    </div>

                    <!-- Admin Actions -->
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @if (!$volunteer->is_verified)
                            <form action="{{ route('admin.volunteers.verify', $volunteer->_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-save-modal" style="width: 100%;">Setujui Relawan
                                    (Verify)</button>
                            </form>
                        @else
                            <form action="{{ route('admin.volunteers.reject', $volunteer->_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-cancel-modal"
                                    style="width: 100%; color: #dc2626; border-color: #fca5a5;">Cabut Verifikasi
                                    (Reject)</button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Right: Detailed Data -->
                <div class="form-grid">
                    <div class="form-group"><label>No. HP (WhatsApp)</label>
                        <p style="font-weight: 600;">{{ $volunteer->phone ?? '-' }}</p>
                    </div>
                    <div class="form-group"><label>Total Pengantaran</label>
                        <p style="font-weight: 600;">
                            {{ $volunteer->deliveries ? $volunteer->deliveries->count() : 0 }} Tugas</p>
                    </div>
                    <div class="form-group"><label>Tipe Kendaraan</label>
                        <p style="font-weight: 600; text-transform: capitalize;">{{ $volunteer->vehicle_type ?? '-' }}
                        </p>
                    </div>
                    <div class="form-group"><label>Plat Nomor</label>
                        <p style="color: #2563eb; font-weight: 800;">{{ $volunteer->vehicle_plate ?? '-' }}</p>
                    </div>
                    <div class="form-group"><label>Latitude (Lokasi Terakhir)</label>
                        <p style="font-weight: 600;">{{ $volunteer->last_latitude ?? '-' }}</p>
                    </div>
                    <div class="form-group"><label>Longitude (Lokasi Terakhir)</label>
                        <p style="font-weight: 600;">{{ $volunteer->last_longitude ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- HISTORY TABLE -->
            <div class="table-card">
                <div class="table-card-header">
                    <h3>Riwayat Tugas Pengantaran</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Tugas ID</th>
                            <th>Makanan</th>
                            <th>Diantar Ke</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($volunteer->deliveries ?? [] as $del)
                            <tr>
                                <td class="td-muted">#{{ substr($del->_id, -6) }}</td>
                                <td class="td-name">{{ $del->food->name ?? 'N/A' }}</td>
                                <td>{{ $del->receiver->foundation_name ?? ($del->receiver->name ?? 'N/A') }}</td>
                                <td>
                                    @if ($del->status === 'delivered')
                                        <span class="badge-active"><span class="badge-dot"></span>Selesai</span>
                                    @else
                                        <span class="badge-inactive"><span
                                                class="badge-dot"></span>{{ ucfirst($del->status) }}</span>
                                    @endif
                                </td>
                                <td class="td-muted">{{ $del->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;padding:20px;color:var(--muted);">Belum
                                    ada riwayat tugas pengantaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
