@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('admin Profile') }}</h4>
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
                <a href="#">{{ __('Profile Settings') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title">{{ __('Update Profile') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="editProfileForm" action="{{ route('admin.account.update_profile') }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ __('Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        @if (!empty($adminInfo->image))
                                            <img src="{{ asset('assets/img/admins/' . $adminInfo->image) }}" alt="image"
                                                 class="uploaded-img">
                                        @else
                                            <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..."
                                                 class="uploaded-img">
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                    @if ($errors->has('image'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('image') }}</p>
                                    @endif
                                    <p class="text-warning mt-2 mb-0">{{ __('Upload squre size image for best quality') . '.' }}</p>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Username') . '*' }}</label>
                                    <input type="text" class="form-control" name="username"
                                           value="{{ $adminInfo->username }}" placeholder="{{ __('Enter username') }}">
                                    @if ($errors->has('username'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('username') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Email') . '*' }}</label>
                                    <input type="email" class="form-control" name="email"
                                           value="{{ $adminInfo->email }}" placeholder="{{ __('Enter email address') }}">
                                    @if ($errors->has('email'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Phone') . '*' }}</label>
                                    <input type="tel" class="form-control" name="phone" value="{{ $adminInfo->phone }}" placeholder="{{ __('Enter phone number') }}">
                                    @if ($errors->has('phone'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('phone') }}</p>
                                    @endif
                                </div>


                                <div class="form-group">
                                    <label>{{ __('First Name') . '*' }}</label>
                                    <input type="text" class="form-control" name="first_name"
                                           value="{{ $adminInfo->first_name }}" placeholder="{{ __('Enter first name') }}">
                                    @if ($errors->has('first_name'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('first_name') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Last Name') . '*' }}</label>
                                    <input type="text" class="form-control" name="last_name"
                                           value="{{ $adminInfo->last_name }}" placeholder="{{ __('Enter last name') }}">
                                    @if ($errors->has('last_name'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('last_name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Address') . '*' }}</label>
                                    <input type="text" class="form-control" name="address"
                                           value="{{ $adminInfo->address }}" placeholder="{{ __('Enter address') }}">
                                    @if ($errors->has('address'))
                                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('address') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Display Admin Profile on Vendor Page') . '*' }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="admin_profile" value="1"
                                                class="selectgroup-input" @if ($space_settings->admin_profile == 1) checked @endif>
                                            <span class="selectgroup-button">{{ __('Show') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="admin_profile" value="0"
                                                class="selectgroup-input" @if ($space_settings->admin_profile == 0) checked @endif>
                                            <span class="selectgroup-button">{{ __('Hide') }}</span>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted text-warning">
                                        {{ __('Choose Show to display your admin profile on the vendor page, or Hide to keep it private') . '.' }}
                                    </small>
                                    <p class="text-danger" id="err_admin_profile"></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="editProfileForm" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
