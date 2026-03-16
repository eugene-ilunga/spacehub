<div class="swiper-slide">
    <div class="product-default product-default-2 border radius-md mb-25">
        <!-- product_img -->
        <figure class="product_img radius-sm">
            <a href="{{ route('space.details', ['slug' => $space->slug, 'id' => $space->space_id]) }}" target="_self"
                title="{{ __('Link') }}" class="lazy-container ratio ratio-5-4 radius-sm">
                <img class="lazyload" src="{{ asset('assets/img/spaces/thumbnail-images/' . $space->thumbnail_image) }}"
                    data-src="{{ asset('assets/img/spaces/thumbnail-images/' . $space->thumbnail_image) }}"
                    alt="{{ @$space->title }}">
            </a>

            <form id="spaceWishlistForm"
                action="{{ route('space.update.wishlist', ['id' => $space->space_id, 'slug' => $space->slug]) }}"
                method="POST">
                @csrf
                <button type="submit" id="spaceWishlist"
                    class="btn btn-icon radius-sm spaceWishlist {{ @$space->wishlisted == true ? 'active' : '' }} "
                    data-tooltip="tooltip" data-bs-placement="top"
                    title=" {{ @$space->wishlisted == true ? __('Remove from wishlist') : __('Save to Wishlist') }} ">
                    @auth('web')
                        <i class="fal fa-bookmark  "></i>
                    @endauth

                    @guest('web')
                        <i class="fal fa-bookmark"></i>
                    @endguest
                </button>
            </form>
            <div class="hover-show">
                <a href="{{ route('space.details', ['slug' => $space->slug, 'id' => $space->space_id]) }}"
                    class="btn btn-md btn-primary radius-sm" title="{{ __('More Details') }}"
                    target="_self">{{ __('More Details') }}</a>
            </div>
        </figure>
        <!-- product_details -->
        <div class="product_details p-15">
            <!-- product_title -->
            <div class="product_title">
                <div class="d-flex flex-wrap gap-10 justify-content-between align-items-center mb-2 product-top">
                    <span class="subtitle lc-1 small fw-medium">
                        <a href="{{ route('space.index', ['category' => $space->category_slug]) }}" target="_self"
                            class="" title="{{ @$space->category_title }}"
                            data-category_id="{{ @$space->space_category_id }}"
                            data-category_slug="{{ @$space->category_slug }}">
                            {{ @$space->category_title }}
                        </a>
                    </span>

                    @if ($space->seller_id != 0)
                        <div class="product_author">
                            <a href="{{ route('frontend.seller.details', ['username' => $space->username]) }}"
                                target="_self" title="{{ $space->username }}">
                                @if (!is_null($space->seller_image))
                                    <img class="lazyload blur-up rounded-circle"
                                        src="{{ asset('assets/admin/img/seller-photo/' . $space->seller_image) }}"
                                        data-src="{{ asset('assets/admin/img/seller-photo/' . $space->seller_image) }}"
                                        alt="Image">
                                @else
                                    <img class="lazyload blur-up rounded-circle"
                                        src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                        data-src="{{ asset('assets/frontend/images/vendor/vendor.png') }}"
                                        alt="Image">
                                @endif
                                <span
                                    class="lc-1">{{ strlen($space->username) > 20 ? mb_substr($space->username, 0, 20, 'UTF-8') . '..' : ucfirst($space->username) }}</span>
                            </a>
                        </div>
                    @else
                        @php
                            $admin = App\Models\Admin::first();

                        @endphp
                        <div class="product_author">
                            <a href="{{ route('frontend.seller.details', ['username' => $admin->username, 'admin' => true]) }}"
                                target="_self" title="{{ @$admin->username }}">
                                @if (!empty($admin->image))
                                    <img class="lazyload blur-up rounded-circle"
                                        src="{{ asset('assets/img/admins/' . $admin->image) }}"
                                        data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                @else
                                    <img class="lazyload blur-up rounded-circle"
                                        src="{{ asset('assets/img/blank-user.jpg') }}"
                                        data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Image">
                                @endif
                                <span
                                    class="lc-1">{{ strlen($admin->username) > 20 ? mb_substr($admin->username, 0, 20, 'UTF-8') . '..' : ucfirst($admin->username) }}</span>
                            </a>
                        </div>
                    @endif
                </div>
                <h5 class="title lc-2 mb-0">
                    <a href="{{ route('space.details', ['slug' => $space->slug, 'id' => $space->space_id]) }}"
                        target="_self" title="{{ @$space->title }}">

                        {{ strlen($space->title) > 40 ? mb_substr($space->title, 0, 40, 'UTF-8') . '...' : $space->title }}
                    </a>
                </h5>
            </div>
            <!-- address -->
            <div class="address mt-10">
                @if (!empty($space->city_name))
                    <p class="font-sm fw-medium">
                        <i class="fal fa-map-marker-alt"></i>
                        <span>
                            {{ $space->city_name }}
                            @if (!empty($space->state_name))
                                , {{ $space->state_name }}
                            @endif
                            @if (!empty($space->country_name))
                                , {{ $space->country_name }}
                            @endif
                        </span>
                    </p>
                @endif
            </div>
            <!-- info_list -->
            <div class="product-info_list list-unstyled">
                <div class="font-sm fw-medium d-inline-block mt-2" data-tooltip="tooltip" data-bs-placement="top"
                    title="{{ __('Space Capacity') }}">
                    <i class="fal fa-user-friends"></i>
                    <span class="ratings-total">
                        {{ @$space->min_guest }}-{{ @$space->max_guest }}</span>
                </div>

                <div class="ratings size-md">
                    <div class="rate bg-img" data-bg-img="{{ asset('assets/frontend/images/rate-star-md.png') }}">
                        <div class="rating-icon bg-img"
                            style="
                            width: {{ $space->average_rating * 20 }}%; 
                            background-image: url({{ asset('assets/frontend/images/rate-star-md.png') }});"
                            data-bg-img="{{ asset('assets/frontend/images/rate-star-md.png') }}">
                        </div>
                    </div>
                    <span
                        class="ratings-total">{{ number_format($space->average_rating, 1) . ' ' . '(' . $space->reviewCount . ')' }}</span>
                </div>
            </div>
            <!-- product_price -->
            <div class="product_price mt-10">
                @php
                    // Get minimum slot price if use_slot_rent is enabled
                    $minSlotPrice =
                        $space->use_slot_rent == 1
                            ? App\Models\TimeSlot::where('space_id', $space->space_id)->min('time_slot_rent')
                            : null;
                @endphp
                <span></span>
                @if (empty($space->space_rent) &&
                        $space->use_slot_rent == 0 &&
                        empty($space->rent_per_hour) &&
                        empty($space->price_per_day))
                    <h5 class="new-price fw-medium">{{ __('Negotiable') }}</h5>
                @else
                    {{-- Priority 1: Time slot rent --}}
                    @if ($space->use_slot_rent == 1 && !empty($minSlotPrice))
                        <h6 class="new-price fw-medium">
                            {{ __('Rent') . ': ' . __('From') . ' ' }}{{ $position == 'left' ? $symbol : '' }}{{ $minSlotPrice }}{{ $position == 'right' ? $symbol : '' }}
                        </h6>

                        {{-- Priority 2: Standard space rent --}}
                    @elseif(!empty($space->space_rent) && $space->use_slot_rent == 0)
                        <h6 class="new-price fw-medium">
                            {{ __('Rent') . ': ' . __('From') . ' ' }}{{ $position == 'left' ? $symbol : '' }}{{ $space->space_rent }}{{ $position == 'right' ? $symbol : '' }}
                        </h6>

                        {{-- Priority 3: Hourly rent --}}
                    @elseif(!empty($space->rent_per_hour))
                        <h6 class="new-price fw-medium">
                            {{ __('Rent') . ': ' }}{{ $position == 'left' ? $symbol : '' }}{{ $space->rent_per_hour }}{{ $position == 'right' ? $symbol : '' }}
                            / <span class="hour-text">{{ __('Hour') }}</span>
                        </h6>

                        {{-- Priority 4: Daily rent --}}
                    @elseif(!empty($space->price_per_day))
                        <h6 class="new-price fw-medium">
                            {{ __('Rent') . ': ' }}{{ $position == 'left' ? $symbol : '' }}{{ $space->price_per_day }}{{ $position == 'right' ? $symbol : '' }}
                            / <span class="day-text">{{ __('Day') }}</span>
                        </h6>

                        {{-- Fallback if no price is available but the initial check passed --}}
                    @else
                        <h6 class="new-price fw-medium">{{ __('Negotiable') }}</h6>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
