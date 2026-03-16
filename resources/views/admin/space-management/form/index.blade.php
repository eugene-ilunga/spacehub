@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('Forms') }}</h4>
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
            <a href="#">{{ __('Forms') }}</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card-title d-inline-block">{{ __('All Forms') }}</div>
                    </div>

                    <div class="col-lg-2">
                        <form action="" method="GET">
                            <input type="hidden" name="language" value="{{ $defaultLang->code }} ">
                            <select name="seller" id="" class="form-control select2" onchange="this.form.submit()">
                                <option value="" selected>{{ __('All') }}</option>
                                <option value="admin" @selected(request()->input('seller') == 'admin')>{{ __('Admin') }}
                                </option>
                                @if (isset($sellers) && $sellers->isNotEmpty())
                                @foreach ($sellers as $seller)
                                <option @selected($seller->id == request()->input('seller')) value="{{ $seller->id }}">
                                    {{ $seller->username }}</option>
                                @endforeach
                                @endif
                            </select>
                        </form>
                    </div>

                    <!-- Added search by name field -->
                    <div class="col-lg-3">
                        <form action="" method="GET">
                            <input type="hidden" name="language" value="{{ $defaultLang->code }}">
                            <input type="hidden" name="seller" value="{{ request()->input('seller') }}">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control"
                                    placeholder="{{ __('Search by name') . '...' }}"
                                    value="{{ request()->input('name') }}" 
                                    onkeypress="if(event.keyCode == 13) document.getElementById('searchForm').submit()">
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-3 offset-lg-0 mt-2 mt-lg-0">
                        <a href="#" data-toggle="modal" data-target="#createModal"
                            class="btn btn-primary btn-sm float-lg-right float-left">
                            <i class="fas fa-plus"></i> {{ __('Add') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (session()->has('error'))
                        <div class="alert alert-warning alert-block">
                            <strong class="text-dark">{{ session()->get('error') }}</strong>
                            <button type="button" class="close" data-dismiss="alert">×</button>
                        </div>
                        @endif

                        @if (count($forms) == 0)
                        <h3 class="text-center mt-2">{{ __('NO FORM FOUND') . '!' }}</h3>
                        @else
                        <div class="table-responsive">
                            <table class="table table-striped mt-3" id="basic-datatables">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Vendor') }}</th>
                                        <th scope="col">{{ __('Form Inputs') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($forms) && $forms->isNotEmpty())
                                    @foreach ($forms as $form)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $form->name }}</td>
                                        <td>
                                            @if (!is_null($form->seller_id))
                                            <a target="_blank"
                                                href="{{ route('admin.end-user.vendor.details', ['id' => $form->seller_id, 'language' => $defaultLang->code]) }}">{{
                                                @$form->seller->username }}</a>
                                            @else
                                            {{ __('Admin') }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.space-management.form.input', ['id' => $form->id, 'language' => request()->input('language')]) }}"
                                                class="btn btn-sm btn-info">
                                                {{ __('Manage') }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($form->status == 1)
                                            <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active')
                                                    }}</span>
                                            </h2>
                                            @else
                                            <h2 class="d-inline-block"><span class="badge badge-danger">{{
                                                    __('Deactive') }}</span>
                                            </h2>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-secondary btn-sm editBtn mb-1" href="#"
                                                data-toggle="modal" data-target="#editModal" data-id="{{ $form->id }}"
                                                data-name="{{ $form->name }}" 
                                                data-status="{{ $form->status }}"
                                                data-seller_id="{{ $form->seller_id }}">
                                                <span class="btn-label">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                            </a>

                                            <form class="deleteForm d-inline-block"
                                                action="{{ route('admin.space-management.delete_form', ['id' => $form->id]) }}"
                                                method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm deleteBtn mb-1">
                                                    <span class="btn-label">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
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
                        {{ $forms->appends([
                        'language' => $defaultLang->code,
                        ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- create modal --}}
@includeIf('admin.space-management.form.create')

{{-- edit modal --}}
@includeIf('admin.space-management.form.edit')
@endsection
