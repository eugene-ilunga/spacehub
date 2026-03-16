@extends('admin.layout')
@section('content')
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
                <a href="#">{{ $space->title ?? '' }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Days') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Weekend') }}</a>
            </li>

        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ __('Weekend') }}</div>
                        </div>
                        <div class="col">
                            <a class="btn btn-info btn-sm float-right d-inline-block"
                                href="{{ route('admin.space_management.space.index', ['language' => $defaultLang->code]) }}">
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
                                            <th scope="col">{{ __('Weekend') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($days as $day)
                                            <tr>
                                                <td>{{ __($day->name) }}</td>

                                                <td>
                                                    <form id="scheduleDay{{ $day->id }}" class="d-inline-block"
                                                        action="{{ route('admin.manage_schedule.time_slot.update_weekend', ['id' => $day->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        <select
                                                            class="form-control form-control-sm {{ $day->is_weekend == 1 ? 'bg-success' : 'bg-danger' }}"
                                                            name="is_weekend" onchange="this.form.submit()">
                                                            <option value="1"
                                                                {{ $day->is_weekend == 1 ? 'selected' : '' }}>
                                                                {{ __('Yes') }}
                                                            </option>
                                                            <option value="0"
                                                                {{ $day->is_weekend == 0 ? 'selected' : '' }}>
                                                                {{ __('No') }}
                                                            </option>
                                                        </select>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="pl-3 pr-3 text-center">
                        <div class="d-inline-block mx-auto">
                            {{ $days->appends([
                                    'language' => $defaultLang->code,
                                    'space_id' => $space_id,
                                ])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
