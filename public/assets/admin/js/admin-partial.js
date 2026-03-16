"use strict";

$(window).on('load', function () {

  // scroll to bottom
  if ($('.messages-container').length > 0) {
    $('.messages-container')[0].scrollTop = $('.messages-container')[0].scrollHeight;
  }
});

$(document).ready(function () {

  // post form
  $('body').on('submit', '#postForm', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();
    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }
        $('#postErrors ul').html(errors);
        $('#postErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#postErrors').offset().top - 100
        }, 1000);
      }
    });
  });

  // show or hide input field according to selected ad type
  $('body').on('change', '.ad-types', function () {
    let adType = $(this).val();
    if (adType == 'banner') {
      if (!$('#slot-input').hasClass('d-none')) {
        $('#slot-input').addClass('d-none');
      }
      $('#image-input').removeClass('d-none');
      $('#url-input').removeClass('d-none');
    } else {
      if (!$('#image-input').hasClass('d-none') && !$('#url-input').hasClass('d-none')) {
        $('#image-input').addClass('d-none');
        $('#url-input').addClass('d-none');
      }
      $('#slot-input').removeClass('d-none');
    }
  });

  $('body').on('change', '.edit-ad-type', function () {
    let adType = $(this).val();
    if (adType == 'banner') {
      if (!$('#edit-slot-input').hasClass('d-none')) {
        $('#edit-slot-input').addClass('d-none');
      }
      $('#edit-image-input').removeClass('d-none');
      $('#edit-url-input').removeClass('d-none');
    } else {
      if (!$('#edit-image-input').hasClass('d-none') && !$('#edit-url-input').hasClass('d-none')) {
        $('#edit-image-input').addClass('d-none');
        $('#edit-url-input').addClass('d-none');
      }
      $('#edit-slot-input').removeClass('d-none');
    }
  });

  // show different input field according to input type for digital product
  $('body').on('change', 'select[name="input_type"]', function () {
    let optionVal = $(this).val();

    if (optionVal == 'upload') {
      $('#file-input').removeClass('d-none');

      if (!$('#link-input').hasClass('d-none')) {
        $('#link-input').addClass('d-none');
      }
    } else if (optionVal == 'link') {
      $('#link-input').removeClass('d-none');
      if (!$('#file-input').hasClass('d-none')) {
        $('#file-input').addClass('d-none');
      }
    }
  });

  // show uploaded zip file name
  $('body').on('change', '.zip-file-input', function (e) {
    let fileName = e.target.files[0].name;
    $('.zip-file-info').text(fileName);
  });

  // product form
  $('body').on('submit', '#productForm', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();
    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);
    let blob_image_url = $('#blob_image').text().trim();


    if (blob_image_url.length > 0) {
      // Extract image type (png/jpg/jpeg) from the base64 URL
      const matches = blob_image_url.match(/^data:image\/([A-Za-z-+]+);base64,/);
      const imageType = matches ? matches[1] : 'png'; // Default to PNG if not detected


      // Generate a filename with the correct extension
      const fileName = `featured_image.${imageType.toLowerCase()}`;


      // Convert base64 to blob
      var base64ImageContent = blob_image_url.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
      var blob = base64ToBlob(base64ImageContent, `image/${imageType}`);

      // Append the blob with a proper filename
      fd.append('thumbnail_image', blob, fileName);

    }
    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }
        $('#productErrors ul').html(errors);
        $('#productErrors').show();
        $('.request-loader').removeClass('show');
        $('html, body').animate({
          scrollTop: $('#productErrors').offset().top - 100
        }, 1000);
      }
    });
  });


  // service form
  $('body').on('submit', '#serviceForm', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();
    let action = $(this).attr('action');
    let fd = new FormData($(this)[0]);

    let blob_image_url = $('#blob_image').text().trim();

    if (blob_image_url.length > 0) {
      // Extract image type (png/jpg/jpeg) from the base64 URL
      const matches = blob_image_url.match(/^data:image\/([A-Za-z-+]+);base64,/);
      const imageType = matches ? matches[1] : 'png'; // Default to PNG if not detected

      // Generate a filename with the correct extension
      const fileName = `featured_image.${imageType.toLowerCase()}`;

      // Convert base64 to blob
      var base64ImageContent = blob_image_url.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
      var blob = base64ToBlob(base64ImageContent, `image/${imageType}`);

      // Append the blob with a proper filename
      fd.append('thumbnail_image', blob, fileName);

    }
    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');
        if (data.status === 'success') {
          location.reload();
        }
        else if (data.status === 'membership-feature') {
          window.location.href = data.redirect;
        }
        else if (data.status === 'error') {

          // Display custom error message
          let errorMessage = `<li>
                <p class="text-danger mb-0">${data.message}</p>
            </li>`;
          $('#serviceErrors ul').html(errorMessage);
          $('#serviceErrors').show();

          $('html, body').animate({
            scrollTop: $('#serviceErrors').offset().top - 100
          }, 1000);
        }
        else if (data.status === 'downgrade') {
          var content = {};
          content.message = featureLimitTxt;
          content.title = warningTxt;
          content.icon = 'fa fa-bell';
          $.notify(content, {
            type: 'warning',
            placement: {
              from: 'top',
              align: 'right'
            },
            showProgressbar: true,
            time: 1000,
            delay: 4000,
          });
          $('#packageLimitModal').modal('show')
        }
        else if (data.status === 'vendor-login-required') {
          // message store into browser localStorage 
          localStorage.setItem('notifyMessage', data.message);
          localStorage.setItem('notifyType', 'warning');

          setTimeout(function () {
            window.location.href = data.redirect;
          }, 300);
        }
      },
      error: function (error) {
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }
        $('#serviceErrors ul').html(errors);
        $('#serviceErrors').show();

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#serviceErrors').offset().top - 100
        }, 1000);
      }
    });
  });

  // uploaded file progress bar and file name preview
  $('body').on('change', '.custom-file-input', function (e) {
    let file = e.target.files[0];
    let fileName = e.target.files[0].name;

    let fd = new FormData();
    fd.append('attachment', file);

    $.ajax({
      xhr: function () {
        let xhr = new window.XMLHttpRequest();

        xhr.upload.addEventListener('progress', function (ele) {
          if (ele.lengthComputable) {
            let percentage = ((ele.loaded / ele.total) * 100);
            $('.progress').removeClass('d-none');
            $('.progress-bar').css('width', percentage + '%');
            $('.progress-bar').html(Math.round(percentage) + '%');

            if (Math.round(percentage) === 100) {
              $('.progress-bar').addClass('bg-success');
              $('#attachment-info').text(fileName);
            }
          }
        }, false);

        return xhr;
      },
      url: $(this).data('url'),
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (res) {

      }
    });
  });

  // close ticket using swal start
  $('body').on('click', '.closeBtn', function (e) {
    e.preventDefault();
    $('.request-loader').addClass('show');

    swal({
      title: areYouSure,
      text: ticketCloseTxt,
      type: 'warning',
      buttons: {
        confirm: {
          text: ticketCloseYesTxt,
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent('.ticketForm').submit();
      } else {
        swal.close();

        $('.request-loader').removeClass('show');
      }
    });
  });
  // close ticket using swal end
});

