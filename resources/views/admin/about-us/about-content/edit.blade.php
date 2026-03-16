<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit About Content') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form create"
              action="{{ route('admin.home_page.update_about_sub_info') }}" method="post">
          <input type="hidden" id="in_id" name="id">
          <input type="hidden" id="in_lang_id" name="lang_id">
          @csrf

          <div class="row no-gutters">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('Title') . '*' }}</label>
                <input type="text" class="form-control" id="in_sub_title" name="sub_title"  placeholder="{{ __('Enter sub title') }}">
                <p id="editErr_sub_title" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row no-gutters">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __('Text') }}</label>
                <input type="text" class="form-control" id="in_sub_text" name="sub_text"  placeholder="{{ __('Enter sub text') }}" >
                <p id="editErr_sub_text" class="mt-2 mb-0 text-danger em"></p>

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


