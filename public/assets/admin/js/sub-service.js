"use strict"

$('body').on('click', '.has_sub_services', function () {
  // Get the value of the selected radio button
  var hasSubServicesValue = $('input[name="has_sub_services"]:checked').val();

  // Toggle visibility based on the selected value
  $('#space_sub_service').toggleClass('d-none', hasSubServicesValue === "0");
  $('#servicePriceAndType').toggleClass('d-none', hasSubServicesValue === "1");

  // Change col class conditionally
  var container = $('#priceTypeContainer');
  if (hasSubServicesValue === "1") {
    container.removeClass('col-md-6').addClass('col-md-12');
  } else {
    container.removeClass('col-md-12').addClass('col-md-6');
  }
});

// Call the function to handle initial state
function priceAndTypeHandle() {
  // Trigger the click event to set initial visibility
  $('.has_sub_services:checked').trigger('click');
}

// Initial call to set the visibility based on the default checked radio button
priceAndTypeHandle();

$(document).ready(function() {
  // Function to handle image preview
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $(input).closest('.form-group').find('.thumb-preview img').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  $(document).on('change', '.img-input-sub-service', function (event) {
    let fileInput = $(this);
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      // Set the image source
      fileInput.closest('td').find('.thumb-preview .uploaded-img-1').attr('src', e.target.result);

      // Create the button element
      let button = $('<button class="rmv-btn"><i class="fa fa-times"></i></button>');

      // Append the button to the thumbnail preview
      fileInput.closest('td').find('.thumb-preview').append(button);

      // Add functionality to remove the image and button
      button.on('click', function () {
        fileInput.closest('td').find('.thumb-preview .uploaded-img-1').attr('src', baseUrl + '/assets/img/noimage.jpg');
        button.remove();
      });
    };
    reader.readAsDataURL(file);
  });


  // Click event for adding rows
  $(document).ready(function() {
    // Initialize clone counter
    var cloneCounter = 0;
    
    var numberOfOption = typeof numberOfVariant !== 'undefined' ? numberOfVariant : 0;
    var maxClones = numberOfOption;
    // Function to check the number of rows and disable delete button if only one row remains
    function toggleDeleteButton() {
      var $tbody = $('table tbody');
      var rowCount = $tbody.children('tr').length;      
      if (rowCount === 2) {
        $tbody.find('.deleteRow').hide(); 
      } else {
        $tbody.find('.deleteRow').show(); 
      }
    }

    // Function to delete a row
    function deleteRow() {
      var $row = $(this).closest('tr');
      var $table = $row.closest('table');
      var $tbody = $table.find('tbody');

      $row.remove();
      cloneCounter--;

      if (cloneCounter < maxClones) {
        $('.addRow').prop('disabled', false);
      }
      toggleDeleteButton(); 

      // Check if the table body is empty after removing the row
      if ($tbody.children().length === 0) {
        $table.find('thead').remove(); // Remove the table header
      }
    }

    // Initialize deletion functionality for existing rows
    $('.deleteRow').on('click', deleteRow);

    $('.addRow').on('click', function() {
      var $table = $(this).closest('table');
      var $tbody = $table.find('tbody'); // find tbody

      if (cloneCounter < maxClones) {
        // Clone the last row in the tbody (either existing or newly added template)
        var $newRow = $tbody.find('tr:last').clone();
        // Reset the thumb-preview image
        $newRow.find('.thumb-preview img').attr('src', baseUrl + '/assets/img/noimage.jpg');
        // Clear the icon button
        $newRow.find('.rmv-btn').remove(); 
        // Remove the onclick attribute from the delete button
        $newRow.find('.deleteRow').removeAttr('onclick');
        $newRow.find('input:not([type="file"])').val('');
        $newRow.find('input').val('');
        $newRow.find('select').val('');
        $newRow.attr('data-index', Date.now());

        $tbody.append($newRow); 
        $newRow.find('.deleteRow').on('click', deleteRow);

        cloneCounter++;
        // Recalculate row count after adding the new row
        toggleDeleteButton(); 
        if (cloneCounter >= maxClones) {
          $('.addRow').prop('disabled', true);
        }
      } else {
        let content = {
          message: `${limitFirstMsg} ${maxNumberOfOption} ${limitLastMsg} .`,
          title: warningTxt,
          icon: 'fas fa-times-circle'
        };

        $.notify(content, {
          type: 'warning',
          placement: {
            from: 'top',
            align: 'right'
          },
          showProgressbar: true,
          time: 1000,
          delay: 4000
        });
      }
    });
    // Initial check for delete button visibility when the page loads
    toggleDeleteButton();
  });

  // Function for deleting rows
  function deleteRow() {
    var $row = $(this).closest('tr');
    var $table = $row.closest('table');
    var $tbody = $table.find('tbody');
    $row.remove();

    // Check if the table body is empty after removing the row
    if ($tbody.children().length === 0) {
      $table.find('thead').remove(); // Remove the table header
    }
  }

  // Initialize deletion functionality for existing rows
  $('.deleteRow').on('click', deleteRow);
});

