"use strict";
document.addEventListener('DOMContentLoaded', function () {
  // Check if jQuery and floatingWhatsApp plugin are loaded
  if (typeof jQuery === 'undefined' || typeof jQuery.fn.floatingWhatsApp === 'undefined') {

    return;
  }

  // Get configuration from global window object
  const config = window.WhatsAppConfig || {};

  // Initialize floatingWhatsApp plugin
  $('#whatsapp-btn').floatingWhatsApp({
    phone: whPhoneNumber,
    headerTitle: whHeaderTitle,
    popupMessage: whpopupMessage,
    showPopup: whatsapp_popup == 1 ? true : false,
    buttonImage: '<img src="' + whatsappImg + '" />',
    position: "right",
    size: "60px",
    backgroundColor: "#25D366",
    headerColor: whPrimaryColor,
    /* here Use site’s primary color */
    zIndex: 9999
  });
});
