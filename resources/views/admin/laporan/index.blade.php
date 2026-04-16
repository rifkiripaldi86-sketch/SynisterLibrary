@extends('layouts.app')

@section('title', 'Laporan — Synister Library')
@section('page-title', 'Laporan')

@section('content')

{{-- FILTER TANGGAL --}}
<div class="card-d mb-4">
    <div class="cd-body">
        <form action="{{ route('admin.laporan.index') }}" method="GET"
              class="d-flex flex-wrap align-items-end gap-3">
            <div>
                <label class="flbl">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari->format('Y-m-d') }}"
                       class="fctrl" style="width:170px;">
            </div>
            <div>
                <label class="flbl">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai->format('Y-m-d') }}"
                       class="fctrl" style="width:170px;">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-A"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('admin.laporan.index') }}" class="btn-G">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
            <div class="d-flex gap-2 ms-auto flex-wrap">
                <a href="{{ route('admin.laporan.index', ['dari' => now()->startOfMonth()->format('Y-m-d'), 'sampai' => now()->format('Y-m-d')]) }}"
                   class="btn-G" style="font-size:.78rem;padding:6px 12px;">Bulan Ini</a>
                <a href="{{ route('admin.laporan.index', ['dari' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'sampai' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}"
                   class="btn-G" style="font-size:.78rem;padding:6px 12px;">Bulan Lalu</a>
                <a href="{{ route('admin.laporan.index', ['dari' => now()->startOfYear()->format('Y-m-d'), 'sampai' => now()->format('Y-m-d')]) }}"
                   class="btn-G" style="font-size:.78rem;padding:6px 12px;">Tahun Ini</a>
            </div>
        </form>
    </div>
</div>

{{-- PERIODE INFO --}}
<div style="font-size:.82rem;color:var(--muted);margin-bottom:20px;">
    Menampilkan data periode
    <strong style="color:var(--amber);">{{ $dari->format('d M Y') }}</strong> —
    <strong style="color:var(--amber);">{{ $sampai->format('d M Y') }}</strong>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-2">
        <div class="stat-c">
            <div class="stat-ic ic-b"><i class="bi bi-arrow-left-right"></i></div>
            <div>
                <div class="stat-v">{{ $ringkasan['total_transaksi'] }}</div>
                <div class="stat-l">Total Transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-c">
            <div class="stat-ic ic-a"><i class="bi bi-bookmark-plus"></i></div>
            <div>
                <div class="stat-v">{{ $ringkasan['total_pinjam'] }}</div>
                <div class="stat-l">Dipinjam</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-c">
            <div class="stat-ic ic-g"><i class="bi bi-bookmark-check"></i></div>
            <div>
                <div class="stat-v">{{ $ringkasan['total_kembali'] }}</div>
                <div class="stat-l">Dikembalikan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-c">
            <div class="stat-ic ic-r"><i class="bi bi-alarm"></i></div>
            <div>
                <div class="stat-v">{{ $ringkasan['total_terlambat'] }}</div>
                <div class="stat-l">Terlambat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-c">
            <div class="stat-ic ic-r"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-v" style="font-size:1.1rem;">
                    Rp {{ number_format($ringkasan['total_denda'], 0, ',', '.') }}
                </div>
                <div class="stat-l">Total Denda</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-c">
            <div class="stat-ic ic-a"><i class="bi bi-book-half"></i></div>
            <div>
                <div class="stat-v">{{ $ringkasan['aktif_dipinjam'] }}</div>
                <div class="stat-l">Sedang Dipinjam</div>
            </div>
        </div>
    </div>
</div>

