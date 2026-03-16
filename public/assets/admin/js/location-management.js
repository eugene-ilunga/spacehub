"use strict";

$(document).ready(function (event){

  // Initialize Select2 on the country dropdown to create state modal

  $('.create-country-dropdown-container select[name="country_id"]').select2({
    placeholder: selectACountryTxt,
    allowClear: true,
    width: '100%',
    ajax: {
      url: getCountriesDataUrl,
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          languageId: $('select[name="language_id"]').val(),
          page: params.page || 1,
          limit: 10
        };
      },
      processResults: function (data, params) {
        params.page = params.page || 1;
        return {
          results: data.countries.map(function (country) {
            return {
              id: country.id,
              text: country.name
            };
          }),
          pagination: {
            more: data.pagination ? data.pagination.more : false
          }
        };
      },
      cache: true
    },
    minimumInputLength: 0
  });

  // Handle language change event
  $('select[name="language_id"]').on('change', function () {
    var languageId = $(this).val();

    // Clear and reset Select2 dropdown
    $('.create-country-dropdown-container select[name="country_id"]').empty().select2('destroy');
    $('.create-country-dropdown-container').removeClass('d-none').addClass('d-block');

    // this code written for state create form
    $('.create-country-dropdown-container select[name="country_id"]').select2({
    
      placeholder: selectACountryTxt,
      width: '100%',
      ajax: {
        url: getCountriesDataUrl,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            languageId: languageId,
            page: params.page || 1,
            limit: 10
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: data.countries.map(function (country) {
              return {
                id: country.id,
                text: country.name
              };
            }),
            pagination: {
              more: data.pagination ? data.pagination.more : false
            }
          };
        },
        cache: true
      },
      minimumInputLength: 0,
      minimumResultsForSearch: -1
    });
  });

// this code written for state edit form
  $('#editModal .edit-country-dropdown-container select[name="country_id"]').select2({
    placeholder: selectACountryTxt,
    width: '100%',
    ajax: {
      url: getCountriesDataUrl,
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          languageId: $('#language_id').val(),
          page: params.page || 1,
          limit: 10
        };
      },
      processResults: function (data, params) {
        params.page = params.page || 1;
        return {
          results: data.countries.map(function (country) {
            return {
              id: country.id,
              text: country.name
            };
          }),
          pagination: {
            more: data.pagination ? data.pagination.more : false
          }
        };
      },
      cache: true
    },
    minimumInputLength: 0
  });

  // Handle language change event to reinitialize country dropdown to craete state in edit modal
  $('select[name="language_id"]').on('change', function () {
    var languageId = $(this).val();

    // Clear and reset Select2 dropdown in edit modal
    $('#editModal .edit-country-dropdown-container select[name="country_id"]').empty().select2('destroy');
    $('#editModal .edit-country-dropdown-container').removeClass('d-none').addClass('d-block');

    // Reinitialize Select2 with new language
    $('#editModal .edit-country-dropdown-container select[name="country_id"]').select2({
      placeholder: selectACountryTxt,
      width: '100%',
      ajax: {
        url: getCountriesDataUrl,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            languageId: languageId,
            page: params.page || 1,
            limit: 2
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: data.countries.map(function (country) {
              return {
                id: country.id,
                text: country.name
              };
            }),
            pagination: {
              more: data.countries.length === 2
            }
          };
        },
        cache: true
      },
      minimumInputLength: 0
    });
  });

  // Handle modal show event to populate the form with existing data
  $('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var stateId = button.data('id');
    var countryId = button.data('country-id');
    var stateName = button.data('name');
    var status = button.data('status');

    // Populate form fields
    var modal = $(this);
    modal.find('#in_id').val(stateId);
    modal.find('#in_name').val(stateName);
    modal.find('#in_status').val(status);

    // Set the selected country in Select2
    if (countryId) {
      $.ajax({
        url: getCountriesDataUrl,
        type: 'GET',
        data: { languageId: $('select[name="language_id"]').val(), id: countryId },
        success: function (data) {
          var country = data.countries.find(c => c.id == countryId);
          if (country) {
            var newOption = new Option(country.name, country.id, true, true);
            modal.find('#in_country_id').append(newOption).trigger('change');
          }
        },
        error: function () {
          $('#editErr_country_id').text('An error occurred while fetching the country. Please try again later.');
        }
      });
    }
  });

  // get state data according to country for create modal in the city create form 

  $('.create-state-dropdown-container select[name="state_id"]').select2({
    ajax: {
      url: getStatesDataUrl, 
      dataType: 'json',
      delay: 250, 
      data: function (params) {
        var countryId = $('.create-city-dropdown-container select[name="country_id"]').val();
        var languageId = $('select[name="language_id"]').val();
        return {
          countryId: countryId,
          languageId: languageId,
          page: params.page || 1, 
          perPage: 10
        };
      },
      processResults: function (data, params) {
        // Only show state dropdown if states are available
        if (data.states && data.states.length > 0) {
          $('.stateIsRequired').removeClass('d-none');
        } else {
          $('.stateIsRequired').addClass('d-none'); 
        }
        return {
          results: data.states.map(function (state) {
            return {
              id: state.id,
              text: state.name
            };
          }),
          pagination: {
            more: data.hasMore 
          }
        };
      },
      cache: true
    },
    placeholder: selectAStateTxt, 
    minimumResultsForSearch: Infinity, 
   
  });

  // Reset state dropdown and show it when country changes
  $('.create-city-dropdown-container select[name="country_id"]').on('change', function () {
    var countryId = $(this).val();
    var languageId = $('select[name="language_id"]').val();

    if (countryId && languageId) {
      $('.create-state-dropdown-container').removeClass('d-none').addClass('d-block');
      $('#err_state_id').text('');

      // Clear existing options and reset Select2
      $('.create-state-dropdown-container select[name="state_id"]').empty().append('<option selected disabled>' + selectAStateTxt + '</option>');
      $('.create-state-dropdown-container select[name="state_id"]').val(null).trigger('change');
    } else {
      $('.create-state-dropdown-container').addClass('d-none').removeClass('d-block');
      $('#err_state_id').text('Please select a country and language.');
    }
  });
});

