<div class="modal fade" id="{{ 'emailModal-' . $booking->id }}" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form class="modal-form" action="{{ route('vendor.booking_record.sendmail', ['id' => $booking->id]) }}"
        method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="emailModalLabel">{{ __('Send Mail') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label>{{ __('Subject') . '*' }}</label>
            <input type="text" class="form-control" name="subject" placeholder="{{ __('Enter Mail Subject') }}" value="{{ old('subject') }}" required >
            <p id="err_subject" class="mt-2 mb-0 text-danger em"></p>
            
               @if ($errors->has('subject'))
        <p class="mt-2 mb-0 text-danger em">{{ $errors->first('subject') }}</p>
    @endif
          </div>

          <div class="form-group">
            <label>{{ __('Message') }}</label>
            <textarea class="form-control summernote" name="message" data-height="300" placeholder="{{ __('Write Your Mail') }}"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" >
            {{ __('Send') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
