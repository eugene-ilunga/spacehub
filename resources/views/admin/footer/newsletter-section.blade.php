         <div class="col-lg-12">
             <div class="card">
                 <div class="card-header">
                     <div class="row">
                         <div class="col-lg-10">
                             <div class="card-title">{{ __('Newsletter Text') }}</div>
                         </div>
                     </div>
                 </div>

                 <div class="card-body">
                     <div class="row">
                         <div class="col-lg-12 mx-auto">
                             <div class="form-group">
                                 <label for="">{{ __('Newsletter section text') . '*' }}</label>
                                 <textarea name="newsletter_text" id="" rows="3" class="form-control">{{ @$data->newsletter_text }}</textarea>
                                 @error('newsletter_text')
                                     <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                 @enderror
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
