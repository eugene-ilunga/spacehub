<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit State') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      @php
        $countries = \App\Models\Country::where('language_id', $language->id)->get();

      @endphp

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form"
              action="{{ route('admin.location_management.state.update') }}"
              method="post">
          @csrf
          <input type="hidden" id="in_id" name="id">
          <input type="hidden" id="language_id" name="language_id" value="{{ $language->id }}">

          <div class=" edit-country-dropdown-container">
            <div class="form-group">
              <label for="">{{ __('Country Name') . '*' }}</label>
              <select name="country_id" id="in_country_id" class="form-control select2">
                <option disabled>{{ __('Select a Status') }}</option>
 
              </select>
              <p id="editErr_country_id" class="mt-2 mb-0 text-danger em"></p>
            </div>
          </div>
          <div class="form-group">
            <label for="">{{ __('State Name') . '*' }}</label>
            <input type="text" id="in_name" class="form-control" name="name"
                   placeholder="{{ __('Enter State Name') }}" autocomplete="off">
            <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="row no-gutters">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('State Status') . '*' }}</label>
                <select name="status" id="in_status" class="form-control">
                  <option disabled>{{ __('Select a Status') }}</option>
                  <option value="1">{{ __('Active') }}</option>
                  <option value="0">{{ __('Deactive') }}</option>
                </select>
                <p id="editErr_status" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