function searchFormSubmit(e) {
  if (e.keyCode == 13) {
    $('#searchForm').submit();
  }
}

$('body').on('change', '#seller_id_service', function () {
  var id = $(this).val();
  $('.request-loader').addClass('show');
  $('.seller_form').each(function () {
    var selector_class_id = $(this).attr('id');
    var lang_id = $(this).attr('data-lang_id');
    var data = {
      id: id,
      lang_id: lang_id
    };
    $('#' + selector_class_id + ' option').remove();
    $.get(form_get_url, data, function (response) {
      $.each(response, function (key, value) {
        $('#' + selector_class_id).append($('<option></option>').val(value.id).html(value.name));
      })
    })
  })
  $('.request-loader').removeClass('show');
})

// select amenity according package for create and edit without downgraded
document.addEventListener('DOMContentLoaded', function () {
  // Attach event listener to each amenity checkbox
  document.querySelectorAll('.amenity-checkbox-without-downgraded').forEach(function (checkbox) {
    // Track initial state
    checkbox.dataset.initialChecked = checkbox.checked;
    checkbox.addEventListener('click', function () {

      let languageCode = this.dataset.code;
      let checkedBoxes = document.querySelectorAll('.amenity-checkbox-without-downgraded[data-code="' + languageCode + '"]:checked').length;

      if (checkedBoxes > numberOfAmenity && this.checked) {

        this.checked = false;
        let content = {
          message: `${aminityLimitTxt} ${numberOfAmenity} ${aminityTxt}`,
          title: errorTxt,
          icon: 'fas fa-times-circle'
        };
        $.notify(content, {
          type: 'danger',
          placement: {
            from: 'top',
            align: 'right'
          },
          showProgressbar: true,
          time: 1000,
          delay: 4000
        });
      }
      else if (!this.checked) {
        let currentCheckbox = this;
        // Proceed with your AJAX call if the limit is not exceeded
        rmvStoredAmenity(this.dataset.spaceId, this.value, languageCode, currentCheckbox);
      }
    });
  });
});

