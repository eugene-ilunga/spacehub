'use strict';

let spaceBookingData = (typeof spaceBooking !== 'undefined') ? spaceBooking : [];
let spaceType = (typeof type !== 'undefined') ? type : '1';

// this code for subscription start here 
$('body').on('submit', '.subscription-form', function (event) {
  event.preventDefault();

  // Show the AJAX preloader
  $('.ajaxPreLoader').removeClass('d-none');

  let formUrl = $(this).attr('action');
  let formMethod = $(this).attr('method');
  let formData = new FormData($(this)[0]);
  $.ajax({
    url: formUrl,
    method: formMethod,
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {

      // Hide the AJAX preloader
      $('.ajaxPreLoader').addClass('d-none');

      $('input[name="email_id"]').val('');
      toastr.success(response.success)
    },
    error: function (errorData) {

      // Hide the AJAX preloader
      $('.ajaxPreLoader').addClass('d-none');

      $('input[name="email_id"]').val('');
      toastr['error'](errorData.responseJSON.error.email_id[0]);

    }
  })
})
// this code for subscription end here

// this code written  for get space ids, those are already booked and disable the calendar date start here 
$(document).ready(function () {
  window.bookingsArray;
})

// this code written  for get space ids, those are already booked and disable the calendar date start here

// this code get input field value , select the item and calculate the total start here
window.addEventListener('load', function () {

  $('input[type="checkbox"], input[type="radio"]').prop('checked', false);
  $('.numberOfGuest').val('');

  // Track the previously selected subserviceId
  var previouslySelectedSubserviceId = null;

  $('body').on('click', '.image-radio-input', function () {
    var $this = $(this);
    var isCustomDay = $this.data('is_custom_day');
    var spaceType = $this.data('space_type');
    var subserviceId = $this.data('sub-service-id');
    var serviceId = $this.data('space-service-id');

    // Handle selection/deselection globally for all spaceTypes
    var isSelected = $this.hasClass('selected');

    // If already selected, deselect it
    if (isSelected) {
      $this.removeClass('selected');
      $this.prop('checked', false);
      // Hide the corresponding selectedDay div when deselected
      $(`.selectedDay-${subserviceId}`).addClass('d-none');
      previouslySelectedSubserviceId = null;
    } else {
      // If not selected, select it
      $this.addClass('selected');
      $this.prop('checked', true);

      // Hide the selectedDay div of the previously selected item
      if (previouslySelectedSubserviceId) {
        $(`.selectedDay-${previouslySelectedSubserviceId}`).addClass('d-none');
      }

      // Show the corresponding selectedDay div for the newly selected item
      $(`.selectedDay-${subserviceId}`).removeClass('d-none');

      // Update the previously selected subserviceId
      previouslySelectedSubserviceId = subserviceId;
    }

    // Calculate totals after the selection change (applies to all spaceTypes)
    calculateTotals();

    // If spaceType is not 3, do not open the modal
    if (spaceType != 3) {
      return;
    }

    // Modal logic only for spaceType == 3
    if (isCustomDay == 1 && spaceType == 3) {
      if (isSelected) {
        // Deselect the current item and hide the corresponding day div
        $this.removeClass('selected');
        $this.prop('checked', false);
        $(`.selectedDay-${subserviceId}`).addClass('d-none');
        calculateTotals();
        return;
      }

      // Remove the 'selected' class and hide the day div from all radio buttons in the group
      $(`input[name="image-${subserviceId}"]`).each(function () {
        $(this).removeClass('selected');
        $(this).prop('checked', false);
        $(`.selectedDay-${subserviceId}`).addClass('d-none');
      });

      // Add 'selected' class to the newly selected radio button
      $this.addClass('selected');
      $this.prop('checked', true);
      $(`.selectedDay-${subserviceId}`).removeClass('d-none');

      // Calculate totals and populate modal fields
      const { grandTotal, newTime, totalHours, numberOfDay = null, startDate, endDate } = calculateTotals();

      $(`.numberOfCustomDay-${subserviceId}`).text(numberOfDay || 1);

      // Update "Day" or "Days" text based on the numberOfDay value
      const dayText = (numberOfDay > 1) ? translations.days : translations.day;
      $(`.selectedDay-${subserviceId} .day-text`).text(dayText);

      calculateTotals();

      var indexValue = $this.data('index_value');
      var numberOfCustomDay = $(`.numberOfCustomDay-${subserviceId}`).text();

      // Set modal values
      $('#dayModal #serviceId').val(serviceId);
      $('#dayModal #serviceIndexValue').val(indexValue);
      $('#dayModal #subserviceId').val(subserviceId);
      $('#dayModal #inputDayForService').val(numberOfCustomDay ? numberOfCustomDay : numberOfDay);
      $('#dayModal').modal('show');
    }
  });
  //this code select or deselect the single choose subservice and call the calculateTotals function end

  //this code select or deselect the single choose without subservice and call the calculateTotals function start
  $('body').on('click', '.image-checkbox-input', function () {
    let $this = $(this);
    let isCustomDay = $this.data('is_custom_day');
    let spaceType = $this.data('space_type');
    let subserviceId = $this.data('sub-service-id');
    let serviceId = $this.data('space-service-id');
    let isSelected = $this.hasClass('selected');

    // If already selected, deselect it
    if (isSelected) {
      $this.removeClass('selected');
      $this.prop('checked', false);
      // Hide the corresponding selectedDay div when deselected
      $(`.selectedDay-${subserviceId}`).addClass('d-none');
    } else {
      // If not selected, select it
      $this.addClass('selected');
      $this.prop('checked', true);
      // Show the corresponding selectedDay div
      $(`.selectedDay-${subserviceId}`).removeClass('d-none');
    }

    // Calculate totals after the selection change (applies to all spaceTypes)
    calculateTotals();

    // If spaceType is not 3, do not open the modal
    if (spaceType != 3) {
      return;
    }

    // Modal logic only for spaceType == 3 and isCustomDay == 1
    if (isCustomDay == 1 && spaceType == 3) {
      if (isSelected) {
        // Deselect the current item and hide the corresponding day div
        $this.removeClass('selected');
        $this.prop('checked', false);
        $(`.selectedDay-${subserviceId}`).addClass('d-none');
        calculateTotals();
        return;
      }

      // Add 'selected' class to the newly selected checkbox
      $this.addClass('selected');
      $this.prop('checked', true);
      $(`.selectedDay-${subserviceId}`).removeClass('d-none');

      // Calculate totals and populate modal fields
      const { grandTotal, newTime, totalHours, numberOfDay = null, startDate, endDate } = calculateTotals();

      $(`.numberOfCustomDay-${subserviceId}`).text(numberOfDay || 1);

      const dayText = (numberOfDay > 1) ? translations.days : translations.day;
      $(`.selectedDay-${subserviceId} .day-text`).text(dayText);

      calculateTotals();

      var indexValue = $this.data('index_value');
      var numberOfCustomDay = $(`.numberOfCustomDay-${subserviceId}`).text();

      // Set modal values dynamically
      $('#dayModal #serviceId').val(serviceId);
      $('#dayModal #serviceIndexValue').val(indexValue);
      $('#dayModal #subserviceId').val(subserviceId);
      $('#dayModal #inputDayForService').val(numberOfCustomDay ? numberOfCustomDay : numberOfDay);
      $('#dayModal').modal('show');
    }
  });
  //this code select or deselect the single choose without subservice and call the calculateTotals function end

  //this code select or deselect the single choose subservice and call the calculateTotals function start
  $('body').on('click', '.input-checkbox', function () {
    let $this = $(this);
    let isCustomDay = $this.data('is_custom_day');
    let spaceType = $this.data('space_type');
    let serviceId = $this.data('space-service-id');
    let isSelected = $this.hasClass('selected');

    // If already selected, deselect it
    if (isSelected) {
      $this.removeClass('selected');
      $this.prop('checked', false);
      // Hide the corresponding selectedDay div when deselected
      $(`.selectedDay-${serviceId}`).addClass('d-none');
    } else {
      // If not selected, select it
      $this.addClass('selected');
      $this.prop('checked', true);
      // Show the corresponding selectedDay div
      $(`.selectedDay-${serviceId}`).removeClass('d-none');
    }

    // Calculate totals after the selection change (applies to all spaceTypes)
    calculateTotals();

    // If spaceType is not 3, do not open the modal
    if (spaceType != 3) {
      return;
    }

    // Modal logic only for spaceType == 3 and isCustomDay == 1
    if (isCustomDay == 1 && spaceType == 3) {
      if (isSelected) {
        // Deselect the current item and hide the corresponding day div
        $this.removeClass('selected');
        $this.prop('checked', false);
        $(`.selectedDay-${serviceId}`).addClass('d-none');
        calculateTotals();
        return;
      }

      // Add 'selected' class to the newly selected checkbox
      $this.addClass('selected');
      $this.prop('checked', true);
      $(`.selectedDay-${serviceId}`).removeClass('d-none');

      // Calculate totals and populate modal fields
      const { grandTotal, newTime, totalHours, numberOfDay = null, startDate, endDate } = calculateTotals();

      $(`.numberOfCustomDay-${serviceId}`).text(numberOfDay || 1);

      const dayText = (numberOfDay > 1) ? translations.days : translations.day;
      $(`.selectedDay-${serviceId} .day-text`).text(dayText);

      calculateTotals();

      var indexValue = $this.data('index_value');
      var numberOfCustomDay = $(`.numberOfCustomDay-${serviceId}`).text();

      // Set modal values dynamically
      $('#dayModal #serviceId').val(serviceId);
      $('#dayModal #serviceIndexValue').val(indexValue);
      $('#dayModal #subserviceId').val('');
      $('#dayModal #inputDayForService').val(numberOfCustomDay ? numberOfCustomDay : numberOfDay);
      $('#dayModal').modal('show');
    }

  });
  //this code select or deselect the single choose subservice and call the calculateTotals function end

  // this code calculate the subtotal, grandtotal according to seletced items , space rent 
  function calculateTotals() {

    // clear all error message when any event trigger
    $('.em').remove();
    const numberOfGuest = $('.numberOfGuest').val() || 1;
    let spaceRent = 0, rentPerDay = 0, rentPerHour = 0, numberOfDay = 1, customHour = 1;
    let bookingDate, startDate, endDate, newTime, newTimeWithoutInterval;
    let startTime = null, endTime = null, totalHours = null;
    let formattedStartTime = null;


    // Calculate space rent based on space type
    if (spaceType == 1) {
      if (isTimeSlotRent == 1) {
        const selectedTimeSlot = $('#eventTime option:selected');
        const timeSlotRentString = selectedTimeSlot.data('time_slot_rent');
        const timeSlotRent = timeSlotRentString ? parseFloat(timeSlotRentString) : 0;
        spaceRent = timeSlotRent;
      }
      else {
        const spaceRentText = $('.spaceRent').text();
        spaceRent = spaceRentText ? parseFloat(spaceRentText.replace('$', '').replace(/,/g, '')) : 0;
      }

    } else if (spaceType == 3) {
      bookingDate = $('#eventDate').val();
      const rentPerDayText = $('.rentPerDay').text();
      
      rentPerDay = rentPerDayText ? parseFloat(rentPerDayText.replace('$', '').replace(/,/g, '')) : 0;

      if (bookingDate) {
        [startDate, endDate] = bookingDate.split(' - ').map(date => date.trim());
        const allDates = getAllDatesBetween(startDate, endDate);

        const uniqueDates = [...new Set(allDates.map(date => date.toISOString().split('T')[0]))];
        numberOfDay = uniqueDates.length || 1; // Default to 1 if no unique dates
        $('.numberOfDay').text(numberOfDay);

        // this code check the numberOfDay number for add or remove 's' from the text
        const isMoreThanOne = numberOfDay > 1;

        $('.numberOfDayTextMoreThanOne').toggleClass("d-none", !isMoreThanOne).toggleClass("d-block", isMoreThanOne);
        $('.numberOfDayTextOneOrZero').toggleClass("d-none", isMoreThanOne).toggleClass("d-block", !isMoreThanOne);

      }
    } else if (spaceType == 2) {
      const rentPerHourText = $('.rentPerHour').text();
      rentPerHour = rentPerHourText ? parseFloat(rentPerHourText.replace('$', '').replace(/,/g, '')) : 0;
      customHour = Math.abs(parseInt($('#hours').val()));
      let prepareTimeValue = parseInt($('#prepareTimeId').val()) || 0;

      if (customHour == 0) {
        $('.em').remove();
        $('input[name="hours"]').after('<p class="mt-1 mb-0 text-danger em">' + translations.inValidNumber + '</p>');
        return;
      }
      const selectedTime = $('#timepickerForHourly').val() || '';
      const convertedTime = is24HourFormat(selectedTime) ? selectedTime : convertTo24HourFormat(selectedTime);
      const [hours, minutes] = convertedTime.split(':').map(Number);

      startTime = new Date();
      startTime.setHours(hours, minutes, 0);
      // Format startTime as HH:MM
      let startHours = String(startTime.getHours()).padStart(2, '0');
      let startMinutes = String(startTime.getMinutes()).padStart(2, '0');
      formattedStartTime = `${startHours}:${startMinutes}`;
      const requestStartTime = convertTimeToMinutes(formattedStartTime);

      endTime = new Date(startTime);
      endTime.setHours(startTime.getHours() + customHour);

      // Format the new time as HH:MM
      let newHourWithoutInterval = String(endTime.getHours()).padStart(2, '0');
      let newMinuteWithoutInterval = String(endTime.getMinutes()).padStart(2, '0');
      newTimeWithoutInterval = `${newHourWithoutInterval}:${newMinuteWithoutInterval}`;

      // Calculate the difference in milliseconds
      let timeDifference = endTime - startTime; // in milliseconds
      totalHours = timeDifference / (1000 * 60 * 60); // convert to hours
      // Add prepareTimeValue (in minutes) to endTime
      endTime.setMinutes(endTime.getMinutes() + prepareTimeValue);

      // Format the new time as HH:MM
      let newHours = String(endTime.getHours()).padStart(2, '0');
      let newMinutes = String(endTime.getMinutes()).padStart(2, '0');
      newTime = `${newHours}:${newMinutes}`;

      $('.totalHourForSpaceType2').text(isNaN(customHour) ? 0 : customHour);

      // this code check the customHour number for add or remove 's' from the text
      const isMoreThanOne = customHour > 1;

      $('.numberOfHourTextMoreThanOne').toggleClass("d-none", !isMoreThanOne).toggleClass("d-block", isMoreThanOne);
      $('.numberOfHourTextOneOrZero').toggleClass("d-none", isMoreThanOne).toggleClass("d-block", !isMoreThanOne);
    }

    // Calculate service totals
    let serviceTotal = 0;
    $('input[name^="image-"], input[name="checkbox"]').each(function () {
      var $this = $(this);
      const priceAttr = $this.attr('data-price');
      const price = priceAttr ? parseFloat(priceAttr.replace(/,/g, '')) : 0;

      const serviceId = $this.attr('data-space-service-id');
      const subserviceId = $this.attr('data-sub-service-id');
      var isCustomDay = $this.data('is_custom_day');
      var spaceType = $this.data('space_type');

      // Ensure that calculations are only performed on checked items
      if ($this.is(':checked')) {

        // Handle logic only for spaceType == 3 and isCustomDay == 1
        if (spaceType == 3 && isCustomDay == 1) {

          var numberOfCustomDay = $(`.numberOfCustomDay-${subserviceId || serviceId}`).text();
          // Multiply price by numberOfCustomDay and update serviceTotal
          var priceType = $this.attr('data-price_type');
          serviceTotal += price * numberOfCustomDay * (priceType == 'per person' ? numberOfGuest : 1);
        } else {

          // For other spaceTypes, calculate without opening the modal
          var priceType = $this.attr('data-price_type');
          let numberOfDay = $('.numberOfDay').text();

          serviceTotal += price * (numberOfDay == 0 ? 1 : numberOfDay) * (priceType == 'per person' ? numberOfGuest : 1);
        }
      }
    });

    // Calculate subtotal and grand total
    const subTotal = serviceTotal + spaceRent + (rentPerDay * numberOfDay) + rentPerHour;
    let grandTotal;

    if (spaceType == 1) {
      grandTotal = subTotal;
    } else if (spaceType == 2) {
      grandTotal = subTotal * (customHour || 1);
    } else if (spaceType == 3) {
      grandTotal = subTotal;
    }

    // Update the UI
    $('.serviceTotal').text(`$${serviceTotal.toFixed(2)}`);
    $('.subTotalAmount').text(`$${grandTotal.toFixed(2)}`);

    // Return an object containing grandTotal, startTime, and endTime
    if (spaceType == 3) {
      return { grandTotal, newTime, totalHours, numberOfDay, startDate, endDate, serviceTotal };
    }
    else if (spaceType == 2) {
      return { grandTotal, newTime, totalHours, startDate, endDate, newTimeWithoutInterval, serviceTotal, formattedStartTime };
    }
    else if (spaceType == 1) {
      return { grandTotal, serviceTotal };
    }
  }

  window.calculateTotals = calculateTotals;

  $('body').on('input', '.numberOfGuest', function () {
    calculateTotals();
  });

  if (spaceType == 3) {

    $('.checkInDate').on('apply.daterangepicker', function (ev, picker) {
      const dateRange = `${picker.startDate.format('MM/DD/YYYY')} - ${picker.endDate.format('MM/DD/YYYY')}`;
      $(this).val(dateRange);
      calculateTotals();
    });
  }

  if (spaceType == 2) {
    $('#hours').on('input', function () {
      calculateTotals();
    });
  }

  $(document).on('change', '#eventTime', function () {
    const selectedOption = $(this).find('option:selected');

    const timeSlotRent = parseFloat(selectedOption.data('time_slot_rent')) || 0;

    if (spaceType == 1 && isTimeSlotRent == 1) {
      const formattedRent = `${currencyPosition == 'left' ? currencySymbol : ''}${timeSlotRent.toFixed(2)}${currencyPosition == 'right' ? currencySymbol : ''}`;
      $('.timeSlotRentValue').text(formattedRent);
      $('#timeSlotRentWrapper').removeClass('d-none');
      $('#spaceRent').hide();

    } else {
      $('#timeSlotRentWrapper').addClass('d-none');
      $('#spaceRent').show();
    }
    calculateTotals();
  });

});
// this code get input field value , select the item and calculate the total end here


