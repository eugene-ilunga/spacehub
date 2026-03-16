'use strict';

$(document).on("click", ".quantity-down", function () {
  var numProduct = Number($(this).next().val());
  if (numProduct > 0) $(this).next().val(numProduct - 1);
});
$(document).on("click", ".quantity-up", function () {

  var numProduct = Number($(this).prev().val());

  $(this).prev().val(numProduct);
});

// Shop single slider
var shopSingleThumb = new Swiper(".shop-thumbnails", {
  loop: true,
  speed: 1000,
  spaceBetween: 20,
  slidesPerView: 4,
  centerSlide: true
});
var shopSingleSlider = new Swiper(".shop-single-slider", {
  loop: true,
  speed: 1000,
  autoplay: {
    delay: 3000
  },
  watchSlidesProgress: true,
  thumbs: {
    swiper: shopSingleThumb,
  },

  // Navigation arrows
  navigation: {
    nextEl: ".slider-btn-next",
    prevEl: ".slider-btn-prev",
  },
});

// Shop Slider
var swiper = new Swiper(".shop-slider", {
  speed: 400,
  spaceBetween: 25,
  loop: false,
  slidesPerView: 4,

  // Navigation arrows
  navigation: {
    nextEl: "#shop-slider-next",
    prevEl: "#shop-slider-prev",
  },

  breakpoints: {
    320: {
      slidesPerView: 1
    },
    576: {
      slidesPerView: 2
    },
    992: {
      slidesPerView: 3
    },
    1200: {
      slidesPerView: 4
    },
  }
})



/****************************** */

/************** shop add cart, update cart remove cart & checkout  **************** */

/****************************** */


// add item to the cart by clicking on shop icon
$('.add-to-cart-icon').on('click', function (e) {
  e.preventDefault();

  let url = $(this).attr('href');

  $.get(url, function (response) {
    if ('success' in response) {
      $('#product-count').text(response.numOfProducts);

      toastr['success'](response.success);
      $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
    } else if ('error' in response) {
      toastr['error'](response.error);
    }
  });
});


// set the product quantity by clicking on (+) or (-) button
$('.add-btn').on('click', function () {
  let quantity = $(this).prev().val();

  $(this).prev().val(parseInt(quantity) + 1);
});

$('.sub-btn').on('click', function () {
  let quantity = $(this).next().val();

  if (parseInt(quantity) > 1) {
    $(this).next().val(parseInt(quantity) - 1);
  }
});


// add item to the cart by clicking on 'Add To Cart' button
$('.add-to-cart-btn').on('click', function (event) {
  event.preventDefault();

  let url = $(this).attr('href');
  let amount = $('#product-quantity').val();

  // replace 'qty' string with value
  url = url.replace('qty', amount);

  $.get(url, function (response) {
    if ('success' in response) {
      $('#product-count').text(response.numOfProducts);

      toastr['success'](response.success);
      $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
    } else if ('error' in response) {
      toastr['error'](response.error);
    }
  });
});


// update the cart by clicking on 'Update Cart' button
$('#update-cart-btn').on('click', function (event) {
  event.preventDefault();

  $('.ajaxPreLoader').removeClass('d-none');

  let updateCartURL = $(this).attr('href');

  // initialize empty array
  let productId = [];
  let productUnitPrice = [];
  let productQuantity = [];

  // using each() function to get all the values of same class
  $('.product-id').each(function () {
    productId.push($(this).val());
  });

  $('.product-unit-price').each(function () {
    let price = $(this).text();

    // convert string to number then push to array
    productUnitPrice.push(parseFloat(price));
  });

  $('.product-qty').each(function () {
    let quantity = $(this).val();

    // convert string to number then push to array
    productQuantity.push(parseInt(quantity));
  });

  // initialize a formData
  let formData = new FormData();

  // now, append all the array's value in formData key to send it to the controller
  for (let index = 0; index < productId.length; index++) {
    formData.append('id[]', productId[index]);
    formData.append('unitPrice[]', productUnitPrice[index]);
    formData.append('quantity[]', productQuantity[index]);
  }
  let csrfToken = $('meta[name="csrf-token"]').attr('content');

  $.ajax({
    method: 'POST',
    url: updateCartURL,
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    success: function (response) {

      $('.ajaxPreLoader').addClass('d-none');

      // update the total price of each product and the cart total
      let cartTotal = 0;
      let cart_total_qty = 0;

      $('.per-product-total').each(function (index) {
        let totalPrice = productUnitPrice[index] * productQuantity[index];
        cartTotal += totalPrice;
        cart_total_qty += productQuantity[index];

        $(this).text(totalPrice.toFixed(2));

        //check if qty is 0 then remove product
        if (productQuantity[index] < 1) {
          $('#in-product-id' + productId).remove();
          $('#cart-product-item' + productId).remove();
        }
      });

      $('#cart_total_price').text(cartTotal.toFixed(2));
      $('#cart_total_qty').text(cart_total_qty);

      if (response.total_products < 1) {
        $('#cart-table').empty();
        // then, show a message in div tag

        $('#cart-message').html(cartEmptyTxt);
      }

      toastr['success'](response.success);
      $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
    },
    error: function (errorData) {

      $('.ajaxPreLoader').addClass('d-none');

      toastr['error'](errorData.responseJSON.error);
    }
  });
});


