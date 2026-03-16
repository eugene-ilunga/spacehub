<div class="sort-area sort-area-2">
    <div class="row align-items-center">
        <div class="col-xl-6">
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center gap-2  flex-wrap">
        <div class="d-xl-none">
            <button class="btn btn-sm btn-outline icon-end mb-20" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#widgetOffcanvas" aria-controls="widgetOffcanvas">
                {{ __('Filter') }} <i class="fal fa-filter"></i>
            </button>
        </div>
        <div class="d-block d-xl-none">
            <button class="btn btn-sm btn-outline icon-end mb-20" type="button" data-bs-toggle="modal" data-bs-target="#mapModal" aria-controls="mapModal">
                <i class="fa-light fa-location-dot"></i> {{ __('View Map') }}
            </button>
        </div>
        <div class=" ms-auto">
            <ul class="sort-list list-unstyled mb-20">
                <li class="item">
                    <div class="sort-item d-flex align-items-center">
                        <label class="me-2 font-sm">{{ __('Sort By') }}:</label>
                        <select name="type" class="niceselect right radius-sm sorting-search">
                            <option value="new">{{ __('Newest first') }}</option>
                            <option value="old">{{ __('Oldest first') }}</option>
                            <option value="lowToHigh"> {{ __('Price') . ' : ' . __('Low to high')  }}</option>
                            <option value="highToLow"> {{ __('Price') . ' : ' . __(('High to low')) }}</option>
                        </select>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>
