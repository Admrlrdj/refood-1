<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteers - RE-FOOD Admin</title>
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

        .topbar-title {
            font-size: 1.5rem;
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
            cursor: pointer;
            position: relative;
        }

        .admin-badge svg {
            width: 17px;
            height: 17px;
            color: #2e7d32;
        }

        .admin-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            min-width: 160px;
            z-index: 200;
            overflow: hidden;
        }

        .admin-dropdown.open {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            font-size: 0.84rem;
            font-weight: 500;
            color: var(--text);
            text-decoration: none;
            transition: background .12s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            font-family: inherit;
        }

        .dropdown-item:hover {
            background: #f4f6f8;
        }

        .dropdown-item.danger {
            color: #dc2626;
        }

        .dropdown-item svg {
            width: 15px;
            height: 15px;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
        }

        .content {
            padding: 24px 28px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* 4 STAT CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .stat-card {
            border-radius: 12px;
            padding: 16px 18px;
            color: #fff;
            overflow: hidden;
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #fb923c, #ea580c);
        }

        .stat-card.green {
            background: linear-gradient(135deg, #4ade80, #16a34a);
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
        }

        .stat-card.purple {
            background: linear-gradient(135deg, #c084fc, #9333ea);
        }

        .stat-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            opacity: .92;
            margin-bottom: 8px;
        }

        .stat-label svg {
            width: 14px;
            height: 14px;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -1px;
        }

        .stat-top-name {
            font-size: 0.95rem;
            font-weight: 800;
            margin-top: 4px;
        }

        .stat-top-sub {
            font-size: 0.72rem;
            opacity: .85;
        }

        /* TABLE */
        .table-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .table-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card-header h3 {
            font-size: 0.95rem;
            font-weight: 700;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #2e7d32;
            color: #fff;
            font-family: inherit;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
        }

        .btn-add:hover {
            background: #1b5e20;
        }

        .btn-add svg {
            width: 15px;
            height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 10px 16px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 1px solid var(--border);
            background: #f8f9fb;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .1s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fafbfc;
        }

        tbody td {
            padding: 12px 16px;
            font-size: 0.85rem;
        }

        .td-name {
            font-weight: 700;
        }

        .td-muted {
            color: var(--muted);
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

        .badge-inactive {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f3f4f6;
            color: #6b7280;
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

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0fdf4;
            color: #2e7d32;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 5px 12px;
            font-family: inherit;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background .12s;
        }

        .btn-view:hover {
            background: #dcfce7;
        }

        .btn-view svg {
            width: 13px;
            height: 13px;
        }

        /* PAGINATION */
        .pagination-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            padding: 14px;
            border-top: 1px solid var(--border);
        }

        .page-btn {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: #fff;
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .15s;
        }

        .page-btn:hover {
            background: #f4f6f8;
        }

        .page-btn.active {
            background: #2e7d32;
            color: #fff;
            border-color: #2e7d32;
        }

        .page-btn.arrow {
            color: var(--muted);
        }

        .page-dots {
            color: var(--muted);
            font-size: 0.82rem;
        }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 500;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 14px;
            padding: 26px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal h3 {
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group.full {
            grid-column: 1/-1;
        }

        .form-group label {
            font-size: 0.73rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            background: #f8f9fb;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-family: inherit;
            font-size: 0.87rem;
            color: var(--text);
            outline: none;
            transition: border-color .15s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2e7d32;
            background: #fff;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .btn-cancel-modal {
            padding: 9px 20px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #fff;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-save-modal {
            padding: 9px 20px;
            border-radius: 8px;
            border: none;
            background: #2e7d32;
            color: #fff;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('crudTable', () => ({
                isModalOpen: false,
                isEditModalOpen: false,
                isDeleteModalOpen: false,
                editForm: {},
                deleteForm: {
                    id: '',
                    name: ''
                },

                openEditModal(data) {
                    this.editForm = {
                        ...data
                    };
                    this.editForm.id = data._id; // Penting untuk MongoDB
                    this.isEditModalOpen = true;
                },

                openDeleteModal(id, name) {
                    this.deleteForm = {
                        id: id,
                        name: name
                    };
                    this.isDeleteModalOpen = true;
                }
            }));
        });
    </script>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-box"><strong>RE</strong><span>food</span></div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                    <rect x="14" y="14" width="7" height="7" rx="1" />
                </svg>Dashboard</a>
            <a href="{{ route('admin.foods.index') }}"
                class="nav-item {{ request()->routeIs('admin.foods.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                    <line x1="6" y1="1" x2="6" y2="4" />
                    <line x1="10" y1="1" x2="10" y2="4" />
                    <line x1="14" y1="1" x2="14" y2="4" />
                </svg>Foods</a>
            <a href="{{ route('admin.deliveries.index') }}"
                class="nav-item {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
                    <rect x="1" y="3" width="15" height="13" rx="1" />
                    <path d="m16 8 5 3v5h-5V8z" />
                    <circle cx="5.5" cy="18.5" r="2.5" />
                    <circle cx="18.5" cy="18.5" r="2.5" />
                </svg>Deliveries</a>
            <a href="{{ route('admin.donors.index') }}"
                class="nav-item {{ request()->routeIs('admin.donors.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>Donors</a>
            <a href="{{ route('admin.receivers.index') }}"
                class="nav-item {{ request()->routeIs('admin.receivers.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>Receivers</a>
            <a href="{{ route('admin.volunteers.index') }}"
                class="nav-item {{ request()->routeIs('admin.volunteers.*') ? 'active' : '' }}"><svg
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10" />
                    <path
                        d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32" />
                </svg>Volunteers</a>
            <a href="{{ route('admin.reports.index') }}"
                class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                </svg>Reports</a>
            <a href="{{ route('admin.settings') }}"
                class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="3" />
                    <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                </svg>Settings</a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <h1 class="topbar-title">Volunteers</h1>
            <div style="position:relative;">
                <div class="admin-badge" id="adminBtn" onclick="toggleDropdown()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                </div>
                <div class="admin-dropdown" id="adminDropdown">
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <div class="content" x-data="crudTable()">
            @if (session('success'))
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- 4 STAT CARDS -->
            <div class="stats-grid">
                <div class="stat-card orange">
                    <div class="stat-label"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72" />
                        </svg>Total Volunteers</div>
                    <div class="stat-number">{{ $stats['total'] }}</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-label"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>Active Volunteers</div>
                    <div class="stat-number">{{ $stats['active'] }}</div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-label"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="1" y="3" width="15" height="13" rx="1" />
                            <path d="m16 8 5 3v5h-5V8z" />
                        </svg>Total Deliveries</div>
                    <div class="stat-number">{{ $stats['total_deliveries'] }}</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-label"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>Top Volunteer</div>
                    @if ($stats['top_volunteer'])
                        <div class="stat-top-name">{{ Str::limit($stats['top_volunteer']->name, 12) }}</div>
                        <div class="stat-top-sub">
                            {{ $stats['top_volunteer']->deliveries ? $stats['top_volunteer']->deliveries->count() : 0 }}
                            deliveries</div>
                    @else
                        <div class="stat-top-name">—</div>
                    @endif
                </div>
            </div>

            <div x-show="isModalOpen" class="modal-overlay" style="display: none;"
                :style="isModalOpen ? 'display: flex;' : ''">
                <div class="modal" @click.away="isModalOpen = false">
                    <h3>Tambah Relawan (Kurir)</h3>
                    <form action="{{ route('admin.volunteers.store') }}" method="POST">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group"><label>Nama Relawan</label><input type="text" name="name"
                                    required></div>
                            <div class="form-group"><label>No. HP (WhatsApp)</label><input type="text"
                                    name="phone"></div>
                            <div class="form-group"><label>Tipe Kendaraan</label><input type="text"
                                    name="vehicle_type" placeholder="Misal: Motor Matic"></div>
                            <div class="form-group"><label>Plat Nomor</label><input type="text"
                                    name="vehicle_plate" placeholder="B 1234 ABC"></div>
                            <div class="form-group full"><label>Email Address</label><input type="email"
                                    name="email"></div>
                            <div class="form-group full"><label>Alamat Domisili</label>
                                <textarea name="address"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="isModalOpen = false"
                                class="btn-cancel-modal">Batal</button>
                            <button type="submit" class="btn-save-modal">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="isEditModalOpen" class="modal-overlay" style="display: none;"
                :style="isEditModalOpen ? 'display: flex;' : ''">
                <div class="modal" @click.away="isEditModalOpen = false">
                    <h3>Edit Data Relawan</h3>
                    <form :action="'{{ url('admin/volunteers') }}/' + editForm.id" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-grid">
                            <div class="form-group"><label>Nama Relawan</label><input type="text" name="name"
                                    x-model="editForm.name" required></div>
                            <div class="form-group"><label>No. HP (WA)</label><input type="text" name="phone"
                                    x-model="editForm.phone"></div>
                            <div class="form-group"><label>Tipe Kendaraan</label><input type="text"
                                    name="vehicle_type" x-model="editForm.vehicle_type"></div>
                            <div class="form-group"><label>Plat Nomor</label><input type="text"
                                    name="vehicle_plate" x-model="editForm.vehicle_plate"></div>
                            <div class="form-group full"><label>Email Address</label><input type="email"
                                    name="email" x-model="editForm.email"></div>
                            <div class="form-group full"><label>Alamat</label>
                                <textarea name="address" x-model="editForm.address"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="isEditModalOpen = false"
                                class="btn-cancel-modal">Batal</button>
                            <button type="submit" class="btn-save-modal">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="isDeleteModalOpen" class="modal-overlay" style="display: none;"
                :style="isDeleteModalOpen ? 'display: flex;' : ''">
                <div class="modal" @click.away="isDeleteModalOpen = false">
                    <h3 style="color: #dc2626;">Konfirmasi Hapus</h3>
                    <p style="font-size: 0.9rem; margin-bottom: 20px;">Hapus relawan <strong
                            x-text="deleteForm.name"></strong> secara permanen?</p>
                    <div class="modal-footer">
                        <button type="button" @click="isDeleteModalOpen = false"
                            class="btn-cancel-modal">Batal</button>
                        <form :action="'{{ url('admin/volunteers') }}/' + deleteForm.id" method="POST"
                            style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-save-modal" style="background: #dc2626;">Ya,
                                Hapus!</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-card-header"
                    style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Volunteers List</h3>
                    <button type="button" @click="isModalOpen = true" class="btn-add">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Data
                    </button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Volunteer Name</th>
                            <th>Total Deliveries</th>
                            <th>Last Delivered</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($volunteers as $vol)
                            <tr>
                                <td class="td-name">{{ $vol->name }}</td>
                                <td>{{ $vol->deliveries ? $vol->deliveries->count() : 0 }}</td>
                                <td class="td-muted">
                                    {{ $vol->deliveries->last()?->created_at?->format('d M Y') ?? '-' }}
                                </td>
                                <td>
                                    <div
                                        style="display: flex; flex-direction: column; gap: 6px; align-items: flex-start;">
                                        <!-- Status Approval Admin -->
                                        @if ($vol->is_verified)
                                            <span class="badge-active"><span class="badge-dot"></span>Active</span>
                                        @else
                                            <span class="badge-inactive"
                                                style="color: #dc2626; background: #fee2e2;"><span
                                                    class="badge-dot"></span>Pending</span>
                                        @endif

                                        <!-- Status Login App / DND Mode (ON/OFF) -->
                                        @if ($vol->status === 'aktif' || $vol->status === 'online')
                                            <span class="badge-active"
                                                style="background: #e0f2fe; color: #2563eb;"><span
                                                    class="badge-dot"></span>ON</span>
                                        @else
                                            <span class="badge-inactive"><span class="badge-dot"></span>OFF</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.volunteers.show', $vol->_id) }}" class="btn-view"
                                        title="Detail">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>
                                    <button type="button" @click='openEditModal(@json($vol))'
                                        class="btn-view"
                                        style="color: #ea580c; border-color: #fdba74; background: #fff7ed;"
                                        title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button type="button"
                                        @click="openDeleteModal('{{ $vol->_id }}', '{{ addslashes($vol->name) }}')"
                                        class="btn-view"
                                        style="color: #dc2626; border-color: #fca5a5; background: #fef2f2;"
                                        title="Hapus">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;padding:30px;color:var(--muted);">Belum
                                    ada data volunteer</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($volunteers->hasPages())
                    <div class="pagination-wrap">
                        @if ($volunteers->onFirstPage())
                            <span class="page-btn arrow" style="opacity:.4;"><svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" width="14" height="14">
                                    <polyline points="15 18 9 12 15 6" />
                                </svg></span>
                        @else
                            <a href="{{ $volunteers->previousPageUrl() }}" class="page-btn arrow"><svg
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    width="14" height="14">
                                    <polyline points="15 18 9 12 15 6" />
                                </svg></a>
                        @endif
                        @foreach ($volunteers->getUrlRange(1, $volunteers->lastPage()) as $page => $url)
                            @if ($page == $volunteers->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @elseif($page == 1 || $page == $volunteers->lastPage() || abs($page - $volunteers->currentPage()) <= 1)
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @elseif(abs($page - $volunteers->currentPage()) == 2)
                                <span class="page-dots">...</span>
                            @endif
                        @endforeach
                        @if ($volunteers->hasMorePages())
                            <a href="{{ $volunteers->nextPageUrl() }}" class="page-btn arrow"><svg
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    width="14" height="14">
                                    <polyline points="9 18 15 12 9 6" />
                                </svg></a>
                        @else
                            <span class="page-btn arrow" style="opacity:.4;"><svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" width="14" height="14">
                                    <polyline points="9 18 15 12 9 6" />
                                </svg></span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>


    <script>
        function toggleDropdown() {
            document.getElementById('adminDropdown').classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            const btn = document.getElementById('adminBtn'),
                dd = document.getElementById('adminDropdown');
            if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('open');
        });
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('crudTable', () => ({
                isModalOpen: false,
                isEditModalOpen: false,
                isDeleteModalOpen: false,
                editForm: {},
                deleteForm: {
                    id: '',
                    name: ''
                },

                openEditModal(data) {
                    this.editForm = {
                        ...data
                    };
                    this.editForm.id = data._id; // Penting untuk action form MongoDB
                    this.isEditModalOpen = true;

                    // Trigger peta jika ada (Guard aman)
                    setTimeout(() => {
                        if (typeof initLeafletEditMap === 'function') {
                            let lat = data.latitude || data.last_latitude || -6.200000;
                            let lng = data.longitude || data.last_longitude || 106.816666;
                            initLeafletEditMap(lat, lng);
                        }
                    }, 250);
                },

                openDeleteModal(id, name) {
                    this.deleteForm = {
                        id: id,
                        name: name
                    };
                    this.isDeleteModalOpen = true;
                }
            }));
        });
    </script>
</body>

</html>