function handleDatePicker(input, picker) {
  const tzMoment = moment.tz(picker.startDate.format('YYYY-MM-DD'), timeZone);
  let bookingDate = tzMoment.format('MM/DD/YYYY');
  $(input).val(bookingDate);

  let spaceId = $(input).attr('data-space_id');
  let sellerId = $(input).attr('data-seller_id');

  const $eventTime = $('#eventTime');
  $eventTime.empty();

  if (spaceType == 1) {

    $('.ajaxPreLoader').removeClass('d-none');

    $.ajax({
      url: getTimeSlotUrl,
      type: "GET",
      data: {
        selectedDate: bookingDate,
        spaceId: spaceId,
        sellerId: sellerId,
      },
      success: function (data) {

        $('.ajaxPreLoader').addClass('d-none');

        $eventTime.empty();

        if (data.length == 0) {
          $eventTime.append(`<option value="">${translations.noResulrFound}</option>`)
            .prop('disabled', true)
            .val('')
            .trigger('change');
          return;
        }

        $eventTime.prop('disabled', false);
        $eventTime.append(`<option value="">${translations.selectTimeslot}</option>`);

        $.each(data, function (key, item) {
          let startTime = item.start_time;
          let endTime = item.end_time;

          let rent = parseFloat(item.time_slot_rent) || 0;

          $eventTime.append(
            `<option value="${item.time_slot_id}" data-time_slot_rent="${rent}">${startTime} - ${endTime}</option>`
          );
        });

        // Always reset value and notify Select2
        $eventTime.val(null).trigger('change.select2');
      },
      error: function (err) {
        $('.ajaxPreLoader').addClass('d-none');
        console.error("AJAX error:", err);
      }
    });
  }
}

