'use strict'

let spaceBookingData = (typeof spaceBooking !== 'undefined') ? spaceBooking : [];
let spaceType = (typeof type !== 'undefined') ? type : '1';

// this code written  for get space ids, those are already booked and disable the calendar date start here 
$(document).ready(function () {
  window.bookingsArray;
})

$(function () {
  $('.checkInDate').daterangepicker({
    "singleDatePicker": (spaceType == 3) ? false : true,
    "drops": "auto",
    "autoApply": (spaceType == 3) ? false : true,
    "timePicker": false,
    autoUpdateInput: false,
    minDate: new Date(),
    maxDate: moment().add(2, 'years').toDate(),

    isCustomDate: function (date) {
      // Apply custom classes based on your logic
      let today = moment().startOf('day');
      let classes = [];

      // Check if the current date is a holiday
      let isHoliday = holidayDate.some(holiday => moment(holiday.date).isSame(date, 'day'));
      if (isHoliday) {
        classes.push('holiday-date');
      }

      // Check if the date is a weekend based on weekendDays
      let isWeekend = weekendDays.some(day => moment(date).format('dddd') == day.name);
      if (isWeekend) {
        classes.push('weekend-day');
      }

      let todaysAndFutureBookings = window.bookingsArray.filter(booking => {
        let bookingStartDate = moment(booking.bookingStartDate).startOf('day');
        let bookingEndDate = moment(booking.bookingEndDate).startOf('day');

        // Check if the booking includes today or starts after today
        return bookingStartDate.isSameOrAfter(today) || bookingEndDate.isSameOrAfter(today);
      });

      let isBooked = todaysAndFutureBookings.some(booking => {
        let bookingStartDate = moment(booking.bookingStartDate);
        let bookingEndDate = moment(booking.bookingEndDate);
        return date.isBetween(bookingStartDate, bookingEndDate, null, '[]');
      });
      if (isBooked) {
        return 'booked-date';
      }
      return classes.length > 0 ? classes : undefined;
    },

    isInvalidDate: function (date) {

      // Strip time from the date being checked
      let checkDate = moment(date).startOf('day');

      // Check if the current date is in the weekendDays array
      let isWeekend = weekendDays.some(day => moment(date).format('dddd') == day.name);

      // Check if the current date is after 2 years from now
      let isAfterTwoYears = date.isAfter(moment().add(2, 'years'));

      // Check if the current date is a holiday
      let isHoliday = holidayDate.some(holiday => moment(holiday.date).isSame(checkDate, 'day'));


      // Disable dates based on existing bookings
      let isBookingDate = window.bookingsArray.some(booking => {
        let bookingStartDate = moment(booking.bookingStartDate);
        let bookingEndDate = moment(booking.bookingEndDate);
        return checkDate.isBetween(bookingStartDate, bookingEndDate, null, '[]');
      });

      return isWeekend || isAfterTwoYears || isBookingDate || isHoliday;
    }
  });

  // Function to add weekend-day class to <th> elements start
  function addWeekendClassToHeaders() {
    const dayAbbreviations = {
      Sunday: 'Su',
      Monday: 'Mo',
      Tuesday: 'Tu',
      Wednesday: 'We',
      Thursday: 'Th',
      Friday: 'Fr',
      Saturday: 'Sa'
    };

    // Add class for weekend headers based on weekendDays
    $('.daterangepicker .calendar-table th').each(function () {
      let headerText = $(this).text().trim();
      // Find the corresponding day name in weekendDays
      let isWeekendHeader = weekendDays.some(day => dayAbbreviations[day.name] == headerText);
      if (isWeekendHeader) {
        $(this).addClass('weekend-day');
      } else {
        $(this).removeClass('weekend-day');
      }
    });
  }

  // Add weekend-day class to headers when the date picker is shown or updated
  $('body').on('show.daterangepicker', '.checkInDate', function (ev, picker) {
    addWeekendClassToHeaders();
  });

  // Reapply weekend-day class to headers when the calendar is updated (Next/Prev buttons)
  $('body').on('apply.daterangepicker', '.checkInDate', function (ev, picker) {
    addWeekendClassToHeaders();
  });

  // Use MutationObserver to detect calendar updates (Next/Prev buttons)
  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.type == 'childList') {
        addWeekendClassToHeaders();
      }
    });
  });

  // Observe changes in the calendar table
  const calendarTable = document.querySelector('.daterangepicker .calendar-table');
  if (calendarTable) {
    observer.observe(calendarTable, {
      childList: true,
      subtree: true
    });
  }

  // Cleanup observer when the date picker is closed
  $('body').on('hide.daterangepicker', '.checkInDate', function () {
    observer.disconnect();
  });

  // Function to add weekend-day class to <th> elements start

  $('body').on('apply.daterangepicker', '.checkInDate', function (ev, picker) {
    if (spaceType == 3) {
      var date_range = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');
      $(this).val(date_range);
    } else {
      $(this).val(picker.startDate.format('MM/DD/YYYY'));
    }
  });

  $('body').on('cancel.daterangepicker', '.checkInDate', function () {
    $(this).val('');
  });
});

