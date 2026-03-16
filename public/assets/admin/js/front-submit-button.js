'use strict';
//form submit button 3
$(function($){
  // Handle Enter key press on the keyword input
  $("input[name='keyword']").on('keypress', function (e) {
    if (e.which === 13) {
      e.preventDefault();
      $("#submitBtn3").click();
    }
  })

  $("#submitBtn3").on('click', function (e) {
    $(e.target).attr('disabled', true);
    $(".request-loader").addClass("show");

    if ($(".iconpicker-component").length > 0) {
      $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
    }

    let ajaxForm3 = document.getElementById('ajaxForm3');
    let fd = new FormData(ajaxForm3);
    let url = $("#ajaxForm3").attr('action');
    let method = $("#ajaxForm3").attr('method');

    if ($("#ajaxForm3 .summernote").length > 0) {
      $("#ajaxForm3 .summernote").each(function (i) {
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
        // Clear the input field after successful submission
        $("input[name='keyword']").val('');

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
          document.getElementById('err_' + x).innerHTML = error.responseJSON.errors[x][0];
        }

        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);
      }
    });
  });

});


