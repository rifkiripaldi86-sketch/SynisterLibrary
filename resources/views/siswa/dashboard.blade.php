@extends('layouts.app')

@section('title', 'Dashboard — Synister Library')
@section('page-title', 'Dashboard')

@section('content')

{{-- GREETING --}}
<div style="background:linear-gradient(135deg,rgba(138,109,26,.08),rgba(138,109,26,.02));border:1px solid rgba(138,109,26,.12);border-radius:var(--radius);padding:20px 24px;margin-bottom:24px;">
    <div style="font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--amber);margin-bottom:4px;">
        Selamat datang, {{ auth()->user()->name }}! 👋
    </div>
    <div style="font-size:.82rem;color:var(--muted);">
        Kelas {{ auth()->user()->kelas }} &middot; NIS {{ auth()->user()->no_induk }}
    </div>
</div>

{{-- STAT CARDS --}}
@if(isset($stats))
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-c">
            <div class="stat-ic ic-a"><i class="bi bi-book-half"></i></div>
            <div>
                <div class="stat-v">{{ $stats['sedang_pinjam'] ?? 0 }}</div>
                <div class="stat-l">Sedang Dipinjam</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-c">
            <div class="stat-ic ic-r"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-v">{{ $stats['terlambat'] ?? 0 }}</div>
                <div class="stat-l">Terlambat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-c">
            <div class="stat-ic ic-g"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-v">{{ $stats['total_kembali'] ?? 0 }}</div>
                <div class="stat-l">Dikembalikan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-c">
            <div class="stat-ic ic-b"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-v">{{ $stats['total_pinjam'] ?? 0 }}</div>
                <div class="stat-l">Total Pinjam</div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-3">

    {{-- PINJAMAN AKTIF --}}
    <div class="col-12 col-lg-8">
        <div class="card-d">
            <div class="cd-head d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bookmark-check me-2" style="color:var(--amber)"></i>Pinjaman Aktif</span>
                <a href="{{ route('siswa.riwayat') }}" class="btn-oA" style="padding:4px 12px;font-size:.76rem;">
                    Lihat Riwayat
                </a>
            </div>
            <div class="cd-body p-0">
                @php
                    $aktif = $pinjaman_aktif ?? $pinjaman ?? collect();
                @endphp

                @if($aktif->isEmpty())
                <div class="text-center py-5" style="color:var(--muted)">
                    <i class="bi bi-bookmark fs-3 d-block mb-2"></i>
                    <div style="margin-bottom:12px;font-size:.85rem;">Tidak ada pinjaman aktif</div>
                    <a href="{{ route('siswa.peminjaman.create') }}" class="btn-A d-inline-flex">
                        <i class="bi bi-bookmark-plus"></i> Pinjam Buku
                    </a>
                </div>
                @else
                <div class="table-responsive">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($aktif as $t)
                            @php $isLate = \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->isPast(); @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:500;font-size:.86rem;">{{ $t->book->judul_buku }}</div>
                                    <div style="font-size:.74rem;color:var(--muted);">{{ $t->book->penulis }}</div>
                                </td>
                                <td style="font-size:.82rem;color:var(--muted);white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    <span style="{{ $isLate ? 'color:var(--red);font-weight:500' : 'color:var(--muted)' }}">
                                        {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}
                                    </span>
                                    @if($isLate)
                                    <div style="font-size:.7rem;color:var(--red);">
                                        {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->diffInDays(today()) }} hari terlambat
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($isLate)
                                        <span class="bd bd-r">Terlambat</span>
                                    @else
                                        <span class="bd bd-a">Dipinjam</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="p-3">
                    <a href="{{ route('siswa.pengembalian.create') }}" class="btn-Gr">
                        <i class="bi bi-bookmark-check"></i> Kembalikan Buku
                    </a>
                </div> --}}
                @endif
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-12 col-lg-4">

        {{-- AKSI CEPAT --}}
        <div class="card-d mb-3">
            <div class="cd-head">
                <i class="bi bi-lightning-fill me-2" style="color:var(--amber)"></i>Aksi Cepat
            </div>
            <div class="cd-body d-flex flex-column gap-2">
                <a href="{{ route('siswa.peminjaman.create') }}" class="btn-A w-100 justify-content-center">
                    <i class="bi bi-bookmark-plus"></i> Pinjam Buku
                </a>
                {{-- <a href="{{ route('siswa.pengembalian.create') }}" class="btn-oA w-100 justify-content-center">
                    <i class="bi bi-bookmark-check"></i> Kembalikan Buku
                </a> --}}
                <a href="{{ route('siswa.katalog') }}" class="btn-G w-100 justify-content-center">
                    <i class="bi bi-grid"></i> Lihat Katalog
                </a>
            </div>
        </div>

        {{-- BUKU TERSEDIA --}}
        @if(isset($rekomendasi) && $rekomendasi->isNotEmpty())
        <div class="card-d">
            <div class="cd-head">
                <i class="bi bi-stars me-2" style="color:var(--amber)"></i>Buku Tersedia
            </div>
            <div class="cd-body" style="padding:8px 0;">
                @foreach($rekomendasi as $b)
                <div class="d-flex align-items-center gap-3 px-4 py-2"
                     style="{{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
                    <div style="width:34px;height:48px;border-radius:4px;background:var(--bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                        @if($b->cover_image)
                            <img src="{{ Storage::url($b->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="bi bi-book" style="color:#CCCCCC;font-size:.8rem;"></i>
                        @endif
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.83rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $b->judul_buku }}
                        </div>
                        <div style="font-size:.72rem;color:var(--muted);">{{ $b->penulis }}</div>
                    </div>
                    <span class="bd bd-g" style="flex-shrink:0;">{{ $b->stok }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
