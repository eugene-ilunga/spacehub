@extends('vendors.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Payment') }}</h4>
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
                <a href="#">{{ __('Payment') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="card-title d-inline-block">
                                {{ __('Payment') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="card p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-check color-white p-3 rounded-circle bg-success text-white"></i>
                            </div>
                            @if ($type == 'membership')
                            <h1>{{ __('Membership Request Sent') . '.' }}</h1>
                            <p>
                                {{ __('Your membership request has been sent successfully') . '.' }}
                                {{ __('You will receive an email once your request is approved, including activation and expiry details') . '.' }}
                            </p>
                        @elseif ($type == 'extend')
                            <h1>{{ __('Membership Extend Request Sent') . '.' }}</h1>
                            <p>
                                {{ __('Your membership extension request has been sent successfully') . '.' }}
                                {{ __('You will receive an email once your extension is approved, including the updated expiry date') . '.' }}
                            </p>
                        @elseif ($type == 'feature')
                            <h1>{{ __('Feature Request Sent') . '.' }}</h1>
                            <p>
                                {{ __('Your space feature request is sent successfully') . '.' }}
                                {{ __('You will be notified via email once the request is approved') . '.' }}
                            </p>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer"></div>
        </div>
    </div>
    </div>
@endsection
