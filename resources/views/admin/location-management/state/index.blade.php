@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('States') }}</h4>
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
                <a href="#">{{ __('States') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                            <div class="card-title mb-0 d-inline-block">{{ __('States') }}</div>
                        </div>

                        <!-- Search Form Columns -->
                        <div class="col-lg-5 col-12">
                            <form id="filterForm" action="{{ route('admin.location_management.state.index') }}"
                                method="GET" class="row">
                                <div class="col-lg-6 col-12 mb-2 mb-lg-0">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Search by name') }}..." value="{{ request('name') }}"
                                        onkeypress="if(event.keyCode == 13) this.form.submit()">
                                </div>
                                <div class="col-lg-6 col-12 mb-2 mb-lg-0">
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
                                data-href="{{ route('admin.location_management.state.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($states) === 0)
                                <h3 class="text-center mt-2">{{ __('NO STATE FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('Country') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($states as $state)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ @$state->id }}">
                                                    </td>
                                                    <td>
                                                        {{ strlen(@$state->name) > 50 ? mb_substr(@$state->name, 0, 50, 'UTF-8') . '...' : @$state->name }}
                                                    </td>
                                                    @if ($state->country_name)
                                                        <td>
                                                            {{ strlen(@$state->country_name) > 50 ? mb_substr(@$state->country_name, 0, 50, 'UTF-8') . '...' : @$state->country_name }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            {{ '---' }}
                                                        </td>
                                                    @endif
                                                    <td>
                                                        @if (@$state->status == 1)
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

                                                        <a class="btn btn-secondary btn-sm mr-1 editBtn" href="#"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $state->id }}" data-name="{{ $state->name }}"
                                                            data-country_name="{{ $state->country_name }}"
                                                            data-country_id="{{ $state->country_id }}"
                                                            data-status="{{ $state->status }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit') }}
                                                        </a>

                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.location_management.state.destroy') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ @$state->id }}">

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
                            {{ $states->appends([
                                    'language' => $defaultLang->code,
                                    'name' => request('name'),
                                    'country' => request('country'),
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- create modal --}}
    @include('admin.location-management.state.create')

    {{-- edit modal --}}
    @include('admin.location-management.state.edit')
@endsection

@section('script')
    <script type="text/javascript">
        var getCountriesDataUrl = "{{ route('admin.location_management.get_country_data') }}";
        var loadCountryUrl = "{{ route('admin.get_countries') }}";
        var loadStateUrl = "{{ route('admin.get_states') }}";
        var loadCityUrl = "{{ route('admin.get_cities') }}";
        var getStatesDataUrl = "{{ route('admin.location_management.city.get_states_by_country') }}";
        var langId = {{ $defaultLang->id ?? '' }};
    </script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/location-management.js') }}"></script>
  
@endsection
