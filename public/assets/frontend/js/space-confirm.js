"use strict";
let prevGatewayId;
$('.offline-gateway-info').addClass('d-none');
// Show the selected offline gateway section
$('select[name="gateway"]').on('change', function () {
  var selectedGatewayId = $(this).val();
  $('.offline-gateway-info').addClass('d-none');
  $('#offline-gateway-' + selectedGatewayId).removeClass('d-none').addClass('d-block');
});


// If there is a validation error, show the relevant offline gateway section
if (window.offlineValidationErrors || window.oldGatewayId) {
  var errorGatewayId = window.oldGatewayId;
  if (errorGatewayId) {
    $('#offline-gateway-' + errorGatewayId).removeClass('d-none').addClass('d-block');
  }
  else if (errorGatewayId == 'iyzico') {

    $('#iyzico-payment-form').removeClass('d-none');
  }
}


// Apply coupon code


function updateTotals(response) {

  var discount = parseFloat(response.discount);
  var grandTotal = parseFloat(response.grandTotal);
  var subtotal = parseFloat(response.subtotal);
  var taxAmount = parseFloat(response.taxAmount); // Ensure this exists in your response

  if (isNaN(grandTotal) || isNaN(discount) || isNaN(subtotal)) {
    toastr.error("Error calculating totals.");
    return false;
  }

  // Format values
  var formattedGrandTotal = grandTotal.toFixed(2);
  var formattedDiscountAmount = discount.toFixed(2);
  var formattedSubtotalAmount = subtotal.toFixed(2);
  var formattedTaxAmount = !isNaN(taxAmount) ? taxAmount.toFixed(2) : "0.00";

  // Show or hide discount <li>
  if (discount > 0) {
    $('#discount-li').show();
    $('#discount-amount').text(formattedDiscountAmount);
  } else {
    $('#discount-li').hide();
  }

  // Update tax amount
  $('#tax-amount').text(formattedTaxAmount);

  // Update other totals
  $('#grand-total-amount').text(formattedGrandTotal);
  $('input[name="grand_total"]').val(formattedGrandTotal);
  $('.sub-total:has(p:contains("Subtotal")) .price').text(formattedSubtotalAmount);

  return true;
}

(function ($) {
  'use strict';
  $('#coupon-code').on('keypress', function (e) {
    let key = e.which;
    if (key == 13) {
      applyCoupon(e);
    }
  });
})(jQuery);

