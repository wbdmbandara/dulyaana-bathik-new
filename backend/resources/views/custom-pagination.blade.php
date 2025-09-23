@if ($paginator->hasPages())
    <nav aria-label="Pagination Navigation" class="mt-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="pagination-info mb-2 mb-md-0">
                <span class="text-muted small">
                    Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong> 
                    of <strong>{{ $paginator->total() }}</strong> results
                </span>
            </div>
            
            <div class="pagination-links">
                <ul class="pagination pagination-sm mb-0 shadow-sm">
                    {{-- First Page Link --}}
                    @if (!$paginator->onFirstPage())
                        <li class="page-item">
                            <a class="page-link text-primary border" href="{{ $paginator->url(1) }}">First</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link bg-light text-muted border">First</span>
                        </li>
                    @endif

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link bg-light text-muted border">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link text-primary border" href="{{ $paginator->previousPageUrl() }}">Previous</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled">
                                <span class="page-link bg-light text-muted border">{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link bg-primary border-primary text-white">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link text-primary border hover-bg-light" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link text-primary border" href="{{ $paginator->nextPageUrl() }}">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link bg-light text-muted border">Next</span>
                        </li>
                    @endif

                    {{-- Last Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link text-primary border" href="{{ $paginator->url($paginator->lastPage()) }}">Last</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link bg-light text-muted border">Last</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif