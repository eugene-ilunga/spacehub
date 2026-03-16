@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Contact Page') }}</h4>
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
                <a href="#">{{ __('Pages') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Contact Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Information') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <form action="{{ route('admin.home_page.contact.update_info') }}" method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Update Information') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 offset-lg-2">
                                <div class="form-group">
                                    <label>{{ __('Email Address') }}</label>
                                    <input type="text" class="form-control" name="email_address" data-role="tagsinput"
                                        value="{{ !empty($data) ? $data->email_address : '' }}"
                                        placeholder="{{ __('Enter Email Address') }}">
                                    @if ($errors->has('email_address'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('email_address') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Contact Number') }}</label>
                                    <input type="text" class="form-control" name="mobile_number" data-role="tagsinput"
                                        value="{{ !empty($data) ? $data->mobile_number : '' }}"
                                        placeholder="{{ __('Enter Contact Number') }}">
                                    @if ($errors->has('mobile_number'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('mobile_number') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="card-title">{{ __('Contact Information') }}</div>
                        </div>
                        <div class="col-lg-3">

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">

                            <form id="aboutForm"
                                action="{{ route('admin.home_page.contact.update_content_info', ['language' => request()->input('language')]) }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ __('Title') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ empty($contactContent) ? '' : $contactContent->title }}"
                                        placeholder="{{ __('Enter title') }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('Text') }}</label>
                                    <textarea class="form-control summernote" name="text" placeholder="{{ __('Enter text') }}" data-height="300">{{ empty($contactContent) ? '' : $contactContent->text }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('Location') }}</label>
                                    <input type="text" class="form-control" name="location"
                                        value="{{ empty($contactContent) ? '' : $contactContent->location }}"
                                        placeholder="{{ __('Enter Location') }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="aboutForm" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
