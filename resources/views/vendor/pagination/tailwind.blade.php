@if ($paginator->hasPages())
<nav class="flex flex-col items-center gap-3" aria-label="Paginación">

    {{-- Mobile: prev / next --}}
    <div class="flex gap-2 sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm border border-[var(--border)] rounded-[var(--radius)] opacity-40 cursor-not-allowed">← Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="inline-flex items-center px-4 py-2 text-sm border border-[var(--border)] rounded-[var(--radius)] hover:bg-[var(--muted)] transition-colors">← Anterior</a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="inline-flex items-center px-4 py-2 text-sm border border-[var(--border)] rounded-[var(--radius)] hover:bg-[var(--muted)] transition-colors">Siguiente →</a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm border border-[var(--border)] rounded-[var(--radius)] opacity-40 cursor-not-allowed">Siguiente →</span>
        @endif
    </div>

    {{-- Desktop: page numbers --}}
    <div class="hidden sm:flex items-center gap-1">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 border border-[var(--border)] rounded-[var(--radius-inner)] opacity-40 cursor-not-allowed">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="inline-flex items-center justify-center w-9 h-9 border border-[var(--border)] rounded-[var(--radius-inner)] hover:bg-[var(--muted)] transition-colors"
               aria-label="{{ __('pagination.previous') }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </a>
        @endif

        {{-- Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 text-sm opacity-40 cursor-default select-none">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                              class="inline-flex items-center justify-center w-9 h-9 text-sm font-semibold bg-[var(--primary)] text-[var(--primary-foreground)] rounded-[var(--radius-inner)] cursor-default select-none">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex items-center justify-center w-9 h-9 text-sm border border-[var(--border)] rounded-[var(--radius-inner)] hover:bg-[var(--muted)] transition-colors"
                           aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="inline-flex items-center justify-center w-9 h-9 border border-[var(--border)] rounded-[var(--radius-inner)] hover:bg-[var(--muted)] transition-colors"
               aria-label="{{ __('pagination.next') }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 border border-[var(--border)] rounded-[var(--radius-inner)] opacity-40 cursor-not-allowed">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            </span>
        @endif

    </div>
</nav>
@endif
