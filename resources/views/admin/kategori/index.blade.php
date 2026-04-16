@extends('layouts.app')

@section('title', 'Kategori — Synister Library')
@section('page-title', 'Kelola Kategori')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <form action="{{ route('admin.kategori.index') }}" method="GET" class="d-flex gap-2">
        <div style="position:relative;">
            <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.82rem;pointer-events:none;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="fctrl" style="padding-left:32px;width:220px;">
        </div>
        <button type="submit" class="btn-A"><i class="bi bi-search"></i> Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.kategori.index') }}" class="btn-G"><i class="bi bi-x"></i> Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.kategori.create') }}" class="btn-A"><i class="bi bi-plus-lg"></i> Tambah Kategori</a>
</div>

<div class="card-d">
    <div class="cd-head d-flex align-items-center justify-content-between">
        <span><i class="bi bi-tags me-2" style="color:var(--amber)"></i>Daftar Kategori</span>
        <span style="font-size:.78rem;color:var(--muted);">{{ $kategori->total() }} kategori</span>
    </div>
    <div class="cd-body p-0">
        @if($kategori->isEmpty())
        <div class="text-center py-5" style="color:var(--muted)">
            <i class="bi bi-tags fs-2 d-block mb-2"></i>
            <div style="margin-bottom:14px;">Belum ada kategori</div>
            <a href="{{ route('admin.kategori.create') }}" class="btn-A d-inline-flex"><i class="bi bi-plus-lg"></i> Tambah Kategori</a>
        </div>
        @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Buku</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategori as $i => $k)
                    <tr>
                        <td style="color:var(--muted);font-size:.78rem;">{{ $kategori->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:14px;height:14px;border-radius:50%;background:{{ $k->warna }};flex-shrink:0;box-shadow:0 0 0 2px rgba(255,255,255,.1);"></div>
                                <span style="font-weight:500;font-size:.88rem;">{{ $k->nama }}</span>
                            </div>
                        </td>
                        <td style="font-size:.78rem;color:var(--muted);font-family:monospace;">{{ $k->slug }}</td>
                        <td style="font-size:.82rem;color:var(--muted);max-width:260px;">
                            {{ $k->deskripsi ? Str::limit($k->deskripsi, 60) : '—' }}
                        </td>
                        <td>
                            <span class="bd bd-a">{{ $k->books_count }} buku</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.kategori.edit', $k->id) }}" class="btn-oA" style="padding:5px 10px;font-size:.78rem;" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori {{ addslashes($k->nama) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-R" style="padding:5px 10px;font-size:.78rem;" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 pb-3 pt-2">{{ $kategori->links() }}</div>
        @endif
    </div>
</div>

@endsection