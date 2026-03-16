@extends('admin.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('admin.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Section Titles') }}</h4>
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
                <a href="#">{{ __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Section Titles') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form
                    action="{{ route('admin.home_page.update_section_titles', ['language' => request()->input('language')]) }}"
                    method="post">
                    @csrf
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="card-title">{{ __('Section Titles') }}</div>
                            </div>

                            <div class="col-lg-2">

                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="form-group">
                                    <label>{{ __('Category Section Title') }}</label>
                                    <input class="form-control" name="category_section_title"
                                        value="{{ !is_null($data) ? $data->category_section_title : '' }}"
                                        placeholder="{{ __('Enter Category Section Title') }}">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Featured Space Section Title') }}</label>
                                    <input class="form-control" name="featured_space_section_title"
                                        value="{{ !is_null($data) ? $data->featured_space_section_title : '' }}"
                                        placeholder="{{ __('Enter Featured Space Section Title') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Work Process Section Title') }}</label>
                                    <input class="form-control" name="work_process_section_title"
                                        value="{{ !is_null($data) ? $data->work_process_section_title : '' }}"
                                        placeholder="{{ __('Enter Work Process Section Title') }}">
                                </div>


                                <div class="form-group">
                                    <label>{{ __('Testimonials Section Title') }}</label>
                                    <input class="form-control" name="testimonials_section_title"
                                        value="{{ !is_null($data) ? $data->testimonials_section_title : '' }}"
                                        placeholder="{{ __('Enter Testimonials Section Title') }}">
                                </div>


                                <div class="form-group">
                                    <label>{{ __('Popular Cities Section Title') }}</label>
                                    <input class="form-control" name="popular_cities_section_title"
                                        value="{{ !is_null($data) ? $data->popular_cities_section_title : '' }}"
                                        placeholder="{{ __('Enter Popular Cities Section Title') }}">
                                </div>


                                <div class="form-group">
                                    <label>{{ __('Space Banner Section Title') }}</label>
                                    <input class="form-control" name="space_banner_section_title"
                                        value="{{ !is_null($data) ? $data->space_banner_section_title : '' }}"
                                        placeholder="{{ __('Enter Space Banner Section Title') }}">
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