// remove product(s) by clicking on cross icon
$('.remove-product-icon').on('click', function (event) {
  event.preventDefault();

  let removeProductURL = $(this).attr('href');

  // get the product-id from the url to use it later.
  let productId = $(this).data('product_id');
  let cartItem = 'cart-product-item' + productId;

  $.get(removeProductURL, function (response) {
    if ('success' in response) {
      if (response.numOfProducts > 0) {
        // remove only the selected product from DOM
        $('#' + cartItem).remove();
        $('#in-product-id' + productId).remove();

        $('#cart_total_price').text(response.cartTotal);
        $('#cart_total_qty').text(response.numOfProducts);
      } else {
        // remove cart info, cart table and buttons(upadate cart, checkout) from DOM
        $('#cart-table').remove();

        // then, show a message in div tag
        const markUp = `<div class="text-center">
              <h3>${cartEmptyTxt}</h3>
            </div>`;

        $('#cart-message').html(markUp);
      }

      toastr['success'](response.success);
      $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
    } else if ('error' in response) {
      toastr['error'](response.error);
    }
  });
});

// Handle rating selection
$('body').on('click', '.review-value', function () {
  let ratingValue = $(this).find('i').first().data('ratingval');
  $('#rating-id').val(ratingValue);

  // Optional: Highlight selected rating visually
  $('.review-value').removeClass('selected');
  $(this).addClass('selected');
});


$('body').on('keypress', '#searchByProductName', function (event) {

  if (event.which === 13) {
    $('#title').val($(this).val());
    $('#page').val(1);
    inputField();
  }
});

/* Price range */

function getQueryParam(param) {
  var urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

// 1. Fix variable name typo (filterSliders vs filterSliders)
var filterSlider = document.querySelector("[data-range-slider='filterPriceSlider']");
var input0 = document.getElementById('min');
var input1 = document.getElementById('max');

// 2. Proper number conversion with fallback values
var initialMinElement = document.getElementById('initial_min_price');
var initialMaxElement = document.getElementById('initial_max_price');

var initialMinPrice = initialMinElement ?
  Number(initialMinElement.value.replace(/[^0-9.-]/g, '')) || 0 : 0;
var initialMaxPrice = initialMaxElement ?
  Number(initialMaxElement.value.replace(/[^0-9.-]/g, '')) || 1000 : 1000;

var inputs = [input0, input1];

// 3. Get and validate URL parameters
var urlMinValue = getQueryParam('min');
var urlMaxValue = getQueryParam('max');

// 4. Ensure start values are within valid range
var startValues = [
  urlMinValue ? Math.max(initialMinPrice, Number(urlMinValue)) : initialMinPrice,
  urlMaxValue ? Math.min(initialMaxPrice, Number(urlMaxValue)) : initialMaxPrice
];


// 5. Main slider initialization with proper validation
if (filterSlider) {
  // Ensure min < max
  if (startValues[0] >= startValues[1]) {
    startValues[1] = startValues[0] + 100;
  }

  noUiSlider.create(filterSlider, {
    start: startValues,
    connect: true,
    step: 10,
    margin: 10,
    range: {
      min: initialMinPrice,
      max: initialMaxPrice
    },
    // behaviour: 'drag-tap',
    // tooltips: [true, true]
  });

  // Update events
  filterSlider.noUiSlider.on("update", function (values, handle) {
    var formattedValues = values.map(v => "$" + Math.round(v));
    $("[data-range-value='filterPriceSliderValue']").text(formattedValues.join(" - "));
    inputs[handle].value = Math.round(values[handle]);
  });

  filterSlider.noUiSlider.on("end", function (values, handle) {
    // Round the final values
    var roundedValues = values.map(v => Math.round(v));
    document.getElementById("min").value = roundedValues[0];
    document.getElementById("max").value = roundedValues[1];

    localStorage.setItem('minValue', roundedValues[0]);
    localStorage.setItem('maxValue', roundedValues[1]);

    if (typeof inputField === 'function') {
      inputField();
    }
  });

  // Input change handlers
  inputs.forEach(function (input, handle) {
    if (input) {
      input.addEventListener('change', function () {
        var value = Math.min(
          Math.max(initialMinPrice, Number(this.value)),
          initialMaxPrice
        );
        filterSlider.noUiSlider.setHandle(handle, value);
      });
    }
  });
}
function inputField() {
  $('#searchProductForm').submit();
}

