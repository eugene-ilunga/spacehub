@extends('vendors.layout')
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Spaces') }}</h4>
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
                <a href="#">{{ $space->title ?? '' }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Days') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Days') }}</div>
                        </div>
                        <div class="col">
                            <a class="btn btn-info btn-sm float-right d-inline-block"
                                href="{{ route('vendor.space_management.space.index', ['language' => $defaultLang->code]) }}">
                                <span class="btn-label">
                                    <i class="fas fa-backward mdb_12"></i>
                                </span>
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped mt-3" id="basic-datatables">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Day') }}</th>
                                            <th scope="col">{{ __('Time Slots') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($days as $day)
                                            <tr>
                                                <td>{{ __($day->name) }}</td>
                                                @if ($day->is_weekend == 1)
                                                    <td>{{ '---' }}</td>
                                                @else
                                                    <td>
                                                        <form
                                                            action="{{ route('vendor.manage_schedule.time_slot.manage_time_slot', ['dayId' => $day->id, 'spaceId' => $space_id]) }}"
                                                            method="get">
                                                            @csrf
                                                            <input type="hidden" name="space_id"
                                                                value="{{ $space_id }}">
                                                            <input type="hidden" name="day_id"
                                                                value="{{ $day->id }}">
                                                            <input type="hidden" name="language"
                                                                value="{{ $defaultLang->code }}">
                                                            @if ($currentPackage == null)
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-info disabled">{{ __('Manage') }}</button>
                                                            @else
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-info">{{ __('Manage') }}</button>
                                                            @endif
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
@endsection
