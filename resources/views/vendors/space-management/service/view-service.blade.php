@extends('vendors.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('vendors.partials.rtl-style')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Spaces') }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('vendor.dashboard') }}">
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
        @if (count($spaces) > 0)
        @foreach ($spaces as $space)
        <li class="nav-item">
            <a href="#">{{$space->title }}</a>
        </li>
        @endforeach
        @endif
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Services') }}</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">

                    <div class="col-lg-4">
                        <div class="card-title d-inline-block">{{ __('Services') }} </div>
                    </div>

                    <div class="col-lg-3">
                    </div>

                    <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                        <a href="{{ route('vendor.space_management.service.create_under_space', ['id' => request()->space_id, 'language' => $defaultLang->code]) }}"
                            class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                            {{ __('Add Service') }}</a>

                        <a href="{{ route('vendor.space_management.space.index', ['language' => $defaultLang->code]) }}"
                            class="btn btn-primary btn-sm float-lg-right mr-2 float-left">
                            <span class="btn-label">
                                <i class="fas fa-backward mdb_12"></i>
                            </span>
                            {{ __('Back') }}
                        </a>

                        <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                            data-href="{{ route('vendor.space_management.service.bulk_delete') }}">
                            <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($services) == 0)
                        <h3 class="text-center mt-2">{{ __('NO SERVICE FOUND FOR THIS SPACE') . '!' }}</h3>
                        @else
                        <div class="table-responsive">
                            <table class="table table-striped mt-3" id="basic-datatables">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" class="bulk-check" data-val="all">
                                        </th>
    
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Price Type') }}</th>
                                        <th scope="col">
                                            {{ __('Price') }} ({{ $basic->base_currency_text }})
                                        </th>
                                        <th scope="col">{{ __('Variants') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Serial Number') }}</th>
                                        <th scope="col">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check" data-val="{{ $service->id }}">
                                        </td>
                                        <td>
                                            {{ strlen($service->title) > 50 ? mb_substr($service->title, 0, 50, 'UTF-8')
                                            . '...' : $service->title }}
                                        </td>
                                        <td>{{ __(ucfirst($service->price_type)) }}</td>
                                        @if (!empty($service->price && $service->price_type))
                                        <td>{{ $service->price }} ({{ __(ucfirst($service->price_type)) }})</td>
                                        @else
                                        <td>{{ __('---') }}</td>
                                        @endif

                                        <td>
                                            @if (@$service->has_sub_services == 1)
                                            <a href="#" data-toggle="modal"
                                                data-target="#optionModal-{{ $service->id }}"
                                                class="btn btn-success btn-sm">
                                                {{ __('View') }}</a>
                                            @else
                                            <p class="ml-4">
                                                {{ __('---') }}
                                            </p>
                                            @endif
                                        </td>


                                        <td>
                                            @if ($service->status == 1)
                                            <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active')
                                                    }}</span>
                                            </h2>
                                            @else
                                            <h2 class="d-inline-block"><span class="badge badge-danger">{{
                                                    __('Deactive') }}</span>
                                            </h2>
                                            @endif
                                        </td>
                                        <td>{{ $service->serial_number }}</td>

                                        <td>
                                            @if (@$serviceDowngrade)
                                            <form class="deleteForm d-block"
                                                action="{{ route('vendor.space_management.service.delete', ['id' => $service->id]) }}"
                                                method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                                    <span class="btn-label">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                            @else
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    {{ __('Select') }}
                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a href="{{ route('vendor.space_management.service.edit', ['id' => $service->id, 'language' => $defaultLang->code]) }}"
                                                        class="dropdown-item">
                                                        {{ __('Edit') }}
                                                    </a>

                                                    <form class="deleteForm d-block"
                                                        action="{{ route('vendor.space_management.service.delete', ['id' => $service->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        <button type="submit" class="deleteBtn">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            @endif
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
                        {{ $services->appends([
                        'language' => $defaultLang->code,
                        ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('vendors.space-management.sub-service.index', ['services' => $services])

@endsection
