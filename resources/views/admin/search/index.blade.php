@extends('layouts.app')

@section('title', 'Pencarian — Synister Library')
@section('page-title', 'Pencarian')

@section('content')

{{-- SEARCH FORM --}}
<form method="GET" action="{{ route('admin.search') }}" class="d-flex gap-2 mb-4">
    <div style="position:relative;flex:1;">
        <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.82rem;pointer-events:none;"></i>
        <input type="text" name="q" value="{{ $query }}"
               placeholder="Cari judul buku, penulis, nama siswa, NIS..."
               class="fctrl" style="padding-left:32px;" autofocus>
    </div>
    <button type="submit" class="btn-A"><i class="bi bi-search"></i> Cari</button>
    @if($query)
        <a href="{{ route('admin.search') }}" class="btn-G"><i class="bi bi-x"></i> Reset</a>
    @endif
</form>

@if($query)

    {{-- SUMMARY --}}
    <div style="font-size:.82rem;color:var(--muted);margin-bottom:20px;">
        Hasil untuk <strong style="color:var(--text);">"{{ $query }}"</strong> —
        <span style="color:var(--amber);">{{ $buku->count() }} buku</span> dan
        <span style="color:var(--amber);">{{ $anggota->count() }} anggota</span> ditemukan
    </div>

    <div class="row g-3">

        {{-- HASIL BUKU --}}
        <div class="col-12 col-lg-6">
            <div class="card-d">
                <div class="cd-head d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-book me-2" style="color:var(--amber)"></i>Buku</span>
                    <span style="font-size:.76rem;color:var(--muted);">{{ $buku->count() }} hasil</span>
                </div>
                <div class="cd-body p-0">
                    @if($buku->count())
                    <div class="table-responsive">
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Judul & Penulis</th>
                                    <th>Stok</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($buku as $b)
                                <tr>
                                    <td>
                                        <div style="font-weight:500;font-size:.86rem;">{{ $b->judul_buku }}</div>
                                        <div style="font-size:.74rem;color:var(--muted);">
                                            {{ $b->penulis }}
                                            @if($b->isbn)
                                                <span class="ms-1">· ISBN: {{ $b->isbn }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($b->stok > 0)
                                            <span class="bd bd-g">{{ $b->stok }}</span>
                                        @else
                                            <span class="bd bd-r">Habis</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.buku.show', $b) }}" class="btn-G"
                                               style="padding:4px 9px;font-size:.75rem;" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.buku.edit', $b) }}" class="btn-oA"
                                               style="padding:4px 9px;font-size:.75rem;" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-2" style="border-top:1px solid var(--border);">
                        <a href="{{ route('admin.buku.index', ['search' => $query]) }}"
                           class="btn-ghost" style="font-size:.78rem;">
                            Lihat semua hasil di halaman Buku <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    @else
                    <div class="text-center py-5" style="color:var(--muted)">
                        <i class="bi bi-book fs-2 d-block mb-2" style="opacity:.4;"></i>
                        <div style="font-size:.86rem;">Tidak ada buku ditemukan</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- HASIL ANGGOTA --}}
        <div class="col-12 col-lg-6">
            <div class="card-d">
                <div class="cd-head d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-people me-2" style="color:var(--amber)"></i>Anggota</span>
                    <span style="font-size:.76rem;color:var(--muted);">{{ $anggota->count() }} hasil</span>
                </div>
                <div class="cd-body p-0">
                    @if($anggota->count())
                    <div class="table-responsive">
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($anggota as $a)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="u-avatar"
                                                 style="width:30px;height:30px;font-size:.75rem;flex-shrink:0;">
                                                {{ strtoupper(substr($a->name, 0, 1)) }}
                                            </div>
                                            <div style="font-weight:500;font-size:.86rem;">{{ $a->name }}</div>
                                        </div>
                                    </td>
                                    <td style="font-size:.82rem;color:var(--muted);font-family:monospace;">
                                        {{ $a->no_induk ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="bd bd-a" style="font-size:.72rem;">{{ $a->kelas ?? '—' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.anggota.show', $a) }}" class="btn-G"
                                               style="padding:4px 9px;font-size:.75rem;" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.anggota.edit', $a) }}" class="btn-oA"
                                               style="padding:4px 9px;font-size:.75rem;" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-2" style="border-top:1px solid var(--border);">
                        <a href="{{ route('admin.anggota.index', ['search' => $query]) }}"
                           class="btn-ghost" style="font-size:.78rem;">
                            Lihat semua hasil di halaman Anggota <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                    @else
                    <div class="text-center py-5" style="color:var(--muted)">
                        <i class="bi bi-people fs-2 d-block mb-2" style="opacity:.4;"></i>
                        <div style="font-size:.86rem;">Tidak ada anggota ditemukan</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@else

    {{-- EMPTY STATE --}}
    <div class="text-center py-5" style="color:var(--muted);">
        <i class="bi bi-search" style="font-size:3rem;display:block;margin-bottom:16px;opacity:.25;"></i>
        <div style="font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--text);margin-bottom:8px;">
            Cari apa?
        </div>
        <div style="font-size:.86rem;margin-bottom:24px;">
            Ketik judul buku, nama penulis, nama siswa, atau NIS
        </div>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ route('admin.buku.index') }}" class="btn-oA">
                <i class="bi bi-book"></i> Semua Buku
            </a>
            <a href="{{ route('admin.anggota.index') }}" class="btn-G">
                <i class="bi bi-people"></i> Semua Anggota
            </a>
            <a href="{{ route('admin.transaksi.index') }}" class="btn-G">
                <i class="bi bi-arrow-left-right"></i> Transaksi
            </a>
        </div>
    </div>

@endif

@endsection
