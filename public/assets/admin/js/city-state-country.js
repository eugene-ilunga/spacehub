
'use strict';

$(document).ready(function () {
  // Fetch states based on selected country
  function fetchStates(countryId, $accordionSection, languageId) {
    $.ajax({
      url: stateByCountryUrl,
      type: 'GET',
      data: {
        countryId: countryId,
        language_id: languageId
      },
      success: function (data) {
        var $stateDropdown = $accordionSection.find('.stateDropdown');
        var $stateDropdownContainer = $accordionSection.find('.stateDropdownContainer');
        var $stateRequiredSymbol = $accordionSection.find('.state-required-symbol');
        var $cityDropdownContainer = $accordionSection.find('.cityDropdownContainer');

        // Store the pre-selected state value
        var selectedStateId = $stateDropdown.val();

        $stateDropdown.empty();

        if (data.length > 0) {
          $stateDropdown.append('<option selected disabled>' + selectAStateTxt + '</option>');

          $.each(data, function (index, state) {
            var isSelected = selectedStateId && state.id == selectedStateId ? 'selected' : '';
            $stateDropdown.append('<option value="' + state.id + '" ' + isSelected + '>' + state.name + '</option>');
          });

          $stateDropdownContainer.removeClass('d-none').addClass('d-block');
          $stateRequiredSymbol.removeClass('d-none');
          $cityDropdownContainer.removeClass('col-lg-8').addClass('col-lg-4');
        } else {
          $stateDropdownContainer.addClass('d-none').removeClass('d-block');
          $stateRequiredSymbol.addClass('d-none');
          $cityDropdownContainer.removeClass('col-lg-4').addClass('col-lg-8');
        }

        // Reinitialize Select2 to reflect the updated options
        initSelect2($stateDropdown, loadStateUrl, selectAStateTxt, countryId);
      },
      error: function (xhr, status, error) {
        console.error('Error:', error);
        var $stateRequiredSymbol = $accordionSection.find('.state-required-symbol');
        var $cityDropdownContainer = $accordionSection.find('.cityDropdownContainer');
        $stateRequiredSymbol.addClass('d-none');
        $cityDropdownContainer.removeClass('col-lg-4').addClass('col-lg-8');
      }
    });
  }

  // Fetch cities based on country and optionally state
  function fetchCities(countryId, $accordionSection, languageId, stateId) {
    $.ajax({
      url: cityByCountryOrStateUrl,
      type: 'GET',
      data: {
        stateId: stateId,
        countryId: countryId,
        language_id: languageId
      },
      success: function (data) {
        var $cityDropdown = $accordionSection.find('.cityDropdown');
        var $cityDropdownContainer = $accordionSection.find('.cityDropdownContainer');

        // Store the pre-selected city value
        var selectedCityId = $cityDropdown.val();

        $cityDropdown.empty();

        if (data.length > 0) {
          $cityDropdown.append('<option selected disabled>' + selectACityTxt + '</option>');

          $.each(data, function (index, city) {
            var isSelected = selectedCityId && city.id == selectedCityId ? 'selected' : '';
            $cityDropdown.append('<option value="' + city.id + '" ' + isSelected + '>' + city.name + '</option>');
          });

          $cityDropdownContainer.removeClass('d-none').addClass('d-block');
        } else {
          $cityDropdownContainer.addClass('d-none').removeClass('d-block');
        }

        // Reinitialize Select2 to reflect the updated options
        initSelect2($cityDropdown, loadCityUrl, selectACityTxt, countryId, stateId);
      },
      error: function (xhr, status, error) {
        console.error('Error:', error);
        var $cityDropdownContainer = $accordionSection.find('.cityDropdownContainer');
        $cityDropdownContainer.addClass('d-none').removeClass('d-block');
      }
    });
  }

  // Initialize Select2 for dynamically loaded dropdowns
  function initSelect2(dropdown, url, placeholder, countryId = null, stateId = null) {
    // Get the pre-selected value and text (if any)
    var selectedValue = dropdown.val();
    var selectedText = dropdown.find('option:selected').text() || placeholder;

    // Initialize Select2
    dropdown.select2({
      ajax: {
        url: url,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            language_id: dropdown.data('language-id'),
            countryId: countryId || dropdown.closest('.version-body').find('.countryDropdown').val(),
            stateId: stateId || dropdown.closest('.version-body').find('.stateDropdown').val(),
            per_page: 10,
            page: params.page || 1
          };
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
              more: data.pagination.more
            }
          };
        },
        cache: true
      },
      placeholder: placeholder,
      minimumInputLength: 0,
      // Set initial value if pre-selected
      data: selectedValue ? [{ id: selectedValue, text: selectedText }] : []
    });
  }

  // Country dropdown change event
  $('body').on('change', '.countryDropdown', function () {
    var $accordionSection = $(this).closest('.version-body');
    var countryId = $(this).val();
    var languageId = $(this).data('language-id');

    // Store the selected country_id in a hidden input
    $('#selectedCountryId').val(countryId);

    fetchStates(countryId, $accordionSection, languageId);
    fetchCities(countryId, $accordionSection, languageId);

    // Reinitialize state and city dropdowns
    var $stateDropdown = $accordionSection.find('.stateDropdown');
    var $cityDropdown = $accordionSection.find('.cityDropdown');
    initSelect2($stateDropdown, loadStateUrl, selectAStateTxt, countryId);
    initSelect2($cityDropdown, loadCityUrl, selectACityTxt, countryId);
  });

  // State dropdown change event
  $('body').on('change', '.stateDropdown', function () {
    var $accordionSection = $(this).closest('.version-body');
    var stateId = $(this).val();
    var languageId = $(this).data('language-id');
    var countryId = $('#selectedCountryId').val();

    fetchCities(countryId, $accordionSection, languageId, stateId);

    // Reinitialize city dropdown
    var $cityDropdown = $accordionSection.find('.cityDropdown');
    initSelect2($cityDropdown, loadCityUrl, selectACityTxt, countryId, stateId);
  });

  // Initialize dropdowns for both create and edit forms
  $('.countryDropdownDataload').each(function () {
    var $countryDropdown = $(this);
    var $accordionSection = $countryDropdown.closest('.version-body');
    var languageId = $countryDropdown.data('language-id');
    var selectedCountryId = $countryDropdown.val();

    var $stateDropdown = $accordionSection.find('.stateDropdownDataload');
    var $cityDropdown = $accordionSection.find('.cityDropdownDataload');

    // Initialize Select2 for country, state, and city
    initSelect2($countryDropdown, loadCountryUrl, selectACountryTxt);
    initSelect2($stateDropdown, loadStateUrl, selectAStateTxt);
    initSelect2($cityDropdown, loadCityUrl, selectACityTxt);

    // If a country is selected (edit form), fetch states and cities
    if (selectedCountryId) {
      $('#selectedCountryId').val(selectedCountryId);
      fetchStates(selectedCountryId, $accordionSection, languageId);

      // Initialize state dropdown with pre-selected value
      var selectedStateId = $stateDropdown.val();
      if (selectedStateId) {
        fetchCities(selectedCountryId, $accordionSection, languageId, selectedStateId);
        // Initialize city dropdown with pre-selected value
        initSelect2($cityDropdown, loadCityUrl, selectACityTxt, selectedCountryId, selectedStateId);
      }

      // Check if states are available for the pre-selected country
      $.ajax({
        url: stateByCountryUrl,
        type: 'GET',
        data: {
          countryId: selectedCountryId,
          language_id: languageId
        },
        success: function (data) {
          var $stateRequiredSymbol = $accordionSection.find('.state-required-symbol');
          var $cityDropdownContainer = $accordionSection.find('.cityDropdownContainer');
          if (data.length > 0) {
            $stateRequiredSymbol.removeClass('d-none');
            $cityDropdownContainer.removeClass('col-lg-8').addClass('col-lg-4');
          } else {
            $stateRequiredSymbol.addClass('d-none');
            $cityDropdownContainer.removeClass('col-lg-4').addClass('col-lg-8');
          }
        },
        error: function () {
          var $stateRequiredSymbol = $accordionSection.find('.state-required-symbol');
          var $cityDropdownContainer = $accordionSection.find('.cityDropdownContainer');
          $stateRequiredSymbol.addClass('d-none');
          $cityDropdownContainer.removeClass('col-lg-4').addClass('col-lg-8');
        }
      });
    } else {
      // For create form, set city container to col-lg-8 and hide state required symbol
      var $stateRequiredSymbol = $accordionSection.find('.state-required-symbol');
      $stateRequiredSymbol.addClass('d-none');
    }
  });
});
