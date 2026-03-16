@props(['subservice', 'serviceContent', 'space', 'position', 'symbol', 'key', 'parentIndex'])

<div class="col-md-6 col-xl-4">
    <div class="card bg-none mb-20">
        <div class="card_img mb-1">
            <div class="image-checkbox">
                <input type="checkbox" class="image-checkbox-input" name="image-{{ $parentIndex }}[]"
                    id="img-{{ $subservice->id }}" value="{{ $subservice->id }}" data-img="{{ $subservice->image ?? '' }}"
                    data-price_type="{{ $serviceContent->price_type ?? '' }}"
                    data-space-service-id="{{ $serviceContent->id ?? '' }}"
                    data-sub-service-id="{{ $subservice->id ?? '' }}"
                    data-is_custom_day="{{ $serviceContent->is_custom_day ?? '' }}"
                    data-index_value="{{ @$key }}"
                    data-subservice_selection_type="{{ @$serviceContent->subservice_selection_type }}"
                    data-index_value="{{ @$key }}" data-space_type="{{ $space->space_type ?? '' }}"
                    data-has_subservcie_available="{{ @$serviceContent->has_sub_services }}"
                    data-price="{{ $subservice->price ?? '' }}">
                <label for="img-{{ $subservice->id }}">
                    <div class="lazy-container ratio ratio-5-4 radius-sm">
                        <img class="lazyload"
                            src="{{ asset('assets/img/sub-services/thumbnail-images/' . $subservice->image) }}"
                            data-src="{{ asset('assets/img/sub-services/thumbnail-images/' . $subservice->image) }}"
                            alt="{{ $subservice->service_title ?? '' }}">
                    </div>
                    <span class="btn btn-sm btn-primary no-animation radius-sm">{{ __('Selected') }}</span>
                </label>
            </div>
            @if (isset($space) && !empty($space->space_type) && $space->space_type == 3 && $serviceContent->is_custom_day == 1)
                <div class="selectedDay-{{ $subservice->id }} d-none">
                    <p class="card_text font-sm mb-0">
                        <span class="numberOfCustomDay-{{ $subservice->id }}"></span>
                        <span class="day-text">{{ __('Days') }}</span>
                        <span class="btn-label dayModalEditBtn" data-index_value="{{ @$key }}"
                            data-service_id="{{ @$serviceContent->id }}" data-sub_service_id="{{ @$subservice->id }}"
                            data-space-space_type="{{ @$space->space_type }}">
                            <i class="fas fa-edit"></i>
                        </span>
                    </p>
                </div>
            @endif
        </div>
        <div class="card_content">
            <p class="serviceStageTitle card_text font-sm mb-0">
                {{ $subservice->sub_service_title ?? '' }}
            </p>
            <p class="serviceStagePrice card_text font-sm">
                {{ $position == 'left' ? $symbol : '' }}{{ $subservice->price ?? '' }}{{ $position == 'right' ? $symbol : '' }}{{ $serviceContent->price_type == 'fixed' ? '' : '/' . __('Person') }}
            </p>
        </div>
    </div>
</div>
