<div class="col-xl-3">
    <div class="widget-offcanvas offcanvas-xl offcanvas-start" tabindex="-1" id="widgetOffcanvas"
        aria-labelledby="widgetOffcanvas">
        <div class="offcanvas-header px-20">
            <h4 class="offcanvas-title">{{ __('Filter') }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#widgetOffcanvas"
                aria-label="Close"><i class="fal fa-times"></i></button>
        </div>
        <div class="offcanvas-body p-3 p-lg-0">
            <input type="hidden" name="initial_min_price" value="{{ $min }}" id="initial_min_price">
            <input type="hidden" name="initial_max_price" value="{{ $max }}" id="initial_max_price">
            <aside class="widget-area px-20" data-aos="fade-up">
                <form action="{{ route('shop.products') }}" method="get" id="searchProductForm">
                    <div class="widget py-20">
                        <h5 class="title">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#input" aria-expanded="true" aria-controls="input">
                                {{ __('Product Name') }}
                            </button>
                        </h5>
                        <div id="input" class="collapse show">
                            <div class="accordion-body scroll-y mt-20">
                                <input type="text" name="product_name" value="{{ request()->input('product_name') }}"
                                    placeholder="{{ __('Search By Title') }}" id="searchByProductName"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="widget py-20">
                        <h5 class="title">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#brands" aria-expanded="true" aria-controls="brands">
                                {{ __('Categories') }}
                            </button>
                        </h5>
                        <div id="brands" class="collapse show">
                            <div class="accordion-body scroll-y mt-20">
                                <ul class="list-group custom-radio toggle-list" data-toggle-list="pricingToggle"
                                    data-toggle-show="6">
                                    <li>
                                        <input class="input-radio" type="radio"
                                            onclick="document.getElementById('searchProductForm').submit()"
                                            name="category" id="radio1" value=""
                                            {{ empty(request()->input('category')) ? 'checked' : '' }}>
                                        <label class="form-radio-label"
                                            for="radio1"><span>{{ __('All') }}</span><span
                                                class="qty">({{ $total_products_category_based }})</span></label>
                                    </li>
                                    @foreach ($uniqueCategories as $category)
                                        <li>
                                            <input class="input-radio" type="radio"
                                                onclick="document.getElementById('searchProductForm').submit()"
                                                name="category" id="radio1-{{ $loop->iteration }}"
                                                value="{{ $category->slug }}"
                                                {{ request()->input('category') == $category->slug ? 'checked' : '' }}>
                                            <label class="form-radio-label"
                                                for="radio1-{{ $loop->iteration }}"><span>{{ $category->name }}</span>
                                                @php
                                                    $numberOfProduct = \App\Models\Shop\ProductContent::where([
                                                        ['product_category_id', $category->product_category_id],
                                                        ['language_id', $category->language_id],
                                                    ])
                                                        ->distinct()
                                                        ->get()
                                                        ->count();
                                                @endphp
                                                <span class="qty">({{ $numberOfProduct }})</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                @php
                                    $showMoreText = __('Show More') . ' +';
                                    $showLessText = __('Show Less') . ' -';
                                @endphp
                                <span class="show-more mt-15" data-toggle-btn="toggleListBtn"
                                    data-show-more="{{ $showMoreText }}" data-show-less="{{ $showLessText }}">
                                    {{ __('Show More') . ' +' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="widget widget-price py-20">
                        <h5 class="title">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#price" aria-expanded="true" aria-controls="price">
                                {{ __('Pricing') }}
                            </button>
                        </h5>
                        <div id="price" class="collapse show">
                            <div class="accordion-body mt-20 scroll-y">
                                <div class="row gx-sm-3">
                                    <div class="col-md-6">
                                        <div class="form-group mb-20">
                                            <label class="mb-1">{{ __('Minimum') }}</label>
                                            <input class="form-control size-md radius-0" type="number" name="min"
                                                id="min">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-20">
                                            <label class="mb-1">{{ __('Maximum') }}</label>
                                            <input class="form-control size-md radius-0" type="number"
                                                name="max" id="max">
                                        </div>
                                    </div>
                                </div>
                                <div class="price-item">
                                    <div class="price-slider" data-range-slider='filterPriceSlider'></div>
                                    <div class="price-value">
                                        <span>{{ __('Price') }}:
                                            <span class="filter-price-range"
                                                data-range-value='filterPriceSliderValue'></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="widget widget-ratings py-20">
                        <h5 class="title">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#ratings" aria-expanded="true" aria-controls="ratings">
                                {{ __('Ratings') }}
                            </button>
                        </h5>
                        <div id="ratings" class="collapse show">
                            <div class="accordion-body scroll-y mt-20">
                                <ul class="list-group custom-radio rating-list">
                                    <li>
                                        <input class="input-radio" type="radio"
                                            onclick="document.getElementById('searchProductForm').submit()"
                                            name="rating" id="radioR" value=""
                                            {{ empty(request()->input('rating')) ? 'checked' : '' }}>
                                        <label class="form-radio-label"
                                            for="radioR"><span>{{ __('All') }}</span></label>
                                    </li>
                                    <li>
                                        <input class="input-radio" type="radio"
                                            onclick="document.getElementById('searchProductForm').submit()"
                                            name="rating" id="radioR-5" value="5"
                                            {{ request()->input('rating') == 5 ? 'checked' : '' }}>
                                        <label class="form-radio-label" for="radioR-5"><span>{{ __('5 Stars') }}
                                            </span>
                                        </label>
                                    </li>
                                    <li>
                                        <input class="input-radio" type="radio"
                                            onclick="document.getElementById('searchProductForm').submit()"
                                            name="rating" id="radioR-4" value="4"
                                            {{ request()->input('rating') == 4 ? 'checked' : '' }}>
                                        <label class="form-radio-label"
                                            for="radioR-4"><span>{{ __('4 Stars & Above') }}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <input class="input-radio" type="radio"
                                            onclick="document.getElementById('searchProductForm').submit()"
                                            name="rating" id="radioR-3" value="3"
                                            {{ request()->input('rating') == 3 ? 'checked' : '' }}>
                                        <label class="form-radio-label"
                                            for="radioR-3"><span>{{ __('3 Stars & Above') }}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <input class="input-radio" type="radio"
                                            onclick="document.getElementById('searchProductForm').submit()"
                                            name="rating" id="radioR-2" value="2"
                                            {{ request()->input('rating') == 2 ? 'checked' : '' }}>
                                        <label class="form-radio-label"
                                            for="radioR-2"><span>{{ __('2 Stars & Above') }}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <input class="input-radio" type="radio"
                                            onclick="document.getElementById('searchProductForm').submit()"
                                            name="rating" id="radioR-1" value="1"
                                            {{ request()->input('rating') == 1 ? 'checked' : '' }}>
                                        <label class="form-radio-label"
                                            for="radioR-1"><span>{{ __('1 Star & Above') }}</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="cta">
                        <a href="{{ route('shop.products') }}" class="btn btn-lg btn-primary icon-start w-100">
                            <i class="fal fa-sync-alt"></i>{{ __('Reset All') }}
                        </a>
                    </div>
                </form>
                <!-- Spacer -->
                <div class="pb-40"></div>
                {{-- banner --}}
                @if (!empty(showAd(1)))
                    <div class="text-center pb-30">
                        {!! showAd(1) !!}
                    </div>
                @endif
            </aside>

        </div>

    </div>
</div>
