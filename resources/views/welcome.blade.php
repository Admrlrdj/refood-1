<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RE-FOOD</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Plus Jakarta Sans',sans-serif;
            background:#0a1f0e;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            overflow:hidden;
        }

        /* Background radial glows  */
        .glow-top {
            position:fixed;
            top:-200px; left:50%;
            transform:translateX(-50%);
            width:700px; height:500px;
            border-radius:50%;
            background:radial-gradient(ellipse, rgba(46,125,50,0.35) 0%, transparent 65%);
            pointer-events:none;
        }
        .glow-bottom {
            position:fixed;
            bottom:-200px; left:50%;
            transform:translateX(-50%);
            width:600px; height:400px;
            border-radius:50%;
            background:radial-gradient(ellipse, rgba(46,125,50,0.18) 0%, transparent 65%);
            pointer-events:none;
        }

        /* CENTER CONTENT */
        .center {
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:0;
            position:relative;
            z-index:1;
            text-align:center;
            padding:24px;
        }

        /* APP ICON  */
        .app-icon {
            width:100px;
            height:100px;
            background:#1a3d1e;
            border-radius:22px;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            margin-bottom:36px;
            box-shadow:0 8px 32px rgba(0,0,0,0.3), 0 0 0 1px rgba(46,125,50,0.3);
            position:relative;
        }
        /* Food icon SVG dalam lingkaran kuning */
        .app-icon-inner {
            width:52px;
            height:52px;
            border-radius:50%;
            border:2px solid #f5c842;
            display:flex;
            align-items:center;
            justify-content:center;
            margin-bottom:6px;
        }
        .app-icon-inner svg {
            width:26px;
            height:26px;
            color:#f5c842;
        }
        .app-icon-label {
            font-size:0.6rem;
            font-weight:800;
            color:#f5c842;
            letter-spacing:2.5px;
            text-transform:uppercase;
        }

        /* MAIN TITLE */
        .main-title {
            font-size:3.8rem;
            font-weight:800;
            color:#fff;
            letter-spacing:-1px;
            line-height:1;
            margin-bottom:20px;
        }

        /* SUBTITLE */
        .subtitle {
            font-size:1rem;
            font-weight:400;
            color:rgba(255,255,255,0.5);
            line-height:1.7;
            margin-bottom:48px;
            max-width:360px;
        }

        /* BUTTON  */
        .btn-enter {
            display:inline-flex;
            align-items:center;
            gap:12px;
            background:#2e7d32;
            color:#fff;
            text-decoration:none;
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size:0.95rem;
            font-weight:700;
            letter-spacing:0.5px;
            padding:16px 36px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,0.15);
            box-shadow:0 4px 20px rgba(46,125,50,0.4), 0 1px 0 rgba(255,255,255,0.1) inset;
            transition:background .2s, transform .15s, box-shadow .2s;
        }
        .btn-enter:hover {
            background:#388e3c;
            transform:translateY(-2px);
            box-shadow:0 8px 28px rgba(46,125,50,0.5);
        }
        .btn-enter:active { transform:translateY(0); }
        .btn-enter svg {
            width:18px;
            height:18px;
            transition:transform .2s;
        }
        .btn-enter:hover svg { transform:translateX(3px); }

        /* FOOTER */
        .page-footer {
            position:fixed;
            bottom:28px;
            left:50%;
            transform:translateX(-50%);
            font-size:0.72rem;
            color:rgba(255,255,255,0.2);
            font-weight:500;
            letter-spacing:.5px;
            z-index:1;
            white-space:nowrap;
        }
    </style>
</head>
<body>

<div class="glow-top"></div>
<div class="glow-bottom"></div>

<div class="center">
    <!-- App Icon -->
    <div class="app-icon">
        <div class="app-icon-inner">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                <line x1="6" y1="1" x2="6" y2="4"/>
                <line x1="10" y1="1" x2="10" y2="4"/>
                <line x1="14" y1="1" x2="14" y2="4"/>
            </svg>
        </div>
        <span class="app-icon-label">RE-FOOD</span>
    </div>

    <!-- Title -->
    <h1 class="main-title">RE-FOOD</h1>

    <!-- Subtitle -->
    <p class="subtitle">
        Platform pengelolaan makanan berkelanjutan.<br>
        Kurangi limbah, bagikan kebaikan.
    </p>

    <!-- CTA Button -->
    <a href="{{ route('admin.login') }}" class="btn-enter">
        Admin Login
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="5" y1="12" x2="19" y2="12"/>
            <polyline points="12 5 19 12 12 19"/>
        </svg>
    </a>
</div>

<div class="page-footer">© {{ date('Y') }} RE-FOOD. Admin Panel v1.0</div>

</body>
</html>