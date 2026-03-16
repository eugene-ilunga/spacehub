"use strict"
document.addEventListener('DOMContentLoaded', function () {
  setTimeout(() => {
    let message = localStorage.getItem('notifyMessage');
    let type = localStorage.getItem('notifyType');

    if (message) {
      var content = {};
      content.message = message;
      content.title = type === 'warning' ? warningTxt :
        type === 'danger' ? errorTxt : successTxt;
      content.icon = type === 'warning' ? 'fas fa-exclamation-circle' :
        type === 'danger' ? 'fas fa-times-circle' : 'fas fa-check-circle';

      $.notify(content, {
        type: type,
        placement: {
          from: 'top',
          align: 'right'
        },
        showProgressbar: true,
        time: 1000,
        delay: 4000
      });

      localStorage.removeItem('notifyMessage');
      localStorage.removeItem('notifyType');
    }
  }, 500);
});
