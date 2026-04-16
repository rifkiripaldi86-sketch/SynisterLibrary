@if ($paginator->hasPages())
<nav>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

        {{-- Info --}}
        <div style="font-size:.78rem;color:var(--muted);">
            Menampilkan
            <strong style="color:var(--text);">{{ $paginator->firstItem() }}</strong>–<strong style="color:var(--text);">{{ $paginator->lastItem() }}</strong>
            dari <strong style="color:var(--text);">{{ $paginator->total() }}</strong> data
        </div>

        {{-- Tombol --}}
        <div class="d-flex align-items-center gap-1 flex-wrap">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="btn-G" style="opacity:.4;cursor:not-allowed;padding:6px 12px;font-size:.78rem;">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn-G" style="padding:6px 12px;font-size:.78rem;">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="font-size:.78rem;color:var(--muted);padding:0 4px;">…</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn-A" style="padding:6px 11px;font-size:.78rem;cursor:default;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="btn-G" style="padding:6px 11px;font-size:.78rem;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn-G" style="padding:6px 12px;font-size:.78rem;">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="btn-G" style="opacity:.4;cursor:not-allowed;padding:6px 12px;font-size:.78rem;">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif

        </div>
    </div>
</nav>
@endif
