@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

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

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Spaces') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Space Management') }}</a>
            </li>

            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Spaces') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            @if (count($spaces) > 0)
                @foreach ($spaces as $space)
                    <li class="nav-item">
                        <a
                            href="{{ route('admin.space_management.space.index', ['language' => $defaultLang->code]) }}">{{ $space->title }}</a>
                    </li>
                @endforeach
            @endif
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Services') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">

                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Services') }} </div>
                        </div>
                        <div class="col-lg-3">

                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="{{ route('admin.space_management.service.partial_create', ['id' => request()->space_id, 'language' => $defaultLang->code]) }}"
                                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                                {{ __('Add Service') }}</a>

                            <a href="{{ route('admin.space_management.space.index', ['language' => $defaultLang->code]) }}"
                                class="btn btn-primary btn-sm float-lg-right mr-2 float-left">

                                <span class="btn-label">
                                    <i class="fas fa-backward mdb_12"></i>
                                </span>
                                {{ __('Back') }}
                            </a>

                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('admin.space_management.service.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                            @php
                                $vendorName = \App\Models\Seller::select()->where('id', $sellerId)->first();
                            @endphp
                            @if ($sellerId != 0)
                                @if (!is_null($currentPackage))
                                    <a href="#" class="btn  btn-secondary mr-2  btn-sm btn-round float-right"
                                        data-toggle="modal" data-target="#packageLimitModal">
                                        @if (
                                            $remainingSpace == 'downgraded' ||
                                                count($remainingAmenities) > 0 ||
                                                count($totalSliderImage) > 0 ||
                                                count($totalServices) > 0 ||
                                                count($totalOptions) > 0)
                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                        @endif
                                        {{ __('Limit of') . ' ' }} {{ @$vendorName->username }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($services) == 0)
                                <h3 class="text-center mt-2">{{ __('NO SERVICE FOUND FOR THIS SPACE') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('Price Type') }}</th>
                                                <th scope="col">
                                                    {{ __('Price') }} ({{ $basic->base_currency_text }})
                                                </th>

                                                <th scope="col">{{ __('Variants') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Serial Number') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($services as $service)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $service->id }}">
                                                    </td>
                                                    <td>
                                                        {{ strlen($service->title) > 50 ? mb_substr($service->title, 0, 50, 'UTF-8') . '...' : $service->title }}
                                                    </td>
                                                    <td>{{ __(ucfirst($service->price_type)) }}</td>
                                                    @if ($service->has_sub_services == 0)
                                                        <td>{{ @$service->price }}</td>
                                                    @else
                                                        <td> {{ '---' }}</td>
                                                    @endif
                                                    <td>
                                                        @if (@$service->has_sub_services == 1)
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#optionModal-{{ $service->id }}"
                                                                class="btn btn-success btn-sm">
                                                                {{ __('View') }}</a>
                                                        @else
                                                            <p class="ml-4">
                                                                {{ __('---') }}
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (@$service->status == 1)
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-success">{{ __('Active') }}</span>
                                                            </h2>
                                                        @else
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-danger">{{ __('Deactive') }}</span>
                                                            </h2>
                                                        @endif
                                                    </td>

                                                    <td>{{ @$service->serial_number }}</td>
                                                    <td>

                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ __('Select') }}
                                                            </button>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a href="{{ route('admin.space_management.service.edit', ['space_id' => $space_id, 'id' => $service->id, 'language' => $defaultLang->code]) }}"
                                                                    class="dropdown-item">
                                                                    {{ __('Edit') }}
                                                                    <input type="hidden" name="space_id"
                                                                        value="{{ @$space_id }}">
                                                                </a>

                                                                <form class="deleteForm d-block"
                                                                    action="{{ route('admin.space_management.service.delete', ['id' => $service->id]) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <button type="submit" class="deleteBtn">
                                                                        {{ __('Delete') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @if ($sellerId != 0)
        @include('admin.partials.limit-check')
    @endif
    @include('admin.space-management.sub-service.index', ['services' => $services])

@endsection
