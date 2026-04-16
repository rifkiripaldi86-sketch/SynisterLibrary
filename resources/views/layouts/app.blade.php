<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Synister Library')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ─────────────────────────────
           DESIGN SYSTEM
        ───────────────────────────── */
        :root {
            --bg:        #F7F7F7;
            --card:      #FFFFFF;
            --text:      #111111;
            --muted:     #888888;
            --accent:    #2E2E2E;
            --border:    rgba(0,0,0,0.08);
            --shadow:    0 4px 12px rgba(0,0,0,0.08);
            --radius:    10px;

            /* Sidebar dark - DIPERBAIKI: warna lebih terang */
            --sb-bg:     #1A1A1A;
            --sb-hover:  rgba(255,255,255,0.08);
            --sb-active: rgba(255,255,255,0.12);
            --sb-border: rgba(255,255,255,0.1);
            --sb-muted:  #9A9A9A;      /* lebih terang dari sebelumnya #555 */
            --sb-text:   #E0E0E0;      /* lebih terang dari #AAAAAA */
            --sb-w:      248px;

            /* Status */
            --green:  #2d7d46;
            --green-bg: rgba(45,125,70,0.1);
            --red:    #b83232;
            --red-bg: rgba(184,50,50,0.1);
            --blue:   #2a5fa5;
            --blue-bg:rgba(42,95,165,0.1);
            --amber:  #8a6d1a;
            --amber-bg:rgba(138,109,26,0.1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            margin: 0;
        }

        /* ─────────────────────────────
           SIDEBAR - TEKS LEBIH TERANG
        ───────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sb-w);
            height: 100vh;
            background: var(--sb-bg);
            border-right: 1px solid var(--sb-border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        /* Subtle grid texture on sidebar */
        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* Brand */
        .sidebar-brand {
            padding: 24px 20px 18px;
            border-bottom: 1px solid var(--sb-border);
            flex-shrink: 0;
            position: relative;
        }

        .brand-icon {
            font-size: 1.1rem;
            color: #AAAAAA;  /* cukup terang */
            display: block;
            margin-bottom: 8px;
        }

        .brand-name {
            font-family: 'Cinzel', serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #F5F5F5;  /* lebih putih */
            letter-spacing: 0.08em;
            display: block;
            margin-bottom: 3px;
        }

        .brand-sub {
            font-family: 'Cinzel', serif;
            font-size: 0.5rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: #BBBBBB;  /* lebih terang */
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
        }

        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 2px; }

        .nav-sec {
            font-family: 'Cinzel', serif;
            font-size: 0.5rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: #AAAAAA;  /* lebih terang */
            padding: 16px 20px 6px;
        }

        .sl {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: #D0D0D0;  /* lebih terang dari sebelumnya */
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 400;
            border-left: 2px solid transparent;
            transition: all 0.2s ease;
            position: relative;
        }

        .sl i {
            font-size: 0.88rem;
            flex-shrink: 0;
            opacity: 0.85;  /* lebih terang */
            color: #CCCCCC;
        }

        .sl:hover {
            color: #FFFFFF;
            background: var(--sb-hover);
        }

        .sl.active {
            color: #FFFFFF;
            background: var(--sb-active);
            border-left-color: #FFFFFF;
            font-weight: 500;
        }

        .sl.active i {
            opacity: 1;
            color: #FFFFFF;
        }

        .notif-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 17px;
            height: 17px;
            padding: 0 5px;
            background: var(--red);
            color: #fff;
            border-radius: 9px;
            font-size: 0.58rem;
            font-weight: 700;
            margin-left: auto;
        }

        /* Sidebar footer */
        .sidebar-foot {
            padding: 16px 20px;
            border-top: 1px solid var(--sb-border);
            flex-shrink: 0;
            position: relative;
        }

        .u-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #333;
            border: 1px solid #555;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
            color: #DDDDDD;  /* lebih terang */
            flex-shrink: 0;
            font-family: 'Cinzel', serif;
        }

        .u-name {
            font-size: 0.78rem;
            font-weight: 500;
            color: #EEEEEE;  /* lebih putih */
        }
        .u-role {
            font-size: 0.65rem;
            color: #AAAAAA;  /* lebih terang */
            text-transform: capitalize;
            margin-top: 1px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: var(--radius);
            color: #CCCCCC;
            padding: 8px 12px;
            font-size: 0.78rem;
            margin-top: 12px;
            cursor: pointer;
            transition: all 0.25s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-logout:hover {
            background: rgba(184,50,50,0.15);
            border-color: rgba(184,50,50,0.3);
            color: #ff8888;
        }

        /* ─────────────────────────────
           MAIN AREA
        ───────────────────────────── */
        .main {
            margin-left: var(--sb-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 0 var(--border);
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        /* Search */
        .srch { position: relative; }

        .srch input {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            padding: 7px 12px 7px 32px;
            font-size: 0.8rem;
            width: 210px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.25s ease;
            outline: none;
        }

        .srch input:focus {
            border-color: var(--accent);
            background: var(--card);
            box-shadow: 0 0 0 3px rgba(0,0,0,0.04);
            width: 240px;
        }

        .srch input::placeholder { color: #CCCCCC; }

        .srch i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #CCCCCC;
            font-size: 0.78rem;
            pointer-events: none;
        }

        /* Bell */
        .topbar-bell {
            position: relative;
            color: var(--muted);
            text-decoration: none;
            padding: 7px 9px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            border: 1px solid var(--border);
            background: var(--card);
            transition: all 0.2s;
        }

        .topbar-bell:hover {
            background: var(--bg);
            color: var(--text);
            border-color: rgba(0,0,0,0.15);
        }

        .topbar-bell .dot {
            position: absolute;
            top: 5px; right: 5px;
            width: 7px; height: 7px;
            background: var(--red);
            border-radius: 50%;
            border: 1.5px solid var(--card);
        }

        /* Mobile menu button */
        .btn-menu {
            display: none;
            align-items: center;
            justify-content: center;
            width: 34px; height: 34px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            cursor: pointer;
            font-size: 1rem;
        }

        /* Page body */
        .page-body { padding: 24px 28px; flex: 1; }

        /* ─────────────────────────────
           CARDS
        ───────────────────────────── */
        .card-d {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .card-d .cd-head {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-d .cd-head i { color: var(--muted); font-size: 0.88rem; }

        .card-d .cd-body { padding: 20px; }

        /* Stat cards */
        .stat-c {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.25s ease;
        }

        .stat-c:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            border-color: rgba(0,0,0,0.12);
        }

        .stat-ic {
            width: 44px; height: 44px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            border: 1px solid var(--border);
        }

        .ic-a { background: var(--amber-bg);  color: var(--amber); }
        .ic-b { background: var(--blue-bg);   color: var(--blue); }
        .ic-g { background: var(--green-bg);  color: var(--green); }
        .ic-r { background: var(--red-bg);    color: var(--red); }

        .stat-v {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1;
        }

        .stat-l {
            font-size: 0.73rem;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ─────────────────────────────
           TABLE
        ───────────────────────────── */
        .tbl {
            width: 100%;
            color: var(--text);
            --bs-table-bg: transparent;
        }

        .tbl thead th {
            color: var(--muted);
            font-size: 0.67rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 600;
            border-bottom: 1px solid var(--border);
            padding: 10px 14px;
            white-space: nowrap;
            background: #FAFAFA;
        }

        .tbl tbody td {
            border-bottom: 1px solid var(--border);
            padding: 12px 14px;
            font-size: 0.84rem;
            vertical-align: middle;
            color: var(--text);
        }

        .tbl tbody tr:last-child td { border-bottom: none; }
        .tbl tbody tr:hover td { background: #FAFAFA; }

        /* ─────────────────────────────
           BADGES
        ───────────────────────────── */
        .bd {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .bd-a { background: var(--amber-bg); color: var(--amber); }
        .bd-g { background: var(--green-bg); color: var(--green); }
        .bd-r { background: var(--red-bg);   color: var(--red); }
        .bd-b { background: var(--blue-bg);  color: var(--blue); }

        /* ─────────────────────────────
           BUTTONS
        ───────────────────────────── */
        .btn-A {
            background: var(--accent);
            color: #FFFFFF;
            border: none;
            font-weight: 600;
            border-radius: var(--radius);
            padding: 8px 18px;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-A:hover {
            background: #111;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .btn-oA {
            border: 1px solid rgba(0,0,0,0.15);
            color: var(--accent);
            background: transparent;
            border-radius: var(--radius);
            padding: 7px 15px;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-oA:hover { background: var(--accent); color: #fff; }

        .btn-G {
            background: var(--bg);
            color: var(--muted);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 7px 14px;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-G:hover { background: #EEEEEE; color: var(--text); border-color: rgba(0,0,0,0.12); }

        .btn-R {
            background: var(--red-bg);
            color: var(--red);
            border: 1px solid rgba(184,50,50,0.15);
            border-radius: var(--radius);
            padding: 6px 13px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-R:hover { background: rgba(184,50,50,0.18); }

        .btn-Gr {
            background: var(--green-bg);
            color: var(--green);
            border: 1px solid rgba(45,125,70,0.15);
            border-radius: var(--radius);
            padding: 6px 13px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-Gr:hover { background: rgba(45,125,70,0.18); }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
            border-radius: var(--radius);
            padding: 7px 14px;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-ghost:hover {
            background: var(--red-bg);
            border-color: rgba(184,50,50,0.2);
            color: var(--red);
        }

        /* ─────────────────────────────
           FORMS
        ───────────────────────────── */
        .fctrl {
            background: #FAFAFA;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: var(--radius);
            font-size: 0.875rem;
            padding: 10px 14px;
            width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.25s ease;
            outline: none;
        }

        .fctrl:focus {
            border-color: var(--accent);
            background: var(--card);
            box-shadow: 0 0 0 3px rgba(0,0,0,0.05);
        }

        .fctrl::placeholder { color: #CCCCCC; }

        .flbl {
            font-size: 0.7rem;
            color: #666;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .ferr {
            font-size: 0.72rem;
            color: var(--red);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ─────────────────────────────
           ALERTS
        ───────────────────────────── */
        .fl-ok {
            background: var(--green-bg);
            border: 1px solid rgba(45,125,70,0.2);
            color: var(--green);
            border-radius: var(--radius);
            padding: 12px 16px;
            font-size: 0.84rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fl-er {
            background: var(--red-bg);
            border: 1px solid rgba(184,50,50,0.2);
            color: var(--red);
            border-radius: var(--radius);
            padding: 12px 16px;
            font-size: 0.84rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fl-wn {
            background: var(--amber-bg);
            border: 1px solid rgba(138,109,26,0.2);
            color: var(--amber);
            border-radius: var(--radius);
            padding: 12px 16px;
            font-size: 0.84rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ─────────────────────────────
           PAGINATION
        ───────────────────────────── */
        .pagination .page-link {
            background: var(--card);
            border-color: var(--border);
            color: var(--muted);
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .pagination .page-link:hover {
            background: var(--bg);
            color: var(--text);
            border-color: rgba(0,0,0,0.15);
        }

        .pagination .active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .pagination .disabled .page-link {
            background: #FAFAFA;
            color: #CCCCCC;
        }

        /* ─────────────────────────────
           BOOK CARD
        ───────────────────────────── */
        .book-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 18px;
            transition: all 0.25s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.1);
            border-color: rgba(0,0,0,0.12);
        }

        .book-cover {
            width: 100%;
            aspect-ratio: 2/3;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .book-cover img { width: 100%; height: 100%; object-fit: cover; }
        .book-cover i { font-size: 2rem; color: #CCCCCC; }

        .book-title { font-weight: 600; font-size: 0.88rem; color: var(--text); line-height: 1.35; margin-bottom: 4px; }
        .book-author { font-size: 0.75rem; color: var(--muted); font-style: italic; }

        .book-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 12px;
        }

        .stok-ok { font-size: 0.72rem; color: var(--green); font-weight: 500; }
        .stok-no { font-size: 0.72rem; color: var(--red); font-weight: 500; }

        /* ─────────────────────────────
           RESPONSIVE
        ───────────────────────────── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .page-body { padding: 18px 16px; }
            .topbar { padding: 12px 16px; }
            .srch { display: none; }
            .btn-menu { display: flex; }
        }
    </style>
    @stack('styles')
</head>
<body>

@auth
@php $unread = auth()->user()->unreadAppNotifications()->count(); @endphp
@endauth

{{-- ── SIDEBAR ── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-book-half brand-icon"></i>
        <span class="brand-name">Synister Library</span>
        <span class="brand-sub">Digital School Library</span>
    </div>

    <nav class="sidebar-nav">
        @auth
        @if(auth()->user()->isAdmin())
            <div class="nav-sec">Admin</div>
            <a href="{{ route('admin.dashboard') }}"       class="sl {{ request()->routeIs('admin.dashboard')      ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="{{ route('admin.buku.index') }}"      class="sl {{ request()->routeIs('admin.buku.*')         ? 'active' : '' }}"><i class="bi bi-book"></i> Kelola Buku</a>
            <a href="{{ route('admin.anggota.index') }}"   class="sl {{ request()->routeIs('admin.anggota.*')      ? 'active' : '' }}"><i class="bi bi-people"></i> Kelola Anggota</a>
            <a href="{{ route('admin.transaksi.index') }}" class="sl {{ request()->routeIs('admin.transaksi.*')    ? 'active' : '' }}"><i class="bi bi-arrow-left-right"></i> Transaksi</a>

            <div class="nav-sec">Manajemen</div>
            <a href="{{ route('admin.kategori.index') }}"  class="sl {{ request()->routeIs('admin.kategori.*')     ? 'active' : '' }}"><i class="bi bi-tags"></i> Kategori</a>
            <a href="{{ route('admin.laporan.index') }}"   class="sl {{ request()->routeIs('admin.laporan.*')      ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i> Laporan</a>
            <a href="{{ route('admin.search') }}"          class="sl {{ request()->routeIs('admin.search')         ? 'active' : '' }}"><i class="bi bi-search"></i> Pencarian</a>

            <div class="nav-sec">Sistem</div>
            <a href="{{ route('notifikasi.index') }}"      class="sl {{ request()->routeIs('notifikasi.*')         ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notifikasi
                @if($unread > 0)<span class="notif-badge">{{ $unread > 99 ? '99+' : $unread }}</span>@endif
            </a>

        @else
            <div class="nav-sec">Siswa</div>
            <a href="{{ route('siswa.dashboard') }}"           class="sl {{ request()->routeIs('siswa.dashboard')      ? 'active' : '' }}"><i class="bi bi-house"></i> Dashboard</a>
            <a href="{{ route('siswa.katalog') }}"             class="sl {{ request()->routeIs('siswa.katalog')        ? 'active' : '' }}"><i class="bi bi-grid"></i> Katalog Buku</a>
            <a href="{{ route('siswa.peminjaman.create') }}"   class="sl {{ request()->routeIs('siswa.peminjaman.*')   ? 'active' : '' }}"><i class="bi bi-bookmark-plus"></i> Pinjam Buku</a>
            {{-- <a href="{{ route('siswa.pengembalian.create') }}" class="sl {{ request()->routeIs('siswa.pengembalian.*') ? 'active' : '' }}"><i class="bi bi-bookmark-check"></i> Kembalikan Buku</a> --}}
            <a href="{{ route('siswa.riwayat') }}"             class="sl {{ request()->routeIs('siswa.riwayat')        ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Riwayat</a>

            <div class="nav-sec">Sistem</div>
            <a href="{{ route('notifikasi.index') }}"          class="sl {{ request()->routeIs('notifikasi.*')         ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notifikasi
                @if($unread > 0)<span class="notif-badge">{{ $unread > 99 ? '99+' : $unread }}</span>@endif
            </a>
        @endif
        @endauth
    </nav>

    <div class="sidebar-foot">
        <div class="d-flex align-items-center gap-2">
            <div class="u-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="u-name">{{ auth()->user()->name }}</div>
                <div class="u-role">{{ auth()->user()->role }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ── MAIN ── --}}
<div class="main">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-menu" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <i class="bi bi-list"></i>
            </button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        </div>

        <div class="topbar-right">
            @auth
            @if(auth()->user()->isAdmin())
            <form action="{{ route('admin.search') }}" method="GET" class="srch">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Cari buku atau anggota..." value="{{ request('q') }}">
            </form>
            @endif

            @if($unread > 0)
            <a href="{{ route('notifikasi.index') }}" class="topbar-bell">
                <i class="bi bi-bell" style="font-size:0.95rem;"></i>
                <span class="dot"></span>
            </a>
            @endif
            @endauth
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success') || session('error') || session('warning'))
    <div style="padding: 16px 28px 0;">
        @if(session('success'))
        <div class="fl-ok"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="fl-er"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif
        @if(session('warning'))
        <div class="fl-wn"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('warning') }}</div>
        @endif
    </div>
    @endif

    <div class="page-body">@yield('content')</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.sidebar .sl').forEach(function(link) {
        link.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('open');
        });
    });
</script>
@stack('scripts')
</body>
</html>
