@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl_style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Amenities') }}</h4>
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
                <a href="#">{{ __('Specifications') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Amenities') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <!-- Title Column -->
                        <div class="col-md-4 col-12 mb-2 mb-md-0">
                            <h4 class="card-title mb-0 d-inline-block">{{ __('Amenities') }}</h4>
                        </div>

                        <!-- Search Form Column -->
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                            <form id="filterForm" action="{{ url()->current() }}" method="GET">
                                <input type="text" name="name" class="form-control"
                                    placeholder="{{ __('Search by name') }}..." value="{{ request('name') }}"
                                    onkeypress="if(event.keyCode == 13) this.form.submit()">
                            </form>
                        </div>

                        <!-- Action Buttons Column -->
                        <div class="col-md-5 col-12 text-md-right text-left">
                            <button class="btn btn-danger btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('admin.space_management.amenities.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>

                            <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('Add Amenity') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($amenities) == 0)
                                <h3 class="text-center">{{ __('NO SPACE AMENITY FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Icon') }}</th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('Serial Number') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($amenities as $amenity)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $amenity->id ?? '' }}">
                                                    </td>
                                                    <td><i class="{{ $amenity->icon }}"></i></td>
                                                    <td>
                                                        {{ $amenity->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $amenity->serial_number ?? '' }}
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm mr-1 editBtn" href="#"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $amenity->id ?? '' }}"
                                                            data-language="{{ $amenity->language_id ?? '' }}"
                                                            data-name="{{ $amenity->name ?? '' }}"
                                                            data-icon="{{ $amenity->icon ?? '' }}"
                                                            data-serial_number="{{ $amenity->serial_number ?? '' }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit') }}
                                                        </a>

                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.space_management.amenities.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $amenity->id ?? '' }}">

                                                            <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
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
                            {{ $amenities->appends([
                                    'language' => $defaultLang->code,
                                    'name' => request('name'),
                                ])->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- create modal --}}
    @include('admin.space-management.amenities.create')

    {{-- edit modal --}}
    @include('admin.space-management.amenities.edit')
@endsection
