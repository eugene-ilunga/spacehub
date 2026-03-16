@props([
    'amenities' => [],
    'amenityIds' => [],
])

@php
    // If amenities not provided but IDs are, fetch amenities
    if (empty($amenities) && !empty($amenityIds)) {
        $amenities = \App\Models\SpaceAmenity::query()->whereIn('id', $amenityIds)->get();
    }
@endphp

<div class="tab-pane slide" id="tab2">
    <div class="product-amenities mb-40" data-aos="fade-up">
        <h4 class="title mb-20">{{ __('Amenities') }}</h4>
        <ul class="amenities-list list-unstyled">
            @forelse($amenities as $amenity)
                <li class="border p-25" data-tooltip="tooltip" data-bs-placement="top" title="{{ $amenity->name }}">
                    <div class="icon mb-25">
                        <i class="{{ $amenity->icon }}"></i>
                    </div>
                    <span class="h6 mb-0">{{ $amenity->name }}</span>
                </li>
            @empty
                <li class="text-muted">{{ __('No amenities listed') }}</li>
            @endforelse
        </ul>
    </div>
</div>
