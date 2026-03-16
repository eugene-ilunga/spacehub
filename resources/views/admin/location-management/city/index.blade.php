@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Cities') }}</h4>
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
                <a href="#">{{ __('Locations') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Cities') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3 mb-2 mb-lg-0">
                            <div class="card-title d-inline-block">{{ __('Cities') }}</div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <form id="filterForm" action="{{ route('admin.location_management.city.index') }}"
                                method="GET" class="row">
                                <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Search by name') }}..." value="{{ request('name') }}"
                                        onkeypress="if(event.keyCode == 13) this.form.submit()">
                                </div>
                                <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                                    <input type="text" name="state" class="form-control"
                                        placeholder="{{ __('Search by state') }}..." value="{{ request('state') }}"
                                        onkeypress="if(event.keyCode == 13) this.form.submit()">
                                </div>
                                <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                                    <input type="text" name="country" class="form-control"
                                        placeholder="{{ __('Search by country') }}..." value="{{ request('country') }}"
                                        onkeypress="if(event.keyCode == 13) this.form.submit()">
                                </div>
                            </form>

                        </div>

                        <div class="col-lg-3 col-12 text-lg-right text-left mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal"
                                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                                {{ __('Add') }}</a>

                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('admin.location_management.city.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($cities) === 0)
                                <h3 class="text-center mt-2">{{ __('NO CITY FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('State') }}</th>
                                                <th scope="col">{{ __('Country') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Featured') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cities as $city)
                                                <tr>

                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ @$city->id }}">
                                                    </td>
                                                    <td>
                                                        {{ strlen(@$city->name) > 50 ? mb_substr(@$city->name, 0, 50, 'UTF-8') . '...' : @$city->name }}

                                                    </td>
                                                    @if ($city->state_name)
                                                        <td>
                                                            {{ strlen(@$city->state_name) > 50 ? mb_substr(@$city->state_name, 0, 50, 'UTF-8') . '...' : @$city->state_name }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            {{ '---' }}
                                                        </td>
                                                    @endif
                                                    @if ($city->country_name)
                                                        <td>
                                                            {{ strlen(@$city->country_name) > 50 ? mb_substr(@$city->country_name, 0, 50, 'UTF-8') . '...' : @$city->country_name }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            {{ '---' }}
                                                        </td>
                                                    @endif

                                                    <td>
                                                        @if (@$city->status == 1)
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-success">{{ __('Active') }}</span>
                                                            </h2>
                                                        @else
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-danger">{{ __('Deactive') }}</span>
                                                            </h2>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form id="featuredForm-{{ $city->id }}" class="d-inline-block"
                                                            action="{{ route('admin.location_management.city.feature_update', $city->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm @if ($city->is_featured) bg-success @else bg-danger @endif"
                                                                name="is_featured" onchange="this.form.submit()">
                                                                <option value="1"
                                                                    {{ $city->is_featured ? 'selected' : '' }}>
                                                                    {{ __('Yes') }}
                                                                </option>
                                                                <option value="0"
                                                                    {{ !$city->is_featured ? 'selected' : '' }}>
                                                                    {{ __('No') }}
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </td>

                                                    <td>

                                                        <a class="btn btn-secondary btn-sm mr-1 spaceEditBtn" href="#"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $city->id }}" data-name="{{ $city->name }}"
                                                            data-country_name="{{ $city->country_name }}"
                                                            data-country_id="{{ $city->country_id }}"
                                                            data-state_id="{{ $city->state_id }}"
                                                            data-status="{{ $city->status }}"
                                                            data-image="{{ asset('./assets/img/city/' . $city->image) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit') }}
                                                        </a>

                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.location_management.city.destroy') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ @$city->id }}">

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
                            {{ $cities->appends([
                                    'language' => $defaultLang->code,
                                    'name' => request('name'),
                                    'state' => request('state'),
                                    'country' => request('country'),
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php  $langId = \App\Models\Language::where('code', $defaultLang->code)->first(); @endphp

    {{-- create modal --}}
    @include('admin.location-management.city.create')

    {{-- edit modal --}}
    @include('admin.location-management.city.edit')
@endsection

@section('script')
    <script type="text/javascript">
        var getCountriesDataUrl = "{{ route('admin.location_management.get_country_data') }}";
        var getStatesDataUrl = "{{ route('admin.location_management.city.get_states_by_country') }}";
        var langId = {{ @$langId->id }};
    </script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/location-management.js') }}"></script>
@endsection
