"use strict";

// Move popstate listener outside of $(document).ready
window.addEventListener('popstate', function () {
  $('.request-loader').removeClass('d-block').addClass('d-none');
  $('.request-loader img').removeClass('d-block').addClass('d-none');
});

// Handle BF Cache restoration with pageshow event
window.addEventListener('pageshow', function (event) {
  // Check if the page was restored from BF Cache
  if (event.persisted) {
    $('.request-loader').removeClass('d-block').addClass('d-none');
    $('.request-loader img').removeClass('d-block').addClass('d-none');
  }
});

$(document).ready(function () {
  
  // Hide preloader on page load
  $('.request-loader').removeClass('d-block').addClass('d-none');
  $('.request-loader img').removeClass('d-block').addClass('d-none');
  
  $("#payment-gateway").on('change', function () {

    let data = [];
    offline.map(({
      id,
      name
    }) => {
      data.push(name);
    });
    let paymentMethod = $("#payment-gateway").val();
    const selectedOption = $(this).find('option:selected');

    // Fetch data attributes
    const attachment = selectedOption.data('attachment'); // Fetch data-attachment
    const paymentType = selectedOption.data('payment_type'); // Fetch data-payment_type


    $(".gateway-details").removeClass('d-none');
    $(".gateway-details input").attr('disabled', true);

    // show or hide stripe card inputs
    if (paymentMethod == 'Stripe') {
      $('#stripe-element').removeClass('d-none');
    } else {
      $('#stripe-element').addClass('d-none');
      $('.text-danger').remove();
    }

    if (paymentMethod == 'Authorize.Net') {
      
      $("#tab-anet").removeClass('d-none');
      $("#tab-anet input").removeAttr('disabled');
    }
    else{
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
        url: payemnt_instruction_ulr,
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
                                               <input type="file" name="receipt" value="" class="file-input">
                                               <p class="mb-0 text-warning">** ${Receipt_image_must_be}</p>
                                               <p id="error-message" class="text-danger d-none">Please upload a receipt image.</p>
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
        error: function (data) {
        }
      })
    } else {
      $('#instructions').html('');
    }
  });
});


// Get the space_id value from the data-space_id attribute
$("[data-target='#createModal']").on('click', function () {
  var spaceId = $(this).data('space_id');
  // Set the value of the hidden input field
  $("input[name='space_id']").val(spaceId);
});


$(document).ready(function () {
  $('#stripe-element').addClass('d-none');
  if ($('#payment-gateway').val() === 'Stripe') {
    $('#stripe-element').removeClass('d-none');
  }
  if ($('#payment-gateway').val() === 'Freshpay') {
    $('#tab-freshpay').removeClass('d-none');
    $('#tab-freshpay input, #tab-freshpay select').prop('disabled', false);
  } else {
    $('#tab-freshpay').addClass('d-none');
    $('#tab-freshpay input, #tab-freshpay select').prop('disabled', true);
  }

  // Event listener for payment method change
  $('#payment-gateway').change(function () {
    if ($(this).val() === 'Stripe') {
      $('#stripe-element').removeClass('d-none'); // Show the card element
    } else {
      $('#stripe-element').addClass('d-none'); // Hide the card element
    }

    if ($(this).val() === 'Freshpay') {
      $('#tab-freshpay').removeClass('d-none');
      $('#tab-freshpay input, #tab-freshpay select').prop('disabled', false);
    } else {
      $('#tab-freshpay').addClass('d-none');
      $('#tab-freshpay input, #tab-freshpay select').prop('disabled', true);
    }
  });
})



var stripe = Stripe(stripe_key);

// Create a Stripe Element for the card field
var elements = stripe.elements();
var cardElement = elements.create('card', {
  style: {
    base: {
      iconColor: '#454545',
      color: '#454545',
      fontWeight: '500',
      lineHeight: '50px',
      fontSmoothing: 'antialiased',
      backgroundColor: '#f2f2f2',
      ':-webkit-autofill': {
        color: '#454545',
      },
      '::placeholder': {
        color: '#454545',
      },
    }
  },
});

// Add an instance of the card Element into the `card-element` div
cardElement.mount('#stripe-element');

