<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synister Library — Perpustakaan Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* ========== CSS DASAR (tidak banyak diubah) ========== */
        :root {
            --black:  #000000;
            --bone:   #EDEDED;
            --ash:    #666666;
            --ash-dk: #333333;
            --ash-lt: #999999;
            --surface: #0a0a0a;
            --line:   rgba(237,237,237,0.08);
            --line-md: rgba(237,237,237,0.15);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Libre Baskerville', serif;
            background: var(--black);
            color: var(--bone);
            overflow-x: hidden;
            cursor: default;
        }

        /* PAPER TEXTURE OVERLAY */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 1;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 35%, rgba(0,0,0,0.65) 100%);
            pointer-events: none;
            z-index: 0;
        }

        * { position: relative; z-index: 1; }

        /* NAVBAR */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 56px;
            background: rgba(0,0,0,0.88);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--line);
        }

        .nav-brand {
            font-family: 'Cinzel', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--bone);
            text-decoration: none;
            letter-spacing: 0.12em;
        }

        .nav-brand em { font-style: normal; color: var(--ash); }
        .nav-center { display: flex; gap: 40px; }
        .nav-link {
            font-family: 'Cinzel', serif;
            font-size: 0.65rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--ash);
            text-decoration: none;
            transition: color 0.2s;
        }
        .nav-link:hover { color: var(--bone); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .btn-ghost {
            padding: 8px 22px;
            border: 1px solid var(--ash-dk);
            background: transparent;
            color: var(--ash-lt);
            font-family: 'Cinzel', serif;
            font-size: 0.65rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-ghost:hover { border-color: var(--bone); color: var(--bone); }
        .btn-solid {
            padding: 8px 22px;
            border: 1px solid var(--bone);
            background: var(--bone);
            color: var(--black);
            font-family: 'Cinzel', serif;
            font-size: 0.65rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-solid:hover { background: transparent; color: var(--bone); }

        /* HERO SECTION */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 140px 48px 100px;
            overflow: hidden;
        }
        .hero-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid var(--line);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }
        .hero-ring:nth-child(1) { width: 500px; height: 500px; }
        .hero-ring:nth-child(2) { width: 700px; height: 700px; border-color: rgba(237,237,237,0.04); }
        .hero-ring:nth-child(3) { width: 900px; height: 900px; border-color: rgba(237,237,237,0.025); }
        .hero-candle {
            position: absolute;
            width: 2px; height: 2px;
            background: rgba(237,237,237,0.4);
            border-radius: 50%;
            animation: flicker 3s ease-in-out infinite;
        }
        .hero-candle:nth-child(4) { top: 28%; left: 18%; animation-delay: 0s; }
        .hero-candle:nth-child(5) { top: 60%; left: 12%; animation-delay: 1.1s; }
        .hero-candle:nth-child(6) { top: 35%; right: 16%; animation-delay: 0.7s; }
        .hero-candle:nth-child(7) { top: 68%; right: 20%; animation-delay: 1.9s; }
        @keyframes flicker {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.8); box-shadow: 0 0 6px rgba(237,237,237,0.3); }
        }
        .hero-eyebrow {
            font-family: 'Cinzel', serif;
            font-size: 0.62rem;
            letter-spacing: 0.4em;
            color: var(--ash);
            text-transform: uppercase;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 16px;
            animation: fadeIn 1s ease both;
        }
        .hero-eyebrow::before, .hero-eyebrow::after {
            content: '';
            width: 48px;
            height: 1px;
            background: var(--ash-dk);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(3rem, 7vw, 5.5rem);
            font-weight: 700;
            line-height: 1.05;
            letter-spacing: 0.06em;
            color: var(--bone);
            margin-bottom: 12px;
            animation: fadeIn 1s ease 0.15s both;
        }
        .hero-title-sub {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.1rem, 2.5vw, 1.7rem);
            font-weight: 400;
            font-style: italic;
            color: var(--ash);
            letter-spacing: 0.04em;
            margin-bottom: 36px;
            animation: fadeIn 1s ease 0.3s both;
        }
        .ornament {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin: 28px 0;
            animation: fadeIn 1s ease 0.4s both;
        }
        .ornament-line {
            width: 80px;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--ash-dk));
        }
        .ornament-line.r { background: linear-gradient(to left, transparent, var(--ash-dk)); }
        .ornament-glyph {
            font-family: 'Cinzel', serif;
            font-size: 0.75rem;
            color: var(--ash-dk);
            letter-spacing: 0.15em;
        }
        .hero-desc {
            font-family: 'Libre Baskerville', serif;
            font-size: 1rem;
            line-height: 1.9;
            color: var(--ash);
            max-width: 480px;
            margin: 0 auto 44px;
            animation: fadeIn 1s ease 0.45s both;
        }
        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeIn 1s ease 0.6s both;
        }
        .btn-hero-primary {
            padding: 14px 36px;
            border: 1px solid var(--bone);
            background: var(--bone);
            color: var(--black);
            font-family: 'Cinzel', serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.25s;
        }
        .btn-hero-primary:hover { background: transparent; color: var(--bone); }
        .btn-hero-secondary {
            padding: 14px 36px;
            border: 1px solid var(--ash-dk);
            background: transparent;
            color: var(--ash-lt);
            font-family: 'Cinzel', serif;
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.25s;
        }
        .btn-hero-secondary:hover { border-color: var(--bone); color: var(--bone); }
        .scroll-hint {
            position: absolute;
            bottom: 36px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            animation: fadeIn 1s ease 1s both;
        }
        .scroll-hint span {
            font-family: 'Cinzel', serif;
            font-size: 0.55rem;
            letter-spacing: 0.3em;
            color: var(--ash-dk);
            text-transform: uppercase;
        }
        .scroll-line {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, var(--ash-dk), transparent);
            animation: scrollPulse 2s ease-in-out infinite;
        }
        @keyframes scrollPulse {
            0%, 100% { opacity: 0.3; transform: scaleY(1); }
            50% { opacity: 1; transform: scaleY(1.1); }
        }

        /* STATS BAR */
        .stats-bar {
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            background: var(--surface);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }
        .stat-item {
            padding: 40px 28px;
            text-align: center;
            border-right: 1px solid var(--line);
        }
        .stat-item:last-child { border-right: none; }
        .stat-num {
            font-family: 'Cinzel', serif;
            font-size: 2.6rem;
            font-weight: 700;
            color: var(--bone);
            line-height: 1;
            margin-bottom: 10px;
        }
        .stat-sep {
            width: 24px;
            height: 1px;
            background: var(--ash-dk);
            margin: 0 auto 10px;
        }
        .stat-lbl {
            font-family: 'Cinzel', serif;
            font-size: 0.6rem;
            letter-spacing: 0.25em;
            color: var(--ash);
            text-transform: uppercase;
        }

        /* SECTION BUKU */
        .section { padding: 96px 64px; }
        .section-eyebrow {
            font-family: 'Cinzel', serif;
            font-size: 0.6rem;
            letter-spacing: 0.3em;
            color: var(--ash-dk);
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--bone);
            line-height: 1.25;
        }
        .section-title em { font-style: italic; color: var(--ash); }
        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 52px;
            padding-bottom: 28px;
            border-bottom: 1px solid var(--line);
        }
        .section-link {
            font-family: 'Cinzel', serif;
            font-size: 0.62rem;
            letter-spacing: 0.2em;
            color: var(--ash);
            text-decoration: none;
            text-transform: uppercase;
            border-bottom: 1px solid var(--ash-dk);
            padding-bottom: 2px;
            transition: all 0.2s;
        }
        .section-link:hover { color: var(--bone); border-bottom-color: var(--bone); }

        /* GRID BUKU - card proporsional */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 24px;
        }
        .book-card {
            background: var(--black);
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px -12px rgba(0,0,0,0.6);
            border-color: var(--ash-dk);
        }
        /* area cover dengan rasio 2:3 (potret buku) */
        .book-cover {
            position: relative;
            width: 100%;
            aspect-ratio: 2 / 3;
            background: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid var(--line);
        }
        .book-cover img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .book-cover i {
            font-size: 3rem;
            color: var(--ash-dk);
        }
        .book-info {
            padding: 12px 12px 14px;
        }
        .book-title {
            font-family: 'Playfair Display', serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--bone);
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .book-author {
            font-size: 0.7rem;
            color: var(--ash);
            font-style: italic;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .book-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            font-size: 0.6rem;
            font-family: 'Cinzel', serif;
            letter-spacing: 0.1em;
            border: 1px solid;
            border-radius: 20px;
        }
        .book-badge.tersedia {
            border-color: rgba(237,237,237,0.25);
            color: var(--ash-lt);
        }
        .book-badge.habis {
            border-color: rgba(102,102,102,0.2);
            color: var(--ash-dk);
        }

        /* CARA PAKAI */
        .how-section {
            background: var(--surface);
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            padding: 96px 64px;
        }
        .how-header { text-align: center; margin-bottom: 72px; }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            border: 1px solid var(--line);
        }
        .step-card {
            padding: 44px 32px;
            border-right: 1px solid var(--line);
            text-align: center;
        }
        .step-card:last-child { border-right: none; }
        .step-num-wrap {
            margin: 0 auto 24px;
            width: 52px; height: 52px;
            border: 1px solid var(--ash-dk);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .step-num-wrap::before, .step-num-wrap::after {
            content: '';
            position: absolute;
            width: 5px; height: 5px;
            background: var(--black);
            border: 1px solid var(--ash-dk);
            transform: rotate(45deg);
        }
        .step-num-wrap::before { top: -3px; left: 50%; transform: translateX(-50%) rotate(45deg); }
        .step-num-wrap::after  { bottom: -3px; left: 50%; transform: translateX(-50%) rotate(45deg); }
        .step-num {
            font-family: 'Cinzel', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--bone);
        }
        .step-title {
            font-family: 'Cinzel', serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--bone);
            margin-bottom: 12px;
        }
        .step-desc {
            font-family: 'Libre Baskerville', serif;
            font-size: 0.8rem;
            font-style: italic;
            color: var(--ash);
            line-height: 1.75;
        }

        /* QUOTE */
        .quote-banner {
            padding: 96px 64px;
            text-align: center;
            border-bottom: 1px solid var(--line);
        }
        .quote-text {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.4rem, 3vw, 2.2rem);
            font-style: italic;
            color: var(--bone);
            line-height: 1.6;
            max-width: 680px;
            margin: 0 auto 24px;
        }
        .quote-source {
            font-family: 'Cinzel', serif;
            font-size: 0.6rem;
            letter-spacing: 0.3em;
            color: var(--ash-dk);
            text-transform: uppercase;
        }

        /* FOOTER */
        footer {
            background: var(--black);
            border-top: 1px solid var(--line);
            padding: 48px 64px;
        }
        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-brand {
            font-family: 'Cinzel', serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--bone);
            letter-spacing: 0.15em;
        }
        .footer-brand em { font-style: normal; color: var(--ash-dk); }
        .footer-divider {
            width: 1px;
            height: 32px;
            background: var(--line-md);
        }
        .footer-links {
            display: flex;
            gap: 28px;
        }
        .footer-link {
            font-family: 'Cinzel', serif;
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            color: var(--ash-dk);
            text-decoration: none;
            text-transform: uppercase;
            transition: color 0.2s;
        }
        .footer-link:hover { color: var(--bone); }
        .footer-copy {
            font-family: 'Libre Baskerville', serif;
            font-size: 0.72rem;
            font-style: italic;
            color: var(--ash-dk);
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            nav { padding: 16px 24px; }
            .nav-center { display: none; }
            .hero { padding: 120px 24px 80px; }
            .hero-ring { display: none; }
            .stats-bar { grid-template-columns: repeat(2, 1fr); }
            .stat-item:nth-child(2) { border-right: none; }
            .section, .how-section, .quote-banner { padding: 64px 24px; }
            .section-header { flex-direction: column; align-items: flex-start; gap: 20px; }
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
            .step-card:nth-child(2) { border-right: none; }
            footer { padding: 36px 24px; }
            .footer-inner { flex-direction: column; gap: 24px; text-align: center; }
            .footer-divider { display: none; }
            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 16px;
            }
        }
        @media (max-width: 560px) {
            .stats-bar { grid-template-columns: 1fr; }
            .stat-item { border-right: none; border-bottom: 1px solid var(--line); }
            .stat-item:last-child { border-bottom: none; }
            .steps-grid { grid-template-columns: 1fr; }
            .step-card { border-right: none; border-bottom: 1px solid var(--line); }
            .step-card:last-child { border-bottom: none; }
            .books-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-title { font-size: 2.4rem; }
        }

        /* REVEAL ANIMATION */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .stat-num {
            display: inline-block;
            animation: gentlePop 0.5s cubic-bezier(0.34, 1.2, 0.64, 1) backwards;
        }
        @keyframes gentlePop {
            0% { transform: scale(0.92); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav>
    <a href="{{ route('home') }}" class="nav-brand">Synister <em>Library</em></a>
    <div class="nav-center">
        <a href="#koleksi" class="nav-link">Koleksi</a>
        <a href="#cara-pakai" class="nav-link">Panduan</a>
    </div>
    <div class="nav-actions">
        <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
        <a href="{{ route('register') }}" class="btn-solid">Daftar</a>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="hero-ring"></div>
    <div class="hero-ring"></div>
    <div class="hero-ring"></div>
    <div class="hero-candle"></div>
    <div class="hero-candle"></div>
    <div class="hero-candle"></div>
    <div class="hero-candle"></div>
    <div class="hero-eyebrow">Perpustakaan Sekolah Digital</div>
    <h1 class="hero-title">Synister Library</h1>
    <p class="hero-title-sub">Di mana setiap halaman menyimpan rahasia</p>
    <div class="ornament">
        <div class="ornament-line"></div>
        <div class="ornament-glyph">✦ &nbsp; ✦ &nbsp; ✦</div>
        <div class="ornament-line r"></div>
    </div>
    <p class="hero-desc">
        Memasuki ruang ini berarti membuka pintu menuju pengetahuan yang tak terbatas.
        Pinjam, baca, dan kembalikan — dari satu platform yang tenang dan teratur.
    </p>
    <div class="hero-actions">
        <a href="{{ route('register') }}" class="btn-hero-primary">
            <i class="bi bi-feather"></i> Daftar Sekarang
        </a>
        <a href="{{ route('login') }}" class="btn-hero-secondary">
            <i class="bi bi-key"></i> Sudah punya akun
        </a>
    </div>
    <div class="scroll-hint">
        <span>Gulir</span>
        <div class="scroll-line"></div>
    </div>
</section>

{{-- STATS BAR --}}
<div class="stats-bar reveal">
    <div class="stat-item">
        <div class="stat-num">{{ $totalBuku }}</div>
        <div class="stat-sep"></div>
        <div class="stat-lbl">Judul dalam Arsip</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">{{ $bukuTersedia }}</div>
        <div class="stat-sep"></div>
        <div class="stat-lbl">Siap Dipinjam</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">{{ $totalBuku - $bukuTersedia }}</div>
        <div class="stat-sep"></div>
        <div class="stat-lbl">Sedang Beredar</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">7</div>
        <div class="stat-sep"></div>
        <div class="stat-lbl">Hari Durasi Pinjam</div>
    </div>
</div>

{{-- BUKU TERBARU --}}
@if($bukuTerbaru->count())
<section class="section" id="koleksi">
    <div class="section-header reveal">
        <div>
            <div class="section-eyebrow">Koleksi</div>
            <div class="section-title">Arsip <em>Terkini</em></div>
        </div>
        <a href="{{ route('login') }}" class="section-link">
            Jelajahi semua &nbsp;<i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="books-grid">
        @foreach($bukuTerbaru as $index => $buku)
        <div class="book-card reveal" style="--order: {{ $index % 10 }};">
            <div class="book-cover">
                @if($buku->cover_image && \Storage::disk('public')->exists($buku->cover_image))
                    <img src="{{ \Storage::url($buku->cover_image) }}" alt="{{ $buku->judul_buku }}">
                @else
                    <i class="bi bi-book-half"></i>
                @endif
            </div>
            <div class="book-info">
                <div class="book-title">{{ $buku->judul_buku }}</div>
                <div class="book-author">{{ $buku->penulis }}</div>
                @if($buku->stok > 0)
                    <span class="book-badge tersedia"><i class="bi bi-circle"></i> Tersedia</span>
                @else
                    <span class="book-badge habis"><i class="bi bi-circle-fill"></i> Habis</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- QUOTE --}}
