<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($food) ? 'Edit' : 'Tambah' }} Food - RE-FOOD Admin</title>
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
        .btn-back { display:inline-flex; align-items:center; gap:6px; color:var(--muted); font-size:0.87rem; font-weight:500; text-decoration:none; padding:6px 0; transition:color .15s; }
        .btn-back:hover { color:var(--text); }
        .btn-back svg { width:18px; height:18px; }
        .topbar-title { font-size:1.3rem; font-weight:800; }
        .admin-badge { display:flex; align-items:center; gap:8px; background:#f0f2f5; border:1px solid var(--border); border-radius:50px; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer; position:relative; }
        .admin-badge svg { width:17px; height:17px; color:#2e7d32; }
        .admin-dropdown { display:none; position:absolute; top:calc(100% + 8px); right:0; background:#fff; border:1px solid var(--border); border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.1); min-width:160px; z-index:200; overflow:hidden; }
        .admin-dropdown.open { display:block; }
        .dropdown-item { display:flex; align-items:center; gap:10px; padding:11px 16px; font-size:0.84rem; font-weight:500; color:var(--text); text-decoration:none; transition:background .12s; cursor:pointer; border:none; background:none; width:100%; font-family:inherit; }
        .dropdown-item:hover { background:#f4f6f8; }
        .dropdown-item.danger { color:#dc2626; }
        .dropdown-item svg { width:15px; height:15px; }
        .dropdown-divider { height:1px; background:var(--border); }

        .content { padding:28px; flex:1; }
        .page-title { font-size:1.3rem; font-weight:800; margin-bottom:22px; }

        .form-card { background:#fff; border-radius:14px; border:1px solid var(--border); padding:28px; max-width:820px; }

        .form-grid { display:grid; grid-template-columns:220px 1fr; gap:28px; margin-bottom:24px; }

        /* PHOTO UPLOAD */
        .photo-upload-section { display:flex; flex-direction:column; gap:10px; }
        .photo-label-title { font-size:0.82rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid var(--border); padding-bottom:8px; }
        .photo-drop { width:100%; aspect-ratio:1; border-radius:12px; border:2px dashed var(--border); background:#f8f9fb; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; transition:border-color .2s, background .2s; position:relative; overflow:hidden; }
        .photo-drop:hover { border-color:#2e7d32; background:#f0fdf4; }
        .photo-drop.has-image { border-style:solid; border-color:#2e7d32; }
        .photo-drop input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .photo-preview { position:absolute; inset:0; object-fit:cover; border-radius:10px; display:none; }
        .photo-placeholder { display:flex; flex-direction:column; align-items:center; gap:10px; pointer-events:none; }
        .photo-placeholder svg { width:40px; height:40px; color:#ccc; }
        .photo-placeholder span { font-size:0.78rem; color:var(--muted); text-align:center; line-height:1.4; }
        .photo-placeholder em { font-size:0.72rem; color:#bbb; font-style:normal; }
        .photo-change-hint { font-size:0.75rem; color:var(--muted); text-align:center; margin-top:4px; }

        /* FORM FIELDS */
        .form-fields { display:flex; flex-direction:column; gap:16px; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group.full { grid-column:1/-1; }
        label { font-size:0.8rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; }
        .form-input, .form-select, .form-textarea { width:100%; background:#f8f9fb; border:1px solid var(--border); border-radius:8px; padding:11px 14px; font-family:inherit; font-size:0.9rem; color:var(--text); outline:none; transition:border-color .15s, background .15s; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color:#2e7d32; background:#fff; }
        .form-input::placeholder, .form-textarea::placeholder { color:#bbb; }
        .form-textarea { resize:vertical; min-height:90px; }
        .form-select { cursor:pointer; }
        .input-error { border-color:#dc2626 !important; }
        .error-msg { font-size:0.76rem; color:#dc2626; margin-top:2px; }

        /* FOOTER */
        .form-footer { display:flex; align-items:center; justify-content:flex-end; gap:12px; padding-top:20px; border-top:1px solid var(--border); margin-top:4px; }
        .btn-cancel-form { display:inline-flex; align-items:center; gap:8px; background:#fff; border:1px solid var(--border); color:var(--text); font-family:inherit; font-size:0.87rem; font-weight:600; padding:10px 20px; border-radius:8px; cursor:pointer; text-decoration:none; transition:background .15s; }
        .btn-cancel-form:hover { background:#f4f6f8; }
        .btn-save { display:inline-flex; align-items:center; gap:8px; background:#2e7d32; color:#fff; font-family:inherit; font-size:0.87rem; font-weight:700; padding:11px 24px; border-radius:8px; border:none; cursor:pointer; transition:background .15s; }
        .btn-save:hover { background:#1b5e20; }
        .btn-save svg { width:16px; height:16px; }
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
        <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard
        </a>
        <a href="{{ route('admin.foods.index') }}" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>Foods
        </a>
        <a href="{{ route('admin.deliveries.index') }}" class="nav-item">
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
        <a href="{{ route('admin.reports.index') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Reports
        </a>
        <a href="{{ route('admin.settings') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Settings
        </a>
    </nav>
</aside>

<!-- MAIN -->
<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <a href="{{ route('admin.foods.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="topbar-title">{{ isset($food) ? 'Edit Food' : 'Tambah Food' }}</h1>
        </div>
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
        <div class="page-title">{{ isset($food) ? 'Edit Food' : 'Tambah Food Baru' }}</div>

        <div class="form-card">
            <form method="POST"
                  action="{{ isset($food) ? route('admin.foods.update', $food) : route('admin.foods.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($food)) @method('PUT') @endif

                <div class="form-grid">
                    <!-- PHOTO UPLOAD -->
                    <div class="photo-upload-section">
                        <div class="photo-label-title">Food Photo</div>
                        <div class="photo-drop" id="photoDrop">
                            <input type="file" name="photo" id="photoInput" accept="image/*" onchange="previewPhoto(this)">
                            @if(isset($food) && $food->photo)
                                <img src="{{ asset('storage/'.$food->photo) }}" class="photo-preview" id="photoPreview" style="display:block;">
                            @else
                                <img src="" class="photo-preview" id="photoPreview">
                            @endif
                            <div class="photo-placeholder" id="photoPlaceholder" style="{{ isset($food) && $food->photo ? 'display:none' : '' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <span>Klik untuk upload foto</span>
                                <em>JPG, PNG, WEBP — Max 2MB</em>
                            </div>
                        </div>
                        <div class="photo-change-hint">{{ isset($food) && $food->photo ? 'Klik foto untuk ganti' : '' }}</div>
                        @error('photo')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- FIELDS -->
                    <div class="form-fields">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Nama Makanan *</label>
                                <input type="text" id="name" name="name" class="form-input @error('name') input-error @enderror"
                                       placeholder="Contoh: Nasi Kotak Ayam" value="{{ old('name', $food->name ?? '') }}" required>
                                @error('name')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="category">Kategori</label>
                                <input type="text" id="category" name="category" class="form-input"
                                       placeholder="Contoh: Makanan Berat" value="{{ old('category', $food->category ?? '') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="donor_id">Donor</label>
                                <select id="donor_id" name="donor_id" class="form-select">
                                    <option value="">-- Pilih Donor --</option>
                                    @foreach($donors as $donor)
                                        <option value="{{ $donor->id }}" {{ old('donor_id', $food->donor_id ?? '') == $donor->id ? 'selected' : '' }}>
                                            {{ $donor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="receiver_id">Receiver</label>
                                <select id="receiver_id" name="receiver_id" class="form-select">
                                    <option value="">-- Pilih Receiver --</option>
                                    @foreach($receivers as $receiver)
                                        <option value="{{ $receiver->id }}" {{ old('receiver_id', $food->receiver_id ?? '') == $receiver->id ? 'selected' : '' }}>
                                            {{ $receiver->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="portion">Porsi</label>
                                <input type="number" id="portion" name="portion" class="form-input"
                                       placeholder="Contoh: 25" min="1" value="{{ old('portion', $food->portion ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select id="status" name="status" class="form-select @error('status') input-error @enderror" required>
                                    <option value="available" {{ old('status', $food->status ?? 'available') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="taken"     {{ old('status', $food->status ?? '') == 'taken'     ? 'selected' : '' }}>Taken</option>
                                    <option value="delivered" {{ old('status', $food->status ?? '') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="invalid"   {{ old('status', $food->status ?? '') == 'invalid'   ? 'selected' : '' }}>Invalid</option>
                                </select>
                                @error('status')<div class="error-msg">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="collection_date">Tanggal Pengambilan</label>
                            <input type="datetime-local" id="collection_date" name="collection_date" class="form-input"
                                   value="{{ old('collection_date', isset($food) && $food->collection_date ? \Carbon\Carbon::parse($food->collection_date)->format('Y-m-d\TH:i') : '') }}">
                        </div>

                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea id="note" name="note" class="form-textarea" placeholder="Catatan tambahan tentang makanan ini...">{{ old('note', $food->note ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('admin.foods.index') }}" class="btn-cancel-form">Batal</a>
                    <button type="submit" class="btn-save">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ isset($food) ? 'Update Food' : 'Simpan Food' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleDropdown() { document.getElementById('adminDropdown').classList.toggle('open'); }
document.addEventListener('click', function(e) {
    const btn = document.getElementById('adminBtn'), dd = document.getElementById('adminDropdown');
    if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) dd.classList.remove('open');
});

function previewPhoto(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('photoPreview');
        const placeholder = document.getElementById('photoPlaceholder');
        const drop = document.getElementById('photoDrop');
        preview.src = e.target.result;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
        drop.classList.add('has-image');
    };
    reader.readAsDataURL(file);
}
</script>
</body>
</html>
