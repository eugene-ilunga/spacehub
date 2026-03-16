'use strict';

// Add event listener to the form submit
$('body').on('click','.spaceWishlist', function (e) {
  e.preventDefault(); 
  $(this).closest('form').submit(); 
});

// Handle the form submission
$('body').on('submit','#spaceWishlistForm', function (e) {
  e.preventDefault(); 
  var form = $(this);
  var url = form.attr('action');

  $.ajax({
    url: url,
    type: 'POST',
    data: form.serialize(), 
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (data) {

      // Handle the successful response
      if (data.user_login_route)
      {
        window.location.href = data.user_login_route;
      }

      // Update the UI based on the status
      if (data.status === 'Added') {
        sessionStorage.setItem('toastrMessage', data.message);
        sessionStorage.setItem('toastrType', 'success');
        form.children(".spaceWishlist").addClass('active');
        form.children(".spaceWishlist").attr('aria-label', 'Remove')
        window.location.reload();

      }
      else if (data.status === 'Removed') {
        sessionStorage.setItem('toastrMessage', data.message);
        sessionStorage.setItem('toastrType', 'success');
        form.children(".spaceWishlist").removeClass('active');
        form.children(".spaceWishlist").attr('aria-label', 'Added')
        window.location.reload();
    
      }
      
    },
    error: function (xhr, status, error) {
      // Handle the error response
      console.error(error);
      // Display an error message or update the UI
    }
  });
});

$(window).on('load', function () {
  var message = sessionStorage.getItem('toastrMessage');
  var type = sessionStorage.getItem('toastrType');

  if (message) {
    if (type === 'success') {
      toastr.success(message);
    } else if (type === 'error') {
      toastr.error(message);
    }

    // Clear the session storage to prevent the message from displaying again on future reloads
    sessionStorage.removeItem('toastrMessage');
    sessionStorage.removeItem('toastrType');
  }
});

//current location search 


$('body').on('click', '#currentLocationButton', function (e) {
  e.preventDefault();

  getCurrentLocation()
    .then(address => {
      $('#locationInput').val(address);
    })
    .catch(error => {
      alert(error);
    });
});

function getCurrentLocation() {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) {
      reject('Geolocation is not supported by this browser.');
      return;
    }

    navigator.geolocation.getCurrentPosition(
      function (position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        if (!google_map_api_key) {
          reject('Google Maps API key is missing.');
          return;
        }

        fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${google_map_api_key}`)
          .then(response => response.json())
          .then(data => {

            if (data.status === 'OK' && data.results && data.results.length > 0) {
              const specificResult = data.results.find(result =>
                result.types.includes('street_address') || result.types.includes('premise')
              ) || data.results[0];
              const address = specificResult.formatted_address;

              // Optional: Initialize map to show location
              if (document.getElementById('map')) {
                const map = new google.maps.Map(document.getElementById('map'), {
                  center: { lat: latitude, lng: longitude },
                  zoom: 15
                });
                new google.maps.Marker({
                  position: { lat: latitude, lng: longitude },
                  map: map
                });
              }

              resolve(address);
            } else if (data.status === 'ZERO_RESULTS') {
              reject('No address found for this location.');
            } else if (data.status === 'OVER_QUERY_LIMIT') {
              reject('API quota exceeded. Please try again later.');
            } else if (data.status === 'REQUEST_DENIED') {
              reject('Invalid API key or billing issue.');
            } else {
              reject('Geocoding API error: ' + data.status);
            }
          })
          .catch(error => {
            console.error('Error fetching address:', error);
            reject('Error fetching address: ' + error.message);
          });
      },
      function (error) {
        let errorMessage;
        switch (error.code) {
          case error.PERMISSION_DENIED:
            errorMessage = 'Location access was denied. Please enable location services or enter your location manually.';
            break;
          case error.POSITION_UNAVAILABLE:
            errorMessage = 'Location information is unavailable. Try again later.';
            break;
          case error.TIMEOUT:
            errorMessage = 'The request to get location timed out.';
            break;
          default:
            errorMessage = 'An unknown error occurred while fetching location.';
        }
        reject(errorMessage);
      },
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
  });
}
