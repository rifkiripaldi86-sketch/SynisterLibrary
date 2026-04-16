@extends('layouts.app')

@section('title', 'Tambah Buku — Synister Library')
@section('page-title', 'Tambah Buku')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.buku.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        {{-- KOLOM KIRI --}}
        <div class="col-12 col-lg-8">
            <div class="card-d">
                <div class="cd-head"><i class="bi bi-info-circle me-2" style="color:var(--amber)"></i>Informasi Buku</div>
                <div class="cd-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="flbl">Judul Buku <span style="color:var(--red)">*</span></label>
                            <input type="text" name="judul_buku" value="{{ old('judul_buku') }}" class="fctrl" placeholder="Masukkan judul buku">
                            @error('judul_buku')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Penulis <span style="color:var(--red)">*</span></label>
                            <input type="text" name="penulis" value="{{ old('penulis') }}" class="fctrl" placeholder="Nama penulis">
                            @error('penulis')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Penerbit <span style="color:var(--red)">*</span></label>
                            <input type="text" name="penerbit" value="{{ old('penerbit') }}" class="fctrl" placeholder="Nama penerbit">
                            @error('penerbit')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="flbl">Tahun Terbit <span style="color:var(--red)">*</span></label>
                            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', date('Y')) }}" class="fctrl" min="1900" max="{{ date('Y') }}">
                            @error('tahun_terbit')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="flbl">ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn') }}" class="fctrl" placeholder="978-xxx-xxx-xxx">
                            @error('isbn')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="flbl">Stok <span style="color:var(--red)">*</span></label>
                            <input type="number" name="stok" value="{{ old('stok', 1) }}" class="fctrl" min="0">
                            @error('stok')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="flbl">Kategori</label>
                            <select name="category_id" class="fctrl">
                                <option value="">— Tanpa Kategori —</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}" {{ old('category_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if($kategori->isEmpty())
                                <div style="font-size:.75rem;color:var(--muted);margin-top:5px;">
                                    Belum ada kategori. <a href="{{ route('admin.kategori.create') }}" style="color:var(--amber);">Tambah kategori dulu</a>.
                                </div>
                            @endif
                            @error('category_id')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="flbl">Deskripsi</label>
                            <textarea name="deskripsi" class="fctrl" rows="4" placeholder="Sinopsis atau keterangan buku...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-12 col-lg-4">
            <div class="card-d">
                <div class="cd-head"><i class="bi bi-image me-2" style="color:var(--amber)"></i>Cover Buku</div>
                <div class="cd-body">
                    <div id="cover-preview" style="width:100%;aspect-ratio:2/3;background:var(--navy3);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;overflow:hidden;border:2px dashed rgba(201,168,76,.2);">
                        <div id="cover-placeholder" style="text-align:center;color:var(--muted);">
                            <i class="bi bi-image fs-3 d-block mb-1"></i>
                            <span style="font-size:.76rem;">Belum ada cover</span>
                        </div>
                        <img id="cover-img" src="" style="display:none;width:100%;height:100%;object-fit:cover;">
                    </div>
                    <label for="cover_image" class="btn-oA w-100 justify-content-center" style="cursor:pointer;">
                        <i class="bi bi-upload"></i> Pilih Gambar
                    </label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*." style="display:none;" onchange="previewCover(this)">
                    <div style="font-size:.72rem;color:var(--muted);margin-top:8px;text-align:center;">Semua Format Gambar, maks. 2MB</div>
                    @error('cover_image')<div class="ferr">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex flex-column gap-2 mt-3">
                <button type="submit" class="btn-A w-100 justify-content-center"><i class="bi bi-check-lg"></i> Simpan Buku</button>
                <a href="{{ route('admin.buku.index') }}" class="btn-G w-100 justify-content-center">Batal</a>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function previewCover(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('cover-img').src = e.target.result;
            document.getElementById('cover-img').style.display = 'block';
            document.getElementById('cover-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