// Use both events to guarantee AJAX firing
$('body').on('apply.daterangepicker', '.checkInDate', function (ev, picker) {
  handleDatePicker(this, picker);
});

$('body').on('change', '.checkInDate', function () {
  const picker = $(this).data('daterangepicker');
  handleDatePicker(this, picker);
});


// the code start to submit the proceed to pay form from the space details page for all space type
$(document).ready(function () {
  $('body').on('submit', '#selectedItemsForm', function (event) {
    // Prevent default form submission
    event.preventDefault();
    $('.em').remove();

    // Gather selected items
    let spaceServicesWithSubservice = [];
    let spaceServicesWithoutSubservice = [];
    let subserviceIds = [];
    let spaceId = $('input[name="space_id"]').attr('value');
    let sellerId = $('input[name="seller_id"]').attr('value');
    let bookingDate = $('input[name="eventDate"]').val();
    let timeSlotId = $('select[name="eventTime"]').val();
    let numberOfGuest = $('input[name="number_of_guest"]').val();
    let startTime = $('input[name="start_time"]').val();
    let hours = $('input[name="hours"]').val();


    // start Initialize error flag for validation error field
    let hasError = false;
    // Validate required fields
    if (!bookingDate) {
      $('#eventDate').after('<p class="mt-1 mb-0 text-danger em">' + translations.dateRequired + '</p>');
      hasError = true;
    }

    if (!timeSlotId && spaceType == 1) {
      $('#timeSlotId').after('<p class="mt-1 mb-0 text-danger em">' + translations.timeSlotRequired + '</p>');
      hasError = true;
    }

    if (!numberOfGuest || numberOfGuest < 1) {
      $('input[name="number_of_guest"]').after('<p class="mt-1 mb-0 text-danger em">' + translations.numberOfGuestsRequired + '</p>');
      hasError = true;
    }
    if (!startTime && (spaceType == 2)) {
      $('#startTimeForHourlyRental').after('<p class="mt-1 mb-0 text-danger em">' + translations.startTime + '</p>');
      hasError = true;
    }
    if (!hours && (spaceType == 2)) {
      $('input[name="hours"]').after('<p class="mt-1 mb-0 text-danger em">' + translations.hours + '</p>');
      hasError = true;
    }

    // If there is an error, do not proceed
    if (hasError) {
      return;
    }

    // end Initialize error flag for validation error field

    // this code handle all selected items
    $('input[name^="image-"]:checked, .input-checkbox:checked').each(function () {
      let total = 0;
      let name = $(this).closest('.card').find('.serviceStageTitle, .title').text().trim();
      let priceText = $(this).closest('.card').find('.serviceStagePrice, .qty').text().trim();
      let price = priceText ? parseFloat(priceText.replace('$', '').replace(/,/g, '')) : 0;

      let id = $(this).attr('value');
      let img = $(this).attr('data-img');
      let priceType = $(this).attr('data-price_type');
      let spaceServiceId = $(this).attr('data-space-service-id');
      let subServiceId = $(this).attr('data-sub-service-id');

      if (priceType == 'per person') {
        total += price * numberOfGuest;
      } else {
        total += price;
      }

      let numberOfDay = $('.numberOfDay').text();
      let numberOfCustomDayForSubservice = $(`.numberOfCustomDay-${subServiceId}`).text() || numberOfDay;
      let numberOfCustomDayForService = $(`.numberOfCustomDay-${spaceServiceId}`).text() || numberOfDay;

      if (subServiceId) {
        spaceServicesWithSubservice.push({
          title: name,
          price: total,
          id: id,
          img: img,
          spaceServiceId: spaceServiceId,
          subServiceId: subServiceId,
          numberOfCustomDay: numberOfCustomDayForSubservice
        });
        subserviceIds.push({
          subServiceId: subServiceId
        });
      } else {
        spaceServicesWithoutSubservice.push({
          title: name,
          price: total,
          id: id,
          img: img,
          spaceServiceId: spaceServiceId,
          numberOfCustomDay: numberOfCustomDayForService
        });
      }
    });

    // call the calculateTotals function to get values which these return from the function
    const { grandTotal, newTime, totalHours, numberOfDay = null, startDate, endDate, newTimeWithoutInterval, serviceTotal, formattedStartTime } = calculateTotals();
    var requestStartDate = new Date(startDate);
    var requestEndDate = new Date(endDate);
    requestStartDate.setHours(0, 0, 0, 0);
    requestEndDate.setHours(0, 0, 0, 0);
    let inputBookingDate = new Date(bookingDate);
    inputBookingDate.setHours(0, 0, 0, 0);

    // Send Ajax request to submit selected items
    $('.ajaxPreLoader').removeClass('d-none');

    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: {
        spaceServicesWithSubservice: spaceServicesWithSubservice,
        spaceServicesWithoutSubservice: spaceServicesWithoutSubservice,
        subserviceIds: subserviceIds,
        spaceId: spaceId,
        sellerId: sellerId,
        totalPrice: grandTotal,
        timeSlotId: timeSlotId,
        bookingDate: bookingDate,
        numberOfGuest: numberOfGuest,
        startTime: startTime,
        endTime: newTime,
        endTimeWithoutInterval: newTimeWithoutInterval,
        hours: hours,
        totalHour: totalHours,
        numberOfDay: numberOfDay,
        startDate: startDate,
        endDate: endDate,
        serviceTotal: serviceTotal,
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {

        $('.ajaxPreLoader').addClass('d-none');

        if (response.message) {
          toastr.success(response.message);
        } else if (response.hasOwnProperty('redirectUrl')) {
          window.location.href = response.redirectUrl;
        } else {
          toastr.error('An error occurred during booking. Please try again.');
        }
      },
      error: function (xhr) {

        $('.ajaxPreLoader').addClass('d-none');

        if (xhr.responseJSON && xhr.responseJSON.errors) {
          // Clear previous error messages
          $('.em').remove();

          // Display new error messages
          if (xhr.responseJSON.errors.bookingDate) {
            $('#eventDate').after('<p class="mt-1 mb-0 text-danger em">' + xhr.responseJSON.errors.bookingDate[0] + '</p>');
          }
          if (xhr.responseJSON.errors.timeSlotId) {
            $('#timeSlotId').after('<p class="mt-1 mb-0 text-danger em">' + xhr.responseJSON.errors.timeSlotId[0] + '</p>');
          }
          if (xhr.responseJSON.errors.numberOfGuest) {
            $('input[name="number_of_guest"]').after('<p class="mt-1 mb-0 text-danger em">' + xhr.responseJSON.errors.numberOfGuest[0] + '</p>');
          }
          if (xhr.responseJSON.errors.startTime) {
            $('#startTimeForHourlyRental').after('<p class="mt-1 mb-0 text-danger em">' + xhr.responseJSON.errors.startTime[0] + '</p>');
          }
          if (xhr.responseJSON.errors.hours) {
            $('input[name="hours"]').after('<p class="mt-1 mb-0 text-danger em">' + xhr.responseJSON.errors.hours[0] + '</p>');
          }
        } else {
          toastr.error(errorMessageForBooking);
        }
      },
      complete: function () {
        // Hide the loader after the AJAX call completes
        $('.ajaxPreLoader').addClass('d-none');

      }
    });
  });
});

