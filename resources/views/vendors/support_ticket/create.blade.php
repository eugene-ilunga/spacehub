@extends('vendors.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Add Ticket') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('vendor.dashboard', ['language' => $defaultLang->code]) }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Support Tickets') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Add Ticket') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('vendor.support_ticket.store') }}" enctype="multipart/form-data" method="POST">
                    <div class="card-header">
                        <div class="card-title d-inline-block">{{ __('Add Ticket') }}</div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">

                                @csrf
                                <input type="hidden" name="seller_id" value="{{ Auth::guard('seller')->user()->id }}">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Email') . '*' }}</label>
                                            <input type="email" class="form-control"
                                                   value="{{ Auth::guard('seller')->user()->email }}"
                                                   name="email" placeholder="{{ __('Enter Email') }}">
                                        </div>
                                        @error('email')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Subject') . '*' }}</label>
                                            <input type="text" class="form-control" name="subject"
                                                   placeholder="{{ __('Enter Subject') }}">
                                        </div>
                                        @error('subject')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{ __('Message') . '*' }}</label>
                                            <textarea name="message" rows="4"
                                                      class="form-control summernote" placeholder="{{ __('Write your text') }}"></textarea>
                                            @error('message')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">{{ __('Attachment') }}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="attachment" accept=".zip"
                                                           class="custom-file-input"
                                                           id="zip_filess">
                                                    <label class="custom-file-label"
                                                           for="zip_filess">{{ __('Choose file') }}</label>
                                                </div>
                                            </div>
                                            <p class="text-warning">{{__('Upload only ZIP Files'). ', '.__('Max file size is 20 MB') . '.' }}</p>
                                            @error('attachment')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
