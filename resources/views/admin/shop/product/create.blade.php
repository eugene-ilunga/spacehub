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
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Add Product') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">
                        {{ __('Add Product') . ' (' . __('Type') . ' - ' . __(ucfirst($productType)) . ')' }}
                    </div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('admin.shop_management.select_product_type', ['language' => $defaultLang->code]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <div class="alert alert-danger pb-1 dis-none" id="productErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>

                            <div class="ml-2">
                                <label for=""><strong>{{ __('Slider Images') . '*' }}</strong></label>
                                <form id="slider-dropzone" enctype="multipart/form-data" class="dropzone mt-2 mb-0">
                                    @csrf
                                    <div class="fallback"></div>
                                </form>
                                <p class="text-warning">
                                    {{ __('Recommended Image Size') . ' : ' }}
                                    <span dir="ltr">600 x 580 px</span>
                                </p>
                                <p class="em text-danger mt-3 mb-0" id="err_slider_image"></p>
                            </div>

                            <form id="productForm" action="{{ route('admin.shop_management.store_product') }}"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="product_type" value="{{ $productType }}">

                                <div id="slider-image-id"></div>
                                <div class="form-group">
                                    <div class="col-12 mb-2 pl-0">
                                        <label for="image"><strong>{{ __('Featured Image') }} <span
                                                    class="text-danger">**</span></strong></label>
                                    </div>
                                    <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                            class="cropped-thumbnail-image">
                                    </div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#thumbnail-image-modal">{{ __('Choose Image') }}</button>
                                    <p class="text-warning">
                                        {{ __('Recommended Image Size') . ' : ' }}
                                        <span dir="ltr">750 x 600 px</span>
                                    </p>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Status') . '*' }}</label>
                                            <select name="status" class="form-control">
                                                <option selected disabled>{{ __('Select a Status') }}</option>
                                                <option value="show">{{ __('Show') }}</option>
                                                <option value="hide">{{ __('Hide') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    @if ($productType == 'digital')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Input Type') . '*' }}</label>
                                                <select name="input_type" class="form-control">
                                                    <option selected disabled>{{ __('Select a Type') }}</option>
                                                    <option value="upload">{{ __('File Upload') }}</option>
                                                    <option value="link">{{ __('File Download Link') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group d-none" id="file-input">
                                                <label>{{ __('File') }}</label>
                                                <br>
                                                <input type="file" name="file">
                                                <p class="text-warning mt-2 mb-0">
                                                    <small>{{ __('Only .zip file is allowed.') }}</small>
                                                </p>
                                            </div>

                                            <div class="form-group d-none" id="link-input">
                                                <label>{{ __('Link') }}</label>
                                                <input type="url" class="form-control" name="link"
                                                    placeholder="{{ __('Enter Download Link') }}">
                                            </div>
                                        </div>
                                    @endif

                                    @if ($productType == 'physical')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ __('Stock') . '*' }}</label>
                                                <input type="number" class="form-control" name="stock"
                                                    placeholder="{{ __('Enter Product Stock') }}">
                                            </div>
                                        </div>
                                    @endif

                                    @php $currencyText = $currencyInfo->base_currency_text; @endphp

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Current Price') . '* (' . $currencyText . ')' }}</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="current_price"
                                                placeholder="{{ __('Enter Product Current Price') }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Previous Price') . ' (' . $currencyText . ')' }}</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="previous_price"
                                                placeholder="{{ __('Enter Product Previous Price') }}">
                                        </div>
                                    </div>
                                </div>

                                <div id="accordion" class="mt-5">
                                    @foreach ($languages as $language)
                                        <div class="version {{ $language->direction == 1 ? 'rtl text-right' : 'ltr' }}">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button"
                                                        class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                                        data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . ' ' . __('Language') }}
                                                        {{ $language->is_default == 1 ? __('(Default)') : '' }}
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Title') . '*' }}</label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_title"
                                                                    placeholder="{{ __('Enter title') }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                @php $categories = $language->categories; @endphp

                                                                <label>{{ __('Category') . '*' }}</label>
                                                                <select name="{{ $language->code }}_category_id"
                                                                    class="form-control">
                                                                    <option selected disabled>{{ __('Select a Category') }}
                                                                    </option>

                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}">
                                                                            {{ $category->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Summary') . '*' }}</label>
                                                                <textarea class="form-control" name="{{ $language->code }}_summary" placeholder="{{ __('Enter Summary') }}"
                                                                    rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Content') . '*' }}</label>
                                                                <textarea class="form-control summernote" name="{{ $language->code }}_content" data-height="300"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Keywords') }}</label>
                                                                <input class="form-control"
                                                                    name="{{ $language->code }}_meta_keywords"
                                                                    placeholder="{{ __('Enter Meta Keywords') }}"
                                                                    data-role="tagsinput">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label>{{ __('Meta Description') }}</label>
                                                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                                                    placeholder="{{ __('Enter Meta Description') }}"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            @php $currLang = $language; @endphp

                                                            @foreach ($languages as $language)
                                                                @continue($language->id == $currLang->id)

                                                                <div class="form-check py-0">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                                                        <span
                                                                            class="form-check-sign">{{ __('Clone for') }}
                                                                            <strong
                                                                                class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                                                            {{ __('language') }}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="productForm" class="btn btn-success">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- thumbnail --}}
    <p class="d-none" id="blob_image"></p>
    <div class="modal fade" id="thumbnail-image-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h2>{{ __('Thumbnail') }}*</h2>
                    <button role="button" class="close btn btn-secondary mr-2 destroy-cropper d-none text-white"
                        data-dismiss="modal" aria-label="Close">
                        {{ __('Crop') }}
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group text-center">
                        @php
                            $d_none = 'none';
                        @endphp
                        <div class="thumb-preview" style="background: {{ $d_none }}">
                            <img src="{{ asset('assets/img/noimage.jpg') }}"
                                data-no_image="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                class="uploaded-thumbnail-img" id="image">
                        </div>
                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm  fw-bold upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="thumbnail-input" name="featured_image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- thumbnail end --}}
@endsection

@section('script')
    <script>
        'use strict';
        var maxSliderImage = {{ @$maxSliderImage }};
        const imgUpUrl = "{{ route('admin.shop_management.upload_slider_image') }}";
        const imgRmvUrl = "{{ route('admin.shop_management.remove_slider_image') }}";
    </script>

    <script type="text/javascript" src="{{ asset('assets/admin/js/slider-image.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/cropper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/cropper-init.js') }}"></script>
@endsection