$('.review-value').on('click', function () {
  let ratingValue = $(this).attr('data-ratingVal');

  // first, remove '#FBA31C' color and add '#777777' color to the star
  $('.review-value span').css('color', '#777777');

  // second, add '#FBA31C' color to the selected parent class
  let parentClass = `review-${ratingValue}`;
  $(`.${parentClass} span`).css('color', '#FBA31C');

  // finally, set the rating value to a hidden input field
  $('#rating-id').val(ratingValue);
});

// time format converter function for space type 2 
function convertTo24HourFormat(selectedTime) {
  const [time, modifier] = selectedTime.split(' ');
  let [hours, minutes] = time.split(':');

  if ((modifier == 'PM' || modifier == 'pm') && hours !== '12') {
    hours = parseInt(hours, 10) + 12;
  } else if ((modifier == 'AM' || modifier == 'am') && hours == '12') {
    hours = '00';
  }

  return `${hours}:${minutes}`;
}
window.convertTo24HourFormat = convertTo24HourFormat;

// Check if the time contains a colon and has 2 digits for hours and 2 digits for minutes
function is24HourFormat(time) {
  return /^\d{1,2}:\d{2}$/.test(time);
}
window.is24HourFormat = is24HourFormat;

// calculated end time for space type 2 , this also call from space-filtering.js file
function calculateEndTime(startTime, hoursToAdd) {

  let result;
  if (typeof hoursToAdd == 'string' && hoursToAdd == '') {
    result = 1;
  } else {
    result = hoursToAdd;
  }
  // If hoursToAdd is a string, parse it to an integer
  if (typeof result == 'string') {
    result = parseInt(result, 10);
  }
  let [hours, minutes] = startTime.split(':').map(Number);

  hours += result;
  if (hours >= 24) {
    hours = hours % 24;
  }
  // Format hours and minutes to ensure two digits
  const formattedHours = String(hours).padStart(2, '0');
  const formattedMinutes = String(minutes).padStart(2, '0');
  return `${formattedHours}:${formattedMinutes}`;
}
window.calculateEndTime = calculateEndTime;

