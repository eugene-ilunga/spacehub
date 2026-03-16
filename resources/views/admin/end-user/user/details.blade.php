@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Registered Users') }}</h4>
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
                <a href="#">{{ __('User Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.user_management.registered_users') }}">{{ __('Registered Users') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('User Details') }}</a>
            </li>
        </ul>
        <a href="{{ route('admin.user_management.registered_users') }}?language={{$defaultLang->code}}"
           class="btn btn-primary ml-auto">{{ __('Back') }}</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="h4 card-title">{{ __('Profile Picture') }}</div>
                        </div>

                        <div class="card-body text-center py-4">
                            <img
                                    src="{{ empty($userInfo->image) ? asset('assets/img/profile.jpg') : asset('assets/img/users/' . $userInfo->image) }}"
                                    alt="image" width="150">
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{ __('User Information') }}</div>
                        </div>

                        <div class="card-body">
                            <div class="user-information">
                                @if (!empty($userInfo->first_name) || !empty($userInfo->last_name))
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ __('Name') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->first_name . ' ' . $userInfo->last_name }}
                                    </div>
                                </div>
                                 @endif

                                @if(!empty($userInfo->username))
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ __('Username') . ' :' }}</strong>
                                    </div>
                                    <div class="col-lg-10">
                                        {{ $userInfo->username }}
                                    </div>
                                </div>
                                @endif
                                @if(!empty($userInfo->email_address))
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ __('Email') . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-10">
                                        {{ $userInfo->email_address }}
                                    </div>
                                </div>
                                @endif
                                @if(!empty($userInfo->phone_number))
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ __('Phone') . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-10">
                                        {{ $userInfo->phone_number }}
                                    </div>
                                </div>
                                @endif
                                @if(!empty($userInfo->address))
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ __('Address') . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-10">
                                        {{ $userInfo->address }}
                                    </div>
                                </div>
                                @endif
                                @if(!empty($userInfo->city))
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ __('City') . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-10">
                                        {{ $userInfo->city }}
                                    </div>
                                </div>
                                @endif
                                @if(!empty($userInfo->state))
                                <div class="row mb-2">
                                    <div class="col-lg-2">
                                        <strong>{{ __('State') . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-10">
                                        {{ $userInfo->state }}
                                    </div>
                                </div>
                                @endif
                                @if(!empty($userInfo->country))
                                <div class="row">
                                    <div class="col-lg-2">
                                        <strong>{{ __('Country') . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-10">
                                        {{ $userInfo->country }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
