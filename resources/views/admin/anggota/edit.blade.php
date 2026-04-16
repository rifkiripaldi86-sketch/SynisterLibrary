@extends('layouts.app')

@section('title', 'Edit Anggota — Synister Library')
@section('page-title', 'Edit Anggota')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.anggota.index') }}" class="btn-G"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card-d">
            <div class="cd-head d-flex align-items-center gap-3">
                <div class="u-avatar" style="width:38px;height:38px;font-size:.9rem;">{{ strtoupper(substr($anggotum->name, 0, 1)) }}</div>
                <div>
                    <div style="font-weight:500;">{{ $anggotum->name }}</div>
                    <div style="font-size:.74rem;color:var(--muted);">NIS: {{ $anggotum->no_induk }} · Kelas {{ $anggotum->kelas }}</div>
                </div>
            </div>
            <div class="cd-body">
                <form action="{{ route('admin.anggota.update', $anggotum->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="flbl">Nama Lengkap <span style="color:var(--red)">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $anggotum->name) }}" class="fctrl">
                            @error('name')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Username <span style="color:var(--red)">*</span></label>
                            <input type="text" name="username" value="{{ old('username', $anggotum->username) }}" class="fctrl">
                            @error('username')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">NIS <span style="color:var(--red)">*</span></label>
                            <input type="text" name="no_induk" value="{{ old('no_induk', $anggotum->no_induk) }}" class="fctrl">
                            @error('no_induk')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Kelas <span style="color:var(--red)">*</span></label>
                            <input type="text" name="kelas" value="{{ old('kelas', $anggotum->kelas) }}" class="fctrl">
                            @error('kelas')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="flbl">Password Baru</label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="pwd" class="fctrl" placeholder="Kosongkan jika tidak diganti" style="padding-right:40px;">
                                <button type="button" onclick="togglePwd()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;padding:0;"><i class="bi bi-eye" id="pwd-icon"></i></button>
                            </div>
                            @error('password')<div class="ferr">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex gap-2 mt-2">
                            <button type="submit" class="btn-A"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
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
