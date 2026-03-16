@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Coupons') }}</h4>
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
                <a href="#">{{ __('Coupons') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <!-- Title Column -->
                        <div class="col-lg-4 col-12 mb-2 mb-lg-0">
                            <h4 class="card-title mb-0 d-inline-block">{{ __('Coupons') }}</h4>
                        </div>

                        <!-- Search Form Columns -->
                        <div class="col-lg-5 col-12">
                            <form id="filterForm" action="{{ route('admin.space_management.coupons.index') }}"
                                method="GET" class="row">
                                <div class="col-lg-6 col-12 mb-2 mb-lg-0">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="{{ __('Search by name') }}..." value="{{ request('name') }}"
                                        onkeypress="if(event.keyCode == 13) this.form.submit()">
                                </div>
                                <div class="col-lg-6 col-12 mb-2 mb-lg-0">
                                    <input type="text" name="code" class="form-control"
                                        placeholder="{{ __('Search by code') }}..." value="{{ request('code') }}"
                                        onkeypress="if(event.keyCode == 13) this.form.submit()">
                                </div>
                            </form>
                        </div>

                        <!-- Add Button Column -->
                        <div class="col-lg-3 col-12 text-lg-right text-left mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('Add') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (isset($coupons) && count($coupons) == 0)
                                <h3 class="text-center mt-2">{{ __('NO COUPON FOUND') . '!' }}</h3>
                            @elseif(isset($coupons))
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('Code') }}</th>
                                                <th scope="col">{{ __('Discount') }}</th>
                                                <th scope="col">{{ __('Created') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($coupons as $coupon)
                                                @php
                                                    $todayDate = Carbon\Carbon::now();
                                                    $startDate = Carbon\Carbon::parse($coupon->start_date);
                                                    $endDate = Carbon\Carbon::parse($coupon->end_date);
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{ strlen($coupon->name) > 30 ? mb_substr($coupon->name, 0, 30, 'UTF-8') . '...' : $coupon->name }}
                                                    </td>
                                                    <td>{{ @$coupon->code }}</td>
                                                    <td>
                                                        @if ($coupon->coupon_type == 'fixed')
                                                            {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}{{ $coupon->value }}{{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                                        @else
                                                            {{ $coupon->value . '%' }}
                                                        @endif
                                                    </td>
                                                    <td class="ltr">
                                                        @php
                                                            $createDate = $coupon->created_at;

                                                            // first, get the difference of create-date & today-date
                                                            $diff = $createDate->diffInDays($todayDate);
                                                        @endphp

                                                        {{-- then, get the human read-able value from those dates --}}
                                                        {{ $createDate->subDays($diff)->diffForHumans() }}
                                                    </td>
                                                    <td>
                                                        @if ($startDate->greaterThan($todayDate))
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-warning">{{ __('Pending') }}</span>
                                                            </h2>
                                                        @elseif ($todayDate->between($startDate, $endDate))
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-success">{{ __('Active') }}</span>
                                                            </h2>
                                                        @elseif ($endDate->lessThan($todayDate))
                                                            <h2 class="d-inline-block"><span
                                                                    class="badge badge-danger">{{ __('Expired') }}</span>
                                                            </h2>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm mr-1  mt-1 btn-sm editBtn"
                                                            href="#" data-toggle="modal" data-target="#editModal"
                                                            data-id="{{ $coupon->id }}" data-name="{{ $coupon->name }}"
                                                            data-code="{{ $coupon->code }}"
                                                            data-coupon_type="{{ $coupon->coupon_type }}"
                                                            data-value="{{ $coupon->value }}"
                                                            data-start_date="{{ date_format($startDate, 'm/d/Y') }}"
                                                            data-end_date="{{ date_format($endDate, 'm/d/Y') }}"
                                                            data-serial_number="{{ $coupon->serial_number }}"
                                                            data-seller_id="{{ $coupon->seller_id }}"
                                                            data-hidden_seller_id="{{ $coupon->seller_id }}"
                                                            data-space_type="{{ $coupon->space_type }}"
                                                            data-space_type_hidden="{{ $coupon->space_type}}"
                                                            data-spaces="{{ $coupon->spaces }}"
                                                            data-hidden_spaces="{{ $coupon->spaces }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                        </a>

                                                        <form class="deleteForm d-inline-block"
                                                            action="{{ route('admin.space_management.coupons.delete', ['id' => $coupon->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger  mt-1 btn-sm btn-sm deleteBtn">
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
                            {{ $coupons->appends([
                                    'language' => $defaultLang->code,
                                    'name' => request('name'),
                                    'code' => request('code'),
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- create modal --}}
    @include('admin.space-management.coupon.create')

    {{-- edit modal --}}
    @include('admin.space-management.coupon.edit', ['allSpaces' => $allSpaces])
@endsection

@section('variable')
    <script>
        var selectSpace = "{{ __('Select a space type') }}"
        var getSpaceType = "{{ route('admin.space_management.space.select_space_type') }}";
        var getSpaceUrl = "{{ route('admin.add_booking.get_space') }}";
    </script>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
@endsection
