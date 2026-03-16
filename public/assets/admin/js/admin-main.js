"use strict";
$(function ($) {
  /*****************************************************
   ==========Bootstrap Notify start==========
   ******************************************************/
  function bootnotify(message, title, type) {
    var content = {};

    content.message = message;
    content.title = title;
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: type,
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      allow_dismiss: true,
      delay: 4000
    });
  }

  /*****************************************************
   ==========Bootstrap Notify end==========
   ******************************************************/

  // Call the function to display demo related message

  if (demo_mode == 'active') {
    $.ajaxSetup({
      beforeSend: function (jqXHR, settings, event) {
        if (settings.type == 'POST') {
          if ($(".request-loader").length > 0) {
            $(".request-loader").removeClass('d-block');
            $(".request-loader").removeClass('show');
          }
          // Hide all visible modals
          if ($(".modal.show").length > 0) {

            // Attempt to hide using Bootstrap's method
            $(".modal.show").modal('hide');
            
            // Fallback: Manually remove modal visibility
            setTimeout(function () {
              $(".modal.show").removeClass('show').css('display', 'none');
              $('.modal-backdrop').remove();
              $('body').removeClass('modal-open').css('padding-right', '');
            }, 300); 
          }
          if ($("button[disabled='disabled']").length > 0) {
            $("button[disabled='disabled']").removeAttr('disabled');
          }
          bootnotify('This is Demo version.You can not change anything', 'Warning', 'warning')
          jqXHR.abort(event);
        }
      },
      complete: function () {
        // hide progress spinner
      }
    });
  }


  $(document).ready(function () {
    // Determine the time format
    var timeFormat;
    if (timeFormatForSearch === '12h') {
      timeFormat = 'h:mm A'; // For 12-hour format
    } else if (timeFormatForSearch === '24h') {
      timeFormat = 'HH:mm'; // For 24-hour format
    }

    // Generate time ranges
    generateTimeRanges(timeFormat);

    // Function to generate time ranges
    function generateTimeRanges(timeFormat) {
      // Define time intervals
      let timeIntervals = [
        ['06:00', '07:00'],
        ['07:00', '08:00'],
        ['08:00', '09:00'],
        ['09:00', '10:00'],
        ['10:00', '11:00'],
        ['11:00', '12:00'],
        ['12:00', '13:00'],
        ['13:00', '14:00'],
        ['14:00', '15:00'],
        ['15:00', '16:00'],
        ['16:00', '17:00'],
        ['17:00', '18:00'],
        ['18:00', '19:00'],
        ['19:00', '20:00'],
        ['20:00', '21:00'],
        ['21:00', '22:00'],
        ['22:00', '23:00'],
        ['23:00', '24:00']

      ];

      let options = '';
      timeIntervals.forEach(function (interval) {
        // Format times based on the time format
        let startTime = moment(interval[0], 'HH:mm').format(timeFormat);
        let endTime = moment(interval[1], 'HH:mm').format(timeFormat);

        options += `<option value="${startTime} - ${endTime}">${startTime} - ${endTime}</option>`;
      });

      // Append options to the time range dropdown
      $('.timeRange').append(options);
    }

    // Handle form submission if needed
    $('.timeRange').on('change', function () {
      let selectedTimeRange = $(this).val();
      // You can submit the form or use this value in further logic
    });
  });

  /*****************************************************
   ==========Demo code end==========
   ******************************************************/

  if (account_status == 1 || secret_login == 1) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  } else {
    $.ajaxSetup({
      beforeSend: function (jqXHR, settings) {
        if (settings.type == 'POST' && status == 0) {
          if ($(".request-loader").length > 0) {
            $(".request-loader").removeClass('show');
          }
          if ($(".modal").length > 0) {
            $(".modal").modal('hide');
          }
          if ($("button[disabled='disabled']").length > 0) {
            $("button[disabled='disabled']").removeAttr('disabled');
          }
          bootnotify(loginAlertTxt, alertTxt, 'warning')
          jqXHR.abort(event);
        }
      },
      complete: function () {
      }
    });
  }

  // sidebar search start
  $("body").on('input', '.sidebar-search', function () {
    let term = $(this).val().toLowerCase();

    if (term.length > 0) {
      $(".sidebar ul li.nav-item").each(function (i) {
        let menuName = $(this).find("p").text().toLowerCase();
        let $mainMenu = $(this);

        // if any main menu is matched
        if (menuName.indexOf(term) > -1) {
          $mainMenu.removeClass('d-none');
          $mainMenu.addClass('d-block');
        } else {
          let matched = 0;
          let count = 0;
          // search sub-items of the current main menu (which is not matched)
          $mainMenu.find('span.sub-item').each(function (i) {
            // if any sub-item is matched of the current main menu, set the flag
            if ($(this).text().toLowerCase().indexOf(term) > -1) {
              count++;
              matched = 1;
            }
          });

          // if any sub-item is matched  of the current main menu (which is not matched)
          if (matched == 1) {
            $mainMenu.removeClass('d-none');
            $mainMenu.addClass('d-block');
          } else {
            $mainMenu.removeClass('d-block');
            $mainMenu.addClass('d-none');
          }
        }
      });
    } else {
      $(".sidebar ul li.nav-item").addClass('d-block');
    }
  });
  // sidebar search end

  // bootstrap datepicker & timepicker start
  $('.datepicker').datepicker({
    autoclose: true,
    rtl: true,
    orientation: 'r',
  });

  let oTime = (typeof openingTime !== 'undefined') ? openingTime : '';
  let cTime = (typeof closingTime !== 'undefined') ? closingTime : '';
  let timeFormatDetailPage = (typeof timeFormatForSearch !== 'undefined') ? timeFormatForSearch : 'HH:mm';
  
  if (cTime === "00:00" || cTime === "0:00") {
    cTime = "23:59"; // last minute of day
  }
 
  // this code for space details page 
  $(document).ready(function () {

    let timePickerFormat;
    if (timeFormatDetailPage === '12h') {
      timePickerFormat = 'h:mm p';
    } else {
      timePickerFormat = 'HH:mm';
    }

    // Initialize the timepicker with the determined timeFormat
    $('.timepicker').timepicker({
      timeFormat: timePickerFormat,
      interval: 60,
      showMeridian: false,
      minTime: oTime,
      startTime: oTime,
      maxTime: cTime,
      dynamic: false,
      dropdown: true,
      rtl: true,
      // scrollbar: true,
      change: function (time) {
        // the input field
        var element = $(this), text;
        // get access to this Timepicker instance
        var timepicker = element.timepicker();
        text = 'Selected time is: ' + timepicker.format(time);
        element.siblings('span.help-line').text(text);
      }
    });

  });
  // datepicker & timepicker end
  $(function () {
    $('.checkInDateNotBooking').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      "autoApply":  true,
      minYear: 1901,
      maxYear: parseInt(moment().format('YYYY'), 10)
    }, function (start, end, label) {
      var years = moment().diff(start, 'years');
    });
  });


  $("#ajaxEditForm").attr('onsubmit', 'return false');
  $("#ajaxForm").attr('onsubmit', 'return false');
  // disabling default behave of form submits end


  // fontawesome icon picker start
  $('.icp-dd').iconpicker();
  // fontawesome icon picker end


  // select2 start
  $('.select2').select2();
  // select2 end


  // summernote initialization start
  $(".summernote").each(function (i) {
    tinymce.init({
      selector: '.summernote',
      plugins: 'autolink charmap emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      promotion: false,
      mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
      ]
    });
  });

  // summernote initialization start
  $(".summernote2").each(function (i) {
    tinymce.init({
      selector: '.summernote2',
      plugins: 'autolink charmap emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      promotion: false,
      mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
      ]
    });
  });




  $(document).on('click', ".note-video-btn", function () {
    let i = $(this).index();

    if ($(".summernote").eq(i).parents(".modal").length > 0) {
      setTimeout(() => {
        $("body").addClass('modal-open');
      }, 500);
    }
  });
  // summernote initialization end


  // Form Submit with AJAX Request Start
  $("body").on('click', '#submitBtn', function (e) {
    $(e.target).attr('disabled', true);
    $(".request-loader").addClass("show");

    if ($(".iconpicker-component").length > 0) {
      $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
    }

    let ajaxForm = document.getElementById('ajaxForm');

    let fd = new FormData(ajaxForm);
    let url = $("#ajaxForm").attr('action');
    let method = $("#ajaxForm").attr('method');

    if ($("#ajaxForm .summernote").length > 0) {
      $("#ajaxForm .summernote").each(function (i) {

        let index = i;
        let $toInput = $('.summernote').eq(index);

        let tmcId = $toInput.attr('id');
        let content = tinyMCE.get(tmcId).getContent();

        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      });
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $('.request-loader').removeClass('show');

        $('.em').each(function () {
          $(this).html('');
        });

        if (data.status == 'success') {
          location.reload();
        }
        if (data.status == 'warning') {
          location.reload();
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
        else if (data.status === 'no_membership') {
          // Display warning message and redirect
          window.location.href = data.redirect_url; // Redirect to the plan extension page
        }
      },
      error: function (error) {
        $('.em').each(function () {
          $(this).html('');
        });

        for (let x in error.responseJSON.errors) {
          document.getElementById('err_' + x).innerHTML = error.responseJSON.errors[x][0];
        }

        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);
      }
    });
  });
  // Form Submit with AJAX Request End


  // Form Prepopulate After Clicking Edit Button Start
  $("body").on('click', '.editBtn', function () {
    $('.em').each(function () {
      $(this).html('');
    });

    let datas = $(this).data();

    delete datas['toggle'];

    for (let x in datas) {

      if ($("#in_" + x).hasClass('summernote')) {
        tinyMCE.activeEditor.setContent(datas[x])
      } else if ($("#in_" + x).hasClass('summernote2')) {
        tinyMCE.activeEditor.setContent(datas[x]);
      } else if ($("#in_" + x).data('role') == 'tagsinput') {
        if (datas[x].length > 0) {
          let arr = datas[x].split(" ");

          for (let i = 0; i < arr.length; i++) {
            $("#in_" + x).tagsinput('add', arr[i]);
          }
        } else {
          $("#in_" + x).tagsinput('removeAll');
        }
      } else if ($("input[name='" + x + "']").attr('type') == 'radio') {
        $("input[name='" + x + "']").each(function (i) {
          if ($(this).val() == datas[x]) {
            $(this).prop('checked', true);
          }
        });
      } else if ($("#in_" + x).hasClass('select2')) {
        $("#in_" + x).val(datas[x]);
        $("#in_" + x).trigger('change');
      } else {
        $("#in_" + x).val(datas[x]);

        if ($('.in_image').length > 0) {
          $('.in_image').attr('src', datas['image']);
        }
        //added by rakib for space category in edit modal start
        if ($('.uploaded-background-img').length > 0) {
          $('.uploaded-background-img').attr('src', datas['bg_image']);
        }
        //added by rakib for space category in edit modal end

        if ($('#in_icon').length > 0) {
          $('#in_icon').attr('class', datas['icon']);
        }
      }
    }

    if ('edit' in datas && datas.edit === 'editAdvertisement') {
      if (datas.ad_type === 'banner') {
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
    }

    // focus & blur colorpicker inputs
    setTimeout(() => {
      $(".jscolor").each(function () {
        $(this).focus();
        $(this).blur();
      });
    }, 300);
  });
  // Form Prepopulate After Clicking Edit Button End


  // Form Update with AJAX Request Start
  $("body").on('click', '#updateBtn', function (e) {

    $(".request-loader").addClass("show");

    if ($(".edit-iconpicker-component").length > 0) {
      $("#editInputIcon").val($(".edit-iconpicker-component").find('i').attr('class'));
    }

    let ajaxEditForm = document.getElementById('ajaxEditForm');
    let fd = new FormData(ajaxEditForm);

    // Only append start_time and end_time if the fields exist in the current form
    if ($("#in_start_time").length > 0 && $("#in_end_time").length > 0) {
      const startTime = $("#in_start_time").val();
      const endTime = $("#in_end_time").val();
      fd.set("start_time", startTime);
      fd.set("end_time", endTime);
    }

    let url = $("#ajaxEditForm").attr('action');
    let method = $("#ajaxEditForm").attr('method');

    if ($("#ajaxEditForm .summernote").length > 0) {
      $("#ajaxEditForm .summernote").each(function (i) {
        let index = i;
        let $toInput = $('.summernote').eq(index);
        let tmcId = $toInput.attr('id');
        let content = tinyMCE.get(tmcId).getContent();
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      })
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);

        $('.em').each(function () {
          $(this).html('');
        });

        if (data.status == 'success') {
          location.reload();
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
      },
      error: function (error) {
        $('.em').each(function () {
          $(this).html('');
        });
        for (let x in error.responseJSON.errors) {
          document.getElementById('editErr_' + x).innerHTML = error.responseJSON.errors[x][0];
        }

        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);
      }
    });
  });
  // Form Update with AJAX Request End

  // Form Update with AJAX Request Start
  $("body").on('click', '#updateBtn2', function (e) {
    $(".request-loader").addClass("show");

    if ($(".edit-iconpicker-component").length > 0) {
      $("#editInputIcon").val($(".edit-iconpicker-component").find('i').attr('class'));
    }

    let ajaxEditForm2 = document.getElementById('ajaxEditForm2');
    let fd = new FormData(ajaxEditForm2);
    let url = $("#ajaxEditForm2").attr('action');
    let method = $("#ajaxEditForm2").attr('method');

    if ($("#ajaxEditForm2 .summernote2").length > 0) {
      $("#ajaxEditForm2 .summernote2").each(function (i) {
        let index = i;
        let $toInput = $('.summernote2').eq(index);
        let tmcId = $toInput.attr('id');
        let content = tinyMCE.get(tmcId).getContent();
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      })
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);

        $('.em').each(function () {
          $(this).html('');
        });

        if (data.status == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        $('.em').each(function () {
          $(this).html('');
        });

        for (let x in error.responseJSON.errors) {
          document.getElementById('editErr_' + x).innerHTML = error.responseJSON.errors[x][0];
        }

        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);
      }
    });
  });
  // Form Update with AJAX Request End


  // Delete Using AJAX Request Start
  $('body').on('click', '.deleteBtn', function (e) {
    e.preventDefault();
    $(".request-loader").addClass("show");

    swal({
      title: areYouSure,
      text: notAbleToRevert,
      type: 'warning',
      buttons: {
        confirm: {
          text: yesDeleteiIt,
          className: 'btn btn-success'
        },
        cancel: {
          text: CancelText,
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent(".deleteForm").submit();
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  // Delete Using AJAX Request End

  // Bulk Delete Using AJAX Request Start
  $("body").on('change','.bulk-check', function () {
    let val = $(this).data('val');
    let checked = $(this).prop('checked');

    // if selected checkbox is 'all' then check all the checkboxes
    if (val == 'all') {
      if (checked) {
        $(".bulk-check").each(function () {
          $(this).prop('checked', true);
        });
      } else {
        $(".bulk-check").each(function () {
          $(this).prop('checked', false);
        });
      }
    }

    // if any checkbox is checked then flag = 1, otherwise flag = 0
    let flag = 0;

    $(".bulk-check").each(function () {
      let status = $(this).prop('checked');

      if (status) {
        flag = 1;
      }
    });

    // if any checkbox is checked then show the delete button
    if (flag == 1) {
      $(".bulk-delete").addClass('d-inline-block');
      $(".bulk-delete").removeClass('d-none');
    } else {
      // if no checkbox is checked then hide the delete button
      $(".bulk-delete").removeClass('d-inline-block');
      $(".bulk-delete").addClass('d-none');
    }
  });

  $('body').on('click', '.bulk-delete', function () {
    swal({
      title: areYouSure,
      text: notAbleToRevert,
      type: 'warning',
      buttons: {
        confirm: {
          text: yesDeleteiIt,
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(".request-loader").addClass('show');
        let href = $(this).data('href');
        let ids = [];

        // take ids of checked one's
        $(".bulk-check:checked").each(function () {
          if ($(this).data('val') != 'all') {
            ids.push($(this).data('val'));
          }
        });

        let fd = new FormData();
        for (let i = 0; i < ids.length; i++) {
          fd.append('ids[]', ids[i]);
        }

        $.ajax({
          url: href,
          method: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function (data) {
            $(".request-loader").removeClass('show');

            if (data.status == "success") {
              location.reload();
            }
          }
        });
      } else {
        swal.close();
      }
    });
  });
  // Bulk Delete Using AJAX Request End


  // Uploaded Image Preview Start
  $('.img-input').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.uploaded-img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });

  // Uploaded Image Preview Start
  $('.img-input-logo').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.uploaded-img-logo').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });

  // Uploaded preloader Image Preview Start
  $('.preloader-img-input').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.uploaded-preloader-img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });


  // Uploaded Background Image Preview Start
  $('.background-img-input').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();
    reader.onload = function (e) {
      $('.uploaded-background-img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });
  // Uploaded Background Image Preview End

  $(document).ready(function () {
    $('body').on('change', '.background-img-input-preview', function (event) {
      let file = event.target.files[0];
      let reader = new FileReader();
      let previewId = $(this).data('preview-id');

      if (file) {
        reader.onload = function (e) {
          $('#' + previewId).attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
      } else {
        
      }
    });
  });


  // Change Input Direction Start
  $('select[name="language_id"]').change(function () {
    $('.request-loader').addClass('show');

    let langId = $(this).val();
    let rtlURL = `${baseUrl}/language-management/${langId}/check-rtl`;
    let categoryURL;

    if ($('select[name="service_category_id"]').length > 0) {
      categoryURL = `${baseUrl}/service-management/language/${langId}/service-categories`;
    }

    // send ajax request to check whether the selected language is 'rtl' or not
    $.get(rtlURL, function (response) {
      $('.request-loader').removeClass('show');

      if ('successData' in response) {
        if (response.successData == 1) {
          $('form.create input').each(function () {
            if (!$(this).hasClass('ltr')) {
              $(this).addClass('rtl');
            }
          });

          $('form.create select').each(function () {
            if (!$(this).hasClass('ltr')) {
              $(this).addClass('rtl');
            }
          });

          $('form.create textarea').each(function () {
            if (!$(this).hasClass('ltr')) {
              $(this).addClass('rtl');
            }
          });

          $('form.create .note-editor.note-frame .note-editing-area .note-editable').each(function () {
            if (!$(this).hasClass('ltr')) {
              $(this).addClass('rtl');
            }
          });
        } else {
          $('form.create input, form.create select, form.create textarea, form.create .note-editor.note-frame .note-editing-area .note-editable').removeClass('rtl');
        }

        // get service-categories
        if (typeof categoryURL !== 'undefined') {
          $.get(categoryURL, function (resp) {
            let categories = resp.serviceCategories;

            // remove previous categories from dom
            $('.service-category').each(function () {
              $(this).remove();
            });

            // append new categories to dom
            if (categories.length > 0) {
              categories.forEach(category => {
                $('select[name="service_category_id"]').append(`<option value="${category.id}" class="service-category">
                  ${category.name}
                </option>`);
              });
            }
          });
        }
      } else {
        alert(response.errorData);
      }
    });
  });
  // Change Input Direction End

  $(".niceselect").niceSelect();


});

function cloneInput(fromId, toId, event) {
  let $target = $(event.target);

  if ($target.is(':checked')) {
    $('#' + fromId + ' .form-control').each(function (i) {
      let index = i;
      let val = $(this).val();
      let $toInput = $('#' + toId + ' .form-control').eq(index);

      if ($(this).hasClass('summernote')) {
        let val = tinyMCE.activeEditor.getContent();
        let tmcId = $toInput.attr('id');
        tinyMCE.get(tmcId).setContent(val);
      } else if ($(this).data('role') == 'tagsinput') {
        if (val.length > 0) {
          let tags = val.split(',');
          tags.forEach(tag => {
            $toInput.tagsinput('add', tag);
          });
        } else {
          $toInput.tagsinput('removeAll');
        }
      } else {
        $toInput.val(val);
      }
    });
  } else {
    $('#' + toId + ' .form-control').each(function (i) {
      let $toInput = $('#' + toId + ' .form-control').eq(i);

      if ($(this).hasClass('summernote')) {
        let tmcId = $toInput.attr('id');
        tinyMCE.get(tmcId).setContent('');
      } else if ($(this).data('role') == 'tagsinput') {
        $toInput.tagsinput('removeAll');
      } else {
        $toInput.val('');
      }
    });
  }
}

function loaders() {
  $(function ($) {
    $(".request-loader").addClass("show");
  })
}

$(document).ready(function () {
  $("body").on('click', '#seller_admin_approval', function () {
    if ($('#seller_admin_approval').is(":checked")) {
      $('.admin_approval_notice').removeClass('d-none');
    } else {
      $('.admin_approval_notice').addClass('d-none');
    }
  });
})
$("[name='qr_builder_status']").on('change', function () {
  var val = $(this).val();
  if (val == 0) {
    $('#qr_code_save_limit').addClass('d-none');
  } else {
    $('#qr_code_save_limit').removeClass('d-none');
  }
});

// withdraw payment status
$('body').on('click','.withdrawStatusBtn', function (e) {
  e.preventDefault();
  $(".request-loader").addClass("show");

  swal({
    title: areYouSure,
    text: notAbleToRevert,
    type: 'warning',
    buttons: {
      confirm: {
        text: 'Yes',
        className: 'btn btn-success'
      },
      cancel: {
        visible: true,
        className: 'btn btn-danger'
      }
    }
  }).then((Delete) => {
    if (Delete) {
      var url = $(this).attr('href');
      window.location.href = url;
    } else {
      swal.close();
      $(".request-loader").removeClass("show");
    }
  });
});
// withdraw payment status end

//the code shows the remove element modal and hide limit check modal when seller's package is downgraded
$(document).ready(function () {
  $('body').on('click', '.remove-button', function () {
    var modalId = $(this).data('target');
    $('#packageLimitModal').modal('hide');
    $(modalId).modal('show');
  });
});

// language selection 
$('body').on('change', '.langBtn', function () {
  let $this = $(this);
  var $code = $(this).val();
  // Get the current full URL
  let curr_url = window.location.href;
  // Remove all instances of the language parameter
  let cleaned_url = curr_url.replace(/([&?])language=[^&]*(&?)/g, function (match, p1, p2) {
    return p1 === '?' ? '?' : p2 ? '&' : '';
  });

  // Add the new language parameter
  let new_url = cleaned_url + (cleaned_url.includes('?') ? '&' : '?') + 'language=' + $code;

  $.ajax({
    url: $("#setLocale").val(),
    method: 'get',
    data: {
      code: $code
    },
    success: function (data) {
      window.location = new_url;

    }
  });
})


//get space type from the package
$(document).ready(function () {
  $('body').on('change', '.featureTypeForSpace', function () {
    var sellerId = $(this).val();
    $.ajax({
      url: getSpaceType,
      type: 'GET',
      data: { seller_id: sellerId },
      success: function (response) {
        function populateSelectOptions() {
          const $selectElement = $('#featureSpaceType');
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

        var featureSpaceTypeContainer = $('#featureSpaceTypeContainer');
        if (response.features.length > 1 || response.seller_id == 0) {
          featureSpaceTypeContainer.removeClass('d-none');
          featureSpaceTypeContainer.addClass('d-block');

        }
        else if (response.features.length == 1) {
          featureSpaceTypeContainer.addClass('d-none');
          featureSpaceTypeContainer.removeClass('d-block');
          var type = '';
          if (response.features[0] == 'Fixed Timeslot Rental') {
            var type = 'fixed_time_slot_rental';
          }
          else if (response.features[0] == 'Hourly Rental') {
            var type = 'hourly_rental';
          }
          else if (response.features[0] == 'Multi Day Rental') {
            var type = 'multi_day_rental';
          }
          // Set the value of the hidden input
          $('#spaceType').val(type);
        }

      },
      error: function () {
        console.error('Failed to fetch the space type feature');
      }
    });
  });
});

$(document).ready(function() {
  $(".time-24slot").flatpickr({
      enableTime: true,
      noCalendar: true,
      dateFormat: timeFormatForSearch == '12h' ? "h:i K" : "H:i",
      time_24hr: timeFormatForSearch == '12h' ? false : true,
      minuteIncrement: 1,
      allowInput: true,
      static: false
  });
}); 

// this code for booking in booking management
document.addEventListener('DOMContentLoaded', function () {
  const bookingNumberInput = document.querySelector('input[name="booking_number"]');
  const customerNameInput = document.querySelector('input[name="customer_name"]');

  // Auto-submit after a delay (e.g., 1 second)
  const autoSubmit = function (event) {
    setTimeout(() => {
      if (event.target.form) {
        event.target.form.submit();
      }
    }, 1000); // Adjust delay as needed
  };

  // Check if bookingNumberInput exists before adding event listener
  if (bookingNumberInput) {
    bookingNumberInput.addEventListener('input', autoSubmit);
  } 

  // Check if customerNameInput exists before adding event listener
  if (customerNameInput) {
    customerNameInput.addEventListener('input', autoSubmit);
  }
});

// this code for space create form
$(document).ready(function () {
  // Check which one exists: create or edit form hidden field
  const isFixedTimeSlotRental = $('#fixedTimeSlotRental').length
    ? $('#fixedTimeSlotRental').val() == '1'
    : $('#editFixedTimeSlotRental').val() == '1';

  function toggleRentFields() {
    const useSlotRent = $('#use_slot_rent_yes').is(':checked');

    if (useSlotRent) {
      // For both create & edit, use both hide methods safely
      $('#baseRentField').addClass('d-none').css('display', 'none');
      if (isFixedTimeSlotRental) {
        $('#spaceSizeCol').removeClass('col-lg-6').addClass('col-lg-12');
      }
    } else {
      $('#baseRentField').removeClass('d-none').css('display', 'block');
      if (isFixedTimeSlotRental) {
        $('#spaceSizeCol').removeClass('col-lg-12').addClass('col-lg-6');
      }
    }
  }

  // Bind event
  $('input[name="use_slot_rent"]').on('change', toggleRentFields);

  // Trigger initially
  toggleRentFields();
});

// custom page form
$('body').on('submit', '#pageForm', function (e) {
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
      $('#pageErrors ul').html(errors);
      $('#pageErrors').removeClass('d-none')

      $('.request-loader').removeClass('show');

      $('html, body').animate({
        scrollTop: $('#pageErrors').offset().top - 100
      }, 1000);
    }
  });
});


/*============================================
    Read more toggle button
============================================*/

var read_more = "Read More";
var read_less = "Read Less";

// When modal is shown
$(document).on("shown.bs.modal", ".modal", function () {
  $(this).find(".click-show").each(function () {
    var p = $(this).find("p")[0];

    // Show button if text overflows
    if (p.scrollHeight > p.clientHeight) {
      $(this).find(".read-more-btn").show();

      // Set button text according to current state
      if ($(this).hasClass("show")) {
        $(this).find(".read-more-btn").text(read_less);
      } else {
        $(this).find(".read-more-btn").text(read_more);
      }
    }
  });
});

// Toggle read more / read less
$(document).on("click", ".read-more-btn", function () {
  var parent = $(this).closest(".click-show");
  parent.toggleClass("show");

  if (parent.hasClass("show")) {
    $(this).text(read_less);
  } else {
    $(this).text(read_more);
  }
});



/*=================================
** Role & Permissions selectgroup
===================================*/
$(document).ready(function () {
  $(".selectgroup-wrapper-item").each(function () {
    const wrapper = $(this);
    const parentCheckbox = wrapper.find(".parent").closest("label").find(".selectgroup-input");
    const childCheckboxes = wrapper.find(".selectgroup-input").not(parentCheckbox);

    // parent click -> all children update
    parentCheckbox.on("change", function () {
      let checked = $(this).is(":checked");
      childCheckboxes.prop("checked", checked);
    });

    // child change -> parent update
    childCheckboxes.on("change", function () {
      const allChecked = childCheckboxes.length === childCheckboxes.filter(":checked").length;
      parentCheckbox.prop("checked", allChecked);
    });
  });
});



