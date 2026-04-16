@extends('layouts.app')

@section('title', $anggotum->name . ' — Synister Library')
@section('page-title', 'Detail Anggota')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.anggota.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
    <a href="{{ route('admin.anggota.edit', $anggotum->id) }}" class="btn-oA"><i class="bi bi-pencil"></i> Edit Anggota</a>
</div>

<div class="row g-3">

    {{-- KOLOM KIRI: Profil --}}
    <div class="col-12 col-lg-3">
        <div class="card-d">
            <div class="cd-body text-center">
                {{-- Avatar besar --}}
                <div style="width:80px;height:80px;border-radius:50%;background:var(--amber);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--navy);margin:0 auto 16px;">
                    {{ strtoupper(substr($anggotum->name, 0, 1)) }}
                </div>

                <div style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--cream);margin-bottom:4px;">{{ $anggotum->name }}</div>
                <div style="font-size:.8rem;color:var(--muted);margin-bottom:16px;">@{{ $anggotum->username }}</div>
                <span class="bd bd-a">{{ $anggotum->kelas }}</span>

                <div style="margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,.07);">
                    <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:.82rem;">
                        <span style="color:var(--muted);">NIS</span>
                        <span style="font-family:monospace;font-weight:500;">{{ $anggotum->no_induk }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:.82rem;">
                        <span style="color:var(--muted);">Role</span>
                        <span style="text-transform:capitalize;font-weight:500;">{{ $anggotum->role }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center" style="font-size:.82rem;">
                        <span style="color:var(--muted);">Bergabung</span>
                        <span>{{ $anggotum->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTIK SINGKAT --}}
        <div class="card-d mt-3">
            <div class="cd-head"><i class="bi bi-bar-chart me-2" style="color:var(--amber)"></i>Statistik</div>
            <div class="cd-body" style="padding:12px 20px;">
                @php
                    $totalPinjam   = $riwayat->total();
                    $masihDipinjam = $anggotum->transactions->whereIn('status', ['dipinjam','terlambat'])->count();
                    $totalDenda    = $anggotum->transactions->sum('denda');
                @endphp
                <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid rgba(255,255,255,.06);font-size:.84rem;">
                    <span style="color:var(--muted);">Total Pinjam</span>
                    <strong>{{ $totalPinjam }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid rgba(255,255,255,.06);font-size:.84rem;">
                    <span style="color:var(--muted);">Masih Dipinjam</span>
                    <strong style="{{ $masihDipinjam > 0 ? 'color:var(--amber)' : '' }}">{{ $masihDipinjam }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2" style="font-size:.84rem;">
                    <span style="color:var(--muted);">Total Denda</span>
                    <strong style="{{ $totalDenda > 0 ? 'color:var(--red)' : '' }}">
                        {{ $totalDenda > 0 ? 'Rp ' . number_format($totalDenda, 0, ',', '.') : '—' }}
                    </strong>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Riwayat --}}
    <div class="col-12 col-lg-9">

        {{-- PINJAMAN AKTIF (jika ada) --}}
        @php $pinjamanAktif = $anggotum->transactions->whereIn('status', ['dipinjam','terlambat']); @endphp
        @if($pinjamanAktif->isNotEmpty())
        <div class="card-d mb-3" style="border-color:rgba(201,168,76,.25);">
            <div class="cd-head" style="background:rgba(201,168,76,.06);">
                <i class="bi bi-bookmark-check me-2" style="color:var(--amber)"></i>
                Pinjaman Aktif
                <span class="bd bd-a ms-2">{{ $pinjamanAktif->count() }}</span>
            </div>
            <div class="cd-body p-0">
                <div class="table-responsive">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pinjamanAktif as $t)
                            @php $isLate = \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->isPast(); @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:500;font-size:.86rem;">{{ $t->book->judul_buku }}</div>
                                    <div style="font-size:.73rem;color:var(--muted);">{{ $t->book->penulis }}</div>
                                </td>
                                <td style="font-size:.82rem;color:var(--muted);white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    <span style="{{ $isLate ? 'color:var(--red);font-weight:500' : 'color:var(--muted)' }}">
                                        {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}
                                    </span>
                                    @if($isLate)
                                    <div style="font-size:.7rem;color:var(--red);">
                                        {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->diffInDays(today()) }} hari terlambat
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($isLate)
                                        <span class="bd bd-r">Terlambat</span>
                                    @else
                                        <span class="bd bd-a">Dipinjam</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.transaksi.kembalikan', $t->id) }}" method="POST"
                                          onsubmit="return confirm('Proses pengembalian buku ini?')">
                                        @csrf
                                        <button type="submit" class="btn-Gr" style="padding:4px 10px;font-size:.75rem;">
                                            <i class="bi bi-check2"></i> Kembalikan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- SEMUA RIWAYAT --}}
        <div class="card-d">
            <div class="cd-head d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2" style="color:var(--amber)"></i>Semua Riwayat Peminjaman</span>
                <span style="font-size:.78rem;color:var(--muted);">{{ $riwayat->total() }} transaksi</span>
            </div>
            <div class="cd-body p-0">
                @if($riwayat->isEmpty())
                <div class="text-center py-5" style="color:var(--muted)">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    <div>Belum ada riwayat peminjaman</div>
                </div>
                @else
                <div class="table-responsive">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Dikembalikan</th>
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
                                    <div style="font-size:.73rem;color:var(--muted);">{{ $t->book->penulis }}</div>
                                </td>
                                <td style="font-size:.82rem;color:var(--muted);white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}
                                </td>
                                <td style="font-size:.82rem;color:var(--muted);white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($t->tanggal_kembali_rencana)->format('d M Y') }}
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
