<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - RE-FOOD</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Plus Jakarta Sans',sans-serif;
            background:#fff;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            overflow:hidden;
        }

        /* Subtle background decoration */
        body::before {
            content:'';
            position:fixed;
            top:-180px; left:-180px;
            width:500px; height:500px;
            border-radius:50%;
            background:radial-gradient(circle, rgba(46,125,50,0.07) 0%, transparent 70%);
            pointer-events:none;
        }
        body::after {
            content:'';
            position:fixed;
            bottom:-180px; right:-180px;
            width:500px; height:500px;
            border-radius:50%;
            background:radial-gradient(circle, rgba(46,125,50,0.07) 0%, transparent 70%);
            pointer-events:none;
        }

        /* LOGIN CARD */
        .card {
            background:#2e7d32;
            border-radius:24px;
            padding:40px 36px 36px;
            width:100%;
            max-width:420px;
            box-shadow:0 24px 80px rgba(46,125,50,0.22), 0 4px 20px rgba(0,0,0,0.08);
            position:relative;
            z-index:1;
        }

        /* LOGO */
        .logo-wrap {
            display:flex;
            flex-direction:column;
            align-items:center;
            margin-bottom:28px;
        }
        .logo-box {
            background:#fff;
            border-radius:14px;
            width:62px;
            height:62px;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            margin-bottom:18px;
            box-shadow:0 4px 16px rgba(0,0,0,0.12);
        }
        .logo-re {
            font-size:1.35rem;
            font-weight:800;
            color:#2e7d32;
            line-height:1;
            letter-spacing:-0.5px;
        }
        .logo-food {
            font-size:0.55rem;
            font-weight:700;
            color:#2e7d32;
            letter-spacing:2px;
            text-transform:uppercase;
            margin-top:1px;
        }
        .card-title {
            font-size:1rem;
            font-weight:800;
            color:#fff;
            text-align:center;
            letter-spacing:1.5px;
            text-transform:uppercase;
        }
        .card-subtitle {
            font-size:0.78rem;
            font-weight:700;
            color:#f5c842;
            text-align:center;
            letter-spacing:2px;
            margin-top:4px;
        }

        /* FORM */
        .form-group {
            margin-bottom:16px;
        }
        .form-group label {
            display:block;
            font-size:0.78rem;
            font-weight:600;
            color:rgba(255,255,255,0.8);
            margin-bottom:6px;
            letter-spacing:.3px;
        }
        .input-wrap {
            position:relative;
            display:flex;
            align-items:center;
        }
        .input-icon {
            position:absolute;
            left:14px;
            width:16px;
            height:16px;
            color:rgba(255,255,255,0.6);
            pointer-events:none;
        }
        .form-input {
            width:100%;
            background:rgba(255,255,255,0.12);
            border:1.5px solid rgba(255,255,255,0.2);
            border-radius:10px;
            padding:12px 44px;
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size:0.9rem;
            font-weight:500;
            color:#fff;
            outline:none;
            transition:border-color .2s, background .2s;
        }
        .form-input::placeholder { color:rgba(255,255,255,0.4); }
        .form-input:focus {
            border-color:rgba(255,255,255,0.6);
            background:rgba(255,255,255,0.18);
        }
        .form-input.error {
            border-color:#fca5a5;
            background:rgba(239,68,68,0.1);
        }

        /* Toggle password */
        .toggle-pw {
            position:absolute;
            right:14px;
            background:none;
            border:none;
            cursor:pointer;
            color:rgba(255,255,255,0.5);
            padding:0;
            display:flex;
            align-items:center;
            transition:color .15s;
        }
        .toggle-pw:hover { color:rgba(255,255,255,0.9); }
        .toggle-pw svg { width:17px; height:17px; }

        /* Forgot password */
        .forgot-wrap {
            display:flex;
            justify-content:flex-end;
            margin-top:-6px;
            margin-bottom:20px;
        }
        .forgot-link {
            font-size:0.78rem;
            font-weight:600;
            color:rgba(255,255,255,0.65);
            text-decoration:none;
            transition:color .15s;
        }
        .forgot-link:hover { color:#fff; }

        /* Error alert */
        .alert-error {
            background:rgba(239,68,68,0.18);
            border:1px solid rgba(239,68,68,0.4);
            border-radius:10px;
            padding:10px 14px;
            font-size:0.82rem;
            font-weight:600;
            color:#fca5a5;
            margin-bottom:16px;
            display:flex;
            align-items:center;
            gap:8px;
        }
        .alert-error svg { width:15px; height:15px; flex-shrink:0; }

        /* Login button */
        .btn-login {
            width:100%;
            background:#fff;
            color:#2e7d32;
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size:0.88rem;
            font-weight:800;
            letter-spacing:2px;
            text-transform:uppercase;
            padding:14px;
            border:none;
            border-radius:10px;
            cursor:pointer;
            transition:background .18s, transform .12s, box-shadow .18s;
            box-shadow:0 4px 16px rgba(0,0,0,0.12);
        }
        .btn-login:hover {
            background:#f0fdf4;
            transform:translateY(-1px);
            box-shadow:0 6px 20px rgba(0,0,0,0.15);
        }
        .btn-login:active { transform:translateY(0); }

        /* Footer */
        .footer {
            text-align:center;
            margin-top:28px;
            font-size:0.72rem;
            font-weight:500;
            color:rgba(255,255,255,0.4);
            letter-spacing:.3px;
        }
    </style>
</head>
<body>

<div class="card">
    <!-- Logo -->
    <div class="logo-wrap">
        <div class="logo-box">
            <span class="logo-re">RE</span>
            <span class="logo-food">food</span>
        </div>
        <div class="card-title">Admin Login Page</div>
        <div class="card-subtitle">RE-FOOD</div>
    </div>

    <!-- Error -->
    @if($errors->any())
    <div class="alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ $errors->first() }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <!-- Username -->
        <div class="form-group">
            <label>Username</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <input
                    type="text"
                    name="username"
                    class="form-input {{ $errors->has('username') ? 'error' : '' }}"
                    placeholder="Enter your username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    autofocus>
            </div>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label>Password</label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input
                    type="password"
                    name="password"
                    id="passwordInput"
                    class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                    placeholder="Enter your password"
                    autocomplete="current-password">
                <button type="button" class="toggle-pw" onclick="togglePassword()" id="toggleBtn">
                    <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Forgot password -->
        <div class="forgot-wrap">
            <a href="#" class="forgot-link">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-login">LOGIN</button>
    </form>

    <div class="footer">© {{ date('Y') }} RE-FOOD. Admin Panel v1.0</div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    icon.innerHTML = isPass
        ? '<line x1="1" y1="1" x2="23" y2="23"/><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>'
        : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}
</script>
</body>
</html>