function applyCoupon(event) {
  event.preventDefault();

  $('.ajaxPreLoader').removeClass('d-none');

  let code = $('#coupon-code').val();
  let grandTotal = $('input[name="grand_total"]').val();
  let id = $('input[name="space_id"]').val();
  let subtotalText = $('.sub-total:has(p:contains("Subtotal")) .price').text().trim();
  // Remove currency symbols and commas
  let subtotal = parseFloat(subtotalText.replace(/[^0-9.-]+/g, ""));

  // Fetch the host IP address
  $.getJSON('https://api.ipify.org?format=json', function (data) {
    let hostIp = data.ip;

    let url = getCouponDataUrl;
    let requestData = {
      coupon: code,
      initTotal: grandTotal,
      subtotal: subtotal,
      spaceId: id,
      hostIp: hostIp,
      _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    $.post(url, requestData, function (response) {

      $('.ajaxPreLoader').addClass('d-none');

      $('#coupon-code').val('');
      if ('success' in response) {
        if (updateTotals(response)) {
          toastr['success'](response.success);
        }
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      // Hide the AJAX preloader on error
      $('.ajaxPreLoader').addClass('d-none');
      toastr['error']('Failed to apply coupon. Please try again.');
    });
  }).fail(function (jqXHR, textStatus, errorThrown) {
    // Hide the AJAX preloader if IP fetch fails
    $('.ajaxPreLoader').addClass('d-none');
    toastr['error']('Failed to fetch IP address. Please try again.');
  });
}

/**
 * show or hide payment gateway input fields,
 * also show or hide offline gateway informations according to checked payment gateway
 */

$('select[name="gateway"]').on('change', function () {
  let value = $(this).val();

  let gatewayType = $(this).find(':selected').data('gateway_type');

  if (gatewayType == 'online') {
    // hide previously selected gateway
    if (prevGatewayId) {
      $(`#gateway-attachment-${prevGatewayId}`).hide();
      $(`#gateway-description-${prevGatewayId}`).hide();
      $(`#gateway-instructions-${prevGatewayId}`).hide();
    }
    // show or hide 'stripe' form
    if (value == 'iyzico') {
      $('#iyzico-payment-form').removeClass('d-none');
    } else {
      $('#iyzico-payment-form').addClass('d-none');
    }

    // show or hide 'stripe' form
    if (value == 'stripe' || window.oldGatewayId == 'stripe') {
      $('#stripe-element').removeClass('d-none');
    } else {
      $('#stripe-element').addClass('d-none');
    }

    // show or hide 'authorize.net' form
    if (value == 'authorize.net' || window.oldGatewayId == 'authorize.net') {

      $('#authorizenet-form').removeClass('d-none');
    } else {

      $('#authorizenet-form').addClass('d-none');
    }

    if (value == 'freshpay') {
      $('#freshpay-form').removeClass('d-none');
    } else {
      $('#freshpay-form').addClass('d-none');
    }

  }
  else {
    // hide 'stripe' & 'authorize.net' form
    if (!$('#stripe-element').hasClass('d-none')) {
      $('#stripe-element').addClass('d-none');
      $('#stripe-element').removeClass('d-block');
    }

    $('#authorizenet-form').addClass('d-none');
    $('#iyzico-payment-form').addClass('d-none');
    $('#freshpay-form').addClass('d-none');

    // hide previously selected gateway
    if (prevGatewayId) {
      $(`#gateway-attachment-${prevGatewayId}`).hide();
      $(`#gateway-description-${prevGatewayId}`).hide();
      $(`#gateway-instructions-${prevGatewayId}`).hide();
    }
    prevGatewayId = value;
  }
});


$('#payment-form-btn').on('click', function (e) {

  e.preventDefault();
  // Clear previous error messages
  $('.em').remove();
  // start Initialize error flag for validation error field
  var firstName = $('#firstName').val();
  var phoneNumber = $('#phone').val();
  var emailAddress = $('#email').val();
  var selectedGateway = $('#payment-gateway').val();


  let hasError = false;
  // Validate required fields
  if (!firstName) {
    $('#firstName').after(`<p class="mt-1 mb-0 text-danger em">${firstNameError}</p>`);
    hasError = true;
  }
  if (!phoneNumber) {
    $('#phone').after(`<p class="mt-1 mb-0 text-danger em">${phoneNumberError}</p>`);
    hasError = true;
  }
  if (!emailAddress) {
    $('#email').after(`<p class="mt-1 mb-0 text-danger em">${emailAddressError}</p>`);
    hasError = true;
  }
  if (!selectedGateway) {
    $('#payment-gateway-error').after(`<p class="mt-1 mb-0 text-danger em">${paymentGatewayError}</p>`);
    hasError = true;
  }
  if (hasError) {
    return;
  }

  let gateway = $('select[name="gateway"]').val();

  if (gateway == 'authorize.net') {
    sendPaymentDataToAnet();
  } else if (gateway == 'stripe') {
    paymentForStripe();
  } else {
    $('#preLoader').show();
    $('#payment-form').submit();
  }
});
//payment gateway js start

// Authorize.Net js code
function sendPaymentDataToAnet() {
  // set up authorisation to access the gateway.
  var authData = {};
  authData.clientKey = clientKey;
  authData.apiLoginID = loginId;

  var cardData = {};
  cardData.cardNumber = document.getElementById('cardNumber').value;
  cardData.month = document.getElementById('expMonth').value;
  cardData.year = document.getElementById('expYear').value;
  cardData.cardCode = document.getElementById('cardCode').value;

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
    $('#anetErrors').show();
  } else {
    paymentFormUpdate(response.opaqueData);
  }
}

function paymentFormUpdate(opaqueData) {
  document.getElementById('opaqueDataDescriptor').value = opaqueData.dataDescriptor;
  document.getElementById('opaqueDataValue').value = opaqueData.dataValue;
  document.getElementById('payment-form').submit();
}
function paymentForStripe() {
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
}

if (typeof stripe_key != 'undefined') {
  // Set your Stripe public key
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
  // Send the token to your server
}

function stripeTokenHandler(token) {
  // Add the token to the form data before submitting to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form to your server
  document.getElementById('payment-form').submit();
}
//payment gateway js end

// Show or hide the payment gateway form based on the old gateway id
document.addEventListener('DOMContentLoaded', function () {
  // Check for validation errors and show the modal if errors exist
  if (document.getElementById('iyzico-validation-errors') || window.oldGatewayId == 'iyzico') {
    $('#iyzico-payment-form').removeClass('d-none');
  }

  // this code for authorize.net payment gateway 
  if (window.oldGatewayId == 'authorize.net') {

    $('#authorizenet-form').removeClass('d-none');
  } else {

    $('#authorizenet-form').addClass('d-none');
  }

  // this code for stripe payment gateway 
  if (window.oldGatewayId == 'stripe') {
    $('#stripe-element').removeClass('d-none');
  } else {
    $('#stripe-element').addClass('d-none');
  }

  // this code for freshpay payment gateway
  if (window.oldGatewayId == 'freshpay') {
    $('#freshpay-form').removeClass('d-none');
  } else {
    $('#freshpay-form').addClass('d-none');
  }
});
