@php
    $position = $currencyInfo->base_currency_symbol_position;
    $symbol = $currencyInfo->base_currency_symbol;

@endphp
@if ($total_spaces === 0)
    <div class="row">
        <div class="col">
            <h3 class="text-center mt-5">{{ __('No Space Founds') . '!' }}</h3>
        </div>
    </div>
@else
    <div class="row">
        @foreach ($featuredSpaces as $space)
            <x-space.featured :space="$space" :position="$position" :symbol="$symbol" />
        @endforeach
        @foreach ($spaces as $space)
            <x-space.regular :space="$space" :position="$position" :symbol="$symbol" />
        @endforeach

    </div>
@endif

<nav class="pagination-nav mb-25">
    <ul class="space-search-pagination">
        {{ $spaces->appends([
                'keyword' => request()->input('keyword'),
                'rating' => request()->input('rating'),
                'sort' => request()->input('sort'),
                'country' => request()->input('country'),
                'state' => request()->input('state'),
                'city' => request()->input('city'),
                'vendor' => request()->input('vendor'),
                'guest_capacity' => request()->input('guest_capacity'),
                'category' => request()->input('category'),
            ])->links() }}
    </ul>

</nav>
<!-- Spacer -->
<div class="pb-25"></div>
@if (!empty(showAd(3)))
    <div class="text-center mt-4">
        {!! showAd(3) !!}
    </div>
@endif
