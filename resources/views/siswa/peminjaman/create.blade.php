@extends('layouts.app')

@section('title', 'Pinjam Buku — Synister Library')
@section('page-title', 'Pinjam Buku')

@section('content')

{{-- INFO ATURAN & SISA KUOTA --}}
<div class="d-flex align-items-start gap-2 mb-4" style="background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.18);border-radius:10px;padding:12px 16px;font-size:.83rem;">
    <i class="bi bi-info-circle-fill" style="color:var(--amber);flex-shrink:0;margin-top:2px;"></i>
    <div style="color:var(--muted);">
        Maks. <strong style="color:var(--cream);">3 buku</strong> aktif sekaligus &middot;
        Durasi pinjam <strong style="color:var(--cream);">7 hari</strong> &middot;
        Denda <strong style="color:var(--cream);">Rp 1.000/hari</strong> keterlambatan
        <br>
        <span>Sisa kuota pinjaman: <strong style="color:var(--amber);">{{ $sisa_kuota }}</strong> dari 3 buku</span>
    </div>
</div>

{{-- SEARCH --}}
<form action="{{ route('siswa.peminjaman.create') }}" method="GET" class="d-flex gap-2 mb-4">
    <div style="position:relative;flex:1;">
        <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.82rem;pointer-events:none;"></i>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari judul, penulis, kategori..." class="fctrl" style="padding-left:32px;">
    </div>
    <button type="submit" class="btn-A"><i class="bi bi-search"></i> Cari</button>
    @if(request('search'))
        <a href="{{ route('siswa.peminjaman.create') }}" class="btn-G"><i class="bi bi-x"></i> Reset</a>
    @endif
</form>

{{-- FORM PEMINJAMAN MULTI-BUKU (LIST/TABLE) --}}
<form action="{{ route('siswa.peminjaman.store') }}" method="POST" id="multiPinjamForm">
    @csrf
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button type="button" id="selectAllBtn" class="btn-oA" style="padding:4px 12px;font-size:.8rem;">
                <i class="bi bi-check-all"></i> Pilih Semua
            </button>
            <button type="button" id="deselectAllBtn" class="btn-oA" style="padding:4px 12px;font-size:.8rem;">
                <i class="bi bi-x-circle"></i> Batal Pilih
            </button>
        </div>
        <button type="submit" class="btn-A" id="submitPinjam" {{ $sisa_kuota <= 0 ? 'disabled' : '' }}>
            <i class="bi bi-bookmark-plus"></i> Pinjam Terpilih
        </button>
    </div>

    @if($buku->count())
    <div class="card-d p-0">
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:40px;"><input type="checkbox" id="checkboxMaster" style="width:18px;height:18px;"></th>
                        <th>Cover</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($buku as $b)
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" name="book_ids[]" value="{{ $b->id }}"
                                   class="book-checkbox"
                                   style="width:18px;height:18px;cursor:pointer;"
                                   {{ in_array($b->id, $sedang_dipinjam) ? 'disabled' : '' }}
                                   {{ $sisa_kuota <= 0 && !in_array($b->id, $sedang_dipinjam) ? 'disabled' : '' }}>
                        </td>
                        <td style="width:50px;">
                            <div style="width:40px;height:55px;background:var(--navy3);border-radius:4px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                @if($b->cover_image)
                                    <img src="{{ Storage::url($b->cover_image) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="bi bi-book" style="color:rgba(201,168,76,.4);font-size:1.2rem;"></i>
                                @endif
                            </div>
                        </td>
                        <td style="font-weight:500;">{{ $b->judul_buku }}</td>
                        <td style="color:var(--muted);">{{ $b->penulis }}</td>
                        <td>
                            @if($b->category)
                                <span class="bd bd-a" style="font-size:.72rem;">{{ $b->category->nama }}</span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td><span class="stok-ok"><i class="bi bi-circle-fill" style="font-size:.45rem;"></i> {{ $b->stok }}</span></td>
                        <td>
                            @if(in_array($b->id, $sedang_dipinjam))
                                <span class="bd bd-r" style="background:rgba(224,90,90,.15);color:#e05a5a;">Sedang Dipinjam</span>
                            @else
                                <span class="bd bd-g">Tersedia</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $buku->links() }}</div>
    @else
    <div class="text-center py-5" style="color:var(--muted)">
        <i class="bi bi-search fs-2 d-block mb-2"></i>
        <div style="margin-bottom:8px;">Tidak ada buku ditemukan</div>
        <div style="font-size:.82rem;">Coba kata kunci lain atau semua buku sedang dipinjam</div>
        @if(request('search'))
            <a href="{{ route('siswa.peminjaman.create') }}" class="btn-G mt-3 d-inline-flex"><i class="bi bi-x"></i> Hapus Filter</a>
        @endif
    </div>
    @endif
