@extends('layouts.app')

@section('title', 'Catat Peminjaman — Synister Library')
@section('page-title', 'Catat Peminjaman')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--multiple {
        min-height: 42px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: var(--amber, #C9A84C);
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 2px 8px;
        margin-top: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 6px;
        border-right: 1px solid rgba(255,255,255,0.3);
        padding-right: 6px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background-color: transparent;
        color: #ffd700;
    }
    .select2-search__field {
        width: 100% !important;
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.transaksi.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card-d">
            <div class="cd-head"><i class="bi bi-plus-circle me-2" style="color:var(--amber)"></i>Form Peminjaman Buku (Maksimal 3 Buku)</div>
            <div class="cd-body">
                <form action="{{ route('admin.transaksi.store') }}" method="POST" id="pinjamForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="flbl">Anggota / Peminjam <span class="text-danger">*</span></label>
                            <select name="user_id" class="fctrl" id="user_id" required>
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
                            <label class="flbl">Pilih Buku (Ketik judul buku, bisa lebih dari 1) <span class="text-danger">*</span></label>
                            <select name="book_ids[]" id="book_ids" class="fctrl" multiple="multiple" style="width:100%" required>
                                @foreach($buku as $b)
                                    <option value="{{ $b->id }}"
                                            data-judul="{{ $b->judul_buku }}"
                                            data-penulis="{{ $b->penulis }}"
                                            data-stok="{{ $b->stok }}">
                                        {{ $b->judul_buku }} - {{ $b->penulis }} (ID: {{ $b->id }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Ketik judul buku untuk mencari. Pilih maksimal 3 buku.</small>
                            @error('book_ids')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="flbl">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" class="fctrl">
                            @error('tanggal_pinjam')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="flbl">Durasi Peminjaman <span class="text-danger">*</span></label>
                            <select name="durasi" class="fctrl" required>
                                <option value="1" {{ old('durasi') == 1 ? 'selected' : '' }}>1 hari (kembali besok)</option>
                                <option value="3" {{ old('durasi') == 3 ? 'selected' : '' }}>3 hari</option>
                                <option value="7" {{ old('durasi') == 7 ? 'selected' : '' }} selected>1 minggu (7 hari)</option>
                            </select>
                            <small class="text-muted">Tanggal kembali akan dihitung otomatis.</small>
                            @error('durasi')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="flbl">Catatan (opsional)</label>
                            <textarea name="catatan" class="fctrl" rows="3" placeholder="Catatan khusus jika ada...">{{ old('catatan') }}</textarea>
                            @error('catatan')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 alert alert-secondary" style="background:rgba(201,168,76,.07); border:1px solid rgba(201,168,76,.15);">
                            <i class="bi bi-info-circle-fill" style="color:var(--amber);"></i>
                            Denda keterlambatan: <strong>Rp 1.000/hari</strong>. Maksimal 3 buku aktif per anggota.
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn-A"><i class="bi bi-check-lg"></i> Catat Peminjaman</button>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#book_ids').select2({
            placeholder: "Ketik judul buku disini...",
            allowClear: true,
            width: '100%',
            templateResult: formatBook,
            templateSelection: formatBookSelection
        });

        function formatBook(book) {
            if (!book.id) return book.text;
            var $element = $(book.element);
            var judul = $element.data('judul');
            var penulis = $element.data('penulis');
            var stok = $element.data('stok');
            var stokBadgeClass = stok > 0 ? 'bg-success' : 'bg-danger';
            var $book = $(
                '<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">' +
                    '<div>' +
                        '<strong class="d-block text-dark">' + judul + '</strong>' +
                        '<small class="text-muted"><i class="bi bi-person"></i> ' + penulis + '</small>' +
                    '</div>' +
                    '<div>' +
                        '<span class="badge ' + stokBadgeClass + '">Stok: ' + stok + '</span>' +
                    '</div>' +
                '</div>'
            );
            return $book;
        }

        function formatBookSelection(book) {
            if (!book.id) return book.text;
            var $element = $(book.element);
            return $element.data('judul');
        }

        $('#book_ids').on('select2:select', function(e) {
            var selected = $('#book_ids').val() || [];
            if (selected.length > 3) {
                alert('Maksimal meminjam 3 buku.');
                $(this).val(selected.slice(0,3)).trigger('change');
            }
        });

        $('#pinjamForm').on('submit', function(e) {
            var selected = $('#book_ids').val() || [];
            if (selected.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 buku.');
            } else if (selected.length > 3) {
                e.preventDefault();
                alert('Maksimal 3 buku.');
            }
        });
    });
</script>
@endpush
