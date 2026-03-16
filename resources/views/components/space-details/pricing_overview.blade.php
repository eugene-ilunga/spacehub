@props([
    'spaceContent' => null,
    'space' => null,
    'position' => 'left',
    'symbol' => '$',
])
<div class="widget widget-select p-20 bg-primary-light">
    <h4 class="title mb-20"> {{ __('Pricing Overview') }}
    </h4>
    <ul class="list-group pb-30">
        <li id="timeSlotRentWrapper" class="d-none">
            <span class="h6 mb-0">{{ __('Time Slot Rent') }}</span>
            <span class="h6 mb-0 timeSlotRentValue"></span>
        </li>
        @if (isset($spaceContent) &&
                !empty($spaceContent->space_rent) &&
                $spaceContent->space_type != 3 &&
                $spaceContent->space_type != 2)
            <li id="spaceRent">
                <span class="h6 mb-0">{{ __('Total Rent') }}</span>
                <span
                    class="h6 mb-0 spaceRent">{{ $position == 'left' ? $symbol : '' }}{{ number_format($spaceContent->space_rent ?? 0,2)  }}{{ $position == 'right' ? $symbol : '' }}</span>
            </li>
        @endif
        @if (isset($spaceContent) && $spaceContent->space_type == 3 && !empty($spaceContent->price_per_day))
            <li id="rentPerDay">
                <span class="h6 mb-0">{{ __('Rent Per Day') }}</span>
                <span
                    class="h6 mb-0 rentPerDay">{{ $position == 'left' ? $symbol : '' }}{{ @$spaceContent->price_per_day }}{{ $position == 'right' ? $symbol : '' }}</span>
            </li>
        @endif

        @if (isset($spaceContent) && $spaceContent->space_type == 2 && !empty($spaceContent->rent_per_hour))
            <li id="rentPerHour">
                <span class="h6 mb-0">{{ __('Rent Per Hour') }}</span>
                <span
                    class="h6 mb-0 rentPerHour">{{ $position == 'left' ? $symbol : '' }}{{ @$spaceContent->rent_per_hour }}{{ $position == 'right' ? $symbol : '' }}</span>
            </li>
        @endif
        @if (isset($spaceContent) && $spaceContent->space_type == 3)
            <li>
                <span class="h6 mb-0 numberOfDayTextOneOrZero">{{ __('Number of Day') }}</span>
                <span class="h6 mb-0 d-none numberOfDayTextMoreThanOne">{{ __('Number of Days') }}</span>
                <span class="h6 mb-0 numberOfDay">0</span>
            </li>
        @endif

        <li>
            <span class="h6 mb-0">{{ __('Services Total') }}</span>
            <span
                class="h6 mb-0 serviceTotal">{{ $position == 'left' ? $symbol : '' }}0.00{{ $position == 'right' ? $symbol : '' }}</span>
        </li>
        @if (isset($spaceContent) && $spaceContent->space_type == 2)
            <li>
                <span class="h6 mb-0 numberOfHourTextOneOrZero">{{ __('Total Hour') }}</span>
                <span class="h6 mb-0 numberOfHourTextMoreThanOne d-none">{{ __('Total Hours') }}</span>
                <span class="h6 mb-0 totalHourForSpaceType2">0</span>
            </li>
        @endif
        <li id="subTotalAmount">
            <span class="h6 mb-0 color-primary"> {{ __('Subtotal') }}</span>
            <span class="h6 mb-0 color-primary subTotalAmount">
                @if (isset($spaceContent) &&
                        !empty($spaceContent->space_rent) &&
                        $spaceContent->space_type != 3 &&
                        $spaceContent->space_type != 2)
                    {{ $position == 'left' ? $symbol : '' }}{{ number_format($spaceContent->space_rent, 2) }}
                @else
                    0.00
                @endif
                {{ $position == 'right' ? $symbol : '' }}
            </span>
        </li>
    </ul>

    @if (isset($space) && optional($space)->booking_status == 1)
    @else
        <button id="confirmBookingButton" class="btn btn-lg btn-primary radius-sm w-100" type="submit"
            aria-label="button"> {{ __('Proceed To Pay') }}
        </button>
    @endif
</div>
