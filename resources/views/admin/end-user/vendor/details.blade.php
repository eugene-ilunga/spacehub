@extends('admin.layout') 

@php
    $seller_id = $seller->id;
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
        <h4 class="page-title">{{ __('Registered Vendors') }}</h4>
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
                <a href="#">{{ __('Vendors Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                    href="{{ route('admin.end-user.vendor.registered_vendor', ['language' => $defaultLang->code]) }}">{{ __('Registered Vendors') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Vendor Details') }}</a>
            </li>
        </ul>
        <a href="{{ route('admin.end-user.vendor.registered_vendor') }}?language={{ $defaultLang->code }}"
            class="btn btn-primary ml-auto">{{ __('Back') }}</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="h4 card-title">{{ __('Vendor Information') }}</div>
                            <h2 class="text-center">
                                @if ($seller->photo != null)
                                    <img class="admin-seller-photo rounded-circle"
                                        src="{{ asset('assets/admin/img/seller-photo/' . $seller->photo) }}" alt="..."
                                        class="uploaded-img">
                                @else
                                    <img class="admin-seller-photo rounded-circle"
                                        src="{{ asset('assets/frontend/images/vendor/vendor.png') }}" alt="..." class="uploaded-img">
                                @endif

                            </h2>
                        </div>

                        <div class="card-body">
                            <div class="payment-information">

                                @php
                                    $currPackage = \App\Http\Helpers\SellerPermissionHelper::currPackageOrPending(
                                        $seller->id,
                                    );
                                    $currMemb = \App\Http\Helpers\SellerPermissionHelper::currMembOrPending(
                                        $seller->id,
                                    );
                                @endphp
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <strong>{{ __('Current Package') . ': ' }}</strong>
                                    </div>
                                    <div class="col-lg-6">
                                        @if ($currPackage)
                                            <a target="_blank"
                                                href="{{ route('admin.package.edit', $currPackage->id) }}?language={{ $defaultLang->code }}">{{ __($currPackage->title) }}</a>
                                            <span
                                                class="badge badge-secondary badge-xs mr-2">{{ __(ucfirst($currPackage->term)) }}</span>
                                            <button type="submit" class="btn btn-xs btn-warning" data-toggle="modal"
                                                data-target="#editCurrentPackage"><i class="far fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.end-user.vendor.currPackage_remove') }}"
                                                class="d-inline-block deleteForm" method="POST">
                                                @csrf
                                                <input type="hidden" name="seller_id" value="{{ $seller->id }}">
                                                <button type="submit" class="btn btn-xs btn-danger deleteBtn"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>

                                            <p class="mb-0">
                                                @if ($currMemb->is_trial == 1)
                                                    ({{ __('Expire Date') . ':' }}
                                                    {{ Carbon\Carbon::parse($currMemb->expire_date)->format('M-d-Y') }})
                                                    <span class="badge badge-primary">{{ __('Trial') }}</span>
                                                @else
                                                    ({{ __('Expire Date') . ':' }}
                                                    {{ $currPackage->term === 'lifetime'
                                                        ? __('Lifetime')
                                                        : Carbon\Carbon::parse($currMemb->expire_date)->format('M-d-Y') }})
                                                @endif
                                                @if ($currMemb->status == 0)
                                                    <form id="statusForm{{ $currMemb->id }}" class="d-inline-block"
                                                        action="{{ route('admin.payment-log.update') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $currMemb->id }}">
                                                        <select class="form-control form-control-sm bg-warning"
                                                            name="status"
                                                            onchange="document.getElementById('statusForm{{ $currMemb->id }}').submit();">
                                                            <option value=0 selected>{{ __('Pending') }}</option>
                                                            <option value=1>{{ __('Success') }}</option>
                                                            <option value=2>{{ __('Rejected') }}</option>
                                                        </select>
                                                    </form>
                                                @endif
                                            </p>
                                        @else
                                            <a data-target="#addCurrentPackage" data-toggle="modal"
                                                class="btn btn-xs btn-primary text-white"><i class="fas fa-plus"></i>
                                                {{ __('Add Package') }}</a>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $nextPackage = \App\Http\Helpers\SellerPermissionHelper::nextPackage($seller->id);
                                    $nextMemb = \App\Http\Helpers\SellerPermissionHelper::nextMembership($seller->id);
                                @endphp
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <strong>{{ __('Next Package') . ': ' }}</strong>
                                    </div>
                                    <div class="col-lg-6">
                                        @if ($nextPackage)
                                            <a target="_blank"
                                                href="{{ route('admin.package.edit', $nextPackage->id) }}">{{ $nextPackage->title }}</a>
                                            <span
                                                class="badge badge-secondary badge-xs mr-2">{{ __(ucfirst($nextPackage->term)) }}</span>
                                            <button type="button" class="btn btn-xs btn-warning" data-toggle="modal"
                                                data-target="#editNextPackage"><i class="far fa-edit"></i></button>
                                            <form action="{{ route('admin.end-user.vendor.nextPackage_remove') }}"
                                                class="d-inline-block deleteForm" method="POST">
                                                @csrf
                                                <input type="hidden" name="seller_id" value="{{ $seller->id }}">
                                                <button type="submit" class="btn btn-xs btn-danger deleteBtn"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>

                                            <p class="mb-0">
                                                @if ($currPackage->term != 'lifetime' && $nextMemb->is_trial != 1)
                                                    (
                                                    {{ __('Activation Date') . ':' }}
                                                    {{ Carbon\Carbon::parse($nextMemb->start_date)->format('M-d-Y') }},
                                                    {{ __('Expire Date') . ':' }}
                                                    {{ $nextPackage->term === 'lifetime'
                                                        ? __('Lifetime')
                                                        : Carbon\Carbon::parse($nextMemb->expire_date)->format('M-d-Y') }}
                                                    )
                                                @endif
                                                @if ($nextMemb->status == 0)
                                                    <form id="statusForm{{ $nextMemb->id }}" class="d-inline-block"
                                                        action="{{ route('admin.payment-log.update') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $nextMemb->id }}">
                                                        <select class="form-control form-control-sm bg-warning"
                                                            name="status"
                                                            onchange="document.getElementById('statusForm{{ $nextMemb->id }}').submit();">
                                                            <option value=0 selected>{{ __('Pending') }}</option>
                                                            <option value=1>{{ __('Success') }}</option>
                                                            <option value=2>{{ __('Rejected') }}</option>
                                                        </select>
                                                    </form>
                                                @endif
                                            </p>
                                        @else
                                            @if (!empty($currPackage))
                                                <a class="btn btn-xs btn-primary text-white" data-toggle="modal"
                                                    data-target="#addNextPackage"><i class="fas fa-plus"></i>
                                                    {{ __('Add Package') }}</a>
                                            @else
                                                -
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                @if (!empty($seller->seller_info->name))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('Name') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ @$seller->seller_info->name }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($seller->username))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('Username') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ $seller->username }}
                                        </div>
                                    </div>
                                @endif

                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <strong>{{ __('Email') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {{ $seller->email }}
                                    </div>
                                </div>
                                @if (!empty($seller->phone))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('Phone') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ $seller->phone }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($seller->seller_info->country))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('Country') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ @$seller->seller_info->country }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($seller->seller_info->city))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('City') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ @$seller->seller_info->city }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($seller->seller_info->state))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('State') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ @$seller->seller_info->state }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($seller->seller_info->zip_code))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('Zip Code') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ @$seller->seller_info->zip_code }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($seller->seller_info->address))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('Address') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ @$seller->seller_info->address }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($seller->seller_info->details))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('Details') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ @$seller->seller_info->details }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($seller->seller_info->amount))
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            <strong>{{ __('Balance') . ' :' }}</strong>
                                        </div>
                                        <div class="col-lg-8">
                                            {{ symbolPrice(@$seller->amount) }}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card-title d-inline-block">{{ __('Spaces') }}</div>
                                </div>

                                <div class="col-lg-3">

                                </div>


                                <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                                    <a href="{{ route('admin.space_management.seller_select') }}?language={{ $defaultLang->code }}"
                                        class="btn btn-primary btn-sm float-right">
                                        <i class="fas fa-plus"></i> {{ __('Add Space') }}
                                    </a>

                                    @if ($seller->id != 0)
                                        @if (!is_null($currentPackage))
                                            <a href="#" class="btn btn-secondary mr-2 btn-sm btn-round float-right"
                                                data-toggle="modal" data-target="#packageLimitModal">
                                        @if (
                                            $remainingSpace == 'downgraded' ||
                                                count($remainingAmenities) > 0 ||
                                                count($totalSliderImage) > 0 ||
                                                count($totalServices) > 0 ||
                                                count($totalOptions) > 0)
                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                        @endif
                                                {{ __('Limit of') . ' ' }} {{ @$seller->username }}
                                            </a>
                                        @endif
                                    @endif

                                    <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                        data-href="{{ route('admin.space_management.space.bulk_delete') }}">
                                        <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if ($spaces->isEmpty())
                                        <h3 class="text-center mt-2">{{ __('NO SPACE FOUND') . '!' }}</h3>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped mt-3" id="basic-datatables">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">
                                                            <input type="checkbox" class="bulk-check" data-val="all">
                                                        </th>
                                                        <th scope="col">{{ __('Title') }}</th>
                                                        <th scope="col">{{ __('Vendor') }}</th>
                                                        <th scope="col">{{ __('Category') }}</th>
                                                        <th scope="col">{{ __('Location') }}</th>
                                                        <th scope="col">{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($spaces as $space)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" class="bulk-check"
                                                                    data-val="{{ $space->id }}">
                                                            </td>
                                                            <td>
                                                                <a target="_blank"
                                                                    href="{{ route('space.details', ['slug' => $space->slug, 'id' => $space->id]) }}">{{ strlen($space->title) > 30 ? mb_substr($space->title, 0, 30, 'UTF-8') . '...' : $space->title }}</a>
                                                            </td>
                                                            <td>
                                                                @if (!is_null($space->seller_id))
                                                                    <a target="_blank"
                                                                        href="{{ route('admin.end-user.vendor.details', ['id' => $space->seller_id, 'language' => $defaultLang->code]) }}">{{ @$space->seller->username }}</a>
                                                                @else
                                                                    <span
                                                                        class="badge badge-success">{{ __('Admin') }}</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ @$space->categoryName }}</td>
                                                            <td>{{ @$space->address }}</td>


                                                            <td>
                                                                <div class="dropdown">
                                                                    <button
                                                                        class="btn btn-sm btn-secondary dropdown-toggle"
                                                                        type="button" id="dropdownMenuButton"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false">
                                                                        {{ __('Select') }}
                                                                    </button>

                                                                    <div class="dropdown-menu"
                                                                        aria-labelledby="dropdownMenuButton">
                                                                        <a href="{{ route('admin.space_management.space.edit', ['id' => $space->id, 'language' => $defaultLang->code]) }}"
                                                                            class="dropdown-item">
                                                                            {{ __('Edit') }}
                                                                        </a>

                                                                        <form class="deleteForm d-block"
                                                                            action="{{ route('admin.space_management.space.delete', ['id' => $space->id]) }}"
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
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $sellerId = $seller->id;
    @endphp
        @if ($seller->id != 0)
        @include('admin.partials.limit-check')
    @endif
    @includeIf('admin.end-user.vendor.edit-current-package')
    @includeIf('admin.end-user.vendor.add-current-package')
    @includeIf('admin.end-user.vendor.edit-next-package')
    @includeIf('admin.end-user.vendor.add-next-package')
@endsection
@section('script')
    <script>
        @if (session('openModal'))
            $(document).ready(function() {
                $('#addCurrentPackage').modal('show');
            });
        @endif
    </script>
@endsection
