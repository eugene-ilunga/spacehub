<div class="modal fade" id="bookATourModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Tour Request Form') }}</h5>
                <button type="button" class="modal_close" data-bs-dismiss="modal" aria-label="Close"> <i
                        class="fal fa-times"></i> </button>
            </div>

            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ route('frontend.space.booking.book_a_info', ['slug' => request()->route('slug')]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group mb-20">
                                            <input type="hidden" name="user_id"
                                                value="{{ Auth::check() ? Auth::user()->id ?? '' : '' }}">
                                            <input type="hidden" name="tour_request_form_id"
                                                value="{{ @$spaceContent->tour_request_form_id }}">
                                            <input type="hidden" name="space_id"
                                                value="{{ @$spaceContent->space_id }}">
                                            <input type="hidden" name="seller_id"
                                                value="{{ @$spaceContent->seller_id }}">
                                            <input type="hidden" name="form_type" value="{{ 'bookATourModal' }}">
                                            <label for="name"
                                                class="form-label font-sm">{{ __('Name') }}*</label>
                                            <input id="name" type="text" class="form-control" name="user_name"
                                                placeholder="{{ __('Enter Name') }}"
                                                value="{{ Auth::check() ? Auth::user()->first_name ?? '' : '' }}">
                                            @error('user_name')
                                                <p class="mt-2 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mb-20">
                                            <label for="email"
                                                class="form-label font-sm">{{ __('Email Address') }}*</label>
                                            <input id="email" type="email" class="form-control"
                                                name="user_email_address" placeholder="{{ __('Email Address') }}"
                                                value="{{ Auth::check() ? Auth::user()->email_address ?? '' : '' }}">
                                            @error('user_email_address')
                                                <p class="mt-2 text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    @foreach ($tourInputFields as $inputField)
                                        @if ($inputField->type == 1)
                                            <div class="col-md-6">
                                                <div class="form-group mb-30">
                                                    <label>
                                                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        name="{{ $inputField->name }}"
                                                        placeholder="{{ __($inputField->placeholder) }}"
                                                        value="{{ old($inputField->name) }}">
                                                    @error($inputField->name)
                                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @elseif ($inputField->type == 2)
                                            <div class="col-md-6">
                                                <div class="form-group mb-30">
                                                    <label>
                                                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                                                    </label>
                                                    <input type="number" class="form-control"
                                                        name="{{ $inputField->name }}"
                                                        placeholder="{{ __($inputField->placeholder) }}"
                                                        value="{{ old($inputField->name) }}">
                                                    @error($inputField->name)
                                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @elseif ($inputField->type == 3)
                                            @php $options = json_decode($inputField->options); @endphp

                                            <div class="col-md-6">
                                                <div class="form-group mb-30">
                                                    <label>
                                                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                                                    </label>
                                                    <select class="form-control" name="{{ $inputField->name }}">
                                                        <option selected disabled>{{ __($inputField->placeholder) }}
                                                        </option>

                                                        @foreach ($options as $option)
                                                            <option value="{{ $option }}"
                                                                {{ old($inputField->name) == $option ? 'selected' : '' }}>
                                                                {{ __($option) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error($inputField->name)
                                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @elseif ($inputField->type == 4)
                                            @php $options = json_decode($inputField->options); @endphp

                                            <div class="col-12">
                                                <div class="form-group mb-30">
                                                    <label class="mb-1">
                                                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                                                    </label>
                                                    <br>
                                                    @foreach ($options as $option)
                                                        <div
                                                            class="custom-control custom-checkbox custom-control-inline">
                                                            <input type="checkbox"
                                                                id="{{ 'option-' . $loop->iteration }}"
                                                                name="{{ $inputField->name . '[]' }}"
                                                                class="custom-control-input"
                                                                value="{{ $option }}"
                                                                {{ is_array(old($inputField->name)) && in_array($option, old($inputField->name)) ? 'checked' : '' }}>
                                                            <label for="{{ 'option-' . $loop->iteration }}"
                                                                class="custom-control-label">{{ $option }}</label>
                                                        </div>
                                                    @endforeach
                                                    @error($inputField->name)
                                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @elseif ($inputField->type == 5)
                                            <div class="col-12">
                                                <div class="form-group mb-30">
                                                    <label>
                                                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                                                    </label>
                                                    <textarea class="form-control" name="{{ $inputField->name }}" placeholder="{{ __($inputField->placeholder) }}"
                                                        rows="2">{{ old($inputField->name) }}</textarea>
                                                    @error($inputField->name)
                                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @elseif ($inputField->type == 6)
                                            <div class="col-md-6">
                                                <div class="form-group mb-30">
                                                    <label>
                                                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                                                    </label>
                                                    <input type="text" class="form-control checkInDate ltr"
                                                        name="{{ $inputField->name }}"
                                                        placeholder="{{ __($inputField->placeholder) }}" readonly
                                                        autocomplete="off" value="{{ old($inputField->name) }}">
                                                    @error($inputField->name)
                                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @elseif ($inputField->type == 7)
                                            <div class="col-md-6">
                                                <div class="form-group mb-30">
                                                    <label>
                                                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                                                    </label>
                                                    <input type="text" class="form-control timepicker ltr"
                                                        name="{{ $inputField->name }}"
                                                        placeholder="{{ __($inputField->placeholder) }}" readonly
                                                        autocomplete="off" value="{{ old($inputField->name) }}">
                                                    @error($inputField->name)
                                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="form-group mb-30">
                                                    <label>
                                                        {{ __($inputField->label) }}{{ $inputField->is_required == 1 ? '*' : '' }}
                                                        <span
                                                            class="text-info {{ $currentLanguageInfo->direction == 0 ? 'ms-2' : 'me-2' }}">({{ __('Only .zip file is allowed') . '.' }})</span>
                                                    </label>
                                                    <input type="file"
                                                        name="{{ 'form_builder_' . $inputField->name }}">
                                                    @error("form_builder_$inputField->name")
                                                        <p class="mt-2 text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-lg btn-primary radius-sm  modal_submit_btn" type="submit"
                                        aria-label="button"> {{ __('Submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
