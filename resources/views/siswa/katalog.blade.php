@extends('layouts.app')

@section('title', 'Katalog Buku — Synister Library')
@section('page-title', 'Katalog Buku')

@section('content')

{{-- SEARCH & FILTER --}}
<form action="{{ route('siswa.katalog') }}" method="GET" class="d-flex flex-wrap gap-2 mb-4">
    <div style="position:relative;flex:1;min-width:200px;">
        <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.82rem;pointer-events:none;"></i>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari judul, penulis, atau penerbit..."
               class="fctrl" style="padding-left:32px;">
    </div>

    {{-- ✅ FIX: pakai category_id dan data dari tabel categories --}}
    <select name="category_id" class="fctrl" style="width:160px;">
        <option value="">Semua Kategori</option>
        @foreach($kategori as $k)
            <option value="{{ $k->id }}" {{ request('category_id') == $k->id ? 'selected' : '' }}>
                {{ $k->nama }}
            </option>
        @endforeach
    </select>

    <select name="stok" class="fctrl" style="width:140px;">
        <option value="">Semua</option>
        <option value="tersedia" {{ request('stok') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
        <option value="habis"    {{ request('stok') === 'habis'    ? 'selected' : '' }}>Habis</option>
    </select>

    <button type="submit" class="btn-A"><i class="bi bi-funnel"></i> Filter</button>

    @if(request('search') || request('category_id') || request('stok'))
        <a href="{{ route('siswa.katalog') }}" class="btn-G"><i class="bi bi-x"></i> Reset</a>
    @endif
</form>

{{-- HASIL --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div style="font-size:.82rem;color:var(--muted);">{{ $buku->total() }} buku ditemukan</div>
    <a href="{{ route('siswa.peminjaman.create') }}" class="btn-A" style="padding:7px 14px;font-size:.82rem;">
        <i class="bi bi-bookmark-plus"></i> Pinjam Buku
    </a>
</div>

@if($buku->isEmpty())
<div class="text-center py-5" style="color:var(--muted)">
    <i class="bi bi-search fs-2 d-block mb-2"></i>
    <div>Tidak ada buku yang sesuai filter</div>
    <a href="{{ route('siswa.katalog') }}" class="btn-G mt-3 d-inline-flex"><i class="bi bi-x"></i> Reset Filter</a>
</div>
@else
<div class="row g-3">
    @foreach($buku as $b)
    <div class="col-6 col-md-4 col-xl-3">
        <div class="book-card">
            <div class="book-cover">
                @if($b->cover_image)
                    {{-- ✅ FIX: pakai asset() lebih aman daripada Storage::url() di Blade --}}
                    <img src="{{ Storage::url($b->cover_image) }}" alt="{{ $b->judul_buku }}">
                @else
                    <i class="bi bi-book-half"></i>
                @endif
            </div>
            <div class="book-title">{{ $b->judul_buku }}</div>
            <div class="book-author">{{ $b->penulis }}</div>

            {{-- ✅ FIX: pakai relasi category, bukan kolom 'kategori' yang sudah dihapus --}}
            @if($b->category)
                <div class="mt-1">
                    <span class="bd bd-a" style="font-size:.68rem;background:{{ $b->category->warna }}20;color:{{ $b->category->warna }};">
                        {{ $b->category->nama }}
                    </span>
                </div>
            @endif

            @if($b->deskripsi)
                <div style="font-size:.76rem;color:var(--muted);margin-top:8px;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    {{ $b->deskripsi }}
                </div>
            @endif

            <div class="book-meta">
                <span class="{{ $b->stok > 0 ? 'stok-ok' : 'stok-no' }}">
                    <i class="bi bi-circle-fill" style="font-size:.45rem;vertical-align:middle;margin-right:3px;"></i>
                    {{ $b->stok > 0 ? $b->stok . ' tersedia' : 'Habis' }}
                </span>
                <span style="font-size:.74rem;color:var(--muted);">{{ $b->tahun_terbit }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-4">{{ $buku->links() }}</div>
@endif

@endsection
