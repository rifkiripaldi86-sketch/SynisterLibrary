@extends('layouts.app')

@section('title', 'Dashboard Admin — Synister Library')
@section('page-title', 'Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-c">
            <div class="stat-ic ic-a"><i class="bi bi-book-half"></i></div>
            <div>
                <div class="stat-v">{{ number_format($stats['total_buku']) }}</div>
                <div class="stat-l">Total Buku</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-c">
            <div class="stat-ic ic-b"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-v">{{ number_format($stats['total_anggota']) }}</div>
                <div class="stat-l">Total Anggota</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-c">
            <div class="stat-ic ic-g"><i class="bi bi-arrow-left-right"></i></div>
            <div>
                <div class="stat-v">{{ number_format($stats['sedang_dipinjam']) }}</div>
                <div class="stat-l">Sedang Dipinjam</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-c">
            <div class="stat-ic ic-r"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="stat-v">{{ number_format($stats['terlambat']) }}</div>
                <div class="stat-l">Terlambat</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- TRANSAKSI TERBARU --}}
    <div class="col-12 col-xl-8">
        <div class="card-d">
            <div class="cd-head d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2" style="color:var(--amber)"></i>Transaksi Terbaru</span>
                <a href="{{ route('admin.transaksi.index') }}" class="btn-oA" style="padding:4px 12px;font-size:.76rem;">
                    Lihat Semua
                </a>
            </div>
            <div class="cd-body p-0">
                @if($transaksi_terbaru->isEmpty())
                    <div class="text-center py-5" style="color:var(--muted)">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada transaksi
                    </div>
                @else
                <div class="table-responsive">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi_terbaru as $t)
                            <tr>
                                <td>
                                    <div style="font-weight:500;font-size:.85rem;">{{ $t->user->name }}</div>
                                    <div style="font-size:.73rem;color:var(--muted);">{{ $t->user->no_induk }}</div>
                                </td>
                                <td style="max-width:160px;">
                                    <div style="font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $t->book->judul_buku }}
                                    </div>
                                </td>
                                <td style="font-size:.82rem;color:var(--muted);white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}
                                </td>
                                <td>
                                    @if($t->status === 'dikembalikan')
                                        <span class="bd bd-g">Dikembalikan</span>
                                    @elseif($t->status === 'terlambat')
                                        <span class="bd bd-r">Terlambat</span>
                                    @else
                                        <span class="bd bd-a">Dipinjam</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->status !== 'dikembalikan')
                                    <form action="{{ route('admin.transaksi.kembalikan', $t->id) }}" method="POST"
                                          onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                        @csrf
                                        <button type="submit" class="btn-Gr" style="padding:4px 10px;font-size:.75rem;">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-12 col-xl-4">

        {{-- BUKU TERPOPULER --}}
        <div class="card-d mb-3">
            <div class="cd-head">
                <i class="bi bi-trophy-fill me-2" style="color:var(--amber)"></i>Buku Terpopuler
            </div>
            <div class="cd-body" style="padding:10px 0;">
                @forelse($buku_terpopuler as $i => $b)
                <div class="d-flex align-items-center gap-3 px-4 py-2"
                     style="{{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
                    <div style="width:24px;height:24px;border-radius:6px;background:rgba(138,109,26,.12);color:var(--amber);font-size:.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        {{ $i + 1 }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.84rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $b->judul_buku }}
                        </div>
                        <div style="font-size:.72rem;color:var(--muted);">{{ $b->penulis }}</div>
                    </div>
                    <span class="bd bd-a">{{ $b->transactions_count }}x</span>
                </div>
                @empty
                <div class="text-center py-4" style="color:var(--muted);font-size:.85rem;">
                    Belum ada data
                </div>
                @endforelse
            </div>
        </div>

        {{-- AKSI CEPAT --}}
        <div class="card-d">
            <div class="cd-head">
                <i class="bi bi-lightning-fill me-2" style="color:var(--amber)"></i>Aksi Cepat
            </div>
            <div class="cd-body d-flex flex-column gap-2">
                <a href="{{ route('admin.transaksi.create') }}" class="btn-A w-100 justify-content-center">
                    <i class="bi bi-plus-circle"></i> Catat Peminjaman
                </a>
                <a href="{{ route('admin.buku.create') }}" class="btn-oA w-100 justify-content-center">
                    <i class="bi bi-book-half"></i> Tambah Buku
                </a>
                <a href="{{ route('admin.anggota.create') }}" class="btn-G w-100 justify-content-center">
                    <i class="bi bi-person-plus"></i> Tambah Anggota
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
