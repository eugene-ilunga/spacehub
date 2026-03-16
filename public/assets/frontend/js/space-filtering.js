"use strict";

if (searchFromHome != 'home'){
  document.addEventListener('DOMContentLoaded', function () {
    let baseUrl = window.location.origin + window.location.pathname;
    let params = new URLSearchParams(window.location.search);

    if (params.has('space_type') || window.location.search.length > 0) {
      window.location.replace(baseUrl);
    }
  });
}


$(document).ready(function () {

  // Listen for changes on the space-type-select dropdown
  $('body').on('change', '.space-type-select', function () {
    var selectedValue = $(this).val();

    // If "Hourly Rental" (value 2) is selected, show the date range picker
    if (selectedValue == '2') {
      $('.inputDateRangePicker').removeClass('d-none').addClass('d-block');

      $('.dateRangeSearch').removeClass('d-block').addClass('d-none');
      $('.inputEventDateForFixedTimeSlot').removeClass('d-block').addClass('d-none');
    }
    else if (selectedValue == '3') {
      $('.dateRangeSearch').removeClass('d-none').addClass('d-block');

      $('.inputDateRangePicker').removeClass('d-block').addClass('d-none');
      $('.inputEventDateForFixedTimeSlot').removeClass('d-block').addClass('d-none');

    } else if (selectedValue == '1') {
      $('.inputEventDateForFixedTimeSlot').removeClass('d-none').addClass('d-block');

      $('.inputDateRangePicker').removeClass('d-block').addClass('d-none');
      $('.dateRangeSearch').removeClass('d-block').addClass('d-none');
    } else {
      // Hide the date range picker if any other option is selected
      $('.inputDateRangePicker').removeClass('d-block').addClass('d-none');
      $('.dateRangeSearch').removeClass('d-block').addClass('d-none');
      $('.inputEventDateForFixedTimeSlot').removeClass('d-block').addClass('d-none');
    }
  });
});

//Update browser URL with only non-empty form fields as query parameters.This block filters out form inputs with empty values. this function call in the search form
function updateURLWithNonEmptyParams(formEl) {
  var formArray = $(formEl).serializeArray();
  var filteredParams = [];

  // Keep only non-empty fields
  formArray.forEach(function (item) {
    if (item.value.trim() !== '') {
      filteredParams.push(encodeURIComponent(item.name) + '=' + encodeURIComponent(item.value));
    }
  });

  var queryString = filteredParams.join('&');

  var cleanUrl = window.location.origin + window.location.pathname;
  if (queryString !== '') {
    cleanUrl += '?' + queryString;
  }
  window.history.pushState({}, '', cleanUrl);
}

