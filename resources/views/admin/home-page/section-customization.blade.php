@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Section Hide/Show') }}</h4>
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
                <a href="#">{{ __('Section Hide/Show') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.home_page.update_section_status') }}" method="POST">
                    @csrf
                    <div class="card-header">
                        <div class="card-title d-inline-block">{{ __('Home Page Sections') }}</div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">

                                <div class="form-group">
                                    <label>{{ __('Space Category Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="space_category_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->space_category_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="space_category_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->space_category_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label>{{ __('Features Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="features_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->features_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="features_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->features_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Video Banner Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="video_banner_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->video_banner_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="video_banner_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->video_banner_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Work Process Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="work_process_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->work_process_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="work_process_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->work_process_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Popular Cities Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="popular_city_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->popular_city_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="popular_city_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->popular_city_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Testimonials Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="testimonials_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->testimonials_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="testimonials_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->testimonials_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label>{{ __('Space Banner Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="space_banner_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->space_banner_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="space_banner_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->space_banner_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Footer Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="footer_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->footer_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="footer_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->footer_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('About Section Status') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="about_section_status" value="1"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->about_section_status == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="about_section_status" value="0"
                                                class="selectgroup-input"
                                                {{ $sectionInfo->about_section_status == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
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
