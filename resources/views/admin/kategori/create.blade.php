@extends('layouts.app')

@section('title', 'Tambah Kategori — Synister Library')
@section('page-title', 'Tambah Kategori')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.kategori.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-6">
        <div class="card-d">
            <div class="cd-head"><i class="bi bi-tag me-2" style="color:var(--amber)"></i>Data Kategori Baru</div>
            <div class="cd-body">
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="flbl">Nama Kategori <span style="color:var(--red)">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" class="fctrl" placeholder="Contoh: Fiksi, Sains, Pemrograman">
                            @error('nama')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="flbl">Deskripsi</label>
                            <textarea name="deskripsi" class="fctrl" rows="3" placeholder="Keterangan singkat tentang kategori ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="flbl">Warna <span style="color:var(--red)">*</span></label>

                            {{-- Palet preset --}}
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @php
                                    $presets = [
                                        '#c9a84c','#6ab0f5','#4caf82','#e05a5a',
                                        '#9b7fe8','#f5a623','#50c8c6','#e87b5a',
                                        '#7ec8a0','#d472a0','#5a9ae8','#aaa85a',
                                    ];
                                @endphp
                                @foreach($presets as $warna)
                                <button type="button"
                                    onclick="pilihWarna('{{ $warna }}')"
                                    style="width:28px;height:28px;border-radius:50%;background:{{ $warna }};border:2px solid rgba(255,255,255,.15);cursor:pointer;transition:transform .15s;"
                                    onmouseover="this.style.transform='scale(1.2)'"
                                    onmouseout="this.style.transform='scale(1)'"
                                    title="{{ $warna }}">
                                </button>
                                @endforeach
                            </div>

                            {{-- Input warna + preview --}}
                            <div class="d-flex align-items-center gap-3">
                                <div id="warna-preview" style="width:40px;height:40px;border-radius:8px;background:{{ old('warna', '#c9a84c') }};border:1px solid rgba(255,255,255,.15);flex-shrink:0;transition:background .2s;"></div>
                                <div style="flex:1;">
                                    <input type="color" id="color-picker" value="{{ old('warna', '#c9a84c') }}"
                                           onchange="pilihWarna(this.value)"
                                           style="width:100%;height:38px;border-radius:6px;border:1px solid rgba(255,255,255,.1);background:var(--navy3);cursor:pointer;">
                                </div>
                                <input type="text" name="warna" id="warna-input" value="{{ old('warna', '#c9a84c') }}"
                                       class="fctrl" style="width:110px;font-family:monospace;"
                                       placeholder="#c9a84c" maxlength="7"
                                       oninput="syncWarna(this.value)">
                            </div>
                            @error('warna')<div class="ferr">{{ $message }}</div>@enderror
                        </div>

                        {{-- PREVIEW BADGE --}}
                        <div class="col-12">
                            <label class="flbl">Preview Badge</label>
                            <div style="padding:14px;background:var(--navy3);border-radius:8px;display:flex;align-items:center;gap:10px;">
                                <div id="preview-dot" style="width:10px;height:10px;border-radius:50%;background:{{ old('warna','#c9a84c') }};"></div>
                                <span id="preview-badge"
                                      style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:.78rem;font-weight:500;background:{{ old('warna','#c9a84c') }}22;color:{{ old('warna','#c9a84c') }};border:1px solid {{ old('warna','#c9a84c') }}44;">
                                    {{ old('nama') ?: 'Nama Kategori' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2 mt-1">
                            <button type="submit" class="btn-A"><i class="bi bi-check-lg"></i> Simpan Kategori</button>
                            <a href="{{ route('admin.kategori.index') }}" class="btn-G">Batal</a>
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
function pilihWarna(hex) {
    document.getElementById('warna-input').value = hex;
    document.getElementById('color-picker').value = hex;
    document.getElementById('warna-preview').style.background = hex;
    document.getElementById('preview-dot').style.background = hex;
    const badge = document.getElementById('preview-badge');
    badge.style.background = hex + '22';
    badge.style.color = hex;
    badge.style.borderColor = hex + '44';
}

function syncWarna(val) {
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        pilihWarna(val);
    }
}

// Update preview nama saat mengetik
document.querySelector('input[name="nama"]')?.addEventListener('input', function() {
    const badge = document.getElementById('preview-badge');
    badge.textContent = this.value || 'Nama Kategori';
});
</script>
@endpush