//amenities delete and limit check for space edit in seller panel
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.amenity-checkbox').forEach(function (checkbox) {
    // Track initial state
    checkbox.dataset.initialChecked = checkbox.checked;
    checkbox.addEventListener('click', function () {
      let languageCode = this.dataset.code;
      let checkedBoxes = document.querySelectorAll('.amenity-checkbox[data-code="' + languageCode + '"]:checked').length;
      if (checkedBoxes > numberOfAmenity && this.checked) {
        this.checked = false;
        let content = {
          message: `${aminityLimitTxt} ${numberOfAmenity} ${aminityTxt}`,
          title: errorTxt,
          icon: 'fas fa-times-circle'
        };

        $.notify(content, {
          type: 'danger',
          placement: {
            from: 'top',
            align: 'right'
          },
          showProgressbar: true,
          time: 1000,
          delay: 4000
        });
      }
      else if (!this.checked) {
        let currentCheckbox = this;

        // Proceed with your AJAX call if the limit is not exceeded
        rmvStoredAmenity(this.dataset.spaceId, this.value, languageCode, currentCheckbox);
      }
    });
  });
});

// delete amenity
function rmvStoredAmenity(space_id, amenity_id, code, currentCheckbox) {
  $.ajax({
    url: deleteAmenityUrl,
    type: 'POST',
    data: { 'space_id': space_id, 'amenity_id': amenity_id, 'code': code },
    success: function (response) {
      // Store notification data in localStorage
      localStorage.setItem('notifyMessage', response.message);
      localStorage.setItem('notifyType', 'success');

      // Reload the page
      location.reload();
    },
    error: function (response) {

      localStorage.setItem('notifyMessage', response.responseJSON.message);
      localStorage.setItem('notifyType', 'danger');

      // Reload the page
      location.reload();

      // Check if the error message is 'Sorry, the last amenity cannot be deleted.'
      if (response.status === 400 && response.responseJSON.message === latAmenityDeleteWarningTxt) {
        currentCheckbox.checked = true;
      }
    }
  });
}

// city, state, country selection

$(document).ready(function () {


  $('body').on('change', '.spaceCategoryDropdown', function () {
    var $accordionSection = $(this).closest('.version-body');
    var categoryId = $(this).val();
  

    fetchSubCategories(categoryId, $accordionSection);
  });

});


//get category according to language id to create subcategory

function fetchSubCategories(categoryId, $accordionSection) {
  $.ajax({
    url: subcategoryUrl,
    type: 'GET',
    data: { category_id: categoryId },
    success: function (data) {
      var $subcategoryDropdown = $accordionSection.find('.subcategoryDropdown');
      var $subcategoryDropdownContainer = $accordionSection.find('.subcategoryDropdownContainer');
      var $addressDiv = $accordionSection.find('.addressDiv');

      $subcategoryDropdown.empty();

      if (data.length > 0) {
        $subcategoryDropdown.append('<option selected disabled>' + selectASubcategoryTxt + '</option>');

        $.each(data, function (index, subcategory) {
          $subcategoryDropdown.append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
        });

        $subcategoryDropdownContainer.removeClass('d-none').addClass('d-block');
        $addressDiv.removeClass('col-lg-12').addClass('col-lg-6');
      }
      else {
        $subcategoryDropdownContainer.addClass('d-none').removeClass('d-block');
        $addressDiv.removeClass('col-lg-6').addClass('col-lg-12');
      }
    },
    error: function (xhr, status, error) {
      console.error('Error:', error);
    }
  });

}

