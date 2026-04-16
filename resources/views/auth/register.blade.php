<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Synister Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg:     #F5F5F5;
            --card:   #FFFFFF;
            --text:   #111111;
            --muted:  #888888;
            --accent: #2E2E2E;
            --border: rgba(0,0,0,0.08);
            --shadow: 0 4px 24px rgba(0,0,0,0.08);
            --radius: 10px;
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
        }

        .corner {
            position: absolute;
            width: 52px; height: 52px;
        }
        .corner.tl { top: 36px; left: 36px; border-top: 1px solid rgba(255,255,255,0.1); border-left: 1px solid rgba(255,255,255,0.1); }
        .corner.tr { top: 36px; right: 36px; border-top: 1px solid rgba(255,255,255,0.1); border-right: 1px solid rgba(255,255,255,0.1); }
        .corner.bl { bottom: 36px; left: 36px; border-bottom: 1px solid rgba(255,255,255,0.1); border-left: 1px solid rgba(255,255,255,0.1); }
        .corner.br { bottom: 36px; right: 36px; border-bottom: 1px solid rgba(255,255,255,0.1); border-right: 1px solid rgba(255,255,255,0.1); }

        .left-inner {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 300px;
        }

        .left-icon {
            font-size: 2.6rem;
            color: #DDDDDD;
            display: block;
            margin-bottom: 24px;
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
            color: #444;
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
            background: rgba(255,255,255,0.07);
        }
        .left-divider span {
            font-size: 0.55rem;
            color: #3a3a3a;
            letter-spacing: 0.15em;
        }

        .left-desc {
            font-size: 0.8rem;
            color: #4a4a4a;
            line-height: 1.9;
        }

        .left-quote {
            position: absolute;
            bottom: 52px;
            left: 0; right: 0;
            text-align: center;
            z-index: 1;
            padding: 0 48px;
        }
        .left-quote p {
            font-family: 'Playfair Display', serif;
            font-size: 0.75rem;
            font-style: italic;
            color: #2e2e2e;
            line-height: 1.7;
        }

        /* ── RIGHT PANEL ── */
        .right {
            width: 520px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--bg);
            overflow-y: auto;
        }

        .form-box {
            width: 100%;
            max-width: 400px;
            padding: 20px 0;
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
            transition: color 0.25s ease;
        }
        .back-link:hover { color: var(--text); }

        .mobile-brand {
            display: none;
            text-align: center;
            margin-bottom: 32px;
        }
        .mobile-brand i { font-size: 1.8rem; color: var(--accent); display: block; margin-bottom: 10px; }
        .mobile-brand h1 { font-family: 'Cinzel', serif; font-size: 1.1rem; font-weight: 700; color: var(--text); letter-spacing: 0.1em; }

        /* Card */
        .reg-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 40px 36px;
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

        /* Info box */
        .info-box {
            background: #F7F7F7;
            border: 1px solid var(--border);
            border-left: 3px solid var(--accent);
            border-radius: var(--radius);
            padding: 11px 14px;
            font-size: 0.77rem;
            color: #555;
            display: flex;
            gap: 9px;
            align-items: flex-start;
            margin-bottom: 22px;
            line-height: 1.65;
        }
        .info-box i { color: var(--accent); flex-shrink: 0; margin-top: 1px; }
        .info-box strong { color: var(--text); font-weight: 600; }

        /* Section label */
        .field-section {
            font-family: 'Cinzel', serif;
            font-size: 0.58rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #BBBBBB;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .field-section::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Form grid */
        .row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .fg { margin-bottom: 14px; }

        .flbl {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 7px;
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
            transition: color 0.25s ease;
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
        }
        .fi:focus-within i.ico { color: var(--accent); }

        .ferr {
            font-size: 0.71rem;
            color: #c0392b;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .divider-line {
            height: 1px;
            background: var(--border);
            margin: 18px 0;
        }

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
            transition: color 0.2s;
        }
        .card-footer a:hover { color: #000; }

        /* Responsive */
        @media (max-width: 900px) {
            .left { display: none; }
            .right { width: 100%; padding: 32px 20px; }
            .mobile-brand { display: block; }
        }
        @media (max-width: 480px) {
            .row2 { grid-template-columns: 1fr; }
            .reg-card { padding: 28px 22px; }
        }
    </style>
</head>
<body>

{{-- LEFT PANEL --}}
<div class="left">
    <div class="corner tl"></div>
    <div class="corner tr"></div>
    <div class="corner bl"></div>
    <div class="corner br"></div>

    <div class="left-inner">
        <i class="bi bi-book-half left-icon"></i>
        <div class="left-brand">Synister Library</div>
        <div class="left-tag">Digital School Library</div>
        <div class="left-divider"><span>✦</span></div>
        <p class="left-desc">
            Bergabunglah dengan komunitas pembaca.<br>
            Akses ribuan koleksi buku dan kelola<br>
            peminjamanmu dengan mudah.
        </p>
    </div>

    <div class="left-quote">
        <p>"Not all those who wander are lost,<br>but all who read are found."</p>
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

        <div class="reg-card">
            <div class="card-header">
                <div class="card-title">Create an Account</div>
                <div class="card-sub">Isi data diri kamu untuk membuat akun siswa</div>
            </div>

            <div class="info-box">
                <i class="bi bi-info-circle"></i>
                <span>Akun ini akan mendapatkan role <strong>Siswa</strong>. Gunakan NIS yang sesuai kartu pelajarmu.</span>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="field-section">Data Diri</div>

                <div class="fg">
                    <label class="flbl">Nama Lengkap</label>
                    <div class="fi">
                        <i class="bi bi-person ico"></i>
                        <input type="text" name="name" placeholder="Nama lengkap siswa"
                               value="{{ old('name') }}" autofocus>
                    </div>
                    @error('name')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="row2">
                    <div class="fg">
                        <label class="flbl">NIS</label>
                        <div class="fi">
                            <i class="bi bi-card-text ico"></i>
                            <input type="text" name="no_induk" placeholder="2024001"
                                   value="{{ old('no_induk') }}">
                        </div>
                        @error('no_induk')
                        <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fg">
                        <label class="flbl">Kelas</label>
                        <div class="fi">
                            <i class="bi bi-mortarboard ico"></i>
                            <input type="text" name="kelas" placeholder="XII RPL 1"
                                   value="{{ old('kelas') }}">
                        </div>
                        @error('kelas')
                        <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="fg">
                    <label class="flbl">Username</label>
                    <div class="fi">
                        <i class="bi bi-at ico"></i>
                        <input type="text" name="username" placeholder="Buat username unik"
                               value="{{ old('username') }}">
                    </div>
                    @error('username')
                    <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="divider-line"></div>

                <div class="field-section">Keamanan</div>

                <div class="row2">
                    <div class="fg">
                        <label class="flbl">Password</label>
                        <div class="fi">
                            <i class="bi bi-lock ico"></i>
                            <input type="password" name="password" placeholder="Min. 6 karakter">
                        </div>
                        @error('password')
                        <div class="ferr"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fg">
                        <label class="flbl">Konfirmasi</label>
                        <div class="fi">
                            <i class="bi bi-lock-fill ico"></i>
                            <input type="password" name="password_confirmation" placeholder="Ulangi">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Buat Akun <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="card-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>

    </div>
</div>

</body>
</html>