$(document).ready(function () {

  // initialize input form
  $('body').on('submit', '#searchForm', function (e) {
    e.preventDefault();
    $('.space-container').empty();

    // Check if preloader should be displayed
    $('.space-skeleton-wrapper').show();
    $('.space-container').hide();

    // Serialize the form data directly
    var fd = $(this).serialize();

    //Update browser URL with only non-empty form fields as query parameters.This block filters out form inputs with empty values start
    updateURLWithNonEmptyParams(this);

    $.ajax({
      url: spaceSearchUrl,
      type: 'GET',
      data: fd,
      success: function (data) {

        var combinedData = [];
        if (Array.isArray(data.featuredSpaces)) {
          combinedData = combinedData.concat(data.featuredSpaces);
        }
        if (Array.isArray(data.spaces?.data)) {
          combinedData = combinedData.concat(data.spaces.data);
        }
        globalCombinedData = combinedData;

        $('.space-skeleton-wrapper').hide();

        var combinedData = data.featuredSpaces.concat(data.spaces.data);
        $('.space-container').show().html(data.render);

        //  space rent radio
        let rentVal = $('#space-rent-id').val();
        // Uncheck all space_rent radios
        $('input[name="space_rent"]').prop('checked', false);

        // Check the selected one
        $('input[name="space_rent"][value="' + rentVal + '"]').prop('checked', true);

        //  Only remove active-radio from space_rent labels
        $('input[name="space_rent"]').each(function () {
          $('label[for="' + this.id + '"]').removeClass('active-radio');
        });

        //  Add active-radio to currently selected space_rent
        $('input[name="space_rent"]:checked').each(function () {
          $('label[for="' + this.id + '"]').addClass('active-radio');
        });
        
        // Check screen dimensions
        var screenWidth = $(window).width();
        var screenHeight = $(window).height();
  
        if (screenWidth <= 1195 && screenHeight >= 454) {

          pushtoMap(combinedData, 'modal-main-map');
        } else {

          pushtoMap(combinedData, 'main-map');
        }
        $('.request-loader').removeClass('show');
        $('#catModal').modal('hide');
      },
      error: function (xhr, status, error) {
        $('.space-skeleton-wrapper').hide();
        $('.space-container').show();

      },
    });
  });

  // category wise search
  $('body').on('click', '.category-search', function (e) {
    e.preventDefault();

    // Update category selection
    $('.category-search').removeClass('active');
    $(this).addClass('active');
    let value = $(this).data('category_slug');
    $('#category-id').val(value);
    $('#subcategory-id').val('');

    filterInputs();
  });

  //subcategory search
  $('body').on('click','.subcategory-search', function (e) {
    e.preventDefault();

    $('.category-search').removeClass('active');
    $('.subcategory-search').removeClass('active');
    $('.subcategory-search-modal').removeClass('active');
    let value = $(this).data('subcategory_slug');
    $(this).addClass('active');

    $('#subcategory-id').val(value);
    filterInputs();
  });

  $('body').on('click','.subcategory-search', function (e) {
    e.preventDefault();

    // Remove 'active' class from all .subcategory-search elements
    $('.subcategory-search').removeClass('active');

    // Add 'active' class to the clicked .subcategory-search element
    $(this).addClass('active');
    let value = $(this).data('subcategory_slug');

    $('#subcategory-id').val(value);
    filterInputs();
  });

  $('body').on('click', '.subcategory-search-modal', function (e) {
    e.preventDefault();

    // Remove 'active' class from all .subcategory-search elements
    $('.subcategory-search').removeClass('active');
    $('.subcategory-search-modal').removeClass('active');
    let spaceSubcategory = $(this).data('subcategory_slug');
    let spaceCategory = $(this).data('category_slug');
    $('.category-search-modal').removeClass('active');
    $(this).closest('.menu-list').find('.category-search-modal').addClass('active');
    $(this).addClass('active');

    $('#category-id').val(spaceCategory);
    $('#subcategory-id').val(spaceSubcategory);
    filterInputs();
  });


  // search by title
  $('body').on('submit', '#spaceSearch', function (e) {
    e.preventDefault();
    var keyword = $('.input-search').val();
    $('#keyword-id').val(keyword);
    filterInputs();
  });

  // search by guest capacity
  $('body').on('submit', '#guestCapacitySearch' , function (e) {
    e.preventDefault();
    var guestCapacity = $('.guest-capacity-search').val();
    $('#guest-capacity-id').val(guestCapacity);
    filterInputs();
  });

  // search by sorting
  $('body').on('change', '.sorting-search', function () {
    let value = $(this).val();
    $('#sorting-search-id').val(value);
    filterInputs();
  })


  // search by country
  $('body').on('change', '.country-search', function () {
    let value = $(this).val();
    let languageId = $('#language_id_for_search').val();

    //  Reset values in the form
    $('#state-search-id').val('');
    $('#city-search-id').val('');

    // reset visible dropdowns
    $('.state-search').val('').find('option:not(:first)').remove();
    $('.city-search').val('').find('option:not(:first)').remove();

    // Update niceSelect UI for city and state
    $('.state-search').niceSelect('update');
    $('.city-search').niceSelect('update');

    $('#country-search-id').val(value);
    filterInputs();
    getState(value, languageId);

  })
  // search by state
  $('body').on('change', '.state-search', function () {
    let stateId = $(this).val();
    let languageId = $('#language_id_for_search').val();
    $('#state-search-id').val(stateId);
    filterInputs();
    getCities(stateId, null, languageId)
  })
  // search by  city
  $('body').on('change', '.city-search', function () {
    let value = $(this).val();
    $('#city-search-id').val(value);
    filterInputs();
  })
  // search by location
  $('body').on('submit', '#locationSearch', function (e) {
    e.preventDefault();
    var location = $('.location-search').val();
    $('#space-location-id').val(location);
    filterInputs();
  });

  //current location search 

  $('body').on('click', '#currentLocationButton', function (e) {
    e.preventDefault();

    getCurrentLocation()
      .then(address => {
        // Use the resolved address
        $('#locationInput').val(address);
        $('#space-location-id').val(address);
        filterInputs();
      })
      .catch(error => {
        alert(error);
      });
  });

  // search service by filtering the pricing
  $('body').on('change', '.space-rent-search', function () {
    let value = $(this).val();
    $('#space-rent-id').val(value);
    filterInputs();
  });

  /* Price range */
  var filterSliders = document.querySelector("[data-range-slider='filterPriceSlider']");
  var input0 = document.getElementById('min');
  var input1 = document.getElementById('max');
  var inputs = [input0, input1];
  overallMin = parseFloat(overallMin);
  overallMax = parseFloat(overallMax);

  // Filter price slider
  if (filterSliders) {
    noUiSlider.create(filterSliders, {
      start: [overallMin, overallMax],
      connect: true,
      step: 10,
      margin: 10,
      range: {
        min: overallMin,
        max: overallMax
      }
    });

    filterSliders.noUiSlider.on("update", function (values, handle) {
      $("[data-range-value='filterPriceSliderValue']").text(`${baseCurrency}` + values.join(" - " + `${baseCurrency}`));
      inputs[handle].value = values[handle];
    });

    filterSliders.noUiSlider.on("end", function (values, handle) {
      document.getElementById("min-id").value = inputs[0].value;
      document.getElementById("max-id").value = inputs[1].value;

      // Check the 'rentable-spaces' checkbox automatically
      const rentableSpacesCheckbox = document.getElementById('rentable-spaces');
      const allSpacesRadio = document.getElementById('all-spaces');
      const nonRentableSpacesRadio = document.getElementById('non-rentable-spaces');

      // Check if the slider values are greater than 0
      if (parseFloat(values[0]) > 0 || parseFloat(values[1]) > 0) {
        // Uncheck 'All' and 'Without Rent'
        allSpacesRadio.checked = false;
        nonRentableSpacesRadio.checked = false;

        // Check 'rentable-spaces'
        rentableSpacesCheckbox.checked = true;

        // Trigger change event to update active class
        rentableSpacesCheckbox.dispatchEvent(new Event('change'));

        let value = $('#rentable-spaces').val();
        $('#space-rent-id').val(value);
      } else {
        // If both values are 0, you might want to uncheck everything
        allSpacesRadio.checked = true;
        rentableSpacesCheckbox.checked = false;
        nonRentableSpacesRadio.checked = false;

        // Trigger change event to update active class
        allSpacesRadio.dispatchEvent(new Event('change'));
      }

      filterInputs();
    });

    inputs.forEach(function (input, handle) {
      if (input) {
        input.addEventListener('change', function () {
          filterSliders.noUiSlider.setHandle(handle, this.value);
        });
      }
    });
  }

  // search space by the rating
  $('body').on('change', '.rating-search', function () {
    let value = $(this).val();
    $('#rating-id').val(value);
    filterInputs();

  });


  // Capture space type selection and submit the form
  $('body').on('change', '.space-type-search', function () {
    let spaceType = $(this).val();
    // Clear previous values
    $('#space-type-search-id').val('');
    $('#space-get-quote-search-id').val('');
    // $('#eventDateForFixedTimeSlot').val('');
    $('#custom-hour-search-id').val('');
    $('#eventDateAndTimeForFixedTimeSlot').val('');
    $('#eventDateAndTimeForHourlyRental').val('');

    $('.checkInDateInSearchPage').val('')
    $('.hourly-event-date-search').val('')
    $('.input-custom-hour').val('')
    $('.checkInEventDateForFixedTimeSlot').val('')

    // Reset dateRangeSearch (multi-day)
    resetDaterangepicker('#dateRangeSearch',false);

    // Reset hourly rental picker (single date + time)
    resetDaterangepicker('.hourly-event-date-search', true);

    // Reset fixed time slot picker (single date + time)
    resetDaterangepicker('.event-date-search-for-fixed-timeslot', true);
    
    // Call the function with the selected spaceType
    fetchSpaceData(spaceType);
    if (spaceType != 'get_quote') {
      $('#space-type-search-id').val(spaceType);
    }
    if (spaceType == 'get_quote') {
      $('#space-get-quote-search-id').val(spaceType);
    }

    filterInputs();
  });

  // Capture event date selection and submit the form
  $(document).ready(function () {
    let timeFormatSearchPage = (typeof timeFormatSpace !== 'undefined') ? timeFormatSpace : 'HH:mm';
    let is24Hour = true;

    if (timeFormatSearchPage == '12h') {
      timeFormatSearchPage = 'h:mm a';
      is24Hour = false;
    } else if (timeFormatSearchPage == '24h') {
      timeFormatSearchPage = 'HH:mm';
      is24Hour = true;
    } else {
      timeFormatSearchPage = 'HH:mm';
      is24Hour = true;
    }

    // Define the global locale format
    const localeFormat = 'MM/DD/YYYY ' + timeFormatSearchPage;

    $('.hourly-event-date-search').daterangepicker({
     
      singleDatePicker: true,
      timePicker: true,
      timePicker24Hour: is24Hour,
      autoUpdateInput: false,
      locale: {
        format: localeFormat
      },
      startDate: moment().startOf('hour'),
      minDate: moment().startOf('day'),
    }).on('apply.daterangepicker', function (ev, picker) {

      // Get the selected date and time in the desired format
      var selectedDateTime = picker.startDate.format(localeFormat);
      $('#hourlyEventDate').val(selectedDateTime)
      let customHour = $('.input-custom-hour').val().trim();
      if (customHour == '') {
        customHour = 1;
      }
      $('.input-custom-hour').val(customHour)
      $('#custom-hour-search-id').val(customHour)
      $('#eventDateAndTimeForHourlyRental').val(selectedDateTime)

      filterInputs();
    });
  });

  // when take custom hour for spacetype 2 (hourly rental)
  $('body').on('blur keyup', '.input-custom-hour', function () {

    let customHour = $('.input-custom-hour').val();
    let selectedDateTime = $('#hourlyEventDate').val();

    $('#custom-hour-search-id').val(customHour)
    $('#eventDateAndTimeForHourlyRental').val(selectedDateTime)

    filterInputs();
  });

  // this code for space type 3 (multi-day)

  $('#dateRangeSearch').daterangepicker({
    autoUpdateInput: false,
    locale: {
      format: 'MM/DD/YYYY'
    },
    minDate: moment().startOf('day'),
    maxDate: moment().add(1, 'years').endOf('day')
  }).on('apply.daterangepicker', function (ev, picker) {
    let requestStartDate = new Date(picker.startDate.format('MM/DD/YYYY'));
    let requestEndDate = new Date(picker.endDate.format('MM/DD/YYYY'));
    $('#dateRangeSearch').val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    $('#startDateForMultiday').val(picker.startDate.format('MM/DD/YYYY'));
    $('#endDateForMultiday').val(picker.endDate.format('MM/DD/YYYY'));

    filterInputs();
  });

  // this code for space type 1 (fixed timeslot rental)
  $(document).ready(function () {
    let timeFormatSearchPage = (typeof timeFormatSpace !== 'undefined') ? timeFormatSpace : 'HH:mm';
    let is24Hour = true;

    if (timeFormatSearchPage == '12h') {
      timeFormatSearchPage = 'h:mm a';
      is24Hour = false;
    } else if (timeFormatSearchPage == '24h') {
      timeFormatSearchPage = 'HH:mm';
      is24Hour = true;
    } else {
      timeFormatSearchPage = 'HH:mm';
      is24Hour = true;
    }

    // Define the global locale format
    const localeFormat = 'MM/DD/YYYY ' + timeFormatSearchPage;

    $('.event-date-search-for-fixed-timeslot').daterangepicker({
      singleDatePicker: true,
      timePicker: true,
      timePicker24Hour: is24Hour,
      autoUpdateInput: false,
      startDate: moment().startOf('hour'),
      locale: {
        format: localeFormat
      },
      minDate: moment().startOf('day'),
      maxDate: moment().add(1, 'years').endOf('day')
    }).on('apply.daterangepicker', function (ev, picker) {
      // Get the selected date and time in the desired format
      let selectedTime;
      var selectedDateTime = picker.startDate.format(localeFormat);

      selectedTime = selectedDateTime.split(' ')[1];

      $('#eventDateForFixedTimeSlot').val(selectedDateTime);
      $('#eventDateAndTimeForFixedTimeSlot').val(selectedDateTime);

      filterInputs();
    });
  });

  // submit the search form
  function filterInputs() {
    $('#searchForm').submit();
  }

  // this code get the state data according to selected country

  function getState(countryId, languageId) {
    $.ajax({
      url: getStatesDataUrl,
      type: 'GET',
      data: {
        country_id: countryId,
        language_id: languageId
      },
      success: function (response) {
        const $stateSelect = $('.state-select');

        // Update the state select element with the fetched data
        $('.state-select').empty();
        // Always add the default placeholder option
        $('.state-select').append('<option value="" selected disabled>' + selectState + '</option>');

        if (response.states && response.states.length > 0){
          $('.state-select').closest('.form-group').show();
          $('.state-select').append('<option value=" ">' + allText + '</option>');
          $.each(response.states, function (index, state) {
            $('.state-select').append('<option value="' + state.id + '">' + state.name + '</option>');
          });
          $('.state-select').niceSelect('update');
        }
        else{
          $('.state-select').closest('.form-group').hide();
          getCities(null, countryId, languageId)

        }
        // Update niceSelect AFTER options update
        $stateSelect.niceSelect('update');
      },
      error: function (xhr, status, error) {
        console.error('Error fetching state data:', error);
      }
    });

  }
  // this code get the city data according to selected state
  function getCities(stateId, countryId, languageId) {
    $.ajax({
      url: getCitiesDataUrl,
      type: 'GET',
      data: {
        state_id: stateId,
        language_id: languageId,
        country_id: countryId,
      },
      success: function (response) {
        // Update the state select element with the fetched data
        $('.city-select').empty();
        $('.city-select').append('<option value="" selected disabled>' + selectCity + '</option>');
        if (response.cities && response.cities.length > 0){

          $('.city-select').closest('.form-group').show();
          $('.city-select').append('<option value=" ">' + allText + '</option>');
          $.each(response.cities, function (index, city) {
            $('.city-select').append('<option value="' + city.id + '">' + city.name + '</option>');
          });
          $('.city-select').niceSelect('update');

        }else{
          $('.city-select').closest('.form-group').hide();

        }
      },
      error: function (xhr, status, error) {
        toastr.error('Error fetching city data:', error);
      }
    });
  }
  

  // Listen for click events on pagination links
  $(document).on('click', '.space-search-pagination a', function (e) {
    e.preventDefault();
    var page_number = $(this).attr('href').split('page=')[1];
    e.preventDefault();
    $('.space-container').empty();

    var serializedData = $('#searchForm').serializeArray();

    // Filter out empty fields from the serialized data
    serializedData = serializedData.filter(function (item) {
      return item.value !== '';
    });
    // Convert filtered array back to serialized string
    serializedData = $.param(serializedData);
    serializedData += '&page=' + page_number;

    $.ajax({
      url: spaceSearchUrl,
      type: 'GET',
      data: serializedData,
      success: function (data) {

      
        var combinedData = [];
        if (Array.isArray(data.featuredSpaces)) {
          combinedData = combinedData.concat(data.featuredSpaces);
        }
        if (Array.isArray(data.spaces?.data)) {
          combinedData = combinedData.concat(data.spaces.data);
        }
        globalCombinedData = combinedData;

        $('.space-container').html(data.render);

        var screenWidth = $(window).width();
        var screenHeight = $(window).height();

        if (screenWidth <= 1195 && screenHeight >= 454) { 
          pushtoMap(combinedData, 'modal-main-map');
        } else {
          pushtoMap(combinedData, 'main-map');
        }

        $('#preLoader').hide();
      },
      error: function (xhr, status, error) {
        $('#preLoader').hide();
      }
    });
  });

  // reset the filtering parameter
  $('body').on('click', '.reset-space-search', function (e) {
    e.preventDefault();

    // Show skeleton loader, hide content
    $('.space-skeleton-wrapper').show();
    $('.space-container').hide();

    $.ajax({
      url: spaceSearchUrl,
      type: 'GET',
      success: function (data) {

        // Reset all select fields to their default options and Unselect the empty filed
        $('.country-search').val('').prop('selected', false);
        $('.state-search').val('').prop('selected', false);
        $('.city-search').val('').prop('selected', false);

        //empty all hidden input values
        $("#searchForm input").val('');

        //remove input search value
        $('.input-search').val('');

        // Reset sort by value and unselect the selected option
        $("#sorting-search option").prop("selected", false);
        $("#sorting-search option:first").prop("selected", true);
        $("#sorting-search").niceSelect('update');

        // Uncheck the radio buttons in the 'Pricing Type' section
        $('.space-rent-search').prop('checked', false);

        // Uncheck the radio buttons in the 'Ratings' section
        $('.rating-search').prop('checked', false);
        // Remove all parameters from the URL
        var url = new URL(window.location.href);
        url.search = '';

        // Update the URL in the address bar
        window.history.pushState({}, '', url.toString());

        // Reload the page to reset the state
        location.reload();

        if (filterSliders) {
          filterSliders.noUiSlider.reset();

        }

        if (clusters) {
          map.removeLayer(clusters);
          clusters.clearLayers();
        }
       

        $('.space-container').html(data.render);
        $('.space-skeleton-wrapper').hide();
        $('.space-container').show();

        $('.niceselect').each(function () {
          $(this).niceSelect('update');
        });

      },
      error: function (xhr, status, error) {
        $('#preLoader').hide();
        $('.space-skeleton-wrapper').hide();
        $('.space-container').show();
      },
      complete: function () {

        $('#preLoader').hide();
      }
    });

  })
});