$(document).ready(function () {
  $('#language-select').change(function () {
    var languageId = $(this).val();

    $.ajax({
      url: getCategoryUrl,
      type: 'GET',
      data: { language_id: languageId },
      success: function (response) {
        var categorySelect = $('#category-select');
        categorySelect.empty();
        if (response.length > 0) {
          categorySelect.append('<option selected disabled>' + selectACategoryTxt + '</option>');
          $.each(response, function (key, category) {
            categorySelect.append('<option value="' + category.id + '">' + category.name + '</option>');
          });
        }
        else {
          categorySelect.append('<option selected disabled>' + categoryNotFoundTxt + '</option>');

        }
      },
      error: function () {
        console.error('Failed to fetch categories');
      }
    });
  });
});

//book a tour form show accoding to book a tour status enable
$(document).ready(function () {
  // Function to check the radio button value and show/hide the div
  function toggleBookATourForm() {
    var selectedValue = $('input[name="book_a_tour"]:checked').val();
    if (selectedValue === '0') {
      $('.bookATourForm').hide();
    } else {
      $('.bookATourForm').show();
    }
  }

  // Initial check on page load
  toggleBookATourForm();

  // Event listener for radio button change
  $('body').on('change', '#bookATourStatus', function () {
    toggleBookATourForm();
  });
});

//get quote form show accoding to get quote status enable
$(document).ready(function () {
  // Function to check the radio button value and show/hide the div
  function toggleGetQuoteForm() {
    var selectedValue = $('input[name="booking_status"]:checked').val();

    if (selectedValue === '0') {
      $('.getQuoteForm').hide();
    } else {
      $('.getQuoteForm').show();
    }
  }

  // Initial check on page load
  toggleGetQuoteForm();

  // Event listener for radio button change
  $('body').on('change', '#getQuoteStatus', function () {
    toggleGetQuoteForm();
  });
});


// this code write for  coupon information in the vendor dashboard
var sellerIdForCoupon;
var vendorDashboard;
let getCouponUrl = typeof getCouponDataUrl !== 'undefined' ? getCouponDataUrl : null;

if (getCouponUrl != null) {
  document.addEventListener('DOMContentLoaded', function () {
    // Fetch the data using AJAX
    fetch(getCouponUrl)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.error) {
          console.error(data.error);
          return;
        }

        sellerIdForCoupon = data.seller_id;
        vendorDashboard = data.vendor_dashboard;
      })
      .catch(error => console.error('Error fetching data:', error));
  });
}


