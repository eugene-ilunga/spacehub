@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Subcategories') }}</h4>
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
                <a href="#">{{ __('Subcategories') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                            <div class="card-title d-inline-block">{{ __('Subcategories') }}</div>
                        </div>

                        <div class="col-lg-5 col-12">
                            <form id="filterForm" action="{{ route('admin.space_management.sub-category.index') }}"
                                method="GET" class="row">
                                <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                                    <select name="category" class="form-control" onchange="this.form.submit()">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach ($filterCategories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                                    <select name="subcategory" class="form-control" onchange="this.form.submit()">
                                        <option value="">{{ __('All Subcategories') }}</option>
                                   
                                            @foreach ($filterSubcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}"
                                                    {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                                    {{ $subcategory->name }}
                                                </option>
                                            @endforeach
                            
                                    </select>
                                </div>
                                <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="">{{ __('All Status') }}</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                            {{ __('Active') }}</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            {{ __('Deactive') }}</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-3 col-12 text-lg-right text-left mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal2"
                                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                                {{ __('Add') }}</a>

                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('admin.space_management.sub-category.bulk_delete') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($subcategories) == 0)
                                <h3 class="text-center mt-2">{{ __('NO SUBCATEGORY FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Category') }}</th>
                                                <th scope="col">{{ __('Serial Number') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subcategories as $subcategory)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $subcategory->id }}">
                                                    </td>
                                                    <td>
                                                        {!! truncate_text($subcategory->name, 40) !!}
                                                    </td>
                                                    <td>
                                                        @if ($subcategory->status == 1)
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

                                                        {!! truncate_text($subcategory->categoryName, 60) !!}
                                                    </td>
                                                    <td>{{ $subcategory->serial_number }}</td>
                                                    <td>

                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ __('Select') }}
                                                            </button>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a href="" class="dropdown-item editBtn"
                                                                    data-toggle="modal" data-target="#editModal"
                                                                    data-id="{{ $subcategory->id }}"
                                                                    data-space_category_id="{{ $subcategory->space_category_id }}"
                                                                    data-name="{{ $subcategory->name }}"
                                                                    data-status="{{ $subcategory->status }}"
                                                                    data-serial_number="{{ $subcategory->serial_number }}">
                                                                    {{ __('Edit') }}
                                                                </a>

                                                                <form class="deleteForm d-block"
                                                                    action="{{ route('admin.space_management.sub-category.destroy', ['id' => $subcategory->id]) }}"
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
                            {{ $subcategories->appends([
                                    'language' => $defaultLang->code,
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- create modal --}}
    @include('admin.space-management.sub-category.create')

    {{-- edit modal --}}
    @include('admin.space-management.sub-category.edit')
@endsection

@section('script')
    <script>
        var getCategoryUrl = "{{ route('admin.space_management.get-category') }}";
    </script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/city-state-country.js') }}"></script>
@endsection
