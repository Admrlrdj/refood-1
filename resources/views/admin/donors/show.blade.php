<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Detail - RE-FOOD Admin</title>
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
            transition: opacity .15s;
        }

        .btn-back:hover {
            opacity: .7;
        }

        .btn-back svg {
            width: 18px;
            height: 18px;
        }

        .topbar-title {
            font-size: 1.2rem;
            font-weight: 800;
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

        /* DETAIL CARD */
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

        /* TOP SECTION: foto + info table */
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

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-box svg {
            width: 36px;
            height: 36px;
            color: #d1d5db;
        }

        /* Info table sesuai gambar — grid 2 kolom */
        .info-col {
            padding: 20px 24px;
        }

        .info-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .type-corporate {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .type-individual {
            background: #fef3c7;
            color: #d97706;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        .info-row {
            display: contents;
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

        /* FOODS DONATED TABLE */
        .foods-section {
            padding: 20px 22px;
        }

        .foods-section h3 {
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

        /* ACTION FOOTER */
        .detail-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            background: #f8f9fb;
        }

        .btn-edit {
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
            transition: background .15s;
        }

        .btn-edit:hover {
            background: #f4f6f8;
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
            transition: background .15s;
        }

        .btn-del:hover {
            background: #fee2e2;
        }

        .btn-edit svg,
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

        .empty-row td {
            text-align: center;
            padding: 24px;
            color: var(--muted);
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
            <a href="{{ route('admin.donors.index') }}" class="nav-item active"><svg viewBox="0 0 24 24" fill="none"
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
            <a href="{{ route('admin.volunteers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
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
                <a href="{{ route('admin.donors.index') }}" class="btn-back">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Donor Detail
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
            <style>
                .profile-header {
                    display: grid;
                    grid-template-columns: 280px 1fr;
                    gap: 30px;
                    background: #fff;
                    border: 1px solid var(--border);
                    border-radius: 12px;
                    padding: 30px;
                    margin-bottom: 24px;
                }

                .profile-sidebar {
                    text-align: center;
                    border-right: 1px dashed var(--border);
                    padding-right: 30px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                .profile-avatar {
                    width: 110px;
                    height: 110px;
                    border-radius: 50%;
                    border: 4px solid #f0fdf4;
                    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
                    margin-bottom: 16px;
                    object-fit: cover;
                }

                .profile-name {
                    font-size: 1.3rem;
                    font-weight: 800;
                    color: var(--text);
                    margin-bottom: 4px;
                    line-height: 1.2;
                }

                .profile-sub {
                    font-size: 0.85rem;
                    color: var(--muted);
                    font-weight: 600;
                    margin-bottom: 20px;
                }

                .profile-actions {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                    margin-top: 24px;
                    width: 100%;
                }

                .btn-verify {
                    background: #16a34a;
                    color: #fff;
                    border: none;
                    padding: 12px;
                    border-radius: 8px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: 0.2s;
                    font-size: 0.85rem;
                    width: 100%;
                }

                .btn-verify:hover {
                    background: #15803d;
                }

                .btn-reject {
                    background: #fff;
                    color: #dc2626;
                    border: 1px solid #fca5a5;
                    padding: 12px;
                    border-radius: 8px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: 0.2s;
                    font-size: 0.85rem;
                    width: 100%;
                }

                .btn-reject:hover {
                    background: #fef2f2;
                }

                .btn-back {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    color: var(--muted);
                    text-decoration: none;
                    font-weight: 700;
                    font-size: 0.9rem;
                    transition: 0.2s;
                    margin-bottom: 20px;
                }

                .btn-back:hover {
                    color: var(--text);
                }

                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 20px;
                    align-content: start;
                }

                .info-item {
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                }

                .info-item.full {
                    grid-column: 1 / -1;
                }

                .info-label {
                    font-size: 0.75rem;
                    font-weight: 700;
                    color: var(--muted);
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                .info-value {
                    font-size: 0.95rem;
                    font-weight: 600;
                    color: var(--text);
                    background: #f8f9fb;
                    padding: 14px 16px;
                    border-radius: 8px;
                    border: 1px solid #edf1f5;
                }
            </style>

            <a href="{{ route('admin.donors.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18"
                    height="18">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Daftar Donatur
            </a>

            <div class="profile-header">
                <div class="profile-sidebar">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($donor->restaurant_name ?? $donor->name) }}&background=10B981&color=fff&size=120"
                        class="profile-avatar">
                    <h3 class="profile-name">{{ $donor->restaurant_name ?? $donor->name }}</h3>
                    <p class="profile-sub">PIC: {{ $donor->name }}</p>

                    <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: center;">
                        @if ($donor->is_verified)
                            <span class="badge-active"><span class="badge-dot"></span>Terverifikasi</span>
                        @else
                            <span class="badge-inactive" style="color: #dc2626; background: #fee2e2;"><span
                                    class="badge-dot"></span>Pending</span>
                        @endif

                        @if ($donor->status === 'aktif' || $donor->status === 'online')
                            <span class="badge-active" style="background: #e0f2fe; color: #2563eb;"><span
                                    class="badge-dot"></span>ON</span>
                        @else
                            <span class="badge-inactive"><span class="badge-dot"></span>OFF</span>
                        @endif
                    </div>

                    <div class="profile-actions">
                        @if (!$donor->is_verified)
                            <form action="{{ route('admin.donors.verify', $donor->_id) }}" method="POST"
                                style="width: 100%;">
                                @csrf
                                <button type="submit" class="btn-verify">Setujui Akun</button>
                            </form>
                        @else
                            <form action="{{ route('admin.donors.reject', $donor->_id) }}" method="POST"
                                style="width: 100%;">
                                @csrf
                                <button type="submit" class="btn-reject">Cabut Verifikasi</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">{{ $donor->email ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No. Telepon</div>
                        <div class="info-value">{{ $donor->phone ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tipe Donatur</div>
                        <div class="info-value" style="text-transform: capitalize;">{{ $donor->type ?? 'General' }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total Donasi</div>
                        <div class="info-value" style="color: #2e7d32;">
                            {{ $donor->foods ? $donor->foods->count() : 0 }} Makanan</div>
                    </div>
                    <div class="info-item full">
                        <div class="info-label">Alamat Lengkap</div>
                        <div class="info-value">{{ $donor->address ?? 'Belum ada alamat' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Latitude</div>
                        <div class="info-value" style="font-family: monospace;">{{ $donor->last_latitude ?? '-' }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Longitude</div>
                        <div class="info-value" style="font-family: monospace;">{{ $donor->last_longitude ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-card-header">
                    <h3>Riwayat Donasi Makanan</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Makanan</th>
                            <th>Porsi</th>
                            <th>Status</th>
                            <th>Tgl Dibuat</th>
                            <th>Batas Expired</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donor->foods ?? [] as $food)
                            <tr>
                                <td class="td-name">{{ $food->name }}</td>
                                <td style="font-weight: 700; color: #2e7d32;">{{ $food->portion }}</td>
                                <td>
                                    @if ($food->status === 'available')
                                        <span class="badge-active"><span class="badge-dot"></span>Tersedia</span>
                                    @elseif($food->status === 'booked')
                                        <span class="badge-active" style="background: #fef3c7; color: #ea580c;"><span
                                                class="badge-dot"></span>Diambil Kurir</span>
                                    @else
                                        <span class="badge-inactive"><span class="badge-dot"></span>Selesai</span>
                                    @endif
                                </td>
                                <td class="td-muted">{{ $food->created_at->format('d M Y H:i') }}</td>
                                <td style="color: #dc2626; font-weight: 700;">
                                    {{ \Carbon\Carbon::parse($food->expired_at)->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;padding:30px;color:var(--muted);">Belum
                                    ada riwayat donasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
