@extends('admin.layout')
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
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="card-title d-inline-block">{{ __('Spaces') }}</div>
                    </div>

                    <!-- Title Search with onkeypress -->
                    <div class="col-lg-2">
                        <form action="" method="GET" id="searchForm">
                            <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                            <input type="hidden" name="seller" value="{{ request()->input('seller') }}">
                            <input type="hidden" name="category" value="{{ request()->input('category') }}">
                            <input type="hidden" name="space_type" value="{{ request()->input('space_type') }}">
                            <input type="text" name="title" value="{{ request()->input('title') }}"
                                placeholder="{{ __('Search by title') . '...' }}" class="form-control"
                                onkeypress="if(event.keyCode == 13) document.getElementById('searchForm').submit()">
                        </form>
                    </div>

                    <!-- Category Dropdown -->
                    <div class="col-lg-2">
                        <form action="" method="GET">
                            <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                            <input type="hidden" name="title" value="{{ request()->input('title') }}">
                            <input type="hidden" name="space_type" value="{{ request()->input('space_type') }}">
                            <select name="category" class="form-control select2" onchange="this.form.submit()">
                                <option value="">{{ __('All Categories') }}</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request()->input('category') ==
                                    $category->id)>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <!-- Space Type Dropdown -->
                    <div class="col-lg-2">
                        <form action="" method="GET">
                            <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                            <input type="hidden" name="title" value="{{ request()->input('title') }}">
                            <input type="hidden" name="category" value="{{ request()->input('category') }}">
                            <select name="space_type" class="form-control select2" onchange="this.form.submit()">
                                <option value="">{{ __('All Space Types') }}</option>
                                <option value="1" @selected(request()->input('space_type') == '1')>{{ __('Fixed Timeslot Rental') }}
                                </option>
                                <option value="2" @selected(request()->input('space_type') == '2')>{{ __('Hourly Rental') }}</option>
                                <option value="3" @selected(request()->input('space_type') == '3')>{{ __('Multi-Day Rental') }}
                                </option>
                            </select>
                        </form>
                    </div>

                    <div class="col-lg-2">
                        <form action="" method="GET">
                            <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                            <input type="hidden" name="title" value="{{ request()->input('title') }}">
                            <input type="hidden" name="category" value="{{ request()->input('category') }}">
                            <input type="hidden" name="space_type" value="{{ request()->input('space_type') }}">
                            <select name="seller" id="" class="form-control select2" onchange="this.form.submit()">
                                <option value="" selected>{{ __('All Vendors') }}</option>
                                <option value="admin" @selected(request()->input('seller') == 'admin')>{{ __('admin') }}
                                </option>
                                @foreach ($sellers as $seller)
                                <option @selected($seller->id == request()->input('seller')) value="{{ $seller->id }}">
                                    {{ $seller->username }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="col-lg-2 mt-2 mt-lg-0">
                        <a href="{{ route('admin.space_management.seller_select', ['language' => $defaultLang->code]) }}"
                            class="btn btn-primary btn-sm float-right">
                            <i class="fas fa-plus"></i> {{ __('Add Space') }}
                        </a>

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
                        @if (count($spaces) == 0)
                        <h3 class="text-center mt-2">{{ __('NO SPACE FOUND') . '!' }}</h3>
                        @else
                        <div class="table-responsive">
                            <table class="table spacesAllData table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" class="bulk-check" data-val="all">
                                        </th>
                                        <th scope="col">{{ __('Image') }}</th>
                                        <th scope="col">{{ __('Title') }}</th>
                                        <th scope="col">{{ __('Category') }}</th>
                                        <th scope="col">{{ __('Type') }}</th>
                                        <th scope="col">{{ __('Services') }}</th>
                                        <th scope="col">{{ __('Time Slot') }}</th>
                                        <th scope="col">{{ __('Location') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Vendor') }}</th>
                                        <th scope="col">{{ __('Featured Status') }}</th>
                                        <th scope="col">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($spaces as $space)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check" data-val="{{ $space->id }}">
                                        </td>
                                        <td>
                                            <img src="{{ asset('assets/img/spaces/thumbnail-images/' . $space->thumbnail_image) }}"
                                                alt="client image" width="70" class="mt-2">
                                        </td>
                                        <td>
                                            <a target="_blank"
                                                href="{{ route('space.details', ['slug' => $space->slug, 'id' => $space->id]) }}"
                                                title="{{ @$space->space_title }}">
                                                {{ truncate_text_space_title($space->space_title ?? '', 90) }}

                                            </a>
                                        </td>
                                        <td>
                                           {{ truncate_text_space_title($space->category->name ?? '', 30) }}
                                        </td>
                                        <td>
                                            @if ($space->space_type == 3)
                                            {{ __('Multi-Day Rental') }}
                                            @elseif($space->space_type == 2)
                                            {{ __('Hourly Rental') }}
                                            @elseif($space->space_type == 1)
                                            {{ __('Fixed Timeslot Rental') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($space))
                                            <a href="{{ route('admin.space_management.service.view_services', ['space_id' => $space->id, 'language' => $defaultLang->code]) }}"
                                                class="btn btn-sm btn-info">
                                                {{ __('Manage') }}
                                            </a>
                                            @else
                                            <p class="ml-4">
                                                {{ __('---') }}
                                            </p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($space->space_type == 1)
                                            <a class="btn btn-sm btn-info"
                                                href="{{ route('admin.manage_schedule.time_slot.index', ['space_id' => $space->id, 'language' => $defaultLang->code]) }}">
                                                {{ __('Manage') }}</a>
                                            @else
                                            {{ __('---') }}
                                            @endif
                                        </td>
                                        <td>{{ @$space->address }}</td>
                                        <td>
                                            <h2 class="d-inline-block">
                                                <span
                                                    class="badge badge-{{ $space->status == 1 ? 'success' : 'danger' }}">
                                                    {{ __($space->status == 1 ? 'Active' : 'Deactive') }}
                                                </span>
                                            </h2>
                                        </td>
                                        <td>
                                            @if (!is_null($space->seller_id) && $space->seller_id != 0)
                                            <a target="_blank"
                                                href="{{ route('admin.end-user.vendor.details', ['id' => $space->seller_id, 'language' => $defaultLang->code]) }}">{{
                                                strlen(@$space->seller->username) > 70
                                                ? mb_substr(@$space->seller->username, 0, 70, 'UTF-8') . '...'
                                                : @$space->seller->username }}</a>
                                            @else
                                            <span class="badge badge-success">{{ __('admin') }}</span>
                                            @endif
                                        </td>
                                        @php
                                        $featuredSpace = \App\Models\SpaceFeature::where(
                                        'space_id',
                                        $space->id,
                                        )->first();
                                        @endphp

                                        <td>
                                            @if ($featuredSpace)
                                            @if (is_null($featuredSpace->end_date))
                                            @if ($featuredSpace->booking_status == 'pending')
                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                            @elseif ($featuredSpace->booking_status == 'approved' &&
                                            $featuredSpace->payment_status == 'completed')
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                            @else
                                            <a href="#" data-toggle="modal" data-target="#createModal"
                                                data-space_id="{{ $space->id }}"
                                                class="btn btn-primary btn-sm float-left">
                                                {{ __('Feature It') }}
                                            </a>
                                            @endif
                                            @elseif ($featuredSpace->end_date <= \Carbon\Carbon::now()) <a href="#"
                                                data-toggle="modal" data-target="#createModal"
                                                data-space_id="{{ $space->id }}"
                                                class="btn btn-primary btn-sm float-left">
                                                {{ __('Feature It') }}
                                                </a>
                                                @elseif ($featuredSpace->booking_status == 'approved')
                                                @if ($featuredSpace->seller_id == 0)
                                                <a href="{{ route('admin.space_management.space.update_featured_status', ['id' => $featuredSpace->id]) }}"
                                                    class="btn btn-danger btn-sm float-left">
                                                    {{ __('Unfeature') }}
                                                </a>
                                                @else
                                                <span class="badge badge-success">{{ __('Active') }}</span>
                                                @endif
                                                @elseif ($featuredSpace->booking_status == 'pending')
                                                <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                @else
                                                <a href="#" data-toggle="modal" data-target="#createModal"
                                                    data-space_id="{{ $space->id }}"
                                                    class="btn btn-primary btn-sm float-left">
                                                    {{ __('Feature It') }}
                                                </a>
                                                @endif
                                                @else
                                                <a href="#" data-toggle="modal" data-target="#createModal"
                                                    data-space_id="{{ $space->id }}"
                                                    class="btn btn-primary btn-sm float-left">
                                                    {{ __('Feature It') }}
                                                </a>
                                                @endif
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    {{ __('Select') }}
                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a href="{{ route('admin.space_management.space.edit', ['id' => $space->id, 'language' => $defaultLang->code]) }}"
                                                        class="dropdown-item" data-space_id="{{ $space->id }}">
                                                        {{ __('Edit') }}
                                                    </a>
                                                    <a href="{{ route('admin.manage_weekend.index', ['space_id' => $space->id, 'language' => $defaultLang->code]) }}"
                                                        class="dropdown-item" data-space_id="{{ $space->id }}">
                                                        {{ __('Weekend') }}
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

            <div class="card-footer">
                <div class="pl-3 pr-3 text-center">
                    <div class="d-inline-block mx-auto">
                        {{ $spaces->appends([
                        'language' => $defaultLang->code,
                        'seller' => request()->input('seller'),
                        'title' => request()->input('title'),
                        'category' => request()->input('category'),
                        'space_type' => request()->input('space_type'),
                        ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- create modal --}}
@include('admin.space-management.space.feature-charges')
@endsection