// fetch the data after select the vendor 
$(document).ready(function () {
  $('body').on('change', '.vendorTypeForAddBooking', function () {
    var sellerId = $(this).val();
    $.ajax({
      url: getSpaceType,
      type: 'GET',
      data: { seller_id: sellerId },
      success: function (response) {
        function populateSelectOptions() {
          const $selectElement = $('#featureSpaceTypeForAddBooking');
          // Clear existing options
          $selectElement.empty();
          // Add the default option
          $selectElement.append(
            $('<option>', {
              value: '',
              text: selectSpace,
              selected: true,
              disabled: true
            })
          );

          // Loop through the outputFeatureArray and create options
          $.each(response.outputFeatureArray, function (key, value) {
            $selectElement.append($('<option>', {
              value: key,
              text: value
            }));
          });
        }

        // Call the function to populate the select options
        populateSelectOptions();


        var featureSpaceTypeContainer = $('#featureSpaceTypeContainerForAddBooking');
        if (response.features.length > 1 || response.seller_id == 0) {

          featureSpaceTypeContainer.removeClass('d-none');
          featureSpaceTypeContainer.addClass('d-block');

          $('body').on('change', '#featureSpaceTypeForAddBooking', function () {
          
            const selectedSpaceId = $(this).val();
            const sellerId = $('.vendorTypeForAddBooking').val();
            const spaceType = $('#featureSpaceTypeForAddBooking').val();
            // Call the function with the selected sellerId and spaceType
            getSpaceAccordingToBooking(sellerId, selectedSpaceId);
          });
        } else if (response.features.length == 1) {
          var type = '';
          if (response.features[0] == 'Fixed Timeslot Rental') {
            type = 'fixed_time_slot_rental';
          } else if (response.features[0] == 'Hourly Rental') {
            type = 'hourly_rental';
          } else if (response.features[0] == 'Multi Day Rental') {
            type = 'multi_day_rental';
          }

          // Set the value of the select element
          $('#featureSpaceTypeForAddBooking').val(type);
          let spaceType = type;
          sellerId = $('.vendorTypeForAddBooking').val(); // Updated to use the current sellerId
          $('#spaceType').val(spaceType);
          $('#featureSpaceTypeForAddBooking').trigger('change');

          // Call the async function
          getSpaceAccordingToBooking(sellerId, spaceType);
        }
      },
      error: function () {
        console.error('Failed to fetch the space type feature');
      }
    });
  });

  $('body').on('change', '#featureSpaceTypeForAddBooking', function () {

    const selectedSpaceId = $(this).val();
    const sellerId = $('.vendorTypeForAddBooking').val();
    const $spaceSelect = $('#spaceId');
    $spaceSelect.empty();

    // Call the function with the selected sellerId and spaceType
    if (vendorDashboard == 'vendor_dashboard' && sellerId != 'admin') {
      getSpaceAccordingToBooking(sellerIdForCoupon, selectedSpaceId);
    }
    else {
      getSpaceAccordingToBooking(sellerId, selectedSpaceId);
    }

  });

  async function getSpaceAccordingToBooking(sellerId, spaceType) {
    try {
      const response = await getSpaceAccordingToType(sellerId, spaceType);


      // Clear existing options
      const $spaceSelect = $('#spaceId');
      $spaceSelect.empty();

      // Populate the select with new options from the response
      if (response && response.length > 0) {
        $.each(response, function (index, space) {
          $spaceSelect.append(
            $('<option>', {
              value: space.id,
              text: space.space_title
            })
          );
        });
      } else {
        // Optionally, you can add a message if no spaces are available
        $spaceSelect.append(
          $('<option>', {
            value: '',
            text: noResultFound,
            selected: true,
            disabled: true
          })
        );
      }

    } catch (error) {
      console.error('Error fetching space:', error);
    }
  }

  function getSpaceAccordingToType(sellerId, spaceType) {
    if (sellerId === 'admin') {
      sellerId = 0;
    }

    return new Promise((resolve, reject) => {
      let spaceTypeValue;
      switch (spaceType) {
        case 'fixed_time_slot_rental':
          spaceTypeValue = 1;
          break;
        case 'hourly_rental':
          spaceTypeValue = 2;
          break;
        case 'multi_day_rental':
          spaceTypeValue = 3;
          break;
        default:
          spaceTypeValue = 1;
      }
      $.ajax({
        url: getSpaceUrl,
        type: 'GET',
        data: {
          seller_id: sellerId,
          space_type: spaceTypeValue,
        },
        success: function (response) {

          resolve(response);
        },
        error: function (xhr, status, error) {
          reject(error);
        }
      });
    });
  }
});