$(".spaceEditBtn").on('click', function () {
  $('.em').each(function () {
    $(this).html('');
  });

  let datas = $(this).data();
  delete datas['toggle'];

  for (let x in datas) {
    if ($("#in_" + x).hasClass('summernote')) {
      tinyMCE.activeEditor.setContent(datas[x])
    } else if ($("#in_" + x).hasClass('summernote2')) {
      tinyMCE.activeEditor.setContent(datas[x]);
    } else if ($("#in_" + x).data('role') == 'tagsinput') {
      if (datas[x].length > 0) {
        let arr = datas[x].split(" ");

        for (let i = 0; i < arr.length; i++) {
          $("#in_" + x).tagsinput('add', arr[i]);
        }
      } else {
        $("#in_" + x).tagsinput('removeAll');
      }
    } else if ($("input[name='" + x + "']").attr('type') == 'radio') {
      $("input[name='" + x + "']").each(function (i) {
        if ($(this).val() == datas[x]) {
          $(this).prop('checked', true);
        }
      });
    } else {
      $("#in_" + x).val(datas[x]);

      if ($('.in_image').length > 0) {
        $('.in_image').attr('src', datas['image']);
      }

      if ($('#in_icon').length > 0) {
        $('#in_icon').attr('class', datas['icon']);
      }
    }
  }

  // Check if state_id is empty and hide the state dropdown container
  if (!datas.state_id) {
    $('.edit-state-dropdown-container').addClass('d-none');
  } else {
    $('.edit-state-dropdown-container').removeClass('d-none');
  }
  //  if state_id is empty and hide the state dropdown container
  if (!datas.country_id) {
    $('.edit-country-dropdown-container').addClass('d-none');
  } else {
    $('.edit-country-dropdown-container').removeClass('d-none');
  }
});

