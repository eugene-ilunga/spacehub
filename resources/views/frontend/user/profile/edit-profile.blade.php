@extends('frontend.layout')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->customer_edit_profile_page_title ?? __('Edit Profile') }}
    @else
        {{ __('Edit Profile') }}
    @endif
@endsection
@php
    $title = $pageHeading->customer_edit_profile_page_title ?? __('Edit Profile');
@endphp

@section('content')
    @includeIf('frontend.user.breadcrumb', ['breadcrumb' => $breadcrumb, 'title' => $title])

    <!-- Start User Edit-Profile Section -->
    <section class="user-dashboard pt-100 pb-60">
        <div class="container">
            <div class="row">
                @includeIf('frontend.user.profile.side-navbar')

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="user-profile-details mb-40">
                                <div class="account-info">
                                    <div class="title">
                                        <h4>{{ __('Edit Your Profile') }}</h4>
                                    </div>

                                    <div class="edit-info-area">
                                        <form action="{{ route('user.update_profile') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="upload-img">

                                                <div class="file-upload-area pb-0">
                                                    <div class="file-edit">
                                                        <input type="file" name="image" id="imageUpload">
                                                        <label for="imageUpload"></label>
                                                    </div>
                                                    <div class="file-preview">
                                                        <div id="imagePreview" class="bg-img"
                                                            data-bg-img="{{ is_null($authUser->image) ? asset('assets/img/profile.jpg') : asset('assets/img/users/' . $authUser->image) }}">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            @error('image')
                                                <p class="mb-3 text-danger">{{ $message }}</p>
                                            @enderror
                                            <p class="text-warning mb-3">{{ __('Image Size : 80x80') }}</p>

                                            <div class="row">
                                                <div class="col-lg-6 mb-4">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('First Name') }}" name="first_name"
                                                        value="{{ $authUser->first_name }}">
                                                    @error('first_name')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 mb-4">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Last Name') }}" name="last_name"
                                                        value="{{ $authUser->last_name }}">
                                                    @error('last_name')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 mb-4">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Username') }}" name="username"
                                                        value="{{ empty($authUser->username) ? $authUser->provider_id : $authUser->username }}">
                                                    @error('username')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 mb-4">
                                                    <input type="email" class="form-control"
                                                        placeholder="{{ __('Email Address') }}"
                                                        value="{{ $authUser->email_address }}" readonly>
                                                </div>

                                                <div class="col-lg-6 mb-4">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Phone Number') }}" name="phone_number"
                                                        value="{{ $authUser->phone_number }}">
                                                    @error('phone_number')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 mb-4">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('State') }}" name="state"
                                                        value="{{ $authUser->state }}">
                                                </div>

                                                <div class="col-lg-6 mb-4">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('City') }}" name="city"
                                                        value="{{ $authUser->city }}">
                                                    @error('city')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 mb-4">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Zip Code') }}" name="zip_code"
                                                        value="{{ $authUser->zip_code }}">
                                                    @error('zip_code')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-6 mb-4">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ __('Country') }}" name="country"
                                                        value="{{ $authUser->country }}">
                                                    @error('country')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-12 mb-4">
                                                    <textarea class="form-control" placeholder="{{ __('Address') }}" rows="2" name="address">{{ $authUser->address }}</textarea>
                                                    @error('address')
                                                        <p class="text-danger mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-button">
                                                        <button
                                                            class="btn btn-md btn-primary radius-sm form-btn">{{ __('Submit') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End User Edit-Profile Section -->
@endsection
