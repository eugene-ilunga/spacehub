
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-title">{{ __('Currency') }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Base Currency Symbol') . '*' }}</label>
                                <input type="text" class="form-control" name="base_currency_symbol"
                                    value="{{ !empty($data) ? $data->base_currency_symbol : '' }}">
                                @if ($errors->has('base_currency_symbol'))
                                    <p class="mt-1 mb-0 text-danger">
                                        {{ $errors->first('base_currency_symbol') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Base Currency Symbol Position') . '*' }}</label>
                                <select name="base_currency_symbol_position" class="form-control">
                                    <option selected disabled>
                                        {{ __('Select Symbol Position') }}
                                    </option>
                                    <option value="left"
                                        {{ !empty($data) && $data->base_currency_symbol_position == 'left' ? 'selected' : '' }}>
                                        {{ __('Left') }}
                                    </option>
                                    <option value="right"
                                        {{ !empty($data) && $data->base_currency_symbol_position == 'right' ? 'selected' : '' }}>
                                        {{ __('Right') }}
                                    </option>
                                </select>
                                @if ($errors->has('base_currency_symbol_position'))
                                    <p class="mt-1 mb-0 text-danger">
                                        {{ $errors->first('base_currency_symbol_position') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Base Currency Text') . '*' }}</label>
                                <input type="text" class="form-control" name="base_currency_text"
                                    value="{{ !empty($data) ? $data->base_currency_text : '' }}">
                                @if ($errors->has('base_currency_text'))
                                    <p class="mt-1 mb-0 text-danger">
                                        {{ $errors->first('base_currency_text') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Base Currency Text Position') . '*' }}</label>
                                <select name="base_currency_text_position" class="form-control">
                                    <option selected disabled>
                                        {{ __('Select Text Position') }}
                                    </option>
                                    <option value="left"
                                        {{ !empty($data) && $data->base_currency_text_position == 'left' ? 'selected' : '' }}>
                                        {{ __('Left') }}
                                    </option>
                                    <option value="right"
                                        {{ !empty($data) && $data->base_currency_text_position == 'right' ? 'selected' : '' }}>
                                        {{ __('Right') }}
                                    </option>
                                </select>
                                @if ($errors->has('base_currency_text_position'))
                                    <p class="mt-1 mb-0 text-danger">
                                        {{ $errors->first('base_currency_text_position') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Base Currency Rate') . '*' }}</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ __('1 USD =') }}</span>
                                    </div>
                                    <input type="text" name="base_currency_rate" class="form-control"
                                        value="{{ !empty($data) ? $data->base_currency_rate : '' }}">
                                    <div class="input-group-append">
                                        <span
                                            class="input-group-text">{{ !empty($data) ? $data->base_currency_text : '' }}</span>
                                    </div>
                                </div>
                                @if ($errors->has('base_currency_rate'))
                                    <p class="mt-1 mb-0 text-danger">
                                        {{ $errors->first('base_currency_rate') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
