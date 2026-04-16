@extends('layouts.app')

@section('title', 'Detail Buku — ' . $buku->judul_buku)
@section('page-title', 'Detail Buku')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('siswa.katalog') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali ke Katalog</a>
    <a href="{{ route('siswa.peminjaman.create') }}?book_id={{ $buku->id }}" class="btn-A">
        <i class="bi bi-bookmark-plus"></i> Pinjam Buku Ini
    </a>
</div>

<div class="row g-4">
    {{-- COVER BUKU --}}
    <div class="col-12 col-md-4">
        <div class="card-d">
            <div class="cd-body text-center">
                @if($buku->cover_image && Storage::disk('public')->exists($buku->cover_image))
                    <img src="{{ Storage::url($buku->cover_image) }}" alt="{{ $buku->judul_buku }}"
                         style="width:100%; max-width:280px; border-radius:8px; box-shadow:0 8px 20px rgba(0,0,0,0.3);">
                @else
                    <i class="bi bi-book" style="font-size: 6rem; color: var(--ash-dk);"></i>
                    <div class="mt-2" style="color:var(--muted);">Tidak ada cover</div>
                @endif
            </div>
        </div>
    </div>

    {{-- INFORMASI BUKU --}}
    <div class="col-12 col-md-8">
        <div class="card-d">
            <div class="cd-head">
                <i class="bi bi-info-circle me-2" style="color:var(--amber)"></i> Informasi Buku
            </div>
            <div class="cd-body">
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; width: 140px; color: var(--muted);">Judul Buku</td>
                        <td style="padding: 8px 0; font-weight: 500;">{{ $buku->judul_buku }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: var(--muted);">Penulis</td>
                        <td style="padding: 8px 0;">{{ $buku->penulis }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: var(--muted);">Penerbit</td>
                        <td style="padding: 8px 0;">{{ $buku->penerbit }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: var(--muted);">Tahun Terbit</td>
                        <td style="padding: 8px 0;">{{ $buku->tahun_terbit }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: var(--muted);">ISBN</td>
                        <td style="padding: 8px 0; font-family: monospace;">{{ $buku->isbn ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: var(--muted);">Kategori</td>
                        <td style="padding: 8px 0;">
                            @if($buku->category)
                                <span class="bd" style="background:{{ $buku->category->warna }}22; color:{{ $buku->category->warna }}; border:1px solid {{ $buku->category->warna }}44;">
                                    {{ $buku->category->nama }}
                                </span>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: var(--muted);">Stok</td>
                        <td style="padding: 8px 0;">
                            @if($buku->stok > 0)
                                <span class="bd bd-g">{{ $buku->stok }} eksemplar tersedia</span>
                            @else
                                <span class="bd bd-r">Stok habis</span>
                            @endif
                        </td>
                    </tr>
                    @if($buku->deskripsi)
                    <tr>
                        <td style="padding: 8px 0; color: var(--muted); vertical-align: top;">Deskripsi</td>
                        <td style="padding: 8px 0; line-height: 1.6;">{{ $buku->deskripsi }}</td>
                    </tr>
                    @endif
                </table>

                <div class="mt-4 pt-3 d-flex gap-2">
                    <a href="{{ route('siswa.peminjaman.create') }}?book_id={{ $buku->id }}" class="btn-A">
                        <i class="bi bi-bookmark-plus"></i> Pinjam Buku
                    </a>
                    <a href="{{ route('siswa.katalog') }}" class="btn-G">Kembali ke Katalog</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection