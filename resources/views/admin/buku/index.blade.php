@extends('layouts.app')

@section('title', 'Kelola Buku — Synister Library')
@section('page-title', 'Kelola Buku')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <form action="{{ route('admin.buku.index') }}" method="GET" class="d-flex flex-wrap gap-2">
        <div style="position:relative;">
            <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.82rem;pointer-events:none;"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul, penulis, ISBN..."
                   class="fctrl" style="padding-left:32px;width:240px;">
        </div>
        <select name="category_id" class="fctrl" style="width:180px;">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" {{ request('category_id') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn-A"><i class="bi bi-funnel"></i> Filter</button>
        @if(request('search') || request('category_id'))
            <a href="{{ route('admin.buku.index') }}" class="btn-G"><i class="bi bi-x"></i> Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.buku.create') }}" class="btn-A"><i class="bi bi-plus-lg"></i> Tambah Buku</a>
</div>

<div class="card-d">
    <div class="cd-head d-flex align-items-center justify-content-between">
        <span><i class="bi bi-book me-2" style="color:var(--amber)"></i>Daftar Buku</span>
        <span style="font-size:.78rem;color:var(--muted);">{{ $buku->total() }} buku</span>
    </div>
    <div class="cd-body p-0">
        @if($buku->isEmpty())
        <div class="text-center py-5" style="color:var(--muted)">
            <i class="bi bi-book fs-2 d-block mb-2"></i>
            <div>Belum ada buku</div>
            <a href="{{ route('admin.buku.create') }}" class="btn-A mt-3 d-inline-flex">
                <i class="bi bi-plus-lg"></i> Tambah Buku
            </a>
        </div>
        @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="width:50px;">Cover</th>
                        <th>Judul & Penulis</th>
                        <th>ISBN</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th style="width:130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($buku as $i => $b)
                    <tr>
                        <td style="color:var(--muted);font-size:.78rem;">{{ $buku->firstItem() + $i }}</td>
                        <td>
                            <div style="width:38px;height:52px;border-radius:4px;background:var(--bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                @if($b->cover_image)
                                    <img src="{{ Storage::url($b->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="bi bi-book" style="color:#CCCCCC;font-size:.9rem;"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:500;font-size:.87rem;">{{ $b->judul_buku }}</div>
                            <div style="font-size:.74rem;color:var(--muted);">{{ $b->penulis }} · {{ $b->penerbit }}</div>
                        </td>
                        <td style="font-size:.8rem;color:var(--muted);font-family:monospace;">{{ $b->isbn ?? '—' }}</td>
                        <td>
                            @if($b->category)
                                <span class="bd" style="background:{{ $b->category->warna }}22;color:{{ $b->category->warna }};border:1px solid {{ $b->category->warna }}44;">
                                    {{ $b->category->nama }}
                                </span>
                            @else
                                <span style="color:var(--muted);font-size:.78rem;">—</span>
                            @endif
                        </td>
                        <td style="font-size:.82rem;color:var(--muted);">{{ $b->tahun_terbit }}</td>
                        <td>
                            @if($b->stok > 0)
                                <span class="bd bd-g">{{ $b->stok }}</span>
                            @else
                                <span class="bd bd-r">Habis</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.buku.show', $b->id) }}" class="btn-G"
                                   style="padding:5px 10px;font-size:.78rem;" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.buku.edit', $b->id) }}" class="btn-oA"
                                   style="padding:5px 10px;font-size:.78rem;" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.buku.destroy', $b->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus buku {{ addslashes($b->judul_buku) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-R"
                                            style="padding:5px 10px;font-size:.78rem;" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 pb-3 pt-2">{{ $buku->links() }}</div>
        @endif
    </div>
</div>

@endsection
