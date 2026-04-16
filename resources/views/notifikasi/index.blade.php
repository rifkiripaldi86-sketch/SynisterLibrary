@extends('layouts.app')
@section('title', 'Notifikasi — Synister Library')
@section('page-title', 'Notifikasi')

@push('styles')
<style>
    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        transition: background .15s;
        position: relative;
        cursor: default;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: #FAFAFA; }

    .notif-item.unread { background: rgba(138,109,26,.04); }
    .notif-item.unread:hover { background: rgba(138,109,26,.07); }
    .notif-item.unread::before {
        content: '';
        position: absolute;
        left: 0; top: 50%; transform: translateY(-50%);
        width: 3px; height: 60%;
        background: var(--amber);
        border-radius: 0 2px 2px 0;
    }

    .notif-icon {
        width: 38px; height: 38px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem;
        flex-shrink: 0;
        border: 1px solid var(--border);
    }
    .notif-icon.warning { background: var(--amber-bg); color: var(--amber); border-color: rgba(138,109,26,.15); }
    .notif-icon.danger  { background: var(--red-bg);   color: var(--red);   border-color: rgba(184,50,50,.15); }
    .notif-icon.success { background: var(--green-bg); color: var(--green); border-color: rgba(45,125,70,.15); }
    .notif-icon.info    { background: var(--blue-bg);  color: var(--blue);  border-color: rgba(42,95,165,.15); }
</style>
@endpush

@section('content')

{{-- HEADER ROW: judul kiri, tombol hapus kanan --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div style="font-size:.82rem;color:var(--muted);">
        <i class="bi bi-bell me-1"></i>
        {{ $notifikasi->total() }} notifikasi
        @if($notifikasi->where('is_read', false)->count() > 0)
            &middot; <span style="color:var(--amber);font-weight:500;">{{ $notifikasi->where('is_read', false)->count() }} belum dibaca</span>
        @endif
    </div>
    @if($notifikasi->total() > 0)
    <form action="{{ route('notifikasi.hapus') }}" method="POST"
          onsubmit="return confirm('Hapus SEMUA notifikasi? Tindakan ini tidak bisa dibatalkan.')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-ghost" style="font-size:.78rem;padding:6px 12px;">
            <i class="bi bi-trash"></i> Hapus Semua
        </button>
    </form>
    @endif
</div>

{{-- NOTIF CARD --}}
<div class="card-d">

    @forelse($notifikasi as $notif)
    <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
        <div class="notif-icon {{ $notif->warna() }}">
            <i class="bi {{ $notif->icon() }}"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:3px;">
                <div style="font-size:.875rem;font-weight:{{ $notif->is_read ? '400' : '600' }};color:var(--text);">
                    {{ $notif->judul }}
                </div>
                @if(!$notif->is_read)
                    <span style="width:7px;height:7px;min-width:7px;background:var(--amber);border-radius:50%;"></span>
                @endif
            </div>
            <div style="font-size:.82rem;color:var(--muted);line-height:1.6;margin-bottom:6px;">
                {{ $notif->pesan }}
            </div>
            <div style="font-size:.72rem;color:var(--muted);display:flex;align-items:center;gap:6px;">
                <i class="bi bi-clock"></i>
                {{ $notif->created_at->locale('id')->diffForHumans() }}
                <span style="opacity:.4;">&middot;</span>
                {{ $notif->created_at->format('d M Y, H:i') }}
            </div>
        </div>
    </div>

    @empty
    <div style="padding:64px 20px;text-align:center;">
        <div style="width:64px;height:64px;border-radius:50%;background:var(--bg);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="bi bi-bell-slash" style="font-size:1.6rem;color:#CCCCCC;"></i>
        </div>
        <div style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--text);margin-bottom:6px;">
            Tidak ada notifikasi
        </div>
        <div style="font-size:.84rem;color:var(--muted);">
            Semua aktivitas peminjaman akan muncul di sini.
        </div>
    </div>
    @endforelse

</div>

{{-- PAGINATION --}}
@if($notifikasi->hasPages())
<div class="mt-3">
    {{ $notifikasi->links() }}
</div>
@endif

@endsection