// this function fetch the space and space booking data according to space type for checking availability of space
function fetchSpaceData(spaceType) {
  $.ajax({
    url: spaceDataAccordingToTypeUrl,
    type: 'GET',
    data: {
      spaceType: spaceType,
    },
    success: function (response) {
      window.space = response.space;
      window.timeSlotInfo = response.timeSlot;
    },
    error: function (xhr, status, error) {
      
    }

  });
}

// get the current location and set it to the input field
function getCurrentLocation() {
  return new Promise((resolve, reject) => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          const latitude = position.coords.latitude;
          const longitude = position.coords.longitude;

          // Use the Google Maps Geocoding API to get the address from latitude and longitude
          fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${google_map_api_key}`)
            .then(response => response.json())
            .then(data => {
              if (data.results && data.results.length > 0) {
                const address = data.results[0].formatted_address;

                document.getElementById('locationInput').value = address;
                resolve(address);
              } else {
                reject('Unable to fetch address.');
              }
            })
            .catch(error => {
              console.error('Error fetching address:', error);
              reject('Error fetching address: ' + error.message);
            });
        },
        function (error) {
          reject('Error getting location: ' + error.message);
        }
      );
    } else {
      reject('Geolocation is not supported by this browser.');
    }
  });
}

function resetDaterangepicker(selector, isSingleDateWithTime = false) {
  const $input = $(selector);
  const instance = $input.data('daterangepicker');

  if (!instance) return;

  // Step 1: Clear input field
  $input.val('');

  // Step 2: Force visual highlight reset
  const futureDate = moment().add(1, 'years');
  instance.setStartDate(futureDate);
  instance.setEndDate(futureDate);
  instance.updateCalendars();

  // Step 2: Reset to now
  const resetDate = isSingleDateWithTime ? moment().startOf('hour') : moment();
  instance.setStartDate(resetDate);
  instance.setEndDate(resetDate); // force even in single mode
  instance.updateCalendars();
}

// Initialize Select2 with AJAX and pagination
function initSelect2(dropdown, url, placeholder, countryId = null, stateId = null) {
  if (dropdown.hasClass('select2-hidden-accessible')) {
    dropdown.select2('destroy');
  }

  let selectedValue = dropdown.val();
  let selectedText = dropdown.find('option:selected').text() || placeholder;

  dropdown.select2({
    ajax: {
      url: url,
      type: 'GET',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        let query = {
          search: params.term || '',
          language_id: $('#language_id_for_search').val(),
          per_page: 10, 
          page: params.page || 1
        };
        if (url.includes('states')) {
          query.country_id = countryId || $('.country-search').val();
        } else if (url.includes('cities')) {
          query.country_id = countryId || $('.country-search').val();
          query.state_id = stateId || $('.state-search').val();
        }
        return query;
      },
      processResults: function (data, params) {
        params.page = params.page || 1;
        return {
          results: $.map(data.results, function (item) {
            return {
              id: item.id,
              text: item.name
            };
          }),
          pagination: {
            more: data.pagination && data.pagination.more
          }
        };
      },
      cache: true
    },
    placeholder: placeholder,
    minimumInputLength: 0,
    data: selectedValue && selectedValue !== ' ' ? [{ id: selectedValue, text: selectedText }] : []
  });
}

$(document).ready(function () {
  // Initialize Select2 for country, state, and city dropdowns
  initSelect2($('.countryDataload'), loadCountryUrl, selectCountry);
  initSelect2($('.stateDataload'), loadStateUrl, selectState);
  initSelect2($('.cityDataload'), loadCityUrl, selectCity);
});








