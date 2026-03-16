<div class="modal fade socialMediaModal" id="socialMediaModal" tabindex="-1" aria-labelledby="socialMediaModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">{{ __('Share On') }}</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="actions socialMediaModal_list">
                    <div class="action-btn">
                        <a class="facebook"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}&src=sdkpreparse"
                            target="_blank" rel="noopener">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <span>{{ __('Facebook') }}</span>
                    </div>
                    <div class="action-btn">
                        <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}"
                            class="linkedin" target="_blank" rel="noopener">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <span>{{ __('Linkedin') }}</span>
                    </div>
                    <div class="action-btn">
                        <a class="twitter"
                            href="https://twitter.com/intent/tweet?text={{ urlencode(url()->current()) }}"
                            target="_blank" rel="noopener">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <span>{{ __('Twitter') }}</span>
                    </div>
                    <div class="action-btn">
                        <a class="whatsapp" href="whatsapp://send?text={{ urlencode(url()->current()) }}">
                            <i class="fab fa-whatsapp" target="_blank" rel="noopener"></i>
                        </a>
                        <span>{{ __('Whatsapp') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