// this code written  for get space ids, those are already booked and disable the calendar date start here

// this code get input field value , select the item and calculate the total start here
window.addEventListener('load', function () {

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

    if (spaceType != 3) {
      return;
    }

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
      $(`.selectedDay-${subserviceId}`).addClass('d-none');
    } else {
      $this.addClass('selected');
      $this.prop('checked', true);
      $(`.selectedDay-${subserviceId}`).removeClass('d-none');
    }

    // Calculate totals after the selection change (applies to all spaceTypes)
    calculateTotals();

    if (spaceType != 3) {
      return;
    }

    if (isCustomDay == 1 && spaceType == 3) {
      if (isSelected) {
        $this.removeClass('selected');
        $this.prop('checked', false);
        $(`.selectedDay-${subserviceId}`).addClass('d-none');
        calculateTotals();
        return;
      }

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
      $(`.selectedDay-${serviceId}`).addClass('d-none');
    } else {
      $this.addClass('selected');
      $this.prop('checked', true);
      $(`.selectedDay-${serviceId}`).removeClass('d-none');
    }

    // Calculate totals after the selection change (applies to all spaceTypes)
    calculateTotals();

    if (spaceType != 3) {
      return;
    }

    if (isCustomDay == 1 && spaceType == 3) {
      if (isSelected) {
        $this.removeClass('selected');
        $this.prop('checked', false);
        $(`.selectedDay-${serviceId}`).addClass('d-none');
        calculateTotals();
        return;
      }

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

      $('#dayModal #serviceId').val(serviceId);
      $('#dayModal #serviceIndexValue').val(indexValue);
      $('#dayModal #subserviceId').val('');
      $('#dayModal #inputDayForService').val(numberOfCustomDay ? numberOfCustomDay : numberOfDay);
      $('#dayModal').modal('show');
    }

  });

  // this code calculate the subtotal, grandtotal according to seletced items , space rent 
  function calculateTotals() {
    $('.em').remove();
    const numberOfGuest = $('.numberOfGuest').val() || 1;
    const discountAmount = $('#spaceDiscount').val();
    let spaceRent = 0, rentPerDay = 0, rentPerHour = 0, numberOfDay = 1, customHour = 1;
    let bookingDate, startDate, endDate, newTime;
    let startTime = null, endTime = null, totalHours = null;
    let formattedStartTime = null;
    let formattedEndTime = null;
    let newEndimeWithPreparationTime = null;

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
        numberOfDay = uniqueDates.length || 1; 
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
      const selectedTime = $('#timepickerForHourly').val();
      const is24Hour = is24HourFormat(selectedTime);
      const convertedTime = is24Hour ? selectedTime : convertTo24HourFormat(selectedTime);
      const [hours, minutes] = convertedTime.split(':').map(Number);

      startTime = new Date();
      startTime.setHours(hours, minutes, 0);

      endTime = new Date(startTime);
      endTime.setHours(startTime.getHours() + customHour);

      // Add prepareTimeValue in minutes to endTime here
      if (prepareTimeValue > 0) {
        endTime.setMinutes(endTime.getMinutes() + prepareTimeValue);
      }

      function formatTime(date, use24HourFormat) {
        let h = date.getHours();
        let m = date.getMinutes();
        let formattedMinutes = String(m).padStart(2, '0');
        if (use24HourFormat) {
          let formattedHours = String(h).padStart(2, '0');
          return `${formattedHours}:${formattedMinutes}`;
        } else {
          const ampm = h >= 12 ? 'PM' : 'AM';
          h = h % 12 || 12; // convert 0 => 12 for 12-hour clock
          return `${h}:${formattedMinutes} ${ampm}`;
        }
      }

      let tempEndTime = new Date(startTime);
      tempEndTime.setHours(startTime.getHours() + customHour);

      // Format newTime in the same format as startTime
      newEndimeWithPreparationTime = formatTime(endTime, is24Hour);
      formattedStartTime = formatTime(startTime, is24Hour);
      formattedEndTime = formatTime(tempEndTime, is24Hour);

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
      // Calculate grandTotal for spaceType 1
      grandTotal = subTotal;
    } else if (spaceType == 2) {
      // Calculate grandTotal for spaceType 2
      grandTotal = subTotal * (customHour || 1);
    } else if (spaceType == 3) {
      // Calculate grandTotal for spaceType 3
      grandTotal = subTotal;
    }

    let vatPercentage = $('#taxPerSpace').val();
    let subtotal_1 = grandTotal - discountAmount;
    let vatAmount = subtotal_1 * (vatPercentage / 100);

    let totalAmount = subtotal_1 + vatAmount;
    $('#totalAmountHidden').val(totalAmount);
    $('#vatAmountHidden').val(vatAmount);
    $('#subtotalAmountHidden').val(subtotal_1);

    // Assuming baseCurrencyPosition is either "left" or "right"
    if (baseCurrencyPosition == "left") {
      // Format the serviceTotal and grandTotal with the currency symbol on the left
      $('.serviceTotal').text(`${baseCurrency}${serviceTotal.toFixed(2)}`);
      $('.subTotalAmount').text(`${baseCurrency}${subtotal_1.toFixed(2)}`);
    } else if (baseCurrencyPosition == "right") {
      // Format the serviceTotal and grandTotal with the currency symbol on the right
      $('.serviceTotal').text(`${serviceTotal.toFixed(2)}${baseCurrency}`);
      $('.subTotalAmount').text(`${subtotal_1.toFixed(2)}${baseCurrency}`);
    }

    if (baseCurrencyPosition == 'left') {
      $('#vatAmountSpan').text(`${baseCurrency}${vatAmount.toFixed(2)}`);
    } else if (baseCurrencyPosition == 'right') {
      $('#vatAmountSpan').text(`${vatAmount.toFixed(2)}${baseCurrency}`);
    } else {
      $('#vatAmountSpan').text(`${baseCurrency}${vatAmount.toFixed(2)}`);
    }

    if (baseCurrencyPosition == 'left') {
      $('#totalAmount').text(`${baseCurrency}${totalAmount.toFixed(2)}`);
    } else if (baseCurrencyPosition == 'right') {
      $('#totalAmount').text(`${totalAmount.toFixed(2)}${baseCurrency}`);
    } else {
      $('#totalAmount').text(`${baseCurrency}${totalAmount.toFixed(2)}`);
    }


    // Return an object containing grandTotal, startTime, and endTime
    if (spaceType == 3) {
      return { subtotal_1, vatAmount, totalAmount, newTime, totalHours, numberOfDay, startDate, endDate };
    }
    else if (spaceType == 2) {
      return { subtotal_1, vatAmount, totalAmount, formattedStartTime, formattedEndTime, totalHours, startDate, endDate, newEndimeWithPreparationTime };
    }
    else if (spaceType == 1) {
      return { subtotal_1, vatAmount, totalAmount };
    }
  }

  window.calculateTotals = calculateTotals;

  $('body').on('input', '.numberOfGuest', function () {
    calculateTotals();
  });
  $('body').on('input', '#spaceDiscount', function () {
    calculateTotals();
  });

  if (spaceType == 3) {
    $('body').on('apply.daterangepicker', '.checkInDate', function (ev, picker) {
      calculateTotals('dataRangePicker');
    });
  }
  if (spaceType == 2) {
    $('body').on('input', '#hours', function () {
      calculateTotals();
    });
  }

  $('body').on('change', '#eventTime', function () {
    const selectedOption = $(this).find('option:selected');

    const timeSlotRent = parseFloat(selectedOption.data('time_slot_rent')) || 0.00;

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

// start get input field value , select the item and calculate the total

function handleDatePicker(input, picker) {
  let bookingDate = picker.startDate.format('MM/DD/YYYY');
  $(input).val(bookingDate);

  let spaceId = $(input).attr('data-space_id');
  let sellerId = $(input).attr('data-seller_id');

  const $eventTime = $('#eventTime');
  $eventTime.empty();

  if (spaceType == 1) {
    $.ajax({
      url: getTimeSlotUrl,
      type: "GET",
      data: {
        selectedDate: bookingDate,
        spaceId: spaceId,
        sellerId: sellerId,
      },
      success: function (data) {

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

          let rent = parseFloat(item.time_slot_rent) || 0.00;

          $eventTime.append(
            `<option value="${item.time_slot_id}" data-time_slot_rent="${rent}">${startTime} - ${endTime}</option>`
          );
        });

        // Always reset value and notify Select2
        $eventTime.val(null).trigger('change.select2');
      },
      error: function (err) {
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
    event.preventDefault();
    $('.em').remove();
    
    // Show preloader before AJAX request
    $('.request-loader').addClass('d-block').removeClass('d-none');
    $('.request-loader img').addClass('d-block').removeClass('d-none');

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
    let paymentStatus = $('select[name="payment_status"]').val();
    let paymentGateway = $('select[name="gateway"]').val();
    let discountAmount = $('#spaceDiscount').val();
    let fullName = $('.customerFullName').val();
    let customerPhoneNumber = $('.customerPhoneNumber').val();
    let customerEmailAddress = $('.customerEmailAddress').val();

    // start Initialize error flag for validation error field
    let hasError = false;
    // Validate required fields
    if (!bookingDate) {
      $('#eventDate').after('<p class="mt-1 mb-0 text-danger em">' + translations.dateRequired + '</p>');
      hasError = true;
    }

    // Check if paymentStatus is empty or null
    if (!paymentStatus) {
      // If no payment status is selected, show the error message
      $('#paymentStatusId').after('<p class=" mb-1 text-danger em">' + translations.paymentStatusRequired + '</p>');
      hasError = true;
    }

    if (paymentStatus == 'completed') {
      // If no payment gateway is selected, show an error message
      if (!paymentGateway) {
        // If error message not already displayed, show it

        $('#paymentGatewayErrorId').after('<p class=" mb-2 text-danger em">' + translations.paymentGatewayError + '</p>');
        hasError = true;
      }
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
     
      $('input[name="start_time"]').after('<p class="mt-1 mb-0 text-danger em">' + translations.startTime + '</p>');
      hasError = true;
    }
    if (!hours && (spaceType == 2)) {
      $('input[name="hours"]').after('<p class="mt-1 mb-0 text-danger em">' + translations.hours + '</p>');
      hasError = true;
    }
    if (!fullName) {
      $('input[name="customer_full_name"]').after('<p class="mt-1 mb-0 text-danger em">' + translations.fullName + '</p>');
      hasError = true;
    }
    if (!customerPhoneNumber) {
      $('input[name="customer_phone_number"]').after('<p class="mt-1 mb-0 text-danger em">' + translations.customerPhoneNumber + '</p>');
      hasError = true;
    }
    if (!customerEmailAddress) {
      $('input[name="customer_email"]').after('<p class="mt-1 mb-0 text-danger em">' + translations.customerEmailAddress + '</p>');
      hasError = true;
    }

    // If there is an error, do not proceed
    if (hasError) {
      // Hide preloader if validation fails
      $('.request-loader').removeClass('d-block').addClass('d-none');
      $('.request-loader img').removeClass('d-block').addClass('d-none');
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

      if (subServiceId) {
        spaceServicesWithSubservice.push({
          title: name,
          price: total,
          id: id,
          img: img,
          spaceServiceId: spaceServiceId,
          subServiceId: subServiceId
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
          spaceServiceId: spaceServiceId
        });
      }
    });


    // call the calculateTotals function to get values which these return from the function
    const { subtotal_1, vatAmount, totalAmount, formattedStartTime, formattedEndTime = null, totalHours, numberOfDay = null, startDate, endDate, newEndimeWithPreparationTime } = calculateTotals();

    var requestStartDate = new Date(startDate);
    var requestEndDate = new Date(endDate);
    requestStartDate.setHours(0, 0, 0, 0);
    requestEndDate.setHours(0, 0, 0, 0);

    // var isBookingAvailable = true;
    // let spaceCount = 0;
    let inputBookingDate = new Date(bookingDate);
    inputBookingDate.setHours(0, 0, 0, 0);

    // Send Ajax request to submit selected items
    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: {

        spaceServicesWithSubservice: spaceServicesWithSubservice,
        spaceServicesWithoutSubservice: spaceServicesWithoutSubservice,
        subserviceIds: subserviceIds,
        spaceId: spaceId,
        sellerId: sellerId,
        seller_id: sellerId,
        paymentStatus: paymentStatus,
        paymentGateway: paymentGateway,
        discountAmount: discountAmount,
        taxAmount: vatAmount,
        subtotal: subtotal_1,
        totalAmount: totalAmount,
        timeSlotId: timeSlotId,
        bookingDate: bookingDate,
        numberOfGuest: numberOfGuest,
        startTime: formattedStartTime,
        endTime: newEndimeWithPreparationTime,
        endTimeWithoutInterval: formattedEndTime,
        hours: hours,
        totalHour: hours,
        numberOfDay: numberOfDay,
        startDate: startDate,
        endDate: endDate,
        fullName: fullName,
        customerPhoneNumber: customerPhoneNumber,
        customerEmailAddress: customerEmailAddress,
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {

        // Hide preloader on success
        $('.request-loader').removeClass('d-block').addClass('d-none');
        $('.request-loader img').removeClass('d-block').addClass('d-none');

        ['numberOfGuest', 'bookingDate', 'paymentGateway', 'paymentStatus', 'timeSlotId', 'startTime', 'totalHour'].forEach(field => {
          $(`#${field}Error`).text('');
        });

        if (response.status == 'success') {
          // toastr.success(response.message);
          sessionStorage.setItem('notification', JSON.stringify({
            message: response.message,
            title: successTxt,
            icon: 'fas fa-check-circle'
          }));

          // Reload the page after 1000ms (before notification time is up)
          setTimeout(function () {
            location.reload();
          }, 1000);


        } else if (response.status == 'error') {
          // Handle validation errors
          let type = response.type;
          if (type == 'hourly'){
            sessionStorage.setItem('notification', JSON.stringify({
              message: response.message,
              title: errorTxt,
              icon: 'fas fa-check-circle'
            }));

            // Reload the page after 1000ms (before notification time is up)
            setTimeout(function () {
              location.reload();
            }, 1000);

          }
          else{
            let errors = response.errors;
            Object.keys(errors).forEach(field => {
              let errorMessages = errors[field].join(', '); // Join error messages for the field

              // Dynamically update the corresponding error element
              $(`#${field}Error`).text(errorMessages);
            });
          }

        }
        else if (response.status == 'downgrade') {
          var content = {};
          content.message = translations.downgradeErrorMsg;
          content.title = translations.warningMsg;
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
      error: function (xhr, status, error) {
        
        // Hide preloader on error
        $('.request-loader').removeClass('d-block').addClass('d-none');
        $('.request-loader img').removeClass('d-block').addClass('d-none');
        alert('An unexpected error occurred. Please try again.');
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

// this function for space type 2 , convert time 24 hour format
function formatTime(time) {
  const is24Hour = is24HourFormat(time);
  return is24Hour ? time : convertTo24HourFormat(time);
}


// time format converter function for space type 2 
function convertTo24HourFormat(selectedTime) {
  const [time, modifier] = selectedTime.split(' ');
  let [hours, minutes] = time.split(':');

  if ((modifier == 'PM' || modifier == 'pm') && hours !== '12') {
    hours = parseInt(hours, 10) + 12;
  } else if ((modifier == 'AM' || modifier == 'am') && hours == '12') {
    hours = '00';
  }
  // Format hours to ensure two digits
  hours = String(hours).padStart(2, '0');

  return `${hours}:${minutes}`;
}
// window.convertTo24HourFormat = convertTo24HourFormat;

// Check if the time contains a colon and has 2 digits for hours and 2 digits for minutes
function is24HourFormat(time) {
  return /^\d{1,2}:\d{2}$/.test(time);
}
// window.is24HourFormat = is24HourFormat;

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

$('body').on('click', '#submitBtnForDayValue', function () {

  var dayValue = $('#inputDayForService').val();
  var serviceId = $('#serviceId').val();
  var subserviceId = $('#subserviceId').val();

  const { grandTotal, newTime, totalHours, numberOfDay = 1, startDate, endDate } = calculateTotals();

  // Validate dayValue
  if (dayValue > numberOfDay) {
    sessionStorage.setItem('notification', JSON.stringify({
      message: translations.moreThanNumberOfDay + ' ' + numberOfDay,
      title: errorTxt,
      icon: 'fas fa-check-circle'
    }));

    setTimeout(function () {
      location.reload();
    }, 1000);
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

$('body').on('click', '.dayModalEditSingleBtn', function () {

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
  $('#dayEditModal').modal('show');

});

$('body').on('click', '.dayModalEditBtn', function () {

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
  $('#dayEditModal').modal('show');

});

$('body').on('click', '#submitEditBtnForDayValue', function (event) {

  var subserviceId = $('#dayEditModal #subserviceId').val();
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
  $('#dayModalwithoutService').modal('show');

});

// this modal is to edit the number of day for  service without varient start here
$('body').on('keypress', '#dayModalwithoutService #inputDayForService', function (event) {
  if (event.which == 13) {
    event.preventDefault();
    $('#submitEditBtnWithoutSubservice').click();
  }
});

// this modal is to edit the number of day for  service without varient start here
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

function formatStartAndEndTime(item, timeFormatSpaceDetails) {
  let startTime = item.start_time;
  let endTime = item.end_time;

  let is24HourStartTime = is24HourFormat(startTime);
  let is24HourEndTime = is24HourFormat(endTime);

  if (timeFormatSpaceDetails == '12h') {
    if (is24HourStartTime) {
      startTime = convertTo12HourFormat(startTime);
    }
    if (is24HourEndTime) {
      endTime = convertTo12HourFormat(endTime);
    }
  } else if (timeFormatSpaceDetails == '24h') {
    if (!is24HourStartTime) {
      startTime = convertTo24HourFormat(startTime);
    }
    if (!is24HourEndTime) {
      endTime = convertTo24HourFormat(endTime);
    }
  }

  return { startTime, endTime };
}
$(document).ready(function () {
  // Check if there's a notification stored in sessionStorage
  let notification = sessionStorage.getItem('notification');

  if (notification) {
    // Parse the stored notification data
    notification = JSON.parse(notification);

    // Display the notification
    $.notify(notification, {
      type: 'success',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,  // Time for notification to show
      delay: 4000  // How long the notification will be visible
    });

    // Clear the notification from sessionStorage after it's displayed
    sessionStorage.removeItem('notification');
  }
});

