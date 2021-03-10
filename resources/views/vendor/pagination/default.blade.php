@if ($paginator->hasPages())
<div class="ui pagination menu" aria-label="pagination">
  {{-- Previous Page Link --}}
  @if ($paginator->onFirstPage())
      <a class="item" disabled>{{ trans('pagination.previous') }}</a>
  @else
      <a class="item" href="{{ $paginator->previousPageUrl() }}">{{ trans('pagination.previous') }}</a>
  @endif

  {{-- Pagination Elements --}}
  @foreach ($elements as $element)
      {{-- "Three Dots" Separator --}}
      @if (is_string($element))
          <li><span class="pagination-ellipsis">&hellip;</span></li>
      @endif

      {{-- Array Of Links --}}
      @if (is_array($element))
          @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                  <a class="active item" aria-label="Page {{ $page }}" aria-current="page">{{ $page }}</a>
              @else
                  <a href="{{ $url }}" class="item" aria-label="Goto page {{ $page }}">{{ $page }}</a>
              @endif
          @endforeach
      @endif
  @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a class="item" href="{{ $paginator->nextPageUrl() }}">{{ trans('pagination.next') }}</a>
    @else
        <a class="item" disabled>{{ trans('pagination.next') }}</a>
    @endif
</div>
@endif