// delete subservice thumbnail image in the edit form start

$(document).on('click', '.rmv-btn', function (e) {
  e.preventDefault();
  let button = $(this);
  let indb = button.data('subservice_id');

  // Locate the file input and thumbnail image within the same `td` scope
  let fileInput = button.closest('td').find('.img-input-sub-service');
  let thumbnailImage = button.closest('td').find('.thumb-preview .uploaded-img-1');

  if (indb == undefined){

    // Reset the file input and thumbnail if no subservice ID is provided
    thumbnailImage.attr('src', baseUrl + '/assets/img/noimage.jpg');
    button.remove();
    // Reset the file input
    resetFileInput(fileInput); // Reset the file input
    return;
  }
  $(".request-loader").addClass("show");
  $.ajax({
    url: imageRmvUrl,
    type: 'POST',
    data: {
      fileid: indb
    },
    success: function (data) {
      $(".request-loader").removeClass("show");
      var content = {};

      if (data == 'false') {
        content.message = imgWrongMessage;
        content.title = warningTxt;
        var type = 'warning';
      } else {
        // Update the specific thumbnail image and remove the button
        thumbnailImage.attr('src', baseUrl + '/assets/img/noimage.jpg');
        button.remove();
        content.message = imgRmvMessage;
        content.title = successTxt;
        var type = 'success';
      }
      content.icon = 'fa fa-bell';
      $.notify(content, {
        type: type,
        placement: {
          from: 'top',
          align: 'right'
        },
        showProgressbar: true,
        time: 1000,
        delay: 4000
      });
    }
  });
});

// Function to reset only the file input
function resetFileInput(fileInput) {
  // Clear the value of the file input
  fileInput.val('');
  fileInput.closest('td').find('.thumb-preview .uploaded-img-1').attr('src', baseUrl + '/assets/img/noimage.jpg');
}
// delete subservice thumbnail image in the edit form end

$(document).ready(function(){
  const preselectedServiceId = $('#preselected_service_id').val(); 

  $('#space_id').change(function(){
    let spaceId = $(this).val();
    $('#service_title').html('<option value="">Select Service</option>')
    $.ajax({
      url: getServiceUrl,
      type:'post',
      data:{
        space_id: spaceId
      },
      success:function(result){

        $('#service_title').empty(); // Ensure options are cleared before appending

        $.each(result.services, function (key, service) {
          let option = $('<option></option>');
          option.val(service.service_category_id);
          option.text(service.service_title);

          // Pre-select service if ID matches
          if (preselectedServiceId && service.id == preselectedServiceId) {
            option.attr('selected', true);
          }

          $('#service_title').append(option);
        });
      }
    });
  });

  // Pre-select service on page load (optional)
  if (preselectedServiceId) {
    $('#service_title').val(preselectedServiceId); // Set value based on hidden field
  }
});


// this code for sub-service edit

$(document).ready(function() {

  // Store the initial HTML of the space_sub_service section on page load
  var initialSpaceSubServiceHTML = $('#space_sub_service_edit').html();

  // Initially check the radio button on page load
  if ($('input[name="has_sub_services"]:checked').val() === '1') {
    $('#space_sub_service_edit').removeClass('d-none');
  } else {
    $('#space_sub_service_edit').addClass('d-none');
    clearSubServiceData();
  }

  // Show/hide the section on radio button change
  $('input[name="has_sub_services"]').change(function() {
    if ($(this).val() === '1') {
      $('#space_sub_service_edit').removeClass('d-none');
      restoreSubServiceData();
    } else {
      $('#space_sub_service_edit').addClass('d-none');
      clearSubServiceData();
    }
  });


  // Function to clear the sub service data
  function clearSubServiceData() {
    // Clear input fields
    $('input[name="sub_service_name[]"]').val('');
    $('input[name="sub_service_price[]"]').val('');
    $('select[name="sub_service_status[]"]').val('');

    // Clear table rows
    $('#space_sub_service_edit table tbody').empty();
  }

  // Function to restore the sub service data
  function restoreSubServiceData() {
    // Restore the initial HTML of the space_sub_service section
    $('#space_sub_service_edit').html(initialSpaceSubServiceHTML);
  }
});

// subservice delete from the service edit form start
function rmvSubService(id) {
  $.ajax({
    url: deleteSubServiceUrl,
    type: 'POST',
    data: {'sub_service_id': id },
    success: function (response) {
      // Store the notification message in sessionStorage
      localStorage.setItem('notifyMessage', response.message);
      localStorage.setItem('notifyType', 'success');

      // Reload the page
      location.reload();
    },
    error: function (response) {
      // Store the error notification in sessionStorage
      localStorage.setItem('notifyMessage', response.responseJSON.message);
      localStorage.setItem('notifyType', 'danger');

      // Reload the page
      location.reload();
    }
  });

} 










