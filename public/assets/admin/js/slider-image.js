"use strict"
Dropzone.options.sliderDropzone = {
  paramName: 'slider_image',
  url: imgUpUrl,
  method: 'post',
  maxFiles: maxSliderImage,
  maxFilesize: 5,
  acceptedFiles: 'image/jpeg,image/jpg,image/png',

  success: function (file, response) {
    // remove error message if exist
    $('#err_slider_image').text('');

    $('#slider-image-id').append(`<input type="hidden" id="img-${response.uniqueName}" name="slider_images[]" value="${response.uniqueName}">`);

    // create remove button
    const rmvBtn = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");

    // capture the dropzone instance as closure
    let _this = this;

    // bind an event to the remove button
    rmvBtn.addEventListener('click', function (event) {
      // make sure the button click event doesn't submit the form
      event.preventDefault();
      event.stopPropagation();

      // remove image from dropzone preview
      _this.removeFile(file);
      // Remove the hidden input field for the uploaded image

      // remove image from storage
      rmvImg(response.uniqueName);

    });

    // add the remove button to the file preview element
    file.previewElement.appendChild(rmvBtn);
  },
  error: function (file, message) {
    let errorMessage = 'Image upload failed. Please try again.';

    if (message && message.error && message.error.slider_image && message.error.slider_image[0]) {
      errorMessage = message.error.slider_image[0];
    } else if (message && message.errors && message.errors.slider_image && message.errors.slider_image[0]) {
      errorMessage = message.errors.slider_image[0];
    } else if (typeof message === 'string') {
      errorMessage = message;
    }

    $('#err_slider_image').text(errorMessage);

    // create remove button
    const rmvBtn = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");

    // capture the dropzone instance as closure
    let _this = this;

    // bind an event to the remove button
    rmvBtn.addEventListener('click', function (event) {
      // make sure the button click event doesn't submit the form
      event.preventDefault();
      event.stopPropagation();

      // remove video from dropzone preview
      _this.removeFile(file);
    });

    // add the remove button to the file preview element
    file.previewElement.appendChild(rmvBtn);
  }
};

function rmvImg(unqName) {
  $.ajax({
    url: imgRmvUrl,
    type: 'POST',
    data: {'imageName': unqName},
    success: function (response) {
      const image = document.getElementById(`img-${unqName}`);
      if (image) {
        image.remove();
      }
    },
    error: function (response) {

    }
  });
}

function rmvStoredImg(id, key) {
  $.ajax({
    url: imgDetachUrl,
    type: 'POST',
    data: {'id': id, 'key': key},
    success: function (response) {

      $(`#slider-image-${key}`).remove();

      localStorage.setItem('notifyMessage', response.message);
      localStorage.setItem('notifyType', 'success');

      location.reload();
    },
    error: function (response) {

      localStorage.setItem('notifyMessage', response.responseJSON.message);
      localStorage.setItem('notifyType', 'danger');

      location.reload();
    }
  });
}


