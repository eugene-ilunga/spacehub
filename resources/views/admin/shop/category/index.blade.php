@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Categories') }}</h4>
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
                <a href="#">{{ __('Shop Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Manage Products') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Categories') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <!-- Title Column -->
                        <div class="col-lg-4 col-md-12 mb-2 mb-lg-0">
                            <h5 class="card-title d-inline-block mb-0">{{ __('Product Categories') }}</h5>
                        </div>

                        <!-- Search Form Column -->
                        <div class="col-lg-4 col-md-8 mb-2 mb-md-0">
                            <form action="{{ route('admin.shop_management.product.categories') }}" method="GET"
                                class="form-inline">
                                <div class="input-group w-100">
                                    <input name="title" type="text" class="form-control border-right-0"
                                        placeholder="{{ __('Search by name') . '...' }}" value="{{ request('title') }}"
                                        aria-label="Search categories">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary" aria-label="Search">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Action Buttons Column -->
                        <div class="col-lg-4 col-md-4 text-lg-right text-md-right text-left">
                            <div class="d-inline-block">
                                <a href="#" data-toggle="modal" data-target="#createModal"
                                    class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-plus"></i> {{ __('Add') }}
                                </a>

                                <button class="btn btn-danger btn-sm bulk-delete d-none"
                                    data-href="{{ route('admin.shop_management.product.bulk_delete_category') }}">
                                    <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($categories) == 0)
                                <h3 class="text-center mt-2">{{ __('NO PRODUCT CATEGORY FOUND') . '!' }}</h3>
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
                                                <th scope="col">{{ __('Serial Number') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $category->id }}">
                                                    </td>
                                                    <td>
                                                        {{ strlen($category->name) > 50 ? mb_substr($category->name, 0, 50, 'UTF-8') . '...' : $category->name }}
                                                    </td>
                                                    <td>
                                                        @if ($category->status == 1)
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-success">{{ __('Active') }}</span>
                                                            </h2>
                                                        @else
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-danger">{{ __('Deactive') }}</span>
                                                            </h2>
                                                        @endif
                                                    </td>
                                                    <td>{{ $category->serial_number }}</td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm mt-1 mr-1 editBtn" href="#"
                                                            data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $category->id }}"
                                                            data-name="{{ $category->name }}"
                                                            data-status="{{ $category->status }}"
                                                            data-serial_number="{{ $category->serial_number }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                        </a>

                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.shop_management.product.delete_category', ['id' => $category->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger  mt-1 btn-sm deleteBtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
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
                            {{ $categories->appends([
                                    'language' => $defaultLang->code,
                                    'title' => request('title'),
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- create modal --}}
    @include('admin.shop.category.create')

    {{-- edit modal --}}
    @include('admin.shop.category.edit')
@endsection
