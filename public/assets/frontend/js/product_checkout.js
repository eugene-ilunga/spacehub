"use strict";

$(document).ready(function () {
  $('#stripe-element').addClass('d-none');

})

// copy billing details values to shipping details
$('body').on('click', '#shipping-check', function () {
  if ($(this).prop('checked')) {
    let firstName = $('input[name="billing_first_name"]').val();
    $('input[name="shipping_first_name"]').val(firstName);

    let lastName = $('input[name="billing_last_name"]').val();
    $('input[name="shipping_last_name"]').val(lastName);

    let email = $('input[name="billing_email"]').val();
    $('input[name="shipping_email"]').val(email);

    let phone = $('input[name="billing_contact_number"]').val();
    $('input[name="shipping_contact_number"]').val(phone);

    let address = $('input[name="billing_address"]').val();
    $('input[name="shipping_address"]').val(address);

    let city = $('input[name="billing_city"]').val();
    $('input[name="shipping_city"]').val(city);

    let state = $('input[name="billing_state"]').val();
    $('input[name="shipping_state"]').val(state);

    let country = $('input[name="billing_country"]').val();
    $('input[name="shipping_country"]').val(country);
  } else {
    $('input[name="shipping_first_name"]').val('');
    $('input[name="shipping_last_name"]').val('');
    $('input[name="shipping_email"]').val('');
    $('input[name="shipping_contact_number"]').val('');
    $('input[name="shipping_address"]').val('');
    $('input[name="shipping_city"]').val('');
    $('input[name="shipping_state"]').val('');
    $('input[name="shipping_country"]').val('');
  }
});


// get shipping charge by clicking on radio button
$('body').on('click', 'input[name="shipping_method"]', function () {

  let id = $('input[name="shipping_method"]:checked').val();
  let charge = $('input[name="shipping_method"]:checked').data('shipping_charge');

  $('input[name="shipping_method"][type="hidden"]').val(id);
  // set the amount of selected shipping charge in 'charge summary' table
  $('.shipping-charge-amount').text(charge);

  let url = `${baseURL}/shop/put-shipping-method-id/${id}`;

  $.get(url, function (response) {
  })

  let subTotal = $('#subtotal-amount').text();
  let discount = $('#discount').text();
  if (discount.length > 0) {
    discount = discount.replace(',', '');
    discount = parseFloat(discount);
  } else {
    discount = 0.00;
  }
  let tax = $('#tax-amount').text();

  // get the new grand total
  subTotal = subTotal.replace(',', '');
  subTotal = parseFloat(subTotal);

  tax = tax.replace(',', '');
  tax = parseFloat(tax);

  charge = parseFloat(charge);

  let total = (subTotal - discount) + tax + charge;

  $('#grandtotal-amount').text(total.toFixed(2));
});

/**
 * show or hide stripe gateway input fields,
 * also show or hide offline gateway informations according to checked payment gateway
 */
$('body').on('change', 'select[name="gateway"]', function () {
  let value = $(this).val();
  let dataType = parseInt(value);

  if (isNaN(dataType)) {
    // hide offline gateway informations
    if ($('.offline-gateway-info').hasClass('d-block')) {
      $('.offline-gateway-info').removeClass('d-block');
    }

    $('.offline-gateway-info').addClass('d-none');

    // show or hide stripe card inputs
    if (value == 'stripe') {
      $('#stripe-element').removeClass('d-none');
    } else {
      $('#stripe-element').addClass('d-none');
    }
    // show or hide 'authorize.net' form
    if (value == 'authorize.net') {

      $('#authorizenet-form').removeClass('d-none');
    } else {

      $('#authorizenet-form').addClass('d-none');
    }

    if (value == 'freshpay') {
      $('#freshpay-form').removeClass('d-none');
    } else {
      $('#freshpay-form').addClass('d-none');
    }

  } else {
    // hide stripe gateway card inputs
    if (!$('#stripe-element').hasClass('d-none')) {
      $('#stripe-element').addClass('d-none');
      $('#stripe-element').removeClass('d-block');
    }
    $('#authorizenet-form').addClass('d-none');
    $('#freshpay-form').addClass('d-none');

    // hide offline gateway informations
    if ($('.offline-gateway-info').hasClass('d-block')) {
      $('.offline-gateway-info').removeClass('d-block');
    }

    $('.offline-gateway-info').addClass('d-none');

    // show particular offline gateway informations
    $('#offline-gateway-' + value).removeClass('d-none');
  }
});


function applyCoupon(event) {
  event.preventDefault();

  let code = $('#coupon-code').val();

  // if (code) {
  $('.ajaxPreLoader').removeClass('d-none');
  let url = `${baseURL}/shop/checkout/apply-coupon`;

  let data = {
    coupon: code,
    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  };

  $.post(url, data, function (response) {

    $('.ajaxPreLoader').addClass('d-none');

    if ('success' in response) {
      $('#coupon-code').val('');
      toastr['success'](response.success);

      $("#couponReload").load(location.href + " #couponReload");
    } else if ('error' in response) {
      toastr['error'](response.error);
    }
  }).fail(function (jqXHR, textStatus, errorThrown) {
    // Hide the AJAX preloader on error
    $('.ajaxPreLoader').addClass('d-none');
    toastr['error']('Failed to apply coupon. Please try again.');
  });

}

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

// Handle form submission
var form = document.getElementById('payment-form');
form.addEventListener('submit', function (event) {
  event.preventDefault();
  if ($('#gateway').val() == 'stripe') {
    stripe.createToken(cardElement).then(function (result) {
      if (result.error) {
        // Display errors to the customer
        var errorElement = document.getElementById('stripe-errors');
        errorElement.textContent = result.error.message;
      } else {
        // Send the token to your server
        stripeTokenHandler(result.token);
      }
    });
  } else if ($('#gateway').val() == 'authorize.net') {

    sendPaymentDataToAnet();
  } else {

    $('#payment-form').submit();
  }
});

// Send the token to your server
function stripeTokenHandler(token) {
  // Add the token to the form data before submitting to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form to your server
  form.submit();
}

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

function responseHandler(response) {
  if (response.messages.resultCode === 'Error') {
    var i = 0;
    let errors = ``;

    while (i < response.messages.message.length) {
      errors += `<p class="text-danger" style="margin-bottom: 5px; list-style-type: disc;">
        ${response.messages.message[i].text}
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
