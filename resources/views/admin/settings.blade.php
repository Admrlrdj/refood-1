<!DOCTYPE html>
<html lang="id" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - RE-FOOD Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
    <style>
        :root { --green:#2e7d32; --sidebar-width:200px; --topbar-height:64px; --bg:#f4f6f8; --border:#e4e8ed; --text:#1a2332; --muted:#6b7a8d; --card-bg:#fff; }
        html.dark { --bg:#0f172a; --border:#1e293b; --text:#e2e8f0; --muted:#94a3b8; --card-bg:#1e293b; }
        html.dark .sidebar { background:#14532d; }
        html.dark .topbar  { background:#1e293b; border-color:#334155; }
        html.dark .admin-badge { background:#0f172a; border-color:#334155; color:#e2e8f0; }
        html.dark .settings-card { background:#1e293b; border-color:#334155; }
        html.dark .settings-section-title { border-color:#334155; color:#e2e8f0; }
        html.dark .field-label { color:#e2e8f0; }
        html.dark .field-input, html.dark .field-select { background:#0f172a; border-color:#334155; color:#e2e8f0; }
        html.dark .settings-footer { background:#0f172a; border-color:#334155; }
        html.dark .btn-cancel { background:#1e293b; border-color:#334155; color:#e2e8f0; }
        html.dark .toggle-btn { background:#1e293b; color:#94a3b8; }
        html.dark .modal { background:#1e293b; }
        html.dark .mg input { background:#0f172a; border-color:#334155; color:#e2e8f0; }
        html.dark .modal h3 { color:#e2e8f0; border-color:#334155; }
        html.dark .modal-footer-btns { border-color:#334155; }
        html.font-small  { font-size:13px; }
        html.font-medium { font-size:15px; }
        html.font-large  { font-size:17px; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--bg); color:var(--text); display:flex; min-height:100vh; transition:background .2s,color .2s; }
        .sidebar { width:var(--sidebar-width); background:#2e7d32; display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:100; transition:background .2s; }
        .sidebar-logo { padding:16px 18px; display:flex; align-items:center; border-bottom:1px solid rgba(255,255,255,0.12); min-height:var(--topbar-height); }
        .logo-box { background:#fff; color:#2e7d32; font-weight:800; font-size:0.9rem; line-height:1.15; padding:7px 10px; border-radius:8px; text-align:center; }
        .logo-box span { display:block; font-size:0.62rem; font-weight:600; letter-spacing:1px; color:#2e7d32; }
        .sidebar-nav { flex:1; padding:10px 0; overflow-y:auto; }
        .nav-item { display:flex; align-items:center; gap:12px; padding:11px 18px; color:rgba(255,255,255,0.85); text-decoration:none; font-size:0.87rem; font-weight:500; border-left:3px solid transparent; transition:background .15s; }
        .nav-item:hover { background:rgba(0,0,0,0.15); color:#fff; }
        .nav-item.active { background:#1b5e20; color:#fff; border-left-color:#a5d6a7; }
        .nav-item svg { width:19px; height:19px; flex-shrink:0; }
        .main { margin-left:var(--sidebar-width); flex:1; display:flex; flex-direction:column; }
        .topbar { height:var(--topbar-height); background:var(--card-bg); border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; padding:0 28px; position:sticky; top:0; z-index:50; transition:background .2s; }
        .topbar-title { font-size:1.5rem; font-weight:800; color:var(--text); }
        .admin-badge { display:flex; align-items:center; gap:8px; background:#f0f2f5; border:1px solid var(--border); border-radius:50px; padding:8px 16px; font-size:0.85rem; font-weight:600; color:var(--text); }
        .admin-badge svg { width:17px; height:17px; color:#2e7d32; }
        .content { padding:24px 28px; flex:1; display:flex; flex-direction:column; gap:20px; max-width:640px; }
        .settings-card { background:var(--card-bg); border-radius:12px; border:1px solid var(--border); overflow:hidden; transition:background .2s,border-color .2s; }
        .settings-section-title { font-size:1rem; font-weight:800; padding:18px 22px; border-bottom:1px solid var(--border); color:var(--text); }
        .settings-body { padding:20px 22px; display:flex; flex-direction:column; gap:16px; }
        .field-row { display:grid; grid-template-columns:130px 1fr; align-items:center; gap:16px; }
        .field-label { font-size:0.87rem; font-weight:600; color:var(--text); }
        .field-input { background:#f8f9fb; border:1.5px solid var(--border); border-radius:8px; padding:9px 14px; font-family:inherit; font-size:0.87rem; color:var(--text); outline:none; width:100%; transition:border-color .15s; }
        .field-input:focus { border-color:#2e7d32; }
        .field-input[readonly] { color:var(--muted); cursor:default; }
        .field-select { background:#f8f9fb; border:1.5px solid var(--border); border-radius:8px; padding:9px 14px; font-family:inherit; font-size:0.87rem; color:var(--text); outline:none; width:100%; cursor:pointer; }
        .appearance-toggle { display:flex; border:1.5px solid var(--border); border-radius:8px; overflow:hidden; width:fit-content; }
        .toggle-btn { padding:8px 22px; font-family:inherit; font-size:0.85rem; font-weight:600; border:none; cursor:pointer; transition:background .15s,color .15s; background:var(--card-bg); color:var(--muted); }
        .toggle-btn.active { background:#2e7d32; color:#fff; }
        .btn-change-pw { display:inline-flex; align-items:center; gap:7px; background:#2e7d32; color:#fff; font-family:inherit; font-size:0.85rem; font-weight:600; padding:9px 18px; border-radius:8px; border:none; cursor:pointer; }
        .btn-change-pw:hover { background:#1b5e20; }
        /* FOOTER — logout TERPISAH dari form settings */
        .settings-footer { display:flex; align-items:center; justify-content:space-between; padding:16px 22px; border-top:1px solid var(--border); background:#f8f9fb; transition:background .2s; }
        .btn-logout { display:inline-flex; align-items:center; gap:7px; background:#dc2626; color:#fff; font-family:inherit; font-size:0.85rem; font-weight:600; padding:9px 18px; border-radius:8px; border:none; cursor:pointer; }
        .btn-logout:hover { background:#b91c1c; }
        .btn-logout svg { width:15px; height:15px; }
        .footer-right { display:flex; gap:10px; }
        .btn-cancel { padding:9px 20px; border-radius:8px; border:1px solid var(--border); background:var(--card-bg); font-family:inherit; font-size:0.85rem; font-weight:600; cursor:pointer; color:var(--text); text-decoration:none; }
        .btn-cancel:hover { background:var(--bg); }
        .btn-save { padding:9px 20px; border-radius:8px; border:none; background:#2e7d32; color:#fff; font-family:inherit; font-size:0.85rem; font-weight:700; cursor:pointer; }
        .btn-save:hover { background:#1b5e20; }
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:500; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal { background:var(--card-bg); border-radius:14px; padding:26px; max-width:400px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.2); }
        .modal h3 { font-size:1rem; font-weight:800; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid var(--border); color:var(--text); }
        .modal-form { display:flex; flex-direction:column; gap:12px; }
        .mg { display:flex; flex-direction:column; gap:5px; }
        .mg label { font-size:0.73rem; font-weight:700; color:var(--muted); text-transform:uppercase; }
        .mg input { background:#f8f9fb; border:1.5px solid var(--border); border-radius:8px; padding:10px 12px; font-family:inherit; font-size:0.87rem; color:var(--text); outline:none; }
        .mg input:focus { border-color:#2e7d32; }
        .modal-footer-btns { display:flex; gap:10px; justify-content:flex-end; margin-top:18px; padding-top:14px; border-top:1px solid var(--border); }
        .btn-cm { padding:9px 20px; border-radius:8px; border:1px solid var(--border); background:var(--card-bg); font-family:inherit; font-size:0.85rem; font-weight:600; cursor:pointer; color:var(--text); }
        .btn-sm { padding:9px 20px; border-radius:8px; border:none; background:#2e7d32; color:#fff; font-family:inherit; font-size:0.85rem; font-weight:700; cursor:pointer; }
        .alert { padding:12px 16px; border-radius:8px; font-size:0.87rem; font-weight:500; display:flex; align-items:center; gap:10px; }
        .alert-success { background:#dcfce7; color:#16a34a; border:1px solid #bbf7d0; }
        .alert-error   { background:#fee2e2; color:#dc2626; border:1px solid #fecaca; }
        .alert svg { width:16px; height:16px; }
    </style>
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
        <a href="{{ route('admin.reports.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Reports</a>
        <a href="{{ route('admin.settings') }}" class="nav-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Settings</a>
    </nav>
</aside>

<div class="main">
    <header class="topbar">
        <h1 class="topbar-title">Settings</h1>
        <div class="admin-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
        </div>
    </header>

    <div class="content">
        @if(session('success'))
        <div class="alert alert-success"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>{{ session('error') }}</div>
        @endif

        <div class="settings-card">
            <!-- PROFILE FORM -->
            <form method="POST" action="{{ route('admin.settings.update') }}" id="settingsForm">
                @csrf
                <input type="hidden" name="appearance" id="appearanceInput" value="{{ session('appearance','light') }}">
                <input type="hidden" name="font_size"   id="fontSizeInput"   value="{{ session('font_size','medium') }}">
                <input type="hidden" name="language"    id="languageInput"   value="{{ session('language','en') }}">

                <div class="settings-section-title">Profile</div>
                <div class="settings-body">
                    <div class="field-row">
                        <label class="field-label">Name</label>
                        <input type="text" name="name" class="field-input" value="{{ Auth::guard('admin')->user()->name ?? 'Admin' }}">
                    </div>
                    <div class="field-row">
                        <label class="field-label">Last Login</label>
                        <input type="text" class="field-input" readonly value="{{ Auth::guard('admin')->user()->last_login_at ? \Carbon\Carbon::parse(Auth::guard('admin')->user()->last_login_at)->format('d M Y, H:i').' WIB' : now()->format('d M Y, H:i').' WIB' }}">
                    </div>
                    <div style="display:flex;justify-content:flex-end;">
                        <button type="button" class="btn-change-pw" onclick="document.getElementById('pwModal').classList.add('open')">Change Password</button>
                    </div>
                </div>

                <div class="settings-section-title" style="border-top:1px solid var(--border);">System Setting</div>
                <div class="settings-body">
                    <div class="field-row">
                        <label class="field-label">Appearance</label>
                        <div class="appearance-toggle">
                            <button type="button" id="lightBtn" class="toggle-btn {{ session('appearance','light')=='light'?'active':'' }}" onclick="setAppearance('light')">Light</button>
                            <button type="button" id="darkBtn"  class="toggle-btn {{ session('appearance','light')=='dark' ?'active':'' }}" onclick="setAppearance('dark')">Dark</button>
                        </div>
                    </div>
                    <div class="field-row">
                        <label class="field-label">Language</label>
                        <select id="langSelect" class="field-select" onchange="setLanguage(this.value)">
                            <option value="en" {{ session('language','en')=='en'?'selected':'' }}>English</option>
                            <option value="id" {{ session('language','en')=='id'?'selected':'' }}>Indonesia</option>
                        </select>
                    </div>
                    <div class="field-row">
                        <label class="field-label">Font Size</label>
                        <select id="fontSelect" class="field-select" onchange="setFontSize(this.value)">
                            <option value="small"  {{ session('font_size','medium')=='small' ?'selected':'' }}>Small</option>
                            <option value="medium" {{ session('font_size','medium')=='medium'?'selected':'' }}>Medium</option>
                            <option value="large"  {{ session('font_size','medium')=='large' ?'selected':'' }}>Large</option>
                        </select>
                    </div>
                </div>

                <!-- FOOTER — logout BENAR-BENAR TERPISAH dari form settings -->
                <div class="settings-footer">
                    <div>
                        {{-- Logout pakai form sendiri, BUKAN di dalam form settings --}}
                        <button type="button" class="btn-logout" onclick="doLogout()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Log out
                        </button>
                    </div>
                    <div class="footer-right">
                        <button type="button" class="btn-cancel" onclick="cancelSettings()">Cancel</button>
                        <button type="submit" class="btn-save">Save Change</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Form logout tersembunyi, dipanggil JS --}}
        <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm" style="display:none;">
            @csrf
        </form>
    </div>
</div>

<!-- MODAL CHANGE PASSWORD -->
<div class="modal-overlay" id="pwModal">
    <div class="modal">
        <h3>🔑 Change Password</h3>
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <input type="hidden" name="change_password" value="1">
            <div class="modal-form">
                <div class="mg"><label>Password Saat Ini</label><input type="password" name="current_password" placeholder="••••••••" required></div>
                <div class="mg"><label>Password Baru</label><input type="password" name="new_password" placeholder="Min. 8 karakter" required></div>
                <div class="mg"><label>Konfirmasi Password Baru</label><input type="password" name="new_password_confirmation" placeholder="Ulangi password baru" required></div>
            </div>
            <div class="modal-footer-btns">
                <button type="button" class="btn-cm" onclick="document.getElementById('pwModal').classList.remove('open')">Batal</button>
                <button type="submit" class="btn-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Ambil saved values ────────────────────────────────
const savedAppearance = '{{ session("appearance","light") }}';
const savedFont       = '{{ session("font_size","medium") }}';
const savedLang       = '{{ session("language","en") }}';

// Apply on load
applyAppearance(savedAppearance);
applyFont(savedFont);

// ── Appearance (live + simpan ke localStorage biar semua halaman kena) ──
function setAppearance(mode) {
    document.getElementById('appearanceInput').value = mode;
    document.getElementById('lightBtn').classList.toggle('active', mode==='light');
    document.getElementById('darkBtn').classList.toggle('active', mode==='dark');
    applyAppearance(mode);
    localStorage.setItem('refood_appearance', mode); // ← simpan ke localStorage
}
function applyAppearance(mode) {
    document.getElementById('htmlRoot').classList.toggle('dark', mode==='dark');
}

// ── Font Size ───────────────────────────────────────────
function setFontSize(size) {
    document.getElementById('fontSizeInput').value = size;
    applyFont(size);
    localStorage.setItem('refood_font', size);
}
function applyFont(size) {
    const html = document.getElementById('htmlRoot');
    html.classList.remove('font-small','font-medium','font-large');
    html.classList.add('font-' + size);
}

// ── Language ────────────────────────────────────────────
function setLanguage(lang) {
    document.getElementById('languageInput').value = lang;
    localStorage.setItem('refood_lang', lang);
}

// ── Logout (form terpisah) ───────────────────────────────
function doLogout() {
    if(confirm('Yakin mau logout?')) {
        document.getElementById('logoutForm').submit();
    }
}

// ── Cancel ──────────────────────────────────────────────
function cancelSettings() {
    // Reset ke nilai tersimpan
    setAppearance(savedAppearance);
    document.getElementById('fontSelect').value = savedFont;
    setFontSize(savedFont);
    document.getElementById('langSelect').value = savedLang;
    setLanguage(savedLang);
    window.location.href = '{{ route("admin.dashboard") }}';
}

// ── Modal backdrop close ─────────────────────────────────
document.getElementById('pwModal').addEventListener('click', function(e) {
    if(e.target === this) this.classList.remove('open');
});
</script>
</body>
</html>