"use strict";

function initMap() {
  var inputs = document.getElementsByClassName('search-address');
  Array.prototype.forEach.call(inputs, function (input) {
    var searchBox = new google.maps.places.SearchBox(input);

    searchBox.addListener('places_changed', function () {
      var places = searchBox.getPlaces();
      if (places.length === 0) {
        return;
      }

      var place = places[0];
      if (place.geometry) {
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();

        // Check if this input field is the default language
        var isDefaultLang = input.getAttribute('data-is_default_lang');

        if (isDefaultLang === '1') {
          // Display the latitude and longitude in the visible input fields
          document.querySelector('input[name="latitude"]').value = latitude;
          document.querySelector('input[name="longitude"]').value = longitude;
        }
      }
    });

    // Prevent form submission on pressing Enter when selecting an address
    input.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        event.preventDefault();
      }
    });

    // Event listener to clear latitude and longitude when address input is modified (e.g., backspace)
    input.addEventListener('input', function (event) {

      // Check if the input value is empty or if the user is deleting characters
      if (input.value === '' || event.inputType === 'deleteContentBackward') {

        // Check if this input field is the default language
        var isDefaultLang = input.getAttribute('data-is_default_lang');

        if (isDefaultLang === '1') {
          // Clear the visible latitude and longitude fields
          document.querySelector('input[name="latitude"]').value = '';
          document.querySelector('input[name="longitude"]').value = '';
        }
      }
    });
  });
}
