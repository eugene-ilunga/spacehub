<div class="space-skeleton-wrapper" style="display:none;">
    <div id="space-skeleton-loader">
        <div class="row">
            @for ($i = 0; $i < 12; $i++)
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="skeleton-card">
                        <div class="skeleton-img"></div>
                        <div class="skeleton-content">
                            <div class="skeleton-line medium"></div>
                            <div class="skeleton-line long"></div>
                            <div class="d-flex align-items-center justify-content-between mt-2" style="gap:10px">
                                <div class="skeleton-line short" style="margin-bottom:0;width:40%;"></div>
                                <div class="skeleton-circle"></div>
                            </div>
                            <div class="skeleton-line short" style="margin-top:12px;width:50%;"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
<div class="space-container" id="wishlist-div">

    @if ($total_spaces === 0)
        <div class="row">
            <div class="col">
                <h3 class="text-center mt-5">{{ __('No Space Found!') }}</h3>
            </div>
        </div>
    @else
        <div id="actual-space-list">
            <div class="row">
                @foreach ($featuredSpaces as $space)
                    <x-space.featured :space="$space" :position="$position" :symbol="$symbol" />
                @endforeach

                @foreach ($spaces as $space)
                    <x-space.regular :space="$space" :position="$position" :symbol="$symbol" />
                @endforeach
            </div>
        </div>
    @endif
    <nav class="pagination-nav mb-25 asad">
        <ul class="space-search-pagination">
            {{ $spaces->appends([
                    'keyword' => request()->input('keyword'),
                    'rating' => request()->input('rating'),
                    'sort' => request()->input('sort'),
                    'country' => request()->input('country'),
                    'state' => request()->input('state'),
                    'city' => request()->input('city'),
                    'location' => request()->input('location'),
                    'vendor' => request()->input('vendor'),
                    'guest_capacity' => request()->input('guest_capacity'),
                    'category' => request()->input('category'),
                ])->links() }}
        </ul>

    </nav>
    <!-- Spacer -->
    <div class="pb-25"></div>

    @if (!empty(showAd(3)))
        <div class="text-center mt-2 mb-40">
            {!! showAd(3) !!}
        </div>
    @endif
</div>