// get all date between start and end date
function getAllDatesBetween(startDate, endDate) {
  let dates = [];
  let currentDate = new Date(startDate);

  while (currentDate <= new Date(endDate)) {
    dates.push(new Date(currentDate));
    currentDate.setDate(currentDate.getDate() + 1);
  }
  return dates;
}

// this modal is to create the number of day for varient of service start here
$('body').on('keypress', '#dayModal #inputDayForService', function (event) {
  if (event.which == 13) {
    event.preventDefault();
    $('#submitBtnForDayValue').click();
  }
});

$('body').on('click', '#submitBtnForDayValue', function () {

  var dayValue = $('#inputDayForService').val();
  var serviceId = $('#serviceId').val();
  var subserviceId = $('#subserviceId').val();
  const { grandTotal, newTime, totalHours, numberOfDay = 1, startDate, endDate } = calculateTotals();

  // Validate dayValue
  if (dayValue > numberOfDay) {
    toastr.error(translations.moreThanNumberOfDay + ' ' + numberOfDay);
    return;
  }
  const dayText = (dayValue > 1) ? translations.days : translations.day;
  $(`.selectedDay-${subserviceId} .day-text`).text(dayText);
  if (subserviceId) {
    $(`.selectedDay-${subserviceId} .day-text`).text(dayText);
  }
  else {
    $(`.selectedDay-${serviceId} .day-text`).text(dayText);
  }

  var modalId = '#dayModal';
  $(modalId).modal('hide');

  if (subserviceId) {
    $(`.selectedDay-${subserviceId} .numberOfCustomDay-${subserviceId}`).text('');
    $(`.selectedDay-${subserviceId} .numberOfCustomDay-${subserviceId}`).text(dayValue);
  }
  else {
    $(`.selectedDay-${serviceId} .numberOfCustomDay-${serviceId}`).text('');
    $(`.selectedDay-${serviceId} .numberOfCustomDay-${serviceId}`).text(dayValue);
  }
  calculateTotals();
})
// this modal is to create the number of day for varient of service end here

