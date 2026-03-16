<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title">{{ __('Time Format') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group timezone-wrapper" dir="ltr">
                                <label>{{ __('Time Format') . '*' }}</label>
                                <select name="time_format_id" class="form-control">
                                    <option selected disabled>
                                        {{ __('Select a Time Format') }}
                                    </option>

                                    <option value="12h" {{ $data->time_format == '12h' ? 'selected' : '' }}>{{
                                        __('12-Hour (AM/PM)') }}
                                    </option>
                                    <option value="24h" {{ $data->time_format == '24h' ? 'selected' : '' }}>{{
                                        __('24-Hour') }}
                                    </option>

                                </select>
                                @error('time_format_id')
                                <p class="mt-1 mb-0 text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
