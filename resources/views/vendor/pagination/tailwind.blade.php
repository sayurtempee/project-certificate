@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center mt-4 space-x-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="px-4 py-2 text-sm font-medium text-white rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 hover:opacity-90">
                ‹
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Dots --}}
            @if (is_string($element))
                <span class="px-4 py-2 text-sm font-medium text-gray-500">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        {{-- Halaman Aktif --}}
                        <span
                            class="px-4 py-2 text-sm font-bold text-white rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-500">
                            {{ $page }}
                        </span>
                    @else
                        {{-- Halaman Biasa --}}
                        <a href="{{ $url }}"
                            class="px-4 py-2 text-sm font-medium text-white rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 hover:opacity-90">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="px-4 py-2 text-sm font-medium text-white rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 hover:opacity-90">
                ›
            </a>
        @else
            <span class="px-4 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed">
                ›
            </span>
        @endif
    </nav>
@endif