</form>

@endsection

@push('scripts')
<script>
    const sisaKuota = {{ $sisa_kuota }};
    const checkboxes = document.querySelectorAll('.book-checkbox:not(:disabled)');
    const submitBtn = document.getElementById('submitPinjam');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const masterCheckbox = document.getElementById('checkboxMaster');

    function updateSubmitButton() {
        const checkedCount = document.querySelectorAll('.book-checkbox:checked').length;
        submitBtn.disabled = (checkedCount === 0);
    }

    function limitCheckboxSelection(e) {
        const checkedCount = document.querySelectorAll('.book-checkbox:checked').length;
        if (checkedCount > sisaKuota) {
            e.target.checked = false;
            alert(`Kamu hanya bisa meminjam maksimal ${sisaKuota} buku lagi (sisa kuota).`);
        }
        updateSubmitButton();
        updateMasterCheckbox();
    }

    function updateMasterCheckbox() {
        const allCheckboxes = document.querySelectorAll('.book-checkbox:not(:disabled)');
        const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
        if (allCheckboxes.length === 0) {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = false;
        } else if (checkedBoxes.length === allCheckboxes.length) {
            masterCheckbox.checked = true;
            masterCheckbox.indeterminate = false;
        } else if (checkedBoxes.length > 0) {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = true;
        } else {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = false;
        }
    }

    // Event untuk setiap checkbox
    checkboxes.forEach(cb => {
        cb.addEventListener('change', limitCheckboxSelection);
    });

    // Master checkbox (pilih semua)
    masterCheckbox.addEventListener('change', function(e) {
        const isChecked = e.target.checked;
        let count = 0;
        checkboxes.forEach(cb => {
            if (isChecked && count < sisaKuota) {
                cb.checked = true;
                count++;
            } else {
                cb.checked = false;
            }
        });
        updateSubmitButton();
        if (isChecked && sisaKuota > 0 && checkboxes.length > sisaKuota) {
            alert(`Hanya ${sisaKuota} buku yang dipilih karena sisa kuota.`);
        }
        masterCheckbox.indeterminate = false;
    });

    // Tombol Pilih Semua (paksa pilih sampai sisa kuota)
    selectAllBtn.addEventListener('click', function() {
        let count = 0;
        checkboxes.forEach(cb => {
            if (count < sisaKuota) {
                cb.checked = true;
                count++;
            } else {
                cb.checked = false;
            }
        });
        updateSubmitButton();
        updateMasterCheckbox();
        if (sisaKuota > 0 && checkboxes.length > sisaKuota) {
            alert(`Hanya ${sisaKuota} buku yang dipilih karena sisa kuota.`);
        }
    });

    // Tombol Batal Pilih
    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        updateSubmitButton();
        updateMasterCheckbox();
    });

    // Submit form validation
    document.getElementById('multiPinjamForm').addEventListener('submit', function(e) {
        const checkedCount = document.querySelectorAll('.book-checkbox:checked').length;
        if (checkedCount === 0) {
            e.preventDefault();
            alert('Pilih minimal 1 buku.');
        } else if (checkedCount > sisaKuota) {
            e.preventDefault();
            alert(`Kamu hanya bisa meminjam maksimal ${sisaKuota} buku.`);
        }
    });

    // Inisialisasi
    updateSubmitButton();
</script>
@endpush