<div class="quote-banner reveal">
    <div class="ornament" style="margin-bottom:40px">
        <div class="ornament-line"></div>
        <div class="ornament-glyph">§</div>
        <div class="ornament-line r"></div>
    </div>
    <p class="quote-text">
        "Sebuah ruangan tanpa buku ibarat tubuh tanpa jiwa."
    </p>
    <div class="quote-source">— Marcus Tullius Cicero</div>
    <div class="ornament" style="margin-top:40px">
        <div class="ornament-line"></div>
        <div class="ornament-glyph">§</div>
        <div class="ornament-line r"></div>
    </div>
</div>

{{-- CARA PAKAI --}}
<section class="how-section" id="cara-pakai">
    <div class="how-header reveal">
        <div class="section-eyebrow" style="text-align:center;margin-bottom:12px">Panduan</div>
        <div class="section-title" style="text-align:center">Cara <em>Menggunakan</em></div>
    </div>
    <div class="steps-grid">
        <div class="step-card reveal">
            <div class="step-num-wrap">
                <div class="step-num">I</div>
            </div>
            <div class="step-title">Daftar Akun</div>
            <div class="step-desc">Buat akun dengan NIS dan data kelas. Pendaftaran cepat, mudah, dan tanpa kerumitan.</div>
        </div>
        <div class="step-card reveal">
            <div class="step-num-wrap">
                <div class="step-num">II</div>
            </div>
            <div class="step-title">Cari Buku</div>
            <div class="step-desc">Telusuri katalog berdasarkan judul, penulis, atau kategori yang diminati.</div>
        </div>
        <div class="step-card reveal">
            <div class="step-num-wrap">
                <div class="step-num">III</div>
            </div>
            <div class="step-title">Pinjam</div>
            <div class="step-desc">Klik tombol pinjam, buku langsung tercatat. Durasi tujuh hari per eksemplar.</div>
        </div>
        <div class="step-card reveal">
            <div class="step-num-wrap">
                <div class="step-num">IV</div>
            </div>
            <div class="step-title">Kembalikan</div>
            <div class="step-desc">Konfirmasi pengembalian lewat sistem. Tepat waktu — hindari catatan buruk.</div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer>
    <div class="footer-inner reveal">
        <div class="footer-brand">Synister <em>Library</em></div>
        <div class="footer-divider"></div>
        <div class="footer-links">
            <a href="{{ route('login') }}" class="footer-link">Masuk</a>
            <a href="{{ route('register') }}" class="footer-link">Daftar</a>
        </div>
        <div class="footer-divider"></div>
        <div class="footer-copy">&copy; {{ date('Y') }} — Perpustakaan Sekolah Digital</div>
    </div>
</footer>

{{-- SCROLL REVEAL SCRIPT --}}
<script>
    (function() {
        const revealElements = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: "0px 0px -20px 0px" });
        revealElements.forEach(el => observer.observe(el));

        const statNumbers = document.querySelectorAll('.stat-num');
        statNumbers.forEach((num, idx) => {
            num.style.animationDelay = (idx * 0.1) + 's';
        });
    })();
</script>

</body>
</html>
