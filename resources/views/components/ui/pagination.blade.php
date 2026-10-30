@if ($paginator->hasPages())
    <nav aria-label="{{ __('ui.pagination.label') }}" class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-muted" data-num>
            {{ __('ui.pagination.showing', ['first' => $paginator->firstItem(), 'last' => $paginator->lastItem(), 'total' => $paginator->total()]) }}
        </p>
        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="inline-flex min-h-11 items-center gap-1 rounded px-3 text-sm font-semibold text-muted" aria-disabled="true"><x-ui.icon name="chevron-left" size="18" />{{ __('ui.pagination.previous') }}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex min-h-11 items-center gap-1 rounded border border-line-strong bg-papel px-3 text-sm font-semibold transition-colors hover:bg-hover"><x-ui.icon name="chevron-left" size="18" />{{ __('ui.pagination.previous') }}</a>
            @endif
            <span class="px-2 text-sm text-muted" data-num>{{ __('ui.pagination.page', ['current' => $paginator->currentPage(), 'last' => $paginator->lastPage()]) }}</span>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex min-h-11 items-center gap-1 rounded border border-line-strong bg-papel px-3 text-sm font-semibold transition-colors hover:bg-hover">{{ __('ui.pagination.next') }}<x-ui.icon name="chevron-right" size="18" /></a>
            @else
                <span class="inline-flex min-h-11 items-center gap-1 rounded px-3 text-sm font-semibold text-muted" aria-disabled="true">{{ __('ui.pagination.next') }}<x-ui.icon name="chevron-right" size="18" /></span>
            @endif
        </div>
    </nav>
@endif
