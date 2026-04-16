@extends('layouts.app')

@section('title', 'Transaksi — Synister Library')
@section('page-title', 'Transaksi Peminjaman')

@section('content')

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <form action="{{ route('admin.transaksi.index') }}" method="GET" class="d-flex flex-wrap gap-2">
        <div style="position:relative;">
            <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.82rem;pointer-events:none;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau judul buku..." class="fctrl" style="padding-left:32px;width:230px;">
        </div>
        <select name="status" class="fctrl" style="width:140px;">
            <option value="">Semua Status</option>
            <option value="dipinjam"    {{ request('status') === 'dipinjam'    ? 'selected' : '' }}>Dipinjam</option>
            <option value="terlambat"   {{ request('status') === 'terlambat'   ? 'selected' : '' }}>Terlambat</option>
            <option value="dikembalikan"{{ request('status') === 'dikembalikan'? 'selected' : '' }}>Dikembalikan</option>
        </select>
        <button type="submit" class="btn-A"><i class="bi bi-funnel"></i> Filter</button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.transaksi.index') }}" class="btn-G"><i class="bi bi-x"></i> Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.transaksi.create') }}" class="btn-A"><i class="bi bi-plus-lg"></i> Catat Peminjaman</a>
</div>

<div class="card-d">
    <div class="cd-head d-flex align-items-center justify-content-between">
        <span><i class="bi bi-arrow-left-right me-2" style="color:var(--amber)"></i>Daftar Transaksi</span>
        <span style="font-size:.78rem;color:var(--muted);">{{ $transaksi->total() }} transaksi</span>
    </div>
    <div class="cd-body p-0">
        @if($transaksi->isEmpty())
        <div class="text-center py-5" style="color:var(--muted)">
            <i class="bi bi-arrow-left-right fs-2 d-block mb-2"></i>
            <div>Belum ada transaksi</div>
            <a href="{{ route('admin.transaksi.create') }}" class="btn-A mt-3 d-inline-flex"><i class="bi bi-plus-lg"></i> Catat Peminjaman</a>
        </div>
        @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $i => $t)
                    <tr>
                        <td style="color:var(--muted);font-size:.78rem;">{{ $transaksi->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:500;font-size:.85rem;">{{ $t->user->name }}</div>
                            <div style="font-size:.73rem;color:var(--muted);">{{ $t->user->kelas }}</div>
                         </td>
                        <td style="max-width:180px;">
                            <div style="font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $t->book->judul_buku }}</div>
                            <div style="font-size:.73rem;color:var(--muted);">{{ $t->book->penulis }}</div>
                         </td>
                        <td style="font-size:.82rem;color:var(--muted);white-space:nowrap;">{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}</td>
                        <td style="font-size:.82rem;white-space:nowrap;">
                            <span style="{{ $t->status !== 'dikembalikan' && \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->isPast() ? 'color:var(--red)' : 'color:var(--muted)' }}">
                                {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}
                            </span>
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
                        <td style="font-size:.82rem;">
                            @if($t->denda > 0)
                                <span style="color:var(--red);">Rp {{ number_format($t->denda, 0, ',', '.') }}</span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($t->status !== 'dikembalikan')
                                <form action="{{ route('admin.transaksi.kembalikan', $t->id) }}" method="POST" onsubmit="return confirm('Proses pengembalian buku ini?')">
                                    @csrf
                                    <button type="submit" class="btn-Gr" style="padding:4px 10px;font-size:.75rem;" title="Kembalikan">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.transaksi.edit', $t->id) }}" class="btn-oA" style="padding:4px 10px;font-size:.75rem;" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                <form action="{{ route('admin.transaksi.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus data transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-R" style="padding:4px 10px;font-size:.75rem;" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 pb-3 pt-2">{{ $transaksi->links() }}</div>
        @endif
    </div>
</div>

@endsection
