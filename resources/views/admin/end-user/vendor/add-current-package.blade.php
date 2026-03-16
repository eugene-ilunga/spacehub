<!-- Modal -->
<div class="modal fade" id="addCurrentPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Current Package') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCurrPackageForm" action="{{ route('admin.end-user.vendor.currPackage_add') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="seller_id" value="{{ $seller->id }}">
                  
                    <div class="form-group">
                        <label for="">{{ __('Package') }} **</label>
                        <select name="package_id" id="" class="form-control" required>
                            <option value="" selected disabled>{{ __('Select a Package') }}</option>
                            
                            @foreach ($packages as $package)
                           
                                <option value="{{ $package->id }}">{{ __($package->title)}} ({{ __(ucfirst($package->term))}})
                                </option>
                            @endforeach
                        </select>
                        @error('package_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Payment Method') }}</label>
                        <select name="payment_method" class="form-control">
                            <option value="" selected disabled>{{ __('Select a Payment Method') }}</option>
                            @foreach ($gateways as $gateway)
                                <option value="{{ $gateway->name }}">{{ __($gateway->name) }}</option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" form="addCurrPackageForm" class="btn btn-primary">{{ __('Add') }}</button>
            </div>
        </div>
    </div>
</div>
