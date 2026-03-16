@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit Section') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Pages') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Additional Sections') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
                        <li class="nav-item">
                <a href="#">{{ __('Sections') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Section') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Section') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('admin.home_page.additional_sections.index', ['language' => $language->code]) }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 m-auto">
                            <div class="alert alert-danger pb-1 dis-none" id="pageErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>

                            <form id="pageForm"
                                action="{{ route('admin.home_page.additional_section.update', $section->id) }}"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="page_type" value="home">
                                    <div class="col-lg-6">
                                        <div class="form-group p-0">
                                            <label for="">{{ __('Position') }} <span
                                                    class="text-danger">**</span></label>
                                            <select name="position" class="form-control select2">
                                                <option disabled
                                                    {{ old('position', $section->position ?? '') == null ? 'selected' : '' }}>
                                                    {{ __('Select a Section') }}
                                                </option>
                                                @foreach ($positions as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('position', $section->position ?? '') === $value ? 'selected' : '' }}>
                                                        {{ __($label) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group p-0">
                                            <label for="">{{ __('Serial Number') }} <span
                                                    class="text-danger">**</span></label>
                                            <input type="number" name="serial_number" class="form-control"
                                                value="{{ $section->serial_number }}" placeholder="{{ __('Enter Serial Number') }}">
                                            <p class="text-warning">
                                                {{ __('The higher the serial number is, the later the section will be shown') . '.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
                                        <div class="version {{ $language->direction == 1 ? 'rtl' : 'ltr' }}">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button"
                                                        class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : 'ltr' }}"
                                                        data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . ' ' . __('Language') }}
                                                        {{ $language->is_default == 1 ? __('(Default)') : '' }}
                                                    </button>
                                                </h5>
                                            </div>
                                            @php
                                                $content = App\Models\HomePage\AdditionalSectionContent::where(
                                                    'addition_section_id',
                                                    $section->id,
                                                )
                                                    ->where('language_id', $language->id)
                                                    ->first();
                                            @endphp
                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Name') }} <span
                                                                        class="text-danger">**</span></label>
                                                                <input type="text" class="form-control"
                                                                    name="{{ $language->code }}_section_name"
                                                                    placeholder="{{ __('Enter section name') }}"
                                                                    value="{{ @$content->section_name }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Content') }} <span
                                                                        class="text-danger">**</span></label>
                                                                <textarea class="form-control summernote" name="{{ $language->code }}_content" id="content-{{ $language->id }}"
                                                                    data-height="300">{{ @$content->content }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            @php $currLang = $language; @endphp

                                                            @foreach ($languages as $language)
                                                                @continue($language->id == $currLang->id)

                                                                <div class="form-check py-0">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                                                        <span
                                                                            class="form-check-sign">{{ __('Clone for') }}
                                                                            <strong
                                                                                class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                                                            {{ __('language') }}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="pageForm" class="btn btn-success">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