// this code write for edit coupon information
$(document).ready(function () {
  $('body').on('change', '.vendorTypeForCoupon', function () {
    var sellerId = $(this).val();
    var hiddenSellerId = $('#in_hidden_seller_id').val();
    var spaceTypeHidden = $('#in_space_type_hidden').val();
    $('#in_space_type').empty();
    $('#in_spaces').empty();

    $.ajax({
      url: getSpaceType,
      type: 'GET',
      data: { seller_id: sellerId },
      success: function (response) {
        function populateSelectOptions() {
          const $selectElement = $('#in_space_type');
          // Clear existing options
          $selectElement.empty();
          // Add the default option
          $selectElement.append(
            $('<option>', {
              value: '',
              text: selectSpace,
              selected: true,
              disabled: true
            })
          );
          $.each(response.outputFeatureArray, function (key, value) {
            var $option = $('<option>', {
              value: key,
              text: value
            });
            // Check if the key matches the spaceTypeHidden value
            if (key == spaceTypeHidden && hiddenSellerId == sellerId) {
              $option.attr('selected', 'selected');
            }
            $selectElement.append($option);
          });
        }

        // Call the function to populate the select options
        populateSelectOptions();

        // Call the async function to get space according to the selected space type
        getSpaceAccordingToBooking(sellerId, spaceTypeHidden);


        var featureSpaceTypeContainer = $('#featureSpaceTypeContainerForCoupon');
        if (response.features.length > 1 || response.seller_id == 0) {

          featureSpaceTypeContainer.removeClass('d-none');
          featureSpaceTypeContainer.addClass('d-block');

          $('#in_space_type').on('change', function () {
            const selectedSpaceId = $(this).val();
            const sellerId = $('.vendorTypeForCoupon').val();
            // Call the function with the selected sellerId and spaceType
            getSpaceAccordingToBooking(sellerId, selectedSpaceId);
          });
        } else if (response.features.length == 1) {
          var type = '';
          if (response.features[0] == 'Fixed Timeslot Rental') {
            type = 'fixed_time_slot_rental';
          } else if (response.features[0] == 'Hourly Rental') {
            type = 'hourly_rental';
          } else if (response.features[0] == 'Multi Day Rental') {
            type = 'multi_day_rental';
          }

          // Set the value of the select element
          $('#in_space_type').val(type);
          let spaceType = type;
          sellerId = $('.vendorTypeForCoupon').val(); // Updated to use the current sellerId
          $('#spaceType').val(spaceType);
          $('#in_space_type').trigger('change');

          // Call the async function
          getSpaceAccordingToBooking(sellerId, spaceType);
        }
      },
      error: function () {
        console.error('Failed to fetch the space type feature');
      }
    });
  });

  $('#in_space_type').on('change', function () {
    const selectedSpaceId = $(this).val();

    const sellerId = $('.vendorTypeForCoupon').val();
    const spaceType = $('#in_space_type').val();
    const $spaceSelect = $('#in_spaces');
    $spaceSelect.empty();
    // Optionally, add a default option
    $spaceSelect.append($('<option>', {
      value: '',
      text: 'Select a space' // Change this text as needed
    }));

    // Call the function with the selected sellerId and spaceType
    if (vendorDashboard == 'vendor_dashboard') {
      getSpaceAccordingToBooking(sellerIdForCoupon, selectedSpaceId);
    }
    else {
      getSpaceAccordingToBooking(sellerId, selectedSpaceId);
    }
  });

  async function getSpaceAccordingToBooking(sellerId, spaceType) {
    try {
      const response = await getSpaceAccordingToType(sellerId, spaceType);
      // Clear existing options
      const $spaceSelect = $('#in_spaces');
      const $hiddenSpaces = $('#in_hidden_spaces').val();
      $spaceSelect.empty();

      // Add default option
      $spaceSelect.append(
        $('<option>', {
          value: '',
          text: 'Select a space',
          selected: false,
          disabled: true
        })
      );

      // Split $hiddenSpaces into an array of IDs
      const hiddenSpacesArray = $hiddenSpaces ? $hiddenSpaces.split(',') : [];

      // Populate the select with new options from the response
      if (response && response.length > 0) {
        $.each(response, function (index, space) {
          const $option = $('<option>', {
            value: space.id,
            text: space.space_title
          });

          // Check if the space ID is in the hiddenSpacesArray
          if (hiddenSpacesArray.includes(space.id.toString())) {
            $option.attr('selected', 'selected');
          }

          $spaceSelect.append($option);
        });
      } else {
        // Optionally, you can add a message if no spaces are available
        $spaceSelect.append(
          $('<option>', {
            value: '',
            text: noSpaceAvailableTxt,
            disabled: true
          })
        );
      }

    } catch (error) {
      console.error('Error fetching space:', error);
    }
  }

  function getSpaceAccordingToType(sellerId, spaceType) {
    if (sellerId === 'admin') {
      sellerId = 0;
    }
    return new Promise((resolve, reject) => {
      let spaceTypeValue;
      switch (spaceType) {
        case 'fixed_time_slot_rental':
          spaceTypeValue = 1;
          break;
        case 'hourly_rental':
          spaceTypeValue = 2;
          break;
        case 'multi_day_rental':
          spaceTypeValue = 3;
          break;
        default:
          spaceTypeValue = 1;
      }
      $.ajax({
        url: getSpaceUrl,
        type: 'GET',
        data: {
          seller_id: sellerId,
          space_type: spaceTypeValue,
        },
        success: function (response) {
          resolve(response);
        },
        error: function (xhr, status, error) {
          reject(error);
        }
      });
    });
  }
});

