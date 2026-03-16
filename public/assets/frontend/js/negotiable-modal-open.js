"use strict";
$('#getQuoteModal').on('hidden.bs.modal', function () {
  // Remove all error messages
  $(this).find('.text-danger').remove();

  // Reset form fields
  $(this).find('form')[0].reset();
  $('#bookATourModal').find('.text-danger').remove();
  $('#bookATourModal').find('form')[0].reset();
});

// Handle error messages and form reset for 'bookATourModal'
$('#bookATourModal').on('hidden.bs.modal', function () {
  // Remove all error messages
  $(this).find('.text-danger').remove();

  // Reset form fields
  $(this).find('form')[0].reset();
  $('#getQuoteModal').find('.text-danger').remove();
  $('#getQuoteModal').find('form')[0].reset();
});
