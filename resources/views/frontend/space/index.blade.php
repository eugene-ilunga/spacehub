@extends('frontend.layout')

@php
$title = $pageHeading->spaces_page_title ?? __('Spaces');
@endphp

{{-- Sets meta tag info (title, description, keywords, OG tags) via component --}}
<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@php
$position = $currencyInfo->base_currency_symbol_position;
$symbol = $currencyInfo->base_currency_symbol;

@endphp

@section('content')
<!-- Breadcrumb start -->
<div class="breadcrumb-area breadcrumb-area_v2 d-none d-xl-block header-next bg-primary-light">
    <div class="listing-map">
        <div id="main-map" class="h-100"></div>
    </div>
</div>
<!-- Breadcrumb end -->

<!-- Listing-area start -->

<div class="listing-area pt-50 ">
    <div class="container">
        <div class="row gx-xl-5">
            @include('frontend.space.sidebar')
            <div class="col-xl-9" data-aos="fade-up">
                @include('frontend.space.show-result')

                @include('frontend.space.space-listing')

            </div>
        </div>
    </div>
</div>
<!-- Listing-area end -->
<form class="d-none" action="{{ route('space.index') }}" method="GET" id="searchForm">
    <input type="hidden" id="keyword-id" name="keyword"
        value="{{ !empty(request()->input('keyword')) ? request()->input('keyword') : '' }}">
    <input type="hidden" id="country-search-id" name="country"
        value="{{ !empty(request()->input('country')) ? request()->input('country') : '' }}">
    <input type="hidden" id="state-search-id" name="state"
        value="{{ !empty(request()->input('state')) ? request()->input('state') : '' }}">
    <input type="hidden" id="city-search-id" name="city"
        value="{{ !empty(request()->input('city')) ? request()->input('city') : '' }}">
    <input type="hidden" id="space-location-id" name="location"
        value="{{ !empty(request()->input('location')) ? request()->input('location') : '' }}">

    <input type="hidden" id="guest-capacity-id" name="guest_capacity"
        value="{{ !empty(request()->input('guest-capacity')) ? request()->input('guest-capacity') : '' }}">

    <input type="hidden" id="category-id" name="category"
        value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">

    <input type="hidden" id="subcategory-id" name="subcategory"
        value="{{ !empty(request()->input('subcategory')) ? request()->input('subcategory') : '' }}">

    <input type="hidden" id="space-rent-id" name="space_rent"
        value="{{ !empty(request()->input('space_rent')) ? request()->input('space_rent') : '' }}">

    <input type="hidden" id="rating-id" name="rating"
        value="{{ !empty(request()->input('rating')) ? request()->input('rating') : '' }}">

    <input type="hidden" id="min-id" name="min"
        value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">

    <input type="hidden" id="max-id" name="max"
        value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">

    <input type="hidden" id="sorting-search-id" name="sort"
        value="{{ !empty(request()->input('sort')) ? request()->input('sort') : '' }}">
    <input type="hidden" id="space-type-search-id" name="space_type">
    <input type="hidden" id="space-get-quote-search-id" name="get_quote_status">
    <input type="hidden" id="event-date-search-id" name="event_date"
        value="{{ !empty(request()->input('event-date')) ? request()->input('event-date') : '' }}">
    <input type="hidden" id="custom-hour-search-id" name="custom_hour">
    <input type="hidden" id="startDateForMultiday" name="start_date_for_multiday">
    <input type="hidden" id="endDateForMultiday" name="end_date_for_multiday">
    <input type="hidden" id="eventDateAndTimeForHourlyRental" name="event_date_and_time_for_hourly_rental">
    <input type="hidden" id="eventDateAndTimeForFixedTimeSlot" name="event_date_and_time_for_fixed_time_slot">
    <button type="submit" id="submitBtn"></button>
</form>

<!-- Modal for Map View -->
<div class="modal  fade mapviewModal " id="mapModal" tabindex="-1" aria-labelledby="mapModal"
    aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ __('Google Maps') }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div id="modal-main-map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Map View -->

@endsection
@section('variables')
<script>
    var featuredSpace = {!! json_encode($featuredSpaces) !!};
        var space_listings = {!! json_encode($spaces) !!};
        var timeFormatSpace = {!! json_encode($basicInfo->time_format ?? '') !!};
        var spaceUrl = "{{ route('space.index') }}";
        var selectState = "{{ __('Select State') }}";
        var selectCity = "{{ __('Select City') }}";
        var selectCountry = "{{ __('Select Country') }}";
        var allText = "{{ __('All') }}";
</script>
@endsection

@section('custom-script')
@if ($basicInfo->google_map_api_key_status == 1)
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ $basicInfo->google_map_api_key }}&libraries=places&callback=initMap"
    async defer></script>
@endif


<script type="text/javascript">
        var overallMin = '{{ $overallMin ?? 0 }}';
        var searchFromHome = '{{ $searchFromHome}}';
        var overallMax = '{{ $overallMax ?? 1000 }}';
        var getStatesDataUrl = "{{ route('frontend.space.filter.get_states_by_country') }}";
        var getCitiesDataUrl = "{{ route('frontend.space.filter.get_cities_by_state') }}";
        var spaceSearchUrl = "{{ route('frontend.space.search') }}";
        var spaceDetailsUrl = "{{ route('space.details') }}";
        var spaceDataAccordingToTypeUrl = "{{ route('frontend.space.filter.fetch_space_data') }}";
        var google_map_api_key = '{{ $basicInfo->google_map_api_key }}';
        var spaceDetailsRoute = "{{ route('space.details', ['slug' => ':slug', 'id' => ':id']) }}";
        var loadCountryUrl = "{{ route('web.get_countries') }}";
        var loadStateUrl = "{{ route('web.get_states') }}";
        var loadCityUrl = "{{ route('web.get_cities') }}";
</script>
<!-- Leaflet Map JS -->
<script src="{{ asset('assets/frontend/js/vendors/leaflet.js') }}"></script>
<script src="{{ asset('assets/frontend/js/vendors/leaflet.markercluster.js') }}"></script>
<script src="{{ asset('assets/frontend/js/map.js') }}"></script>

<script src="{{ asset('assets/frontend/js/space-filtering.js') }}"></script>
@endsection
