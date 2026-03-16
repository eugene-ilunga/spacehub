@php
    $seller_id = $sellerId;
    $current_package = null;
    if ($seller_id != 0) {
        $current_package = \App\Http\Helpers\SellerPermissionHelper::currentPackagePermission($seller_id);
        $language = \App\Models\Language::where('is_default', 1)->select('id', 'code')->first();
        $remainingSpace = \App\Http\Helpers\SellerPermissionHelper::spaceCount($seller_id);
        $remainingAmenities = \App\Http\Helpers\SellerPermissionHelper::amenitiesCount($seller_id);
        $totalSliderImage = \App\Http\Helpers\SellerPermissionHelper::sliderImageCount($seller_id);
        $totalServices = \App\Http\Helpers\SellerPermissionHelper::serviceCount($seller_id);
        $totalOptions = \App\Http\Helpers\SellerPermissionHelper::optionCount($seller_id);
        if ($current_package) {
            $packageFeature = json_decode($current_package->package_feature, true);
        } else {
            $current_package = [];
        }
    }
@endphp

@if ($current_package != null)
    <div class="modal fade" id="packageLimitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="exampleModalLongTitle">{{ __('All Limits') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <span
                            class="text-warning">{{ __("If any feature has crossed its current subscription package's limit") .
                                ', ' .
                                __("then you won't be able to add/edit any other feature") .
                                '.' }}</span>
                    </div>
                    <ul class="list-group list-group-bordered">
                        <li class="list-group-item">
                            <div class="d-flex  justify-content-between">
                                <span class="text-focus">
                                    @if ($remainingSpace == 'downgraded')
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    @endif
                                    {{ $current_package->number_of_space == 1 ? __('Space Left') . ':' : __('Spaces Left') . ':' }}
                                </span>
                                @if ($current_package->number_of_space < 999999)
                                    @if ($remainingSpace == 0)
                                        <span class="badge badge-primary badge-sm">{{ __('Limit is Over') }}</span>
                                    @elseif($remainingSpace == 'downgraded')
                                        <span class="badge badge-danger badge-sm">0</span>
                                    @else
                                        <span class="badge badge-primary badge-sm">{{ $remainingSpace }}</span>
                                    @endif
                                @else
                                    <span class="badge badge-primary badge-sm">{{ __('Unlimited') }}</span>
                                @endif
                            </div>
                            @if ($remainingSpace == 'downgraded')
                                <p class="text-warning mt-3 mb-0">
                                    {{ __('Limit has been crossed, you have to delete the space') }}</p>
                            @endif
                        </li>
                        <li class="list-group-item ">
                            <div class="d-flex  justify-content-between">
                                <span class="text-focus">
                                    @if (count($totalSliderImage) > 0)
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    @endif
                                    {{ $current_package->number_of_slider_image_per_space == 1
                                        ? __('Slider Image Per Space') . ':'
                                        : __('Slider Images Per Space') . ':' }}
                                </span>
                                @if ($current_package->number_of_slider_image_per_space < 999999)
                                    <span
                                        class="badge badge-primary badge-sm">{{ $current_package->number_of_slider_image_per_space }}</span>
                                @else
                                    <span class="badge badge-primary badge-sm">
                                        {{ __('Unlimited') }}
                                    </span>
                                @endif
                            </div>
                            @if (count($totalSliderImage) > 0)
                                <p class="text-warning mt-3 mb-0">
                                    {{ __('Apologies, limit crossed. Remove existing slider images to proceed') }}</p>
                            @endif
                        </li>
                        <li class="list-group-item ">
                            <div class="d-flex  justify-content-between">
                                <span class="text-focus">
                                    @if (count($totalServices) > 0)
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    @endif
                                    {{ $current_package->number_of_service_per_space == 1
                                        ? __('Service Per Space') . ':'
                                        : __('Services Per Space') . ':' }}
                                </span>
                                @if ($current_package->number_of_service_per_space < 999999)
                                    <span
                                        class="badge badge-primary badge-sm">{{ $current_package->number_of_service_per_space }}</span>
                                @else
                                    <span class="badge badge-primary badge-sm">
                                        {{ __('Unlimited') }}
                                    </span>
                                @endif
                            </div>
                            @if (count($totalServices) > 0)
                                <p class="text-warning mt-3 mb-0">
                                    {{ __('Apologies, limit crossed. Remove existing services to proceed') }}</p>
                            @endif
                        </li>
                        <li class="list-group-item ">
                            <div class="d-flex  justify-content-between">
                                <span class="text-focus">
                                    @if (count($totalOptions) > 0)
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    @endif
                                    {{ $current_package->number_of_option_per_service == 1
                                        ? __('Variant Per Service') . ':'
                                        : __('Variants Per Service') . ':' }}
                                </span>
                                @if ($current_package->number_of_option_per_service < 999999)
                                    <span
                                        class="badge badge-primary badge-sm">{{ $current_package->number_of_option_per_service }}</span>
                                @else
                                    <span class="badge badge-primary badge-sm">
                                        {{ __('Unlimited') }}
                                    </span>
                                @endif
                            </div>
                            @if (count($totalOptions) > 0)
                                <p class="text-warning mt-3 mb-0">
                                    {{ __('Apologies, limit crossed. Remove existing variants to proceed') }} </p>
                            @endif
                        </li>
                        <li class="list-group-item ">
                            <div class="d-flex  justify-content-between">
                                <span class="text-focus">
                                    @if (count($remainingAmenities) > 0)
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    @endif
                                    {{ $current_package->number_of_amenities_per_space == 1
                                        ? __('Amenity Per Space') . ':'
                                        : __('Amenities Per Space') . ':' }}
                                </span>
                                @if ($current_package->number_of_amenities_per_space < 999999)
                                    <span
                                        class="badge badge-primary badge-sm">{{ $current_package->number_of_amenities_per_space }}</span>
                                @else
                                    <span class="badge badge-primary badge-sm">
                                        {{ __('Unlimited') }}
                                    </span>
                                @endif
                            </div>
                            @if (count($remainingAmenities) > 0)
                                <p class="text-warning mt-3 mb-0">
                                    {{ __('Apologies, limit crossed. Remove existing amenities to proceed') }} </p>
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"
                        data-target="#packageLimitModal">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

