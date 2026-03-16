@extends('admin.layout')

@section('content')
    @php
        if ($seller_name === 0) {
            $seller_name = 'admin';
            $seller_id = 0;
        } else {
            $seller = \App\Models\Seller::where('id', $seller_name)->first();
            $seller_id = $seller->id;
            $seller_name = $seller->username;
        }
      

    @endphp
    <div class="page-header">
        <h4 class="page-title">{{ __('Spaces') }}</h4>
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
                <a href="#">{{ __('Spaces') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Space Type') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-title d-inline-block">{{ __('Select Space Type for') . ' ' . $seller_name }}
                            </div>
                        </div>
                        @php

                        @endphp

                        <div class="col-lg-4 mt-2 mt-lg-0">
                            <a class="btn btn-info btn-sm float-right d-inline-block"
                                href="{{ route('admin.space_management.seller_select') }}?language={{ $defaultLang->code }}">
                                <span class="btn-label">
                                    <i class="fas fa-backward"></i>
                                </span>
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-type">
        <div class="row">
            <div class="col-lg-6">
                <a href="{{ route('admin.space_management.space.create', ['type' => 'fixed_time_slot_rental', 'seller_id' => $seller_id, 'language' => $defaultLang->code]) }}"
                    class="d-block">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="col-icon mx-auto">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="icon-screen-desktop"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers mx-auto text-center">
                                        <h2 class="card-title mt-2 mb-4 text-uppercase">{{ __('Fixed Timeslot Rental') }}
                                        </h2>
                                        <p class="card-category">
                                            <strong>{{ __('Total') . ': ' }}</strong>{{ $spaceCount . ' ' . __('Spaces') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-6">
                <a href="{{ route('admin.space_management.space.create', ['type' => 'hourly_rental', 'seller_id' => $seller_id, 'language' => $defaultLang->code]) }}"
                    class="d-block">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="col-icon mx-auto">
                                        <div class="icon-big text-center icon-warning bubble-shadow-small">
                                            <i class="icon-present"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers mx-auto text-center">
                                        <h2 class="card-title mt-2 mb-4 text-uppercase">{{ __('Hourly Rental') }}</h2>
                                        <p class="card-category">
                                            <strong>{{ __('Total') . ': ' }}</strong>{{ $spaceCount . ' ' . __('Spaces') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
