<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Synister Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg:      #F5F5F5;
            --card:    #FFFFFF;
            --text:    #111111;
            --muted:   #888888;
            --accent:  #2E2E2E;
            --border:  rgba(0,0,0,0.08);
            --shadow:  0 4px 24px rgba(0,0,0,0.08);
            --radius:  10px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── LEFT PANEL ── */
        .left {
            flex: 1;
            background: #111111;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }

        .left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 52px 52px;
            pointer-events: none;
        }

        /* Corner brackets */
        .corner {
            position: absolute;
            width: 52px; height: 52px;
            transition: all 0.3s ease;
            z-index: 15;
        }
        .corner.tl { top: 36px; left: 36px; border-top: 1px solid rgba(255,255,255,0.15); border-left: 1px solid rgba(255,255,255,0.15); }
        .corner.tr { top: 36px; right: 36px; border-top: 1px solid rgba(255,255,255,0.15); border-right: 1px solid rgba(255,255,255,0.15); }
        .corner.bl { bottom: 36px; left: 36px; border-bottom: 1px solid rgba(255,255,255,0.15); border-left: 1px solid rgba(255,255,255,0.15); }
        .corner.br { bottom: 36px; right: 36px; border-bottom: 1px solid rgba(255,255,255,0.15); border-right: 1px solid rgba(255,255,255,0.15); }

        /* Fireflies container - lebih terang */
        .fireflies {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 5;
        }
        .firefly {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #ffdd88;
            border-radius: 50%;
            box-shadow: 0 0 12px #ffcc55, 0 0 4px #ffaa33;
            animation: floatFirefly 10s infinite alternate ease-in-out;
            opacity: 0;
            will-change: transform, opacity;
        }
        @keyframes floatFirefly {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0;
            }
            15% {
                opacity: 0.9;
            }
            85% {
                opacity: 0.8;
            }
            100% {
                transform: translate(var(--dx, 40px), var(--dy, -30px)) scale(1.4);
                opacity: 0;
            }
        }

        /* Efek hover corner */
        .left:hover .corner {
            border-color: rgba(255,255,255,0.35);
        }

        .left-inner {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 300px;
            animation: fadeSlideUp 0.9s ease both;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .left-icon {
            font-size: 2.6rem;
            color: #DDDDDD;
            display: block;
            margin-bottom: 24px;
            animation: softGlow 3s infinite alternate;
        }

        @keyframes softGlow {
            0% { text-shadow: 0 0 0px rgba(255,255,255,0); }
            100% { text-shadow: 0 0 12px rgba(255,220,100,0.5); }
        }

        .left-brand {
            font-family: 'Cinzel', serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: #EDEDED;
            letter-spacing: 0.1em;
            margin-bottom: 6px;
        }

        .left-tag {
            font-family: 'Cinzel', serif;
            font-size: 0.55rem;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 32px;
        }

        .left-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 32px;
        }
        .left-divider::before,
        .left-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }
        .left-divider span {
            font-size: 0.55rem;
            color: #5a5a5a;
            letter-spacing: 0.15em;
            animation: pulseOpacity 3s infinite;
        }
        @keyframes pulseOpacity {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .left-desc {
            font-size: 0.8rem;
            color: #6a6a6a;
            line-height: 1.9;
        }

        .left-quote {
            position: absolute;
            bottom: 52px;
            left: 0; right: 0;
            text-align: center;
            z-index: 10;
            padding: 0 48px;
            animation: fadeSlideUp 1s ease 0.2s both;
        }
        .left-quote p {
            font-family: 'Playfair Display', serif;
            font-size: 0.75rem;
            font-style: italic;
            color: #4e4e4e;
            line-height: 1.7;
            transition: color 0.3s;
        }
        .left-quote p:hover {
            color: #8a8a8a;
        }

        /* ── RIGHT PANEL ── */
        .right {
            width: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--bg);
        }

        .form-box {
            width: 100%;
            max-width: 360px;
            animation: fadeSlideUp 0.8s ease both;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Cinzel', serif;
            font-size: 0.58rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--muted);
            text-decoration: none;
            margin-bottom: 28px;
            transition: all 0.25s ease;
        }
        .back-link:hover {
            color: var(--text);
            transform: translateX(-4px);
        }

        .mobile-brand {
            display: none;
            text-align: center;
            margin-bottom: 32px;
        }
        .mobile-brand i { font-size: 1.8rem; color: var(--accent); display: block; margin-bottom: 10px; }
        .mobile-brand h1 { font-family: 'Cinzel', serif; font-size: 1.1rem; font-weight: 700; color: var(--text); letter-spacing: 0.1em; }

        /* Card */
        .login-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 40px 36px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.12);
        }

        .card-header {
            margin-bottom: 28px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 5px;
        }

        .card-sub {
            font-size: 0.79rem;
            color: var(--muted);
        }

        /* Form */
        .fg { margin-bottom: 16px; }

        .flbl {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 7px;
            transition: color 0.2s;
        }

        .fi { position: relative; }

        .fi i.ico {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #CCCCCC;
            font-size: 0.85rem;
            pointer-events: none;
            transition: all 0.25s ease;
        }

        .fi input {
            width: 100%;
            background: #FAFAFA;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 11px 14px 11px 38px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            color: var(--text);
            transition: all 0.25s ease;
            outline: none;
        }

        .fi input::placeholder { color: #CCCCCC; }

        .fi input:focus {
            border-color: #999;
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.04);
            transform: scale(1.01);
        }

        .fi:focus-within i.ico {
            color: var(--accent);
            transform: translateY(-50%) scale(1.05);
        }

        .ferr {
            font-size: 0.71rem;
            color: #c0392b;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Alerts */
        .alert {
            border-radius: var(--radius);
            padding: 11px 14px;
            font-size: 0.79rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            animation: shakeAlert 0.4s ease;
        }
        @keyframes shakeAlert {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }
        .alert-error  { background: #FEF2F2; border: 1px solid rgba(192,57,43,0.12); color: #c0392b; }
        .alert-success{ background: #F0FDF4; border: 1px solid rgba(39,174,96,0.18); color: #1a7a40; }

        /* Button */
        .btn-submit {
            width: 100%;
            background: var(--accent);
            color: #FFFFFF;
            border: none;
            border-radius: var(--radius);
            padding: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }
        .btn-submit:active::after {
            width: 200px;
            height: 200px;
        }
        .btn-submit:hover {
            background: #000000;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .btn-submit:active { transform: translateY(0); }

        .card-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.79rem;
            color: var(--muted);
        }
        .card-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-block;
        }
        .card-footer a:hover {
            color: #000;
            transform: translateX(3px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .left { display: none; }
            .right { width: 100%; padding: 32px 20px; }
            .mobile-brand { display: block; }
        }
    </style>
</head>
<body>

{{-- LEFT PANEL dengan efek kunang-kunang yang lebih terang --}}
<div class="left">
    <div class="corner tl"></div>
    <div class="corner tr"></div>
    <div class="corner bl"></div>
    <div class="corner br"></div>

    <!-- Container untuk kunang-kunang -->
    <div class="fireflies" id="firefliesContainer"></div>

    <div class="left-inner">
        <i class="bi bi-book-half left-icon"></i>
        <div class="left-brand">Synister Library</div>
        <div class="left-tag">Digital School Library</div>
        <div class="left-divider"><span>✦</span></div>
        <p class="left-desc">
            Sistem manajemen perpustakaan digital.<br>
            Pinjam buku, kelola koleksi, dan pantau<br>
            riwayat dari satu platform.
        </p>
    </div>

    <div class="left-quote">
        <p>"A reader lives a thousand lives before he dies."</p>
    </div>
</div>

{{-- RIGHT PANEL --}}
<div class="right">
    <div class="form-box">

        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Beranda
        </a>

        <div class="mobile-brand">
            <i class="bi bi-book-half"></i>
            <h1>Synister Library</h1>
        </div>

        <div class="login-card">
            <div class="card-header">
                <div class="card-title">Welcome Back</div>
                <div class="card-sub">Masuk ke akun Synister Library kamu</div>
            </div>

            @if(session('error'))
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="fg">
                    <label class="flbl">Username</label>
                    <div class="fi">
                        <i class="bi bi-person ico"></i>
                        <input type="text" name="username" placeholder="Masukkan username"
                               value="{{ old('username') }}" autofocus>
                    </div>
                    @error('username')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="fg">
                    <label class="flbl">Password</label>
                    <div class="fi">
                        <i class="bi bi-lock ico"></i>
                        <input type="password" name="password" placeholder="Masukkan password">
                    </div>
                    @error('password')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Masuk <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="card-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai Siswa</a>
            </div>
        </div>

    </div>
</div>

<script>
    (function createFireflies() {
        const container = document.getElementById('firefliesContainer');
        if (!container) return;
        // Hapus jika sudah ada isinya (untuk mencegah duplikat)
        container.innerHTML = '';
        const numberOfFireflies = 50; // lebih banyak
        const leftPanel = document.querySelector('.left');
        if (!leftPanel) return;

        for (let i = 0; i < numberOfFireflies; i++) {
            const firefly = document.createElement('div');
            firefly.classList.add('firefly');
            // Posisi acak dalam panel kiri
            const posX = Math.random() * 100;
            const posY = Math.random() * 100;
            firefly.style.left = posX + '%';
            firefly.style.top = posY + '%';
            // Durasi animasi acak antara 6s - 14s
            const duration = 6 + Math.random() * 10;
            firefly.style.animationDuration = duration + 's';
            // Delay acak antara 0 - 8s
            const delay = Math.random() * 8;
            firefly.style.animationDelay = delay + 's';
            // Jarak pergerakan lebih jauh
            const dx = (Math.random() - 0.5) * 180;
            const dy = (Math.random() - 0.5) * 120;
            firefly.style.setProperty('--dx', dx + 'px');
            firefly.style.setProperty('--dy', dy + 'px');
            // Ukuran variasi (2px - 6px)
            const size = 2 + Math.random() * 5;
            firefly.style.width = size + 'px';
            firefly.style.height = size + 'px';
            // Warna lebih terang: kuning, oranye, sedikit hijau
            const r = 255;
            const g = 180 + Math.floor(Math.random() * 75); // 180-255
            const b = 80 + Math.floor(Math.random() * 70);  // 80-150
            firefly.style.background = `rgba(${r}, ${g}, ${b}, 0.9)`;
            firefly.style.boxShadow = `0 0 ${8 + Math.random() * 8}px rgba(255, 200, 80, 0.8)`;
            container.appendChild(firefly);
        }
    })();

    // Animasi tambahan: efek hover pada input fields lebih hidup
    const inputs = document.querySelectorAll('.fi input');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.querySelector('.ico')?.style.transform = 'translateY(-50%) scale(1.05)';
        });
        input.addEventListener('blur', () => {
            input.parentElement.querySelector('.ico')?.style.transform = 'translateY(-50%) scale(1)';
        });
    });
</script>
</body>
</html>
