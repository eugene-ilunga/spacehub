<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add About Content') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxForm" class="modal-form create"
              action="{{ route('admin.home_page.about_content.store') }}" method="post">
          @csrf

          <div class="row no-gutters">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('Language') . '*' }}</label>
                <select name="language_id" class="form-control">
                  <option selected disabled>{{ __('Select a Language') }}</option>
                  @foreach ($langs as $lang)
                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                  @endforeach
                </select>
                <p id="err_language_id" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('Title') . '*' }}</label>
                <input type="text" class="form-control" name="sub_title" placeholder="{{ __('Enter sub title') }}">
                <p id="err_sub_title" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('Text') }}</label>
                <input type="text" class="form-control" name="sub_text" placeholder="{{ __('Enter sub text') }}" >
                <p id="err_sub_text" class="mt-2 mb-0 text-danger em"></p>
                {{ @$data->sub_text }}
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>