// Handle form submission
var form = document.getElementById('my-checkout-form');
form.addEventListener('submit', function (event) {
  event.preventDefault();

  const paymentMethod = $('#payment-gateway').val();
  const selectedOption = $("#payment-gateway option:selected");
  const attachment = selectedOption.data('attachment'); // Fetch data-attachment
  const paymentType = selectedOption.data('payment_type'); // Fetch data-payment_type

  // Check if paymentType is "offline" and attachment is 1
  if (paymentType === "offline" && attachment == 1) {
    const fileInput = $('input[name="receipt"]');
    const errorMessage = $('#error-message');
    errorMessage.addClass('d-none'); // Hide previous error message

    if (!fileInput[0].files.length) {
      errorMessage.text(receiptImageIsRequired).removeClass('d-none');
      return;
    }
    // Clear error message if all checks pass
    $('#error-message').addClass('d-none');
  }


  if ($('#payment-gateway').val() == 'Stripe') {
    stripe.createToken(cardElement).then(function (result) {

      if (result.error) {
        // Display errors to the customer
        var errorElement = document.getElementById('stripe-errors');
        errorElement.textContent = stripeError;
      } else {
        // Send the token to your server
        stripeTokenHandler(result.token);
      }
    });
  } else if ($('#payment-gateway').val() == 'Authorize.Net') {
    sendPaymentDataToAnet();
  } else {
    $('.request-loader').addClass('d-block').removeClass('d-none');
    $('.request-loader img').addClass('d-block').removeClass('d-none');

    $('#my-checkout-form').submit();
  }
});

// Send the token to your server
function stripeTokenHandler(token) {
  // Add the token to the form data before submitting to the server
  var form = document.getElementById('my-checkout-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form to your server
  form.submit();
}

//authorize.net functions

function sendPaymentDataToAnet() {
  // Set up authorisation to access the gateway.
  var authData = {};
  authData.clientKey = public_key;
  authData.apiLoginID = login_id;

  var cardData = {};
  cardData.cardNumber = document.getElementById("anetCardNumber").value;
  cardData.month = document.getElementById("anetExpMonth").value;
  cardData.year = document.getElementById("anetExpYear").value;
  cardData.cardCode = document.getElementById("anetCardCode").value;

  // Now send the card data to the gateway for tokenisation.
  // The responseHandler function will handle the response.
  var secureData = {};
  secureData.authData = authData;
  secureData.cardData = cardData;
  Accept.dispatchData(secureData, responseHandler);
}

function translateMessage(message) {
  const translations = {
    "Please provide valid credit card number.": anetCardError,
    "Please provide valid expiration year.": anetYearError,
    "Please provide valid expiration month.": anetMonthError,
    "Expiration date must be in the future.": anetExpirationDateError,
    "Please provide valid CVV.": anetCvvInvalidError,
  };

  return translations[message] || message;
}

function responseHandler(response) {
  if (response.messages.resultCode === 'Error') {
    var i = 0;
    let errors = ``;

    while (i < response.messages.message.length) {
      const errorMessage = response.messages.message[i].text;
      const translatedMessage = translateMessage(errorMessage);
      errors += `<p class="text-danger" style="margin-bottom: 5px; list-style-type: disc;">
        ${translatedMessage}
      </p>`;

      i = i + 1;
    }

    $('#anetErrors').html(errors);
    $('#anetErrors').removeClass('d-none');
  } else {
    paymentFormUpdate(response.opaqueData);
  }
}

function paymentFormUpdate(opaqueData) {
  document.getElementById("opaqueDataDescriptor").value = opaqueData.dataDescriptor;
  document.getElementById("opaqueDataValue").value = opaqueData.dataValue;
  document.getElementById("my-checkout-form").submit();
}

//reset the radio button selection when the modal is closed and opened again
$(document).ready(function () {
  $('#createModal').on('show.bs.modal', function () {
    const modal = $(this);
    modal.find('.feature-radio').each(function () {
      $(this).prop('checked', false);
    });
    modal.find('.feature-radio:first').prop('checked', true);
  });

  $('#createModal').on('hidden.bs.modal', function () {
    const modal = $(this);
    modal.find('.feature-radio').each(function () {
      $(this).prop('checked', false);
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  // Check for validation errors and show the modal if errors exist
  if (document.getElementById('validation-errors')) {
    $('#createModal').modal('show');
  }
});
document.addEventListener('DOMContentLoaded', function () {
  // Check for validation errors and show the modal if errors exist
  if (document.getElementById('validation-errors')) {
    $('#createModal').modal('show');
  }

  // Clear error messages when the modal is closed
  $('#createModal').on('hidden.bs.modal', function () {
    // Clear error messages
    clearErrorMessages();
  });

  function clearErrorMessages() {
    document.querySelectorAll('.text-danger').forEach(function (element) {
      element.textContent = '';
    });

    // Optionally hide error lists if they are used
    document.querySelectorAll('#featureChargeErrors, #paymentMethodErrors, #anetErrors, #stripe-errors').forEach(function (element) {
      element.classList.add('d-none');
    });
  }
});

