@props([
    'address' => '',
    'latitude' => null,
    'longitude' => null,
    'websiteTitle' => '',
])

<div class="tab-pane slide" id="tab3">
    <div class="product-location mb-40">
        <h4 class="title mb-20">{{ __('Location') }}</h4>
        <div class="mb-20">
            <i class="fal fa-map-marker-alt" data-tooltip="tooltip" data-bs-placement="top"
                title="{{ __('Space Location') }}"></i>
            <span data-tooltip="tooltip" data-bs-placement="top"
                title="{{ __('Space Location') }}">{{ $address }}</span>
        </div>
        @if ($latitude && $longitude)
            <div class="lazy-container radius-md ratio">
                <div id="map">
                    <iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0"
                        marginwidth="0"
                        src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $latitude }},%20{{ $longitude }}+({{ $websiteTitle }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                </div>
            </div>
        @endif
    </div>
</div>
