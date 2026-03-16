<div class="modal contact-modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header align-item-center">
                <h4 class="modal-title mb-0" id="contactModalLabel">{{ __('Contact Now') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fal fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('seller.contact.message') }}" method="POST" id="sellerContactForm">
                    @csrf
                    <input type="hidden" name="seller_email"
                        value="{{ request()->input('admin') == true ? $bs->to_mail : $seller->recipient_mail }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-20">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('Enter Your Full Name') }}" name="name">
                                <p class="text-danger em" id="err_name"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-20">
                                <input type="email" class="form-control"
                                    placeholder="{{ __('Enter Your Email Address') }}" name="email">
                                <p class="text-danger em" id="err_email"></p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-20">
                                <input type="text" class="form-control" placeholder="{{ __('Enter Subject') }}"
                                    name="subject">
                                <p class="text-danger em" id="err_subject"></p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-20">
                                <textarea name="message" class="form-control" placeholder="{{ __('Message') }}"></textarea>
                                <p class="text-danger em" id="err_message"></p>
                            </div>
                        </div>
                        @if ($bs->google_recaptcha_status == 1)
                            <div class="col-md-12">
                                <div class="form-group mb-20">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                    <p class="text-danger em" id="err_g-recaptcha-response"></p>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12 text-center">
                            <button class="btn btn-lg btn-primary radius-sm" id="sellerSubmitBtn" type="submit"
                                aria-label="button">{{ __('Send Message') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
