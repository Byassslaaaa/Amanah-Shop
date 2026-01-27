@if ($paginator->total() > 0)
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-xl p-6 border border-gray-100">
        <!-- Results Info -->
        <div class="text-sm text-gray-600">
            Menampilkan <span class="font-semibold text-[#3BB77E]">{{ $paginator->firstItem() }}</span>
            sampai <span class="font-semibold text-[#3BB77E]">{{ $paginator->lastItem() }}</span>
            dari <span class="font-semibold">{{ $paginator->total() }}</span> produk
        </div>

        <!-- Pagination Links -->
        @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation">
            <ul class="flex items-center gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            « Previous
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-[#3BB77E] hover:text-white hover:border-[#3BB77E] transition-all duration-200">
                            « Previous
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li>
                            <span class="px-4 py-2 text-sm font-medium text-gray-500">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="px-4 py-2 text-sm font-bold text-white bg-[#3BB77E] rounded-lg shadow-md">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}"
                                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-[#3BB77E] hover:text-white hover:border-[#3BB77E] transition-all duration-200">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-[#3BB77E] hover:text-white hover:border-[#3BB77E] transition-all duration-200">
                            Next »
                        </a>
                    </li>
                @else
                    <li>
                        <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Next »
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
        @endif
    </div>
@endif
