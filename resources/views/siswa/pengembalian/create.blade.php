@extends('layouts.app')

@section('title', 'Kembalikan Buku — Synister Library')
@section('page-title', 'Kembalikan Buku')

@section('content')

{{-- $pinjaman = collection dari controller, sudah ada ->denda_preview dan ->is_terlambat --}}

@if($pinjaman->isEmpty())
<div class="text-center py-5" style="color:var(--muted)">
    <i class="bi bi-bookmark-check fs-2 d-block mb-2" style="color:var(--green);opacity:.6;"></i>
    <div style="margin-bottom:6px;">Tidak ada pinjaman aktif saat ini</div>
    <div style="font-size:.82rem;margin-bottom:18px;">Semua buku sudah dikembalikan</div>
    <a href="{{ route('siswa.peminjaman.create') }}" class="btn-A d-inline-flex">
        <i class="bi bi-bookmark-plus"></i> Pinjam Buku
    </a>
</div>
@else

<div style="font-size:.85rem;color:var(--muted);margin-bottom:20px;">
    Kamu memiliki <strong style="color:var(--cream);">{{ $pinjaman->count() }} buku</strong> yang sedang dipinjam.
    Pilih buku yang ingin dikembalikan.
</div>

<div class="row g-3">
    @foreach($pinjaman as $t)
    <div class="col-12 col-md-6">
        <div class="card-d" style="{{ $t->is_terlambat ? 'border-color:rgba(224,90,90,.35)' : '' }}">
            <div class="cd-body">
                <div class="d-flex gap-3">
                    {{-- Cover --}}
                    <div style="width:54px;height:76px;border-radius:6px;background:var(--navy3);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                        @if($t->book->cover_image)
                            <img src="{{ Storage::url($t->book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="bi bi-book" style="color:rgba(201,168,76,.35);font-size:1.1rem;"></i>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:500;font-size:.9rem;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $t->book->judul_buku }}
                        </div>
                        <div style="font-size:.76rem;color:var(--muted);margin-bottom:12px;">
                            {{ $t->book->penulis }}
                        </div>

                        <div class="d-flex flex-wrap gap-3 mb-3" style="font-size:.78rem;">
                            <div>
                                <div style="color:var(--muted);">Dipinjam</div>
                                <div>{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}</div>
                            </div>
                            <div>
                                <div style="color:var(--muted);">Batas Kembali</div>
                                <div style="{{ $t->is_terlambat ? 'color:var(--red);font-weight:500' : '' }}">
                                    {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}
                                </div>
                            </div>
                            @if($t->is_terlambat)
                            <div>
                                <div style="color:var(--red);">Keterlambatan</div>
                                <div style="color:var(--red);font-weight:600;">
                                    {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->diffInDays(\Carbon\Carbon::today()) }} hari
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Estimasi denda --}}
                        @if($t->is_terlambat)
                        <div style="background:rgba(224,90,90,.1);border:1px solid rgba(224,90,90,.22);border-radius:8px;padding:10px 12px;margin-bottom:12px;">
                            <div style="font-size:.74rem;color:var(--muted);margin-bottom:2px;">Estimasi denda keterlambatan</div>
                            <div style="font-size:1.05rem;font-weight:700;color:var(--red);">
                                Rp {{ number_format($t->denda_preview, 0, ',', '.') }}
                            </div>
                            <div style="font-size:.71rem;color:var(--muted);margin-top:1px;">
                                Denda final dihitung oleh petugas saat pengembalian
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('siswa.pengembalian.store', $t->id) }}" method="POST"
                              onsubmit="return confirm('{{ $t->is_terlambat
                                ? 'Kembalikan buku ini? Estimasi denda Rp ' . number_format($t->denda_preview, 0) . '. Bayarkan ke petugas perpustakaan.'
                                : 'Konfirmasi pengembalian buku ini?' }}')">
                            @csrf
                            <button type="submit" class="{{ $t->is_terlambat ? 'btn-R' : 'btn-Gr' }}" style="width:100%;justify-content:center;">
                                <i class="bi bi-bookmark-check"></i>
                                {{ $t->is_terlambat ? 'Kembalikan (Ada Denda)' : 'Kembalikan Buku' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
