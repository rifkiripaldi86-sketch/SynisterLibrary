@extends('layouts.app')

@section('title', 'Edit Transaksi — Synister Library')
@section('page-title', 'Edit Transaksi')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.transaksi.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        {{-- INFO TRANSAKSI --}}
        <div class="card-d mb-3">
            <div class="cd-head"><i class="bi bi-info-circle me-2" style="color:var(--amber)"></i>Informasi Transaksi</div>
            <div class="cd-body">
                <div class="row g-2" style="font-size:.86rem;">
                    <div class="col-4" style="color:var(--muted);">Anggota</div>
                    <div class="col-8" style="font-weight:500;">{{ $transaksi->user->name }} <span style="color:var(--muted);font-size:.78rem;">· {{ $transaksi->user->no_induk }}</span></div>
                    <div class="col-4" style="color:var(--muted);">Buku</div>
                    <div class="col-8" style="font-weight:500;">{{ $transaksi->book->judul_buku }}</div>
                    <div class="col-4" style="color:var(--muted);">Tgl Pinjam</div>
                    <div class="col-8">{{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('d M Y') }}</div>
                    <div class="col-4" style="color:var(--muted);">Status</div>
                    <div class="col-8">
                        @if($transaksi->status === 'dipinjam')<span class="bd bd-a">Dipinjam</span>
                        @elseif($transaksi->status === 'terlambat')<span class="bd bd-r">Terlambat</span>
                        @else<span class="bd bd-g">Dikembalikan</span>@endif
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM EDIT --}}
        <div class="card-d">
            <div class="cd-head"><i class="bi bi-pencil-square me-2" style="color:var(--amber)"></i>Edit Tanggal & Catatan</div>
            <div class="cd-body">
                <form action="{{ route('admin.transaksi.update', $transaksi->id) }}" method="POST" id="editForm">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="flbl">Tanggal Kembali Rencana <span style="color:var(--red)">*</span></label>
                            <input type="date" name="tanggal_kembali_rencana"
                                id="tanggal_kembali_rencana"
                                value="{{ old('tanggal_kembali_rencana', \Carbon\Carbon::parse($transaksi->tanggal_kembali_rencana)->format('Y-m-d')) }}"
                                class="fctrl"
                                min="{{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->addDay()->format('Y-m-d') }}">
                            @error('tanggal_kembali_rencana')<div class="ferr">{{ $message }}</div>@enderror
                            <div class="form-text" style="font-size:.7rem; color:var(--muted); margin-top:5px;">
                                <i class="bi bi-info-circle"></i> Batas minimal: 1 hari setelah tanggal pinjam.
                                Perubahan tanggal akan mempengaruhi perhitungan denda keterlambatan secara otomatis.
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="flbl">Catatan</label>
                            <textarea name="catatan" class="fctrl" rows="3" placeholder="Catatan koreksi...">{{ old('catatan', $transaksi->catatan) }}</textarea>
                            @error('catatan')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        {{-- Peringatan jika transaksi sedang terlambat --}}
                        @if($transaksi->status === 'terlambat')
                        <div class="col-12">
                            <div class="alert alert-warning" style="background:rgba(224,90,90,.1); border:1px solid rgba(224,90,90,.3); border-radius:8px; padding:10px 14px;">
                                <i class="bi bi-exclamation-triangle-fill" style="color:var(--red); margin-right:8px;"></i>
                                <span style="font-size:.8rem;">Transaksi ini sudah terlambat. Mengubah tanggal kembali tidak akan menghapus denda yang sudah berjalan.</span>
                            </div>
                        </div>
                        @endif

                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn-A"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
                            <a href="{{ route('admin.transaksi.index') }}" class="btn-G">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('editForm').addEventListener('submit', function(e) {
        const tglKembali = document.getElementById('tanggal_kembali_rencana').value;
        const tglPinjam = '{{ \Carbon\Carbon::parse($transaksi->tanggal_pinjam)->format('Y-m-d') }}';
        if (tglKembali <= tglPinjam) {
            e.preventDefault();
            alert('Tanggal kembali harus lebih besar dari tanggal pinjam.');
        }
    });
</script>
@endpush