$('body').on('click', '.dayModalEditSingleBtn', function () {

  var $this = $(this);
  var indexValue = $this.data('index_value');
  var serviceId = $this.data('service_id');
  var subserviceId = $this.data('sub_service_id');
  var numberOfCustomDay = $('.numberOfCustomDay').text();

  var numberOfCustomDay = $(`.numberOfCustomDay-${subserviceId}`).text();

  $('#dayEditModal #inputDayForService').val('');
  $('#dayEditModal #serviceId').val('');
  $('#dayEditModal #serviceIndexValue').val('');
  $('#dayEditModal #subserviceId').val('');
  $('#dayEditModal #inputDayForService').val('');

  $('#dayEditModal #inputDayForService').val(numberOfCustomDay);
  $('#dayEditModal #serviceId').val(serviceId);
  $('#dayEditModal #serviceIndexValue').val(indexValue);
  $('#dayEditModal #subserviceId').val(subserviceId);
  // Open the modal (if not already handled via Bootstrap modal settings)
  $('#dayEditModal').modal('show');

});

$('body').on('click', '.dayModalEditBtn', function () {

  var $this = $(this);
  var indexValue = $this.data('index_value');
  var serviceId = $this.data('service_id');
  var subserviceId = $this.data('sub_service_id');
  var numberOfCustomDay = $('.numberOfCustomDay').text();

  var numberOfCustomDay = $(`.numberOfCustomDay-${subserviceId}`).text(); // Ensure it gets the text properly

  $('#dayEditModal #inputDayForService').val('');
  $('#dayEditModal #serviceId').val('');
  $('#dayEditModal #serviceIndexValue').val('');
  $('#dayEditModal #subserviceId').val('');
  $('#dayEditModal #inputDayForService').val('');

  $('#dayEditModal #inputDayForService').val(numberOfCustomDay);
  $('#dayEditModal #serviceId').val(serviceId);
  $('#dayEditModal #serviceIndexValue').val(indexValue);
  $('#dayEditModal #subserviceId').val(subserviceId);
  // Open the modal (if not already handled via Bootstrap modal settings)
  $('#dayEditModal').modal('show');

});

// this modal is to edit the number of day for varient of service start here
$('body').on('keypress', '#dayEditModal #inputDayForService', function (event) {
  if (event.which == 13) {
    event.preventDefault();
    $('#submitEditBtnForDayValue').click();
  }
});

$('body').on('click', '#submitEditBtnForDayValue', function (event) {
  let subserviceId = $('#dayEditModal #subserviceId').val();
  let dayValue = $('#dayEditModal #inputDayForService').val();

  const { grandTotal, newTime, totalHours, numberOfDay = 1, startDate, endDate } = calculateTotals();

  // Validate dayValue
  if (dayValue > numberOfDay) {
    toastr.error(translations.moreThanNumberOfDay + ' ' + numberOfDay);
    return;
  }
  const dayText = (dayValue > 1) ? translations.days : translations.day;
  $(`.selectedDay-${subserviceId} .day-text`).text(dayText);

  var modalId = '#dayEditModal';
  $(modalId).modal('hide');

  $(`.numberOfCustomDay-${subserviceId}`).text(dayValue);
  calculateTotals('editBtn');
})
// this modal is to edit the number of day for varient of service end here

// this modal is to edit the number of day for withoutsubservice start here
$('body').on('click', '.dayModalEditBtnWithoutSubservice', function () {

  var $this = $(this);
  var indexValue = $this.data('index_value');
  var serviceId = $this.data('service_id');
  var subserviceId = $this.data('sub_service_id');
  var numberOfCustomDay = $('.numberOfCustomDay').text();

  var numberOfCustomDay = $(`.numberOfCustomDay-${serviceId}`).text(); // Ensure it gets the text properly

  $('#dayModalwithoutService #inputDayForService').val('');
  $('#dayModalwithoutService #serviceId').val('');
  $('#dayModalwithoutService #serviceIndexValue').val('');
  $('#dayModalwithoutService #subserviceId').val('');
  $('#dayModalwithoutService #inputDayForService').val('');

  $('#dayModalwithoutService #inputDayForService').val(numberOfCustomDay);
  $('#dayModalwithoutService #serviceId').val(serviceId);
  $('#dayModalwithoutService #serviceIndexValue').val(indexValue);
  $('#dayModalwithoutService #subserviceId').val(subserviceId);
  // Open the modal (if not already handled via Bootstrap modal settings)
  $('#dayModalwithoutService').modal('show');
});
// this modal is to edit the number of day for withoutsubservice end here

// this modal is to edit the number of day for  service without varient start here
$('body').on('keypress', '#dayModalwithoutService #inputDayForService', function (event) {
  if (event.which == 13) {
    event.preventDefault();
    $('#submitEditBtnWithoutSubservice').click();
  }
});

$('body').on('click', '#submitEditBtnWithoutSubservice', function (event) {

  var serviceId = $('#dayModalwithoutService #serviceId').val();
  let dayValue = $('#dayModalwithoutService #inputDayForService').val();

  const { grandTotal, newTime, totalHours, numberOfDay = 1, startDate, endDate } = calculateTotals();

  // Validate dayValue
  if (dayValue > numberOfDay) {
    toastr.error(translations.moreThanNumberOfDay + ' ' + numberOfDay);
    return;
  }
  const dayText = (dayValue > 1) ? translations.days : translations.day;
  $(`.selectedDay-${serviceId} .day-text`).text(dayText);

  var modalId = '#dayModalwithoutService';
  $(modalId).modal('hide');

  $(`.numberOfCustomDay-${serviceId}`).text(dayValue);
  calculateTotals('editBtn');
})

// this modal is to edit the number of day for  service without varient end here

//convert 24 hour to 12 hour time format conversion
function convertTo12HourFormat(time24) {
  // Split the input into hours and minutes
  let [hours, minutes] = time24.split(':').map(Number);

  // Determine AM or PM suffix
  const suffix = hours >= 12 ? 'PM' : 'AM';

  // Convert hours from 24-hour to 12-hour format
  hours = hours % 12 || 12; // Convert 0 to 12 for midnight

  // Format minutes to always be two digits
  minutes = minutes < 10 ? '0' + minutes : minutes;

  // Return the formatted time
  return `${hours}:${minutes} ${suffix}`;
}

// Helper function to convert HH:MM time to minutes
function convertTimeToMinutes(time) {
  const [hours, minutes] = time.split(':').map(Number);
  return hours * 60 + minutes;
}