document.addEventListener('DOMContentLoaded', function () {
  // Get modal element
  const modalElement = document.getElementById('crop-modal');

  if (!modalElement) {

    return; // Prevent further execution if modal missing
  }

  const cropModal = new bootstrap.Modal(modalElement);
  const thumbnailInput = document.getElementById('thumbnail-input');
  const thumbnailPreview = document.getElementById('thumbnail-preview');
  const modalPreview = document.getElementById('modal-preview');
  const cropBtn = document.getElementById('crop-btn');
  const applyCropBtn = document.getElementById('apply-crop');

  let cropper;

  // Bind modal shown event ONCE (not repeatedly)
  modalElement.addEventListener('shown.bs.modal', function () {
    cropper = new Cropper(modalPreview, {
      aspectRatio: 750 / 600, // Match recommended size ratio
      viewMode: 1,
      autoCropArea: 0.8,
      responsive: true,
      rotatable: true,
    });
  });

  // Destroy cropper when modal hides
  modalElement.addEventListener('hidden.bs.modal', function () {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  });

  // When file input changes
  if (thumbnailInput) {
    thumbnailInput.addEventListener('change', function (e) {
      const files = e.target.files;

      if (files && files.length > 0) {
        const reader = new FileReader();
        reader.onload = function (event) {
          modalPreview.src = event.target.result;
          cropBtn.style.display = 'inline-block';
          cropModal.show();
        };
        reader.readAsDataURL(files[0]);
      }
    });
  } else {
    console.error('File input "thumbnail-input" not found!');
  }

  // Apply crop button click
  if (applyCropBtn) {
    applyCropBtn.addEventListener('click', function () {
      if (cropper) {
        const canvas = cropper.getCroppedCanvas({
          width: 750,
          height: 600,
          minWidth: 750,
          minHeight: 600,
          maxWidth: 750,
          maxHeight: 600,
          fillColor: '#fff',
          imageSmoothingEnabled: true,
          imageSmoothingQuality: 'high',
        });
        thumbnailPreview.src = canvas.toDataURL('image/jpeg');

        const cropData = cropper.getData();
        document.getElementById('thumbnail-x').value = cropData.x;
        document.getElementById('thumbnail-y').value = cropData.y;
        document.getElementById('thumbnail-width').value = cropData.width;
        document.getElementById('thumbnail-height').value = cropData.height;
        document.getElementById('thumbnail-rotate').value = cropData.rotate;

        cropBtn.style.display = 'none';
        cropModal.hide();

        cropper.destroy();
        cropper = null;
      }
    });
  } else {
    console.error('Apply crop button "apply-crop" not found!');
  }
});

// this code for image cropper model 

function base64ToBlob(base64, mime) {
  mime = mime || '';
  var sliceSize = 1024;
  var byteChars = window.atob(base64);
  var byteArrays = [];

  for (var offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
    var slice = byteChars.slice(offset, offset + sliceSize);

    var byteNumbers = new Array(slice.length);
    for (var i = 0; i < slice.length; i++) {
      byteNumbers[i] = slice.charCodeAt(i);
    }

    var byteArray = new Uint8Array(byteNumbers);

    byteArrays.push(byteArray);
  }

  return new Blob(byteArrays, { type: mime });
}