{{-- CHART PINJAMAN PER BULAN --}}
<div class="card-d mb-3">
    <div class="cd-head">
        <i class="bi bi-bar-chart-line me-2" style="color:var(--amber)"></i>Pinjaman 12 Bulan Terakhir
    </div>
    <div class="cd-body">
        @php $maxJumlah = $perBulan->max('jumlah') ?: 1; @endphp
        <div style="display:flex;align-items:flex-end;gap:8px;height:180px;padding-bottom:28px;position:relative;">
            {{-- Garis referensi horizontal --}}
            @foreach([100, 75, 50, 25] as $pct)
            <div style="position:absolute;left:0;right:0;bottom:{{ $pct + 16 }}px;border-top:1px dashed var(--border);pointer-events:none;"></div>
            @endforeach

            @foreach($perBulan as $b)
            @php $tinggi = round(($b['jumlah'] / $maxJumlah) * 140); @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
                <div style="font-size:.65rem;color:{{ $b['jumlah'] > 0 ? 'var(--amber)' : 'var(--muted)' }};font-weight:500;min-height:14px;">
                    {{ $b['jumlah'] > 0 ? $b['jumlah'] : '' }}
                </div>
                <div style="width:100%;height:{{ max($tinggi, 2) }}px;background:{{ $b['jumlah'] > 0 ? 'linear-gradient(180deg,rgba(138,109,26,.85),rgba(138,109,26,.35))' : 'var(--border)' }};border-radius:4px 4px 0 0;transition:height .3s;"></div>
                <div style="font-size:.62rem;color:var(--muted);white-space:nowrap;transform:rotate(-30deg);transform-origin:top center;margin-top:4px;">{{ $b['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row g-3 mb-3">

    {{-- BUKU TERPOPULER --}}
    <div class="col-12 col-lg-6">
        <div class="card-d h-100">
            <div class="cd-head">
                <i class="bi bi-trophy-fill me-2" style="color:var(--amber)"></i>Buku Terpopuler
            </div>
            <div class="cd-body p-0">
                @php $maxBuku = $bukuPopuler->max('transactions_count') ?: 1; @endphp
                @forelse($bukuPopuler as $i => $b)
                @php $pct = round(($b->transactions_count / $maxBuku) * 100); @endphp
                <div class="px-4 py-2" style="{{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <div style="width:22px;height:22px;border-radius:5px;background:rgba(138,109,26,.12);color:var(--amber);font-size:.72rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            {{ $i + 1 }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.84rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $b->judul_buku }}</div>
                            <div style="font-size:.72rem;color:var(--muted);">{{ $b->penulis }}</div>
                        </div>
                        <div class="bd bd-a">{{ $b->transactions_count }}x</div>
                    </div>
                    <div style="height:3px;background:var(--border);border-radius:2px;margin-left:34px;">
                        <div style="height:100%;width:{{ $pct }}%;background:var(--amber);border-radius:2px;transition:width .4s;"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4" style="color:var(--muted);font-size:.85rem;">
                    Tidak ada data pada periode ini
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ANGGOTA TERAKTIF --}}
    <div class="col-12 col-lg-6">
        <div class="card-d h-100">
            <div class="cd-head">
                <i class="bi bi-person-fill-up me-2" style="color:var(--amber)"></i>Anggota Teraktif
            </div>
            <div class="cd-body p-0">
                @php $maxAnggota = $anggotaAktif->max('transactions_count') ?: 1; @endphp
                @forelse($anggotaAktif as $i => $a)
                @php $pct = round(($a->transactions_count / $maxAnggota) * 100); @endphp
                <div class="px-4 py-2" style="{{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <div style="width:22px;height:22px;border-radius:5px;background:var(--blue-bg);color:var(--blue);font-size:.72rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            {{ $i + 1 }}
                        </div>
                        <div class="u-avatar" style="width:28px;height:28px;font-size:.72rem;flex-shrink:0;">
                            {{ strtoupper(substr($a->name, 0, 1)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.84rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $a->name }}</div>
                            <div style="font-size:.72rem;color:var(--muted);">Kelas {{ $a->kelas }}</div>
                        </div>
                        <div class="bd bd-b">{{ $a->transactions_count }}x</div>
                    </div>
                    <div style="height:3px;background:var(--border);border-radius:2px;margin-left:58px;">
                        <div style="height:100%;width:{{ $pct }}%;background:var(--blue);border-radius:2px;transition:width .4s;"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4" style="color:var(--muted);font-size:.85rem;">
                    Tidak ada data pada periode ini
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<div class="row g-3 mb-3">

    {{-- DISTRIBUSI KATEGORI --}}
    <div class="col-12 col-lg-5">
        <div class="card-d h-100">
            <div class="cd-head">
                <i class="bi bi-pie-chart me-2" style="color:var(--amber)"></i>Distribusi Kategori
            </div>
            <div class="cd-body p-0">
                @php $totalKat = $perKategori->sum('total_pinjam') ?: 1; @endphp
                @forelse($perKategori as $kat)
                @php
                    $pct   = round(($kat->total_pinjam / $totalKat) * 100);
                    $warna = $kat->warna ?? '#8a6d1a';
                @endphp
                <div class="d-flex align-items-center gap-3 px-4 py-3"
                     style="{{ !$loop->last ? 'border-bottom:1px solid var(--border)' : '' }}">
                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $warna }};flex-shrink:0;"></div>
                    <div style="flex:1;min-width:0;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="font-size:.84rem;font-weight:500;">{{ $kat->nama }}</span>
                            <span style="font-size:.78rem;color:var(--muted);">{{ $kat->total_pinjam }}x · {{ $pct }}%</span>
                        </div>
                        <div style="height:4px;background:var(--border);border-radius:2px;">
                            <div style="height:100%;width:{{ $pct }}%;background:{{ $warna }};border-radius:2px;opacity:.8;transition:width .4s;"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4" style="color:var(--muted);font-size:.85rem;">
                    Tidak ada data kategori
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- TERLAMBAT AKTIF --}}
    <div class="col-12 col-lg-7">
        <div class="card-d h-100">
            <div class="cd-head d-flex align-items-center justify-content-between">
                <span><i class="bi bi-alarm-fill me-2" style="color:var(--red)"></i>Keterlambatan Aktif</span>
                <span class="bd bd-r">{{ $terlambatAktif->count() }} buku</span>
            </div>
            <div class="cd-body p-0">
                @if($terlambatAktif->isEmpty())
                <div class="text-center py-5" style="color:var(--muted)">
                    <i class="bi bi-check-circle fs-2 d-block mb-2" style="color:var(--green);opacity:.6;"></i>
                    <div style="font-size:.86rem;">Tidak ada keterlambatan aktif</div>
                </div>
                @else
                <div class="table-responsive">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Terlambat</th>
                                <th>Est. Denda</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($terlambatAktif as $t)
                            <tr>
                                <td>
                                    <div style="font-weight:500;font-size:.85rem;">{{ $t->user->name }}</div>
                                    <div style="font-size:.73rem;color:var(--muted);">{{ $t->user->kelas }}</div>
                                </td>
                                <td style="max-width:150px;">
                                    <div style="font-size:.84rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $t->book->judul_buku }}
                                    </div>
                                    <div style="font-size:.72rem;color:var(--muted);">
                                        Batas: {{ $t->tanggal_kembali_rencana->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="bd bd-r">{{ $t->hari_terlambat }} hari</span>
                                </td>
                                <td style="font-size:.83rem;color:var(--red);font-weight:500;">
                                    Rp {{ number_format($t->denda_akrual, 0, ',', '.') }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.transaksi.kembalikan', $t->id) }}" method="POST"
                                          onsubmit="return confirm('Proses pengembalian buku ini?')">
                                        @csrf
                                        <button type="submit" class="btn-Gr"
                                                style="padding:4px 10px;font-size:.75rem;">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
