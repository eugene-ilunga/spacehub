"use strict";
$(document).ready(function () {
  $("#payment-gateway").on('change', function () {
    let data = [];
    offline.map(({
      id,
      name
    }) => {
      data.push(name);
    });
    let paymentMethod = $("#payment-gateway").val();

    // $(".gateway-details").hide();
    // $(".gateway-details input").attr('disabled', true);

    $(".gateway-details").removeClass('d-none');
    $(".gateway-details input").attr('disabled', true);

    // show or hide stripe card inputs
    if (paymentMethod == 'Stripe') {
      $('#stripe-element').removeClass('d-none');
    } else {
      $('#stripe-element').addClass('d-none');
    }

    if (paymentMethod == 'Authorize.Net') {
      $("#tab-anet").removeClass('d-none');
      $("#tab-anet input").removeAttr('disabled');
    } else {
      $("#tab-anet").addClass('d-none');
      $("#tab-anet input").prop('disabled', true);
    }

    if (paymentMethod == 'Freshpay') {
      $("#tab-freshpay").removeClass('d-none');
      $("#tab-freshpay input, #tab-freshpay select").prop('disabled', false);
    } else {
      $("#tab-freshpay").addClass('d-none');
      $("#tab-freshpay input, #tab-freshpay select").prop('disabled', true);
    }

    if (data.indexOf(paymentMethod) != -1) {
      let formData = new FormData();
      formData.append('name', paymentMethod);
      $.ajax({
        url: getOfflinePaymentUrl,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        contentType: false,
        processData: false,
        cache: false,
        data: formData,
        success: function (data) {
          let instruction = $("#instructions");
          let instructions = `<div class="gateway-desc">${data.instructions}</div>`;
          if (data.description != null) {
            var description = `<div class="gateway-desc"><p>${data.description}</p></div>`;
          } else {
            var description = `<div></div>`;
          }
          let receipt = `<div class="form-element mb-2">
                           <label>${receiptTxt}<span>*</span></label><br>
                           <input type="file" name="receipt" value="" class="file-input" required>
                           <p class="mb-0 text-warning">** ${Receipt_image_must_be}</p>
                        </div>`;
          if (data.has_attachment == 1) {
            $("#is_receipt").val(1);
            let finalInstruction = instructions + description + receipt;
            instruction.html(finalInstruction);
          } else {
            $("#is_receipt").val(0);
            let finalInstruction = instructions + description;
            instruction.html(finalInstruction);
          }
          $('#instructions').fadeIn();
        },
        error: function (data) { }
      })
    } else {
      $('#instructions').html('');
    }
  });
});
