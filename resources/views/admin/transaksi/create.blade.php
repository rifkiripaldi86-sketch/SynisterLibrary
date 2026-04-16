@extends('layouts.app')

@section('title', 'Catat Peminjaman — Synister Library')
@section('page-title', 'Catat Peminjaman')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.transaksi.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card-d">
            <div class="cd-head"><i class="bi bi-plus-circle me-2" style="color:var(--amber)"></i>Form Peminjaman Buku (Maksimal 3 Buku)</div>
            <div class="cd-body">
                <form action="{{ route('admin.transaksi.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="flbl">Anggota / Peminjam <span style="color:var(--red)">*</span></label>
                            <select name="user_id" class="fctrl" id="user_id">
                                <option value="">-- Pilih Anggota --</option>
                                @foreach($anggota as $a)
                                    <option value="{{ $a->id }}" {{ old('user_id') == $a->id ? 'selected' : '' }}>
                                        {{ $a->name }} — {{ $a->no_induk }} (Kelas {{ $a->kelas }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="flbl">Pilih Buku (bisa pilih lebih dari 1, maksimal 3 buku total pinjaman aktif) <span style="color:var(--red)">*</span></label>
                            <select name="book_ids[]" class="fctrl" multiple size="8" id="book_ids" style="height: auto; min-height: 200px;">
                                @foreach($buku as $b)
                                    <option value="{{ $b->id }}"
                                        {{ (old('book_ids') && in_array($b->id, old('book_ids'))) ? 'selected' : '' }}>
                                        {{ $b->judul_buku }} — {{ $b->penulis }} (Stok: {{ $b->stok }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Gunakan Ctrl (Windows) atau Cmd (Mac) untuk memilih beberapa buku.</small>
                            @error('book_ids')<div class="ferr">{{ $message }}</div>@enderror
                            @error('book_ids.*')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="flbl">Tanggal Pinjam <span style="color:var(--red)">*</span></label>
                            <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" class="fctrl">
                            @error('tanggal_pinjam')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Tanggal Kembali Rencana <span style="color:var(--red)">*</span></label>
                            <input type="date" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana', date('Y-m-d', strtotime('+7 days'))) }}" class="fctrl">
                            @error('tanggal_kembali_rencana')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="flbl">Catatan (opsional)</label>
                            <textarea name="catatan" class="fctrl" rows="3" placeholder="Catatan khusus jika ada...">{{ old('catatan') }}</textarea>
                            @error('catatan')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12" style="background:rgba(201,168,76,.07);border-radius:8px;border:1px solid rgba(201,168,76,.15);padding:12px 14px;">
                            <div class="d-flex gap-2">
                                <i class="bi bi-info-circle-fill" style="color:var(--amber);flex-shrink:0;margin-top:2px;"></i>
                                <div style="font-size:.8rem;color:var(--muted);">
                                    Denda keterlambatan: <strong style="color:var(--amber);">Rp 1.000/hari</strong> setelah tanggal kembali rencana. Denda dihitung otomatis saat pengembalian diproses.<br>
                                    <strong>Catatan:</strong> Setiap buku akan dicatat sebagai transaksi terpisah. Total pinjaman aktif anggota (termasuk yang akan dipinjam) maksimal <strong>3 buku</strong>.
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 mt-1">
                            <button type="submit" class="btn-A"><i class="bi bi-check-lg"></i> Catat Peminjaman</button>
                            <a href="{{ route('admin.transaksi.index') }}" class="btn-G">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Optional: JavaScript untuk menampilkan jumlah pinjaman aktif saat anggota dipilih --}}
@push('scripts')
<script>
    document.getElementById('user_id').addEventListener('change', function() {
        // Jika ingin menampilkan sisa kuota secara realtime, bisa via AJAX
        // Tidak wajib, hanya untuk UX.
    });
</script>
@endpush

@endsection
