@extends('layouts.app')

@section('title', 'Detail Transaksi — Synister Library')
@section('page-title', 'Detail Transaksi')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.transaksi.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
    @if($transaksi->status !== 'dikembalikan')
        <a href="{{ route('admin.transaksi.edit', $transaksi->id) }}" class="btn-oA"><i class="bi bi-pencil"></i> Edit</a>
    @endif
</div>

<div class="row g-3 justify-content-center">
    <div class="col-12 col-lg-8">

        {{-- STATUS BANNER --}}
        @if($transaksi->status === 'dikembalikan')
        <div class="fl-ok mb-3">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>
                <strong>Buku sudah dikembalikan</strong>
                @if($transaksi->tanggal_kembali_aktual)
                    pada {{ \Carbon\Carbon::parse($transaksi->tanggal_kembali_aktual)->format('d M Y') }}
                @endif
                @if($transaksi->denda > 0)
                    · Denda: <strong>Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</strong>
                @endif
            </div>
        </div>
        @elseif($transaksi->status === 'terlambat' || \Carbon\Carbon::parse($transaksi->tanggal_kembali_rencana)->isPast())
        <div class="fl-er mb-3">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <strong>Buku terlambat dikembalikan!</strong>
                Sudah {{ \Carbon\Carbon::parse($transaksi->tanggal_kembali_rencana)->diffInDays(today()) }} hari melewati batas.
                Estimasi denda: <strong>Rp {{ number_format(\Carbon\Carbon::parse($transaksi->tanggal_kembali_rencana)->diffInDays(today()) * 1000, 0, ',', '.') }}</strong>
            </div>
        </div>
        @endif

        {{-- DETAIL KARTU --}}
        <div class="card-d mb-3">
            <div class="cd-head"><i class="bi bi-file-text me-2" style="color:var(--amber)"></i>Detail Transaksi</div>
            <div class="cd-body">
                <div class="row g-0">

                    {{-- BUKU --}}
                    <div class="col-12" style="padding-bottom:18px;margin-bottom:18px;border-bottom:1px solid rgba(255,255,255,.07);">
                        <div style="font-size:.72rem;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin-bottom:10px;">Buku</div>
                        <div class="d-flex gap-3 align-items-start">
                            <div style="width:52px;height:72px;border-radius:6px;background:var(--navy3);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                                @if($transaksi->book->cover_image)
                                    <img src="{{ Storage::url($transaksi->book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="bi bi-book" style="color:rgba(201,168,76,.35);font-size:1rem;"></i>
                                @endif
                            </div>
                            <div>
                                <div style="font-size:1rem;font-weight:600;color:var(--cream);margin-bottom:3px;">{{ $transaksi->book->judul_buku }}</div>
                                <div style="font-size:.83rem;color:var(--muted);margin-bottom:6px;">{{ $transaksi->book->penulis }} · {{ $transaksi->book->penerbit }}</div>
                                @if($transaksi->book->kategori)
                                    <span class="bd bd-a" style="font-size:.72rem;">{{ $transaksi->book->kategori }}</span>
                                @endif
                                @if($transaksi->book->isbn)
                                    <span style="font-size:.75rem;color:var(--muted);margin-left:8px;font-family:monospace;">ISBN: {{ $transaksi->book->isbn }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- PEMINJAM --}}
                    <div class="col-12" style="padding-bottom:18px;margin-bottom:18px;border-bottom:1px solid rgba(255,255,255,.07);">
                        <div style="font-size:.72rem;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin-bottom:10px;">Peminjam</div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="u-avatar" style="width:40px;height:40px;font-size:.9rem;">
                                {{ strtoupper(substr($transaksi->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:.95rem;font-weight:500;">{{ $transaksi->user->name }}</div>
                                <div style="font-size:.78rem;color:var(--muted);">NIS {{ $transaksi->user->no_induk }} · Kelas {{ $transaksi->user->kelas }}</div>
                            </div>
                            <a href="{{ route('admin.anggota.show', $transaksi->user->id) }}" class="btn-G ms-auto" style="padding:5px 12px;font-size:.78rem;">
                                <i class="bi bi-person"></i> Lihat Profil
                            </a>
                        </div>
                    </div>

                    {{-- TANGGAL --}}
                    <div class="col-12">
                        <div style="font-size:.72rem;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin-bottom:12px;">Waktu Peminjaman</div>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div style="background:var(--navy3);border-radius:8px;padding:12px 14px;text-align:center;">
                                    <div style="font-size:.7rem;color:var(--muted);margin-bottom:4px;">Tgl Pinjam</div>
                                    <div style="font-size:.88rem;font-weight:600;">{{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('d M') }}</div>
                                    <div style="font-size:.75rem;color:var(--muted);">{{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('Y') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div style="background:var(--navy3);border-radius:8px;padding:12px 14px;text-align:center;border:1px solid {{ $transaksi->status !== 'dikembalikan' && \Carbon\Carbon::parse($transaksi->tanggal_kembali_rencana)->isPast() ? 'rgba(224,90,90,.4)' : 'rgba(201,168,76,.15)' }};">
                                    <div style="font-size:.7rem;color:var(--muted);margin-bottom:4px;">Batas Kembali</div>
                                    <div style="font-size:.88rem;font-weight:600;color:{{ $transaksi->status !== 'dikembalikan' && \Carbon\Carbon::parse($transaksi->tanggal_kembali_rencana)->isPast() ? 'var(--red)' : 'var(--amber)' }};">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal_kembali_rencana)->format('d M') }}
                                    </div>
                                    <div style="font-size:.75rem;color:var(--muted);">{{ \Carbon\Carbon::parse($transaksi->tanggal_kembali_rencana)->format('Y') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div style="background:var(--navy3);border-radius:8px;padding:12px 14px;text-align:center;border:1px solid {{ $transaksi->tanggal_kembali_aktual ? 'rgba(76,175,130,.25)' : 'transparent' }};">
                                    <div style="font-size:.7rem;color:var(--muted);margin-bottom:4px;">Dikembalikan</div>
                                    @if($transaksi->tanggal_kembali_aktual)
                                        <div style="font-size:.88rem;font-weight:600;color:var(--green);">
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_kembali_aktual)->format('d M') }}
                                        </div>
                                        <div style="font-size:.75rem;color:var(--muted);">{{ \Carbon\Carbon::parse($transaksi->tanggal_kembali_aktual)->format('Y') }}</div>
                                    @else
                                        <div style="font-size:.88rem;color:var(--muted);">—</div>
                                        <div style="font-size:.72rem;color:var(--muted);">Belum</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div style="background:{{ $transaksi->denda > 0 ? 'rgba(224,90,90,.08)' : 'var(--navy3)' }};border-radius:8px;padding:12px 14px;text-align:center;border:1px solid {{ $transaksi->denda > 0 ? 'rgba(224,90,90,.25)' : 'transparent' }};">
                                    <div style="font-size:.7rem;color:var(--muted);margin-bottom:4px;">Denda</div>
                                    @if($transaksi->denda > 0)
                                        <div style="font-size:.88rem;font-weight:700;color:var(--red);">
                                            Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                                        </div>
                                        <div style="font-size:.72rem;color:var(--muted);">Dibayar ke petugas</div>
                                    @else
                                        <div style="font-size:.88rem;color:var(--green);font-weight:600;">Rp 0</div>
                                        <div style="font-size:.72rem;color:var(--muted);">Tidak ada denda</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CATATAN --}}
                    @if($transaksi->catatan)
                    <div class="col-12 mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.07);">
                        <div style="font-size:.72rem;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin-bottom:6px;">Catatan</div>
                        <div style="font-size:.86rem;color:var(--muted);line-height:1.6;background:var(--navy3);border-radius:8px;padding:12px 14px;">
                            {{ $transaksi->catatan }}
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- AKSI --}}
        @if($transaksi->status !== 'dikembalikan')
        <div class="card-d">
            <div class="cd-head"><i class="bi bi-lightning-fill me-2" style="color:var(--amber)"></i>Aksi</div>
            <div class="cd-body d-flex gap-2 flex-wrap">
                <form action="{{ route('admin.transaksi.kembalikan', $transaksi->id) }}" method="POST"
                      onsubmit="return confirm('Proses pengembalian buku ini?')">
                    @csrf
                    <button type="submit" class="btn-Gr"><i class="bi bi-bookmark-check"></i> Proses Pengembalian</button>
                </form>
                <a href="{{ route('admin.transaksi.edit', $transaksi->id) }}" class="btn-oA">
                    <i class="bi bi-pencil"></i> Edit Transaksi
                </a>
                <form action="{{ route('admin.transaksi.destroy', $transaksi->id) }}" method="POST"
                      onsubmit="return confirm('Hapus data transaksi ini? Tindakan tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-R"><i class="bi bi-trash"></i> Hapus</button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
