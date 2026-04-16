@extends('layouts.app')

@section('title', 'Tambah Anggota — Synister Library')
@section('page-title', 'Tambah Anggota')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.anggota.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card-d">
            <div class="cd-head"><i class="bi bi-person-plus me-2" style="color:var(--amber)"></i>Data Anggota Baru</div>
            <div class="cd-body">
                <form action="{{ route('admin.anggota.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="flbl">Nama Lengkap <span style="color:var(--red)">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="fctrl" placeholder="Nama lengkap siswa">
                            @error('name')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Username <span style="color:var(--red)">*</span></label>
                            <input type="text" name="username" value="{{ old('username') }}" class="fctrl" placeholder="username untuk login">
                            @error('username')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">NIS (No. Induk Siswa) <span style="color:var(--red)">*</span></label>
                            <input type="text" name="no_induk" value="{{ old('no_induk') }}" class="fctrl" placeholder="Contoh: 2024001">
                            @error('no_induk')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Kelas <span style="color:var(--red)">*</span></label>
                            <input type="text" name="kelas" value="{{ old('kelas') }}" class="fctrl" placeholder="Contoh: X-IPA-1">
                            @error('kelas')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Password <span style="color:var(--red)">*</span></label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="pwd" class="fctrl" placeholder="Min. 6 karakter" style="padding-right:40px;">
                                <button type="button" onclick="togglePwd()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;padding:0;"><i class="bi bi-eye" id="pwd-icon"></i></button>
                            </div>
                            @error('password')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mt-2">
                            <div style="display:flex;gap:8px;padding:12px 14px;background:rgba(201,168,76,.07);border-radius:8px;border:1px solid rgba(201,168,76,.15);">
                                <i class="bi bi-info-circle-fill" style="color:var(--amber);flex-shrink:0;margin-top:2px;"></i>
                                <div style="font-size:.8rem;color:var(--muted);">Akun akan dibuat dengan role <strong style="color:var(--cream);">siswa</strong>. Siswa bisa login menggunakan username dan password yang diisi di sini.</div>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 mt-2">
                            <button type="submit" class="btn-A"><i class="bi bi-check-lg"></i> Simpan Anggota</button>
                            <a href="{{ route('admin.anggota.index') }}" class="btn-G">Batal</a>
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
function togglePwd() {
    const pwd = document.getElementById('pwd');
    const icon = document.getElementById('pwd-icon');
    if (pwd.type === 'password') { pwd.type = 'text'; icon.className = 'bi bi-eye-slash'; }
    else { pwd.type = 'password'; icon.className = 'bi bi-eye'; }
}
</script>
@endpush
