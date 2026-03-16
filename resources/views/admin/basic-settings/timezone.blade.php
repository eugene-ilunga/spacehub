
<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title">{{ __('Timezone') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group" dir="ltr">
                                <label>{{ __('Timezone') . '*' }}</label>
                                <select name="timezone_id" class="form-control select2 ltr">
                                    <option selected disabled>
                                        {{ __('Select a Timezone') }}
                                    </option>

                                    @foreach ($timezones as $timezone)
                                        <option value="{{ $timezone->id }}"
                                            {{ $timezone->is_set == 'yes' ? 'selected' : '' }}>
                                            {{ $timezone->timezone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
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
