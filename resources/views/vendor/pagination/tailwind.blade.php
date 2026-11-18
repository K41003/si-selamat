@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Navigasi Halaman') }}" class="flex items-center justify-between">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex min-h-9 items-center rounded-control border border-line bg-canvas/60 px-3.5 text-sm font-medium text-ink-500 cursor-not-allowed">
                    &laquo; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex min-h-9 items-center rounded-control border border-line bg-surface px-3.5 text-sm font-medium text-ink-700 hover:bg-canvas hover:text-ink-900 transition-colors">
                    &laquo; Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex min-h-9 items-center rounded-control border border-line bg-surface px-3.5 text-sm font-medium text-ink-700 hover:bg-canvas hover:text-ink-900 transition-colors">
                    Berikutnya &raquo;
                </a>
            @else
                <span class="inline-flex min-h-9 items-center rounded-control border border-line bg-canvas/60 px-3.5 text-sm font-medium text-ink-500 cursor-not-allowed">
                    Berikutnya &raquo;
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-ink-600">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-ink-900">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-semibold text-ink-900">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-semibold text-ink-900">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="inline-flex rounded-control border border-line shadow-xs overflow-hidden">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Sebelumnya">
                            <span class="inline-flex min-h-9 items-center border-r border-line bg-canvas/60 px-3 text-sm text-ink-500 cursor-not-allowed" aria-hidden="true">
                                <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex min-h-9 items-center border-r border-line bg-surface px-3 text-sm text-ink-700 hover:bg-canvas hover:text-ink-900 transition-colors" aria-label="Sebelumnya">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex min-h-9 items-center border-r border-line bg-surface px-3.5 text-sm font-medium text-ink-500 cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex min-h-9 items-center border-r border-line bg-brand-700 px-3.5 text-sm font-semibold text-white cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex min-h-9 items-center border-r border-line bg-surface px-3.5 text-sm font-medium text-ink-700 hover:bg-canvas hover:text-ink-900 transition-colors" aria-label="Halaman {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex min-h-9 items-center bg-surface px-3 text-sm text-ink-700 hover:bg-canvas hover:text-ink-900 transition-colors" aria-label="Berikutnya">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Berikutnya">
                            <span class="inline-flex min-h-9 items-center bg-canvas/60 px-3 text-sm text-ink-500 cursor-not-allowed" aria-hidden="true">
                                <svg class="size-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
