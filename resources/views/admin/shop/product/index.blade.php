@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Products') }}</h4>
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
                <a href="#">{{ __('Products') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <!-- Title Column -->
                        <div class="col-lg-2 col-md-12 mb-3 mb-lg-0">
                            <h4 class="card-title mb-0">{{ __('Products') }}</h4>
                        </div>

                        <!-- Search and Filters Column -->
                        <div class="col-lg-7 col-md-12 mb-3 mb-lg-0">
                            @includeIf('admin.shop.product.combined_filter')
                        </div>

                        <!-- Action Buttons Column -->
                        <div class="col-lg-3 col-md-12 text-lg-right text-left">
                            <div class="d-inline-block">
                                <a href="{{ route('admin.shop_management.select_product_type', ['language' => $defaultLang->code]) }}"
                                    class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-plus mr-1"></i>{{ __('Add Product') }}
                                </a>

                                <button class="btn btn-danger btn-sm bulk-delete d-none"
                                    data-href="{{ route('admin.shop_management.bulk_delete_product') }}">
                                    <i class="flaticon-interface-5 mr-1"></i>{{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($products) == 0)
                                <h3 class="text-center mt-2">{{ __('NO PRODUCT FOUND') . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Featured Image') }}</th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Category') }}</th>
                                                <th scope="col">{{ __('Product Type') }}</th>
                                                <th scope="col">
                                                    @php $currencyText = $currencyInfo->base_currency_text; @endphp

                                                    {{ __('Price') . ' (' . $currencyText . ')' }}
                                                </th>

                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $product->id }}">
                                                    </td>
                                                    <td>
                                                        <img src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}"
                                                            alt="product image" width="40">
                                                    </td>
                                                    <td>
                                                        {{ strlen($product->title) > 50 ? mb_substr($product->title, 0, 50, 'UTF-8') . '...' : $product->title }}
                                                    </td>
                                                    <td>{{ $product->categoryName }}</td>
                                                    <td class="text-capitalize">{{ __(ucfirst($product->product_type)) }}</td>
                                                    <td>{{ $product->current_price }}</td>

                                                    <td>
                                                        <a class="btn btn-secondary mt-1 btn-sm mr-1"
                                                            href="{{ route('admin.shop_management.edit_product', ['id' => $product->id, 'type' => $product->product_type]) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                        </a>

                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.shop_management.delete_product', ['id' => $product->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger mt-1 btn-sm deleteBtn">
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
                            {{ $products->appends([
                                    'language' => $defaultLang->code,
                                    'title' => request('title'),
                                    'category' => request('category'),
                                    'product_type' => request('product_type'),
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
