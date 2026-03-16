@props(['space','position','symbol'])

<div class="col-md-6 col-lg-4 col-xl-6">
    <div class="row align-items-xl-center g-0 product-default product-column border radius-md mb-25">
        <figure class="col-xl-6 product_img radius-sm">
            <a href="{{ route('space.details', ['slug' => $space->slug, 'id' => $space->space_id]) }}" target="_self"
                title="{{ __('Link') }}" class="lazy-container ratio ratio-5-4 radius-sm">
                <img class="lazyload" src="{{ asset('assets/img/spaces/thumbnail-images/' . $space->image) }}"
                    data-src="{{ asset('assets/img/spaces/thumbnail-images/' . $space->image) }}"
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
        <div class="col-xl-6 p-3 product_details">
            <div class="product_title">
                <span class="subtitle">
                    <a href="{{ route('space.index', ['search_from_home' => 'home', 'category' => $space->category_slug]) }}" target="_self"
                        title="{{ @$space->category_title }}" data-category_id="{{ @$space->category_id }}"
                        data-category_slug="{{ @$space->category_slug }}">
                        {{ @$space->category_title }}</a>
                </span>
                <h5 class="title lc-2 mb-0">
                    <a href="{{ route('space.details', ['slug' => $space->slug, 'id' => $space->space_id]) }}"
                        target="_self" title="{{ @$space->title }}">
                        {{ strlen($space->title) > 40 ? mb_substr($space->title, 0, 40, 'UTF-8') . '...' : $space->title }}</a>
                </h5>
            </div>
            <ul class="product-info_list list-unstyled mt-10">
                @if (!empty($space->city_name))
                    <li class="font-sm">
                        <i class="fal fa-map-marker-alt"></i>
                        <span>
                            @if (!empty($space->city_name))
                                {{ $space->city_name }}
                            @endif
                        </span>
                    </li>
                @endif
                <li class="font-sm">
                    <i class="fal fa-user-friends"></i>
                    <span class="ratings-total">{{ __('Capacity') . ':' }}
                        {{ @$space->min_guest }}-{{ @$space->max_guest }}</span>
                </li>
            </ul>
            
            @if ($space->seller_id != 0)
                <div class="product_author mt-20">
                    <a href="{{ route('frontend.seller.details', ['username' => $space->username]) }}" target="_self"
                        title="{{ $space->username }}">
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
                <div class="product_author mt-20">
                    <a href="{{ route('frontend.seller.details', ['username' => $admin->username, 'admin' => true]) }}"
                        target="_self" title="{{ @$admin->username }}">
                        @if (!empty($admin->image))
                            <img class="lazyload blur-up rounded-circle"
                                src="{{ asset('assets/img/admins/' . $admin->image) }}"
                                data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                        @else
                            <img class="lazyload blur-up rounded-circle" src="{{ asset('assets/img/blank-user.jpg') }}"
                                data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Image">
                        @endif

                        <span
                            class="lc-1">{{ strlen($admin->username) > 20 ? mb_substr($admin->username, 0, 20, 'UTF-8') . '..' : ucfirst($admin->username) }}</span>
                    </a>
                </div>
            @endif
            <div class="ratings size-md mt-20">
                <div class="rate bg-img" data-bg-img="{{ asset('assets/frontend/images/rate-star-md.png') }}">
                    <div class="rating-icon bg-img" 
                    style="
                    width: {{ $space->average_rating * 20 }}%;
                    background-image: url({{ asset('assets/frontend/images/rate-star-md.png') }});"
                        data-bg-img="{{ asset('assets/frontend/images/rate-star-md.png') }}">
                    </div>
                </div>
                <span class="ratings-total">{{ '(' . number_format($space->average_rating, 1) . ')' }}</span>
            </div>
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
                    <h6 class="new-price">{{ __('Negotiable') }}</h6>
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
                        <h6 class="new-price fw-medium">{{ __('Negotiable') }}
                        </h6>
                    @endif
                    
                @endif
            </div>
        </div>
    </div>
    <!-- product-default -->
</div>
