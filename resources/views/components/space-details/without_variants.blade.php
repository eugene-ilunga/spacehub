@props([
    'serviceContent',
    'index',
    'position',
    'symbol',
    'space',
    'key' => null,
    'serviceType' => 'withoutSubservice',
])
<div class="addons">
    <div id="checkbox-container" class="custom-checkbox p-20 bg-white border-bottom">
        <input class="input-checkbox" type="checkbox" name="checkbox" id="checkbox-{{ $index ?? '' }}"
            value="{{ $serviceContent->id ?? '' }}" data-price="{{ $serviceContent->price ?? '' }}"
            data-space-service-id="{{ $serviceContent->id ?? '' }}"
            data-is_custom_day="{{ $serviceContent->is_custom_day ?? '' }}"
            data-space_type="{{ $space->space_type ?? '' }}" data-price_type="{{ $serviceContent->price_type ?? '' }}"
            data-has_subservcie_available="{{ @$serviceContent->has_sub_services }}">
        <label class="form-check-label" for="checkbox-{{ $index ?? '' }}">
            <span class="h6 mb-0">
                <span class="title">{{ @$serviceContent->title_without_subservice }}</span>
                <span
                    class="qty">{{ $position == 'left' ? $symbol : '' }}{{ @$serviceContent->price }}{{ $position == 'right' ? $symbol : '' }}{{ $serviceContent->price_type == 'fixed' ? '' : '/' . __('Person') }}
                </span>
            </span>
        </label>
        @if (isset($space) && !empty($space->space_type) && $space->space_type == 3 && $serviceContent->is_custom_day == 1)
        <div class="selectedDay-{{ $serviceContent->id }} d-none">
            <p class="card_text px-20 py-1 font-sm mb-0 text-start">
                <span class="ms-1 fw-medium numberOfCustomDay-{{ $serviceContent->id }}"></span>
                <span class="day-text">{{ __('Day') }}</span>
                <span class="btn-label ms-1 dayModalEditBtnWithoutSubservice" data-index_value="{{ @$key }}"
                    data-service_id="{{ @$serviceContent->id }}" data-service_type="{{ @$serviceType }}"
                    data-sub_service_id="{{ @$subservice->id }}" data-space-space_type="{{ @$space->space_type }}">
                    <i class="fas fa-edit"></i>
                </span>
            </p>
        </div>
    @endif
    </div>
    @php
        $serviceType = 'withoutSubservice';
    @endphp
    
</div>
