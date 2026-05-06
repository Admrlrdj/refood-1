<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - RE-FOOD</title>
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:#fff; min-height:100vh; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
        body::before { content:''; position:fixed; top:-180px; left:-180px; width:500px; height:500px; border-radius:50%; background:radial-gradient(circle, rgba(46,125,50,0.07) 0%, transparent 70%); pointer-events:none; }
        body::after { content:''; position:fixed; bottom:-180px; right:-180px; width:500px; height:500px; border-radius:50%; background:radial-gradient(circle, rgba(46,125,50,0.07) 0%, transparent 70%); pointer-events:none; }
        .card { background:#2e7d32; border-radius:24px; padding:40px 36px 36px; width:100%; max-width:420px; box-shadow:0 24px 80px rgba(46,125,50,0.22), 0 4px 20px rgba(0,0,0,0.08); position:relative; z-index:1; }
        .logo-wrap { display:flex; flex-direction:column; align-items:center; margin-bottom:28px; }
        .logo-box { background:#fff; border-radius:14px; width:62px; height:62px; display:flex; flex-direction:column; align-items:center; justify-content:center; margin-bottom:18px; box-shadow:0 4px 16px rgba(0,0,0,0.12); }
        .logo-re { font-size:1.35rem; font-weight:800; color:#2e7d32; line-height:1; letter-spacing:-0.5px; }
        .logo-food { font-size:0.55rem; font-weight:700; color:#2e7d32; letter-spacing:2px; text-transform:uppercase; margin-top:1px; }
        .card-title { font-size:1rem; font-weight:800; color:#fff; text-align:center; letter-spacing:1.5px; text-transform:uppercase; }
        .card-subtitle { font-size:0.78rem; font-weight:600; color:rgba(255,255,255,0.6); text-align:center; margin-top:6px; line-height:1.5; }
        .form-group { margin-bottom:16px; }
        .form-group label { display:block; font-size:0.78rem; font-weight:600; color:rgba(255,255,255,0.8); margin-bottom:6px; }
        .input-wrap { position:relative; display:flex; align-items:center; }
        .input-icon { position:absolute; left:14px; width:16px; height:16px; color:rgba(255,255,255,0.6); pointer-events:none; }
        .form-input { width:100%; background:rgba(255,255,255,0.12); border:1.5px solid rgba(255,255,255,0.2); border-radius:10px; padding:12px 44px; font-family:'Plus Jakarta Sans',sans-serif; font-size:0.9rem; font-weight:500; color:#fff; outline:none; transition:border-color .2s, background .2s; }
        .form-input::placeholder { color:rgba(255,255,255,0.4); }
        .form-input:focus { border-color:rgba(255,255,255,0.6); background:rgba(255,255,255,0.18); }
        .alert-success { background:rgba(34,197,94,0.18); border:1px solid rgba(34,197,94,0.4); border-radius:10px; padding:10px 14px; font-size:0.82rem; font-weight:600; color:#86efac; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
        .alert-error { background:rgba(239,68,68,0.18); border:1px solid rgba(239,68,68,0.4); border-radius:10px; padding:10px 14px; font-size:0.82rem; font-weight:600; color:#fca5a5; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
        .alert-success svg, .alert-error svg { width:15px; height:15px; flex-shrink:0; }
        .btn-submit { width:100%; background:#fff; color:#2e7d32; font-family:'Plus Jakarta Sans',sans-serif; font-size:0.88rem; font-weight:800; letter-spacing:2px; text-transform:uppercase; padding:14px; border:none; border-radius:10px; cursor:pointer; transition:background .18s, transform .12s; box-shadow:0 4px 16px rgba(0,0,0,0.12); }
        .btn-submit:hover { background:#f0fdf4; transform:translateY(-1px); }
        .back-link { display:flex; align-items:center; justify-content:center; gap:6px; margin-top:20px; font-size:0.82rem; font-weight:600; color:rgba(255,255,255,0.6); text-decoration:none; transition:color .15s; }
        .back-link:hover { color:#fff; }
        .back-link svg { width:14px; height:14px; }
        .footer { text-align:center; margin-top:24px; font-size:0.72rem; font-weight:500; color:rgba(255,255,255,0.4); }
        .hint { font-size:0.76rem; color:rgba(255,255,255,0.5); margin-top:6px; line-height:1.5; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo-wrap">
        <div class="logo-box">
            <span class="logo-re">RE</span>
            <span class="logo-food">food</span>
        </div>
        <div class="card-title">Forgot Password</div>
        <div class="card-subtitle">Masukkan username kamu, gw kirimin password baru.</div>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.forgot-password.post') }}">
        @csrf
        <div class="form-group">
            <label>Username</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" name="username" class="form-input" placeholder="Masukkan username kamu" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label>Password Baru</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" name="new_password" class="form-input" placeholder="Password baru (min. 8 karakter)" required>
            </div>
            <p class="hint">⚠️ Karena ini admin panel lokal, reset langsung dengan password baru.</p>
        </div>
        <button type="submit" class="btn-submit">RESET PASSWORD</button>
    </form>

    <a href="{{ route('admin.login') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke Login
    </a>
    <div class="footer">© {{ date('Y') }} RE-FOOD. Admin Panel v1.0</div>
</div>
</body>
</html>