$(document).ready(function () {
  // Initialize Select2 for country dropdown with AJAX in the city edit modal
  function initializeCountrySelect2() {
    $('#in_country_id').select2({
      ajax: {
        url: getCountriesDataUrl,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          var languageId = langId;
          return {
            languageId: languageId,
            page: params.page || 1,
            limit: 10
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          return {
            results: data.countries.map(function (country) {
              return {
                id: country.id,
                text: country.name
              };
            }),
            pagination: {
              more: data.pagination ? data.pagination.more : false
            }
          };
        },
        cache: true
      },
      placeholder: selectACountryTxt,
      minimumResultsForSearch: Infinity
    });
  }

  // Initialize Select2 for state dropdown with AJAX in the city edit modal
  function initializeStateSelect2() {
    $('#in_state_id').select2({
      ajax: {
        url: getStatesDataUrl,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          var countryId = $('#in_country_id').val();
          var languageId = langId;
          return {
            countryId: countryId,
            languageId: languageId,
            page: params.page || 1,
            perPage: 10
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          if (data.states && data.states.length > 0) {
            $('.stateIsRequired').removeClass('d-none');
          } else {
            $('.stateIsRequired').removeClass('d-block').addClass('d-none');
          }
          return {
            results: data.states.map(function (state) {
              return {
                id: state.id,
                text: state.name
              };
            }),
            pagination: {
              more: data.hasMore || (data.pagination ? data.pagination.more : false)
            }
          };
        },
        cache: true
      },
      placeholder: selectAStateTxt,
      minimumResultsForSearch: -1, 
      dropdownCssClass: 'select2-dropdown--scrollable' 
    });
  }


  // Initialize Select2 when the modal is shown
  $('#editModal').on('shown.bs.modal', function () {
    initializeCountrySelect2();
    initializeStateSelect2();
  });

  // Handle country change
  $('#in_country_id').off('change').on('change', function () {
    var countryId = $(this).val();
    var languageId = langId;

    if (countryId && languageId) {
      $('.edit-state-dropdown-container').removeClass('d-none').addClass('d-block');
      $('#editErr_state_id').text('');

      // Clear and reset state dropdown
      $('#in_state_id').empty().append('<option selected disabled>' + selectAStateTxt + '</option>');
      $('#in_state_id').val(null).trigger('change');
    } else {
      $('.edit-state-dropdown-container').addClass('d-none').removeClass('d-block');
      $('#editErr_state_id').text('Please select a country.');
    }
  });

  // Handle modal opening with pre-filled data
  $('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var cityId = button.data('id');
    var cityName = button.data('name');
    var countryId = button.data('country_id');
    var countryName = button.data('country_name');
    var stateId = button.data('state_id');
    var status = button.data('status');
    var image = button.data('image');

    // Populate modal fields
    $('#in_id').val(cityId);
    $('#in_name').val(cityName);
    $('#in_status').val(status);
    $('.in_image').attr('src', image);

    // Pre-select country
    if (countryId && countryName) {
      var countryOption = new Option(countryName, countryId, true, true);
      $('#in_country_id').append(countryOption).trigger('change');
    }

    // Pre-select state
    if (countryId && stateId && langId) {
      $.ajax({
        url: getStatesDataUrl,
        type: 'GET',
        data: {
          countryId: countryId,
          languageId: langId,
          stateId: stateId 
        },
        success: function (response) {
          if (response.states.length > 0) {
            var state = response.states.find(s => s.id == stateId);
            if (state) {
              var stateOption = new Option(state.name, state.id, true, true);
              $('#in_state_id').append(stateOption).trigger('change');
              $('.edit-state-dropdown-container').removeClass('d-none').addClass('d-block');
            } else {
              $('#editErr_state_id').text('State not found.');
            }
          } else {
            $('#editErr_state_id').text('No states found for the selected country.');
            $('.edit-state-dropdown-container').addClass('d-none').removeClass('d-block');
          }
        },
        error: function (xhr, status, error) {
          $('#editErr_state_id').text('Error fetching state.');
          $('.edit-state-dropdown-container').addClass('d-none').removeClass('d-block');
        }
      });
    }
  });

  // Handle modal reset when closed
  $('#editModal').on('hidden.bs.modal', function () {
    $('.edit-state-dropdown-container').addClass('d-none').removeClass('d-block');
    $('#in_country_id').empty().append('<option selected disabled>' + selectACountryTxt + '</option>');
    $('#in_country_id').val(null).trigger('change');
    $('#in_state_id').empty().append('<option selected disabled>' + selectAStateTxt + '</option>');
    $('#in_state_id').val(null).trigger('change');
    $('#in_name').val('');
    $('#in_status').val(null);
    $('.in_image').attr('src', '');
    $('#editErr_country_id').text('');
    $('#editErr_state_id').text('');
    $('#editErr_name').text('');
    $('#editErr_status').text('');
    $('#editErr_image').text('');
  });
});














