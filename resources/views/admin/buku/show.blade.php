@extends('layouts.app')

@section('title', $buku->judul_buku . ' — Synister Library')
@section('page-title', 'Detail Buku')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.buku.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
    <a href="{{ route('admin.buku.edit', $buku->id) }}" class="btn-oA"><i class="bi bi-pencil"></i> Edit Buku</a>
</div>

<div class="row g-3">

    {{-- KOLOM KIRI: Cover + Stok --}}
    <div class="col-12 col-lg-3">
        <div class="card-d">
            <div class="cd-body text-center">
                <div style="width:100%;aspect-ratio:2/3;background:var(--navy3);border-radius:10px;display:flex;align-items:center;justify-content:center;overflow:hidden;margin-bottom:16px;border:1px solid rgba(201,168,76,.1);">
                    @if($buku->cover_image)
                        <img src="{{ Storage::url($buku->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <div style="text-align:center;color:var(--muted);">
                            <i class="bi bi-book-half" style="font-size:3rem;display:block;margin-bottom:8px;color:rgba(201,168,76,.3);"></i>
                            <span style="font-size:.75rem;">Tidak ada cover</span>
                        </div>
                    @endif
                </div>

                {{-- Stok badge besar --}}
                <div style="background:{{ $buku->stok > 0 ? 'rgba(76,175,130,.1)' : 'rgba(224,90,90,.1)' }};border:1px solid {{ $buku->stok > 0 ? 'rgba(76,175,130,.25)' : 'rgba(224,90,90,.25)' }};border-radius:10px;padding:14px;">
                    <div style="font-size:2rem;font-weight:700;color:{{ $buku->stok > 0 ? 'var(--green)' : 'var(--red)' }};">{{ $buku->stok }}</div>
                    <div style="font-size:.75rem;color:var(--muted);margin-top:2px;">stok tersedia</div>
                </div>

                @if($buku->kategori)
                <div class="mt-3"><span class="bd bd-a" style="font-size:.78rem;">{{ $buku->kategori }}</span></div>
                @endif
            </div>
        </div>
    </div>

    {{-- KOLOM TENGAH-KANAN: Info + Riwayat --}}
    <div class="col-12 col-lg-9">

        {{-- INFO BUKU --}}
        <div class="card-d mb-3">
            <div class="cd-head"><i class="bi bi-info-circle me-2" style="color:var(--amber)"></i>Informasi Buku</div>
            <div class="cd-body">
                <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--cream);margin-bottom:4px;">{{ $buku->judul_buku }}</h2>
                <div style="font-size:.9rem;color:var(--muted);margin-bottom:20px;">{{ $buku->penulis }}</div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div style="font-size:.72rem;color:var(--muted);margin-bottom:3px;letter-spacing:.5px;text-transform:uppercase;">Penerbit</div>
                        <div style="font-size:.88rem;font-weight:500;">{{ $buku->penerbit }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="font-size:.72rem;color:var(--muted);margin-bottom:3px;letter-spacing:.5px;text-transform:uppercase;">Tahun Terbit</div>
                        <div style="font-size:.88rem;font-weight:500;">{{ $buku->tahun_terbit }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="font-size:.72rem;color:var(--muted);margin-bottom:3px;letter-spacing:.5px;text-transform:uppercase;">ISBN</div>
                        <div style="font-size:.88rem;font-weight:500;font-family:monospace;">{{ $buku->isbn ?? '—' }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="font-size:.72rem;color:var(--muted);margin-bottom:3px;letter-spacing:.5px;text-transform:uppercase;">Total Dipinjam</div>
                        <div style="font-size:.88rem;font-weight:500;">{{ $riwayat->total() }} kali</div>
                    </div>
                </div>

                @if($buku->deskripsi)
                <div style="margin-top:18px;padding-top:18px;border-top:1px solid rgba(255,255,255,.07);">
                    <div style="font-size:.72rem;color:var(--muted);margin-bottom:8px;letter-spacing:.5px;text-transform:uppercase;">Deskripsi</div>
                    <div style="font-size:.86rem;line-height:1.7;color:var(--muted);">{{ $buku->deskripsi }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- RIWAYAT TRANSAKSI --}}
        <div class="card-d">
            <div class="cd-head d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2" style="color:var(--amber)"></i>Riwayat Peminjaman</span>
                <span style="font-size:.78rem;color:var(--muted);">{{ $riwayat->total() }} transaksi</span>
            </div>
            <div class="cd-body p-0">
                @if($riwayat->isEmpty())
                <div class="text-center py-5" style="color:var(--muted)">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    <div>Buku ini belum pernah dipinjam</div>
                </div>
                @else
                <div class="table-responsive">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
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
                                    <div style="font-weight:500;font-size:.86rem;">{{ $t->user->name }}</div>
                                    <div style="font-size:.73rem;color:var(--muted);">{{ $t->user->no_induk }} · Kelas {{ $t->user->kelas }}</div>
                                </td>
                                <td style="font-size:.82rem;color:var(--muted);white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    <span style="{{ $t->status !== 'dikembalikan' && \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->isPast() ? 'color:var(--red)' : 'color:var(--muted)' }}">
                                        {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}
                                    </span>
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    @if($t->tanggal_kembali_aktual)
                                        <span style="color:var(--green);">{{ \Carbon\Carbon::parse($t->tanggal_kembali_aktual)->format('d M Y') }}</span>
                                    @else
                                        <span style="color:var(--muted);">—</span>
                                    @endif
                                </td>
                                <td style="font-size:.82rem;">
                                    @if($t->denda > 0)
                                        <span style="color:var(--red);">Rp {{ number_format($t->denda, 0, ',', '.') }}</span>
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

    </div>
</div>

@endsection
