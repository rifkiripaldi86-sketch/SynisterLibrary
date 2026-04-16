@extends('layouts.app')

@section('title', 'Kelola Anggota — Synister Library')
@section('page-title', 'Kelola Anggota')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <form action="{{ route('admin.anggota.index') }}" method="GET" class="d-flex flex-wrap gap-2">
        <div style="position:relative;">
            <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.82rem;pointer-events:none;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, NIS..." class="fctrl" style="padding-left:32px;width:230px;">
        </div>
        <select name="kelas" class="fctrl" style="width:140px;">
            <option value="">Semua Kelas</option>
            @foreach($daftar_kelas as $k)
                <option value="{{ $k }}" {{ request('kelas') === $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-A"><i class="bi bi-funnel"></i> Filter</button>
        @if(request('search') || request('kelas'))
            <a href="{{ route('admin.anggota.index') }}" class="btn-G"><i class="bi bi-x"></i> Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.anggota.create') }}" class="btn-A"><i class="bi bi-person-plus"></i> Tambah Anggota</a>
</div>

<div class="card-d">
    <div class="cd-head d-flex align-items-center justify-content-between">
        <span><i class="bi bi-people me-2" style="color:var(--amber)"></i>Daftar Anggota</span>
        <span style="font-size:.78rem;color:var(--muted);">{{ $anggota->total() }} anggota</span>
    </div>
    <div class="cd-body p-0">
        @if($anggota->isEmpty())
        <div class="text-center py-5" style="color:var(--muted)">
            <i class="bi bi-people fs-2 d-block mb-2"></i>
            <div>Belum ada anggota</div>
            <a href="{{ route('admin.anggota.create') }}" class="btn-A mt-3 d-inline-flex"><i class="bi bi-person-plus"></i> Tambah Anggota</a>
        </div>
        @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Nama & Username</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Total Pinjam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anggota as $i => $a)
                    <tr>
                        <td style="color:var(--muted);font-size:.78rem;">{{ $anggota->firstItem() + $i }}</td>
                        <td>
                            <div class="u-avatar">{{ strtoupper(substr($a->name, 0, 1)) }}</div>
                        </td>
                        <td>
                            <div style="font-weight:500;font-size:.87rem;">{{ $a->name }}</div>
                            <div style="font-size:.74rem;color:var(--muted);">{{ '@'.$a->username }}</div>
                        </td>
                        <td style="font-size:.82rem;font-family:monospace;color:var(--muted);">{{ $a->no_induk }}</td>
                        <td><span class="bd bd-a">{{ $a->kelas }}</span></td>
                        <td style="font-size:.85rem;">{{ $a->transactions_count }} <span style="color:var(--muted);font-size:.75rem;">buku</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.anggota.edit', $a->id) }}" class="btn-oA" style="padding:5px 10px;font-size:.78rem;"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.anggota.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus anggota {{ addslashes($a->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-R" style="padding:5px 10px;font-size:.78rem;"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 pb-3 pt-2">{{ $anggota->links() }}</div>
        @endif
    </div>
</div>

@endsection
