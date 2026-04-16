@extends('layouts.app')

@section('title', 'Riwayat Peminjaman — Synister Library')
@section('page-title', 'Riwayat Peminjaman')

@section('content')

<div class="card-d">
    <div class="cd-head d-flex align-items-center justify-content-between">
        <span><i class="bi bi-clock-history me-2" style="color:var(--amber)"></i>Semua Riwayat</span>
        <span style="font-size:.78rem;color:var(--muted);">{{ $riwayat->total() }} transaksi</span>
    </div>
    <div class="cd-body p-0">
        @if($riwayat->isEmpty())
        <div class="text-center py-5" style="color:var(--muted)">
            <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
            <div style="margin-bottom:14px;">Belum ada riwayat peminjaman</div>
            <a href="{{ route('siswa.peminjaman.create') }}" class="btn-A d-inline-flex">
                <i class="bi bi-bookmark-plus"></i> Pinjam Buku Pertama
            </a>
        </div>
        @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Tgl Dikembalikan</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $i => $t)
                    <tr>
                        <td style="color:var(--muted);font-size:.78rem;">{{ $riwayat->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:500;font-size:.86rem;">{{ $t->book->judul_buku }}</div>
                            <div style="font-size:.74rem;color:var(--muted);">{{ $t->book->penulis }}</div>
                        </td>
                        {{-- ✅ FIX: kolom sudah di-cast 'date' di model, tidak perlu Carbon::parse() --}}
                        <td style="font-size:.82rem;color:var(--muted);white-space:nowrap;">
                            {{ $t->tanggal_pinjam->format('d M Y') }}
                        </td>
                        <td style="font-size:.82rem;white-space:nowrap;">
                            {{-- Bonus: warna merah jika sudah lewat batas dan belum dikembalikan --}}
                            @if($t->status !== 'dikembalikan' && $t->tanggal_kembali_rencana->isPast())
                                <span style="color:var(--red);">{{ $t->tanggal_kembali_rencana->format('d M Y') }}</span>
                            @else
                                <span style="color:var(--muted);">{{ $t->tanggal_kembali_rencana->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td style="font-size:.82rem;white-space:nowrap;">
                            @if($t->tanggal_kembali_aktual)
                                <span style="color:var(--green);">{{ $t->tanggal_kembali_aktual->format('d M Y') }}</span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td style="font-size:.83rem;">
                            @if($t->denda > 0)
                                <span style="color:var(--red);font-weight:500;">
                                    Rp {{ number_format($t->denda, 0, ',', '.') }}
                                </span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 pb-3 pt-2">{{ $riwayat->links() }}</div>
        @endif
    </div>
</div>

@endsection
