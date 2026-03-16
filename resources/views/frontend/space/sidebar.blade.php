<div class="col-xl-3" data-aos="fade-up">
    <div class="widget-offcanvas offcanvas-xl offcanvas-start" tabindex="-1" id="widgetOffcanvas"
        aria-labelledby="widgetOffcanvas">
        <div class="offcanvas-header px-20">
            <h4 class="offcanvas-title">{{ __('Filter') }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#widgetOffcanvas"
                aria-label="Close"><i class="fal fa-times"></i></button>
        </div>
        <div class="offcanvas-body p-0">
            <aside class="widget-area spaceSideBarArea px-20">
                <div class="widget widget-amenities py-20">
                    <h5 class="title">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse"
                            data-bs-target="#amenities">
                            {{ __('Categories') }}
                        </button>
                    </h5>
                    <div id="amenities" class="collapse show">
                        <div class="accordion-body mt-20 scroll-y">
                            <div class="widget-area">
                                @if (count($categories) > 0)
                                    <div class="widget widget-categories">
                                        <ul class="widget-link list-unstyled toggle-list "
                                            data-toggle-list="pricingToggle" data-toggle-show="5">
                                            <li class="cat-item">
                                                <a href="#"
                                                    class="category-search  {{ empty(request()->input('category')) ? 'active' : '' }}">
                                                    <i
                                                        class="{{ $currentLanguageInfo->direction == 0 ? 'far fa-angle-right' : 'far fa-angle-left' }}"></i>
                                                    {{ __('All') }}

                                                </a>
                                            </li>
                                            @foreach ($categories as $category)
                                                <li class="cat-item dropdown">
                                                    <a href="#" class="category-search {{ $category->slug == request()->input('category') ? 'active' : '' }}"
                                                        data-category_slug="{{ $category->slug }}">
                                                        <i
                                                            class="{{ $currentLanguageInfo->direction == 0 ? 'far fa-angle-right' : 'far fa-angle-left' }}"></i>
                                                        {{ $category->name }}
                                                    </a>
                                                    @php $subcategories = $category->subcategories; @endphp
                                                
                                                    @if (count($subcategories) > 0)
                                                        <ul class="widget-link list-unstyled widget-subcategories">
                                                            @foreach ($subcategories as $subcategory)
                                                                <li>
                                                                    <a href="#"
                                                                        class="subcategory-search {{ $subcategory->slug == request()->input('subcategory') ? 'active' : '' }}"
                                                                        data-subcategory_slug="{{ $subcategory->slug }}">
                                                                        {{ $subcategory->name }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget widget-ratings py-20">
                    <h5 class="title">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#sort4" aria-expanded="true" aria-controls="sort">
                            {{ __('Type') }}
                        </button>
                    </h5>
                    <div id="sort4" class="collapse show">
                        <div class="accordion-body mt-20 scroll-y">
                            <div class="form-group">
                                <select class="niceselect form-control space-type-select space-type-search"
                                    name="space_type">
                                    <option value="" selected disabled>{{ __('Select Space Type') }}
                                    </option>
                                    <option value=" " {{ request()->get('space_type') == ' ' ? 'selected' : '' }}>{{ __('All') }}</option>
                                    <option value="1" {{  request()->get('space_type') == '1' ? 'selected' : '' }}>{{ __('Fixed Timeslot Rental') }}</option>
                                    <option value="2" {{  request()->get('space_type') == '2' ? 'selected' : '' }}>{{ __('Hourly Rental') }}</option>
                                    <option value="3" {{  request()->get('space_type') == '3' ? 'selected' : '' }}>{{ __('Multi-day Rental') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-none dateRangeSearch pt-20">
                        <div class="form-group">
                            <input type="text" name="event_date" class="form-control checkInDateInSearchPage"
                                id="dateRangeSearch"
                                value="{{ !empty(request()->input('event_date')) ? request()->input('event_date') : '' }}"
                                placeholder="{{ __('Select Date') }}">
                        </div>
                    </div>
                    <div class="d-none inputDateRangePicker pt-20">
                        <form action="#" id="eventDateSearch" method="GET">
                            <div class="form-group ">
                                <input type="text" name="event_date" class="form-control hourly-event-date-search"
                                    id="hourlyEventDate"
                                    value="{{ !empty(request()->input('event_date')) ? request()->input('event_date') : '' }}"
                                    placeholder="{{ __('Select Date and Time') }}">
                                <small class="text-danger d-none" id="eventDateError">
                                    {{ __('This field is required') }}
                                </small>

                            </div>
                            <form action="" id="inputCustomHour">
                                <div class="form-group mt-20">
                                    <input type="text" name="custom_hour" class="form-control input-custom-hour"
                                        value="{{ !empty(request()->input('custom_hour')) ? request()->input('custom_hour') : '' }}"
                                        placeholder="{{ __('Enter hours') }}">
                                    <small class="text-danger d-none"
                                        id="customHourError">{{ __('This field is required') }}</small>
                                </div>
                            </form>
                        </form>
                    </div>

                    <div class="d-none inputEventDateForFixedTimeSlot pt-20">
                        <div class="form-group">
                            <input type="text" name="event_date"
                                class="form-control event-date-search-for-fixed-timeslot checkInEventDateForFixedTimeSlot "
                                id="eventDateForFixedTimeSlot"
                                value="{{ !empty(request()->input('event_date')) ? request()->input('event_date') : '' }}"
                                placeholder="{{ __('Select Date and Time') }}">
                            <small class="text-danger d-none"
                                id="eventDateError">{{ __('This field is required') }}</small>
                        </div>
                    </div>
                </div>

                <div class="widget widget-select py-20">
                    <h5 class="title">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#select">
                            {{ __('Filters') }}
                        </button>
                    </h5>
                    <div id="select" class="collapse show">
                        <div class="accordion-body mt-20 scroll-y">
                            <form action="" id="spaceSearch">
                                <div class="form-group mb-20">
                                    <input type="text" name="keyword" class="form-control input-search"
                                        value="{{ !empty(request()->input('keyword')) ? request()->input('keyword') : '' }}"
                                        placeholder="{{ __('Enter Space Title') }}">
                                </div>
                            </form>

                            <form action="" id="guestCapacitySearch">
                                <div class="form-group mb-20">
                                    <input type="number" name="guest_capacity"
                                        class="form-control guest-capacity-search"
                                        value="{{ !empty(request()->input('guest-capacity')) ? request()->input('guest-capacity') : '' }}"
                                        placeholder="{{ __('Enter Capacity') }}">
                                </div>
                            </form>

                            <div class="form-group mb-20" id="getStateData">
                                <input type="hidden" name="language_id" id="language_id_for_search"
                                    value="{{ $currentLanguageInfo->id }}">
                                <select class="form-control country-select country-search select2 countryDataload"
                                    name="country_id">
                                    <option value="" selected disabled>{{ __('Select Country') }} </option>
                                    <option value=" ">{{ __('All') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-20">
                                <select class="form-control state-select state-search select2 stateDataload" name="state_id">
                                    <option value="" selected disabled>{{ __('Select State') }} </option>
                                    <option value=" ">{{ __('All') }}</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-20 ">
                                <select class=" form-control city-select city-search select2 cityDataload" name="city_id">
                                    <option value="" selected disabled>{{ __('Select City') }}</option>
                                    <option value=" ">{{ __('All') }}</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ request()->input('city') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <form action="" id="locationSearch">
                                <div class="form-group currentLocationgroup">
                                    <input type="text" name="location" id="locationInput"
                                        class="form-control location-search search-address"
                                        value="{{ !empty(request()->input('location')) ? request()->input('location') : '' }}"
                                        placeholder="{{ __('Enter location') . '...' }}">
                                    @if ($websiteInfo->google_map_api_key_status == 1)
                                        <button type="button" class="btn btn-sm current-location"
                                            id="currentLocationButton">
                                            <i class="fas fa-crosshairs"></i>
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="widget widget-ratings py-20">
                    <h5 class="title">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#sort1" aria-expanded="true" aria-controls="sort">
                            {{ __('Rent Type') }}
                        </button>
                    </h5>
                    <div id="sort1" class="collapse show">
                        <div class="accordion-body mt-20 scroll-y">
                            <ul class="list-group custom-radio rent-list" id="custom-radio">
                                <li>
                                    <input class="input-radio space-rent-search" type="radio" id="all-spaces"
                                        name="space_rent" value=""
                                        {{ empty(request()->input('space_rent')) ? 'checked' : '' }}>
                                    <label
                                        class="form-radio-label {{ empty(request()->input('space_rent')) ? 'active-radio' : '' }}"
                                        for="all-spaces">
                                        <span class="rating">{{ __('All') }} </span>
                                    </label>
                                </li>
                                <li>
                                    <input class="input-radio space-rent-search" type="radio" id="rentable-spaces"
                                        name="space_rent" value="rentable_spaces"
                                        {{ request()->input('space_rent') == 'rentable_spaces' ? 'checked' : '' }}>
                                    <label
                                        class="form-radio-label {{ request()->input('space_rent') == 'rentable_spaces' ? 'active-radio' : '' }}"
                                        for="rentable-spaces">
                                        <span class="rating">{{ __('Fixed Rent') }} </span>
                                    </label>
                                </li>
                                <li>
                                    <input class="input-radio space-rent-search" type="radio"
                                        id="non-rentable-spaces" name="space_rent" value="negotiable"
                                        {{ request()->input('space_rent') == 'negotiable' ? 'checked' : '' }}>
                                    <label
                                        class="form-radio-label {{ request()->input('space_rent') == 'negotiable' ? 'active-radio' : '' }}"
                                        for="non-rentable-spaces">
                                        <span class="rating">{{ __('Negotiable') }} </span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="widget widget-price py-20">
                    <h5 class="title">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#price" aria-expanded="true" aria-controls="price">
                            {{ __('Rent') }}
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
                                        <input class="form-control size-md radius-0" type="number" name="max"
                                            id="max">
                                    </div>
                                </div>
                            </div>
                            <div class="price-item">
                                <div class="price-slider" data-range-slider='filterPriceSlider'></div>
                                <div class="price-value">
                                    <span>{{ __('Rent') }}:
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
                            data-bs-target="#sort" aria-expanded="true" aria-controls="sort">
                            {{ __('Ratings') }}
                        </button>
                    </h5>
                    <div id="sort" class="collapse show">
                        <div class="accordion-body mt-20 scroll-y">
                            <ul class="list-group custom-radio rating-list">
                                <li>
                                    <input class="input-radio rating-search" type="radio" name="filter_rating"
                                        id="all" value=""
                                        {{ empty(request()->input('rating')) ? 'checked' : '' }}>
                                    <label class="form-radio-label active-radio" for="all">
                                        <span class="rating">{{ __('All') }} </span>
                                    </label>
                                </li>
                                <li>
                                    <input class="input-radio rating-search" type="radio" name="filter_rating"
                                        id="five-star" value="5"
                                        {{ request()->input('rating') == 5 ? 'checked' : '' }}>
                                    <label class="form-radio-label" for="five-star">
                                        <span class="rating">{{ __('5 Stars') }} </span>

                                    </label>
                                </li>
                                <li>
                                    <input class="input-radio rating-search" type="radio" name="filter_rating"
                                        id="four-star" value="4"
                                        {{ request()->input('rating') == 4 ? 'checked' : '' }}>
                                    <label class="form-radio-label" for="four-star">
                                        <span class="rating"> {{ __('4 Stars & Above') }} </span>

                                    </label>
                                </li>
                                <li>
                                    <input class="input-radio rating-search" type="radio" name="filter_rating"
                                        id="three-star" value="3"
                                        {{ request()->input('rating') == 3 ? 'checked' : '' }}>
                                    <label class="form-radio-label" for="three-star">
                                        <span class="rating">{{ __('3 Stars & Above') }} </span>

                                    </label>
                                </li>
                                <li>
                                    <input class="input-radio rating-search" type="radio" name="filter_rating"
                                        id="two-star" value="2"
                                        {{ request()->input('rating') == 2 ? 'checked' : '' }}>
                                    <label class="form-radio-label" for="two-star">
                                        <span class="rating">{{ __('2 Stars & Above') }}</span>

                                    </label>
                                </li>
                                <li>
                                    <input class="input-radio rating-search" type="radio" name="filter_rating"
                                        id="one-star" value="1"
                                        {{ request()->input('rating') == 1 ? 'checked' : '' }}>
                                    <label class="form-radio-label" for="one-star">
                                        <span class="rating"> {{ __('1 Star & Above') }}
                                        </span>

                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mb-30">
                    <a href="{{ route('space.index') }}" class="btn btn-lg btn-primary radius-sm d-block text-center reset-space-search">
                        {{ __('Reset All') }}
                    </a>
                </div>
                <!-- Spacer -->
                @if (!empty(showAd(4)))
                    <div class="text-center mb-30">
                        {!! showAd(4) !!}
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>
