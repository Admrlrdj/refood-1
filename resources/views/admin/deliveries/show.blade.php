<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Detail - RE-FOOD Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/refood-theme.css') }}">

    <!-- LEAFLET CSS UNTUK MAPS & ROUTING -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

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

        .content {
            padding: 24px 28px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
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
            margin-bottom: 10px;
        }

        .btn-back:hover {
            color: var(--text);
        }

        .table-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            padding: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-box {
            background: #f8f9fb;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
        }

        .info-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 8px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
        }

        .info-sub {
            font-size: 0.8rem;
            color: var(--muted);
            font-weight: 500;
        }

        /* Sembunyikan Kotak Instruksi Routing Bawaan agar map terlihat bersih seperti desain */
        .leaflet-routing-container {
            display: none !important;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .badge-status.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-status.process {
            background: #e0f2fe;
            color: #2563eb;
        }

        .badge-status.delivered {
            background: #dcfce7;
            color: #16a34a;
        }
    </style>
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
            <a href="{{ route('admin.deliveries.index') }}" class="nav-item active"><svg viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.2">
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
            <a href="{{ route('admin.volunteers.index') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10" />
                    <path
                        d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32" />
                </svg>Volunteers</a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <h1 class="topbar-title">Delivery Detail</h1>
        </header>

        <div class="content">
            <a href="{{ route('admin.deliveries.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18"
                    height="18">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Daftar Deliveries
            </a>

            @php
                $donorName = $delivery->donor->restaurant_name ?? ($delivery->donor->name ?? 'Anonim');
                $receiverName = $delivery->receiver->foundation_name ?? ($delivery->receiver->name ?? 'Penerima');
                $volunteerName = $delivery->volunteer->name ?? 'Menunggu Kurir';
                $statusMap = [
                    'pending' => ['class' => 'pending', 'label' => 'Mencari Kurir'],
                    'process' => ['class' => 'process', 'label' => 'Dalam Perjalanan'],
                    'delivered' => ['class' => 'delivered', 'label' => 'Selesai / Tiba'],
                ];
                $statusObj = $statusMap[$delivery->status] ?? [
                    'class' => 'pending',
                    'label' => ucfirst($delivery->status),
                ];
            @endphp

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <h2 style="font-size: 1.5rem; font-weight: 800;">ID: #{{ strtoupper(substr($delivery->_id, -8)) }}</h2>
                <span class="badge-status {{ $statusObj['class'] }}">
                    <span
                        style="width: 8px; height: 8px; border-radius: 50%; background: currentColor; display: inline-block;"></span>
                    {{ $statusObj['label'] }}
                </span>
            </div>

            <!-- INFO PIHAK TERLIBAT -->
            <div class="info-grid">
                <!-- Donor -->
                <div class="info-box">
                    <div class="info-title">📍 Lokasi Jemput (Donatur)</div>
                    <div class="info-value">{{ $donorName }}</div>
                    <div class="info-sub" style="margin-bottom: 8px;">PIC: {{ $delivery->donor->name ?? '-' }}
                        ({{ $delivery->donor->phone ?? '-' }})</div>
                    <div class="info-sub">📋 Makanan: <strong
                            style="color:var(--text);">{{ $delivery->food->name ?? 'N/A' }}
                            ({{ $delivery->food->portion ?? 0 }} Porsi)</strong></div>
                </div>

                <!-- Kurir -->
                <div class="info-box">
                    <div class="info-title">🛵 Relawan / Kurir</div>
                    <div class="info-value">{{ $volunteerName }}</div>
                    <div class="info-sub" style="margin-bottom: 8px;">WA:
                        {{ $delivery->volunteer->phone ?? 'Belum ada data' }}</div>
                    @if (isset($delivery->volunteer))
                        <div class="info-sub">Kendaraan: <strong
                                style="color:var(--text);">{{ $delivery->volunteer->vehicle_type ?? '-' }}
                                ({{ $delivery->volunteer->vehicle_plate ?? '-' }})</strong></div>
                    @else
                        <div class="info-sub">Kendaraan: -</div>
                    @endif
                </div>

                <!-- Receiver -->
                <div class="info-box">
                    <div class="info-title">🎯 Tujuan (Yayasan/Panti)</div>
                    <div class="info-value">{{ $receiverName }}</div>
                    <div class="info-sub" style="margin-bottom: 8px;">PIC:
                        {{ $delivery->receiver->pic_name ?? ($delivery->receiver->name ?? '-') }}
                        ({{ $delivery->receiver->phone ?? '-' }})</div>
                    <div class="info-sub">Kebutuhan: <strong
                            style="color:#ea580c;">{{ $delivery->receiver->need_level ?? 0 }}%</strong></div>
                </div>
            </div>

            <!-- MAP CARD (Sesuai Referensi Gambar) -->
            <div class="table-card" style="padding: 28px;">
                <h3 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 4px; color: var(--text);">Map</h3>
                <p style="font-size: 1rem; color: var(--text); font-weight: 500; margin-bottom: 24px;">
                    {{ $donorName }} to {{ $receiverName }}
                </p>

                <!-- Container Map -->
                <div id="deliveryMap"
                    style="height: 450px; border-radius: 12px; border: 1px solid var(--border); z-index: 1;"></div>
            </div>
        </div>
    </div>

    <!-- LEAFLET JS & ROUTING MACHINE -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil koordinat dengan fallback ke default lokasi Bogor jika database kosong
            var donorLat = {{ $delivery->pickup_latitude ?? ($delivery->donor->last_latitude ?? -6.595038) }};
            var donorLng = {{ $delivery->pickup_longitude ?? ($delivery->donor->last_longitude ?? 106.816635) }};

            var receiverLat = {{ $delivery->dropoff_latitude ?? ($delivery->receiver->last_latitude ?? -6.6) }};
            var receiverLng =
                {{ $delivery->dropoff_longitude ?? ($delivery->receiver->last_longitude ?? 106.82) }};

            // Inisialisasi Map
            var map = L.map('deliveryMap', {
                scrollWheelZoom: false // Mencegah ter-scroll tanpa sengaja
            }).setView([donorLat, donorLng], 13);

            // Gaya Peta (Google Maps-like style ala OpenStreetMap)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
            }).addTo(map);

            // Fungsi untuk menggambar jalan / Route merah
            L.Routing.control({
                waypoints: [
                    L.latLng(donorLat, donorLng),
                    L.latLng(receiverLat, receiverLng)
                ],
                lineOptions: {
                    styles: [{
                        color: '#f87171',
                        opacity: 1,
                        weight: 6
                    }] // Warna merah terang sesuai gambar
                },
                createMarker: function(i, wp, n) {
                    var isStart = (i === 0);
                    // Pin Merah untuk start, Pin Biru untuk End
                    var iconUrl = isStart ?
                        'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png' :
                        'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';

                    var popupText = isStart ?
                        '<b>📍 Lokasi Jemput</b><br>{{ addslashes($donorName) }}' :
                        '<b>🎯 Lokasi Antar</b><br>{{ addslashes($receiverName) }}';

                    return L.marker(wp.latLng, {
                        icon: L.icon({
                            iconUrl: iconUrl,
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).bindPopup(popupText);
                },
                show: false, // Menyembunyikan text arah jalan agar UI map tetap bersih
                addWaypoints: false,
                routeWhileDragging: false,
                fitSelectedRoutes: true
            }).addTo(map);

            // (Opsional) Tambahkan Live Marker Kurir Jika ada
            var volLat = {{ $delivery->volunteer->last_latitude ?? 'null' }};
            var volLng = {{ $delivery->volunteer->last_longitude ?? 'null' }};

            if (volLat !== null && volLng !== null) {
                var volIcon = L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png', // Icon motor
                    iconSize: [45, 45],
                    iconAnchor: [22, 22],
                    popupAnchor: [0, -20]
                });
                L.marker([volLat, volLng], {
                        icon: volIcon
                    }).addTo(map).bindPopup('<b>🛵 Posisi Kurir Saat Ini</b><br>{{ addslashes($volunteerName) }}')
                    .openPopup();
            }
        });
    </script>
</body>

</html>
