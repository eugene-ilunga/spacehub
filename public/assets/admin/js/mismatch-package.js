
'use strict'

document.addEventListener('DOMContentLoaded', function () {
  // const buyNowButton = document.getElementById('buyNowButton');

  const packageMismatchModal = new bootstrap.Modal(document.getElementById('packageMismatchModal'));

  const proceedToCheckout = document.getElementById('proceedToCheckout');

  // Handle Buy Now button click
  $("body").on('click', '.buy-now-button', function (event) {
    event.preventDefault();

    const buyNowButton = this;

    // Store the package ID in the modal's Proceed button
    const packageId = buyNowButton.getAttribute('data-package_id');
    const langCode = buyNowButton.getAttribute('data-lang_code');

    // Show the modal
    if (previousPackageId == 'new_vendor') {
      window.location.href = buyPlanUrl + '/' + packageId + `?language=${langCode}`;
    }
    else {
      if (packageId != previousPackageId) {
        packageMismatchModal.show();
      }
      else {
        window.location.href = buyPlanUrl + '/' + packageId + `?language=${langCode}`;
      }
    }

    proceedToCheckout.setAttribute('href', buyPlanUrl + '/' + packageId + `?language=${langCode}`);
  });

  // Handle Proceed button in the modal
  proceedToCheckout.addEventListener('click', function (event) {
    // Proceed with checkout automatically as the link is set dynamically
  });
});
