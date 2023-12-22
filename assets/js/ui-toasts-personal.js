function showLoader() {
  $('#loader').fadeIn();
}

// Hide the loader when the response is received
function hideLoader() {
  $('#loader').fadeOut();
}
$(document).ready(function () {
  $('#personalForm').on('submit', function (event) {
    event.preventDefault();
    showLoader();
    var csrfToken = document.getElementById('csrf-token').getAttribute('value');
    if (!validateForm()) {
      return;
    }
    var formData = new FormData(this);
    $.ajax({
      url: 'backend.php',
      method: 'POST',
      data: formData,
      headers: {
        'X-CSRF-Token': csrfToken // Send the CSRF token as a header
      },
      contentType: false,
      processData: false,
      error: function (jqXHR, textStatus, errorThrown) {
        hideLoader(); // Hide the loader on error
        $('#error-toast .toast-body').text('Backend Error: ' + errorThrown);
        showErrorMessage();
      },
      success: function (response) {
        hideLoader(); // Hide the loader on success
        if (response.errors) {
          var errorContainer = $('#error-toast .toast-body');
          errorContainer.empty();
          $.each(response.errors, function (key, value) {
            errorContainer.append('<p>' + value + '</p>');
          });
          showErrorMessage();
        } else {
          if (response.success) {
            $('#personalForm')[0].reset();
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.success
            }).then(result => {
              if (result.isConfirmed) {
                window.location.href = 'economic.php';
                var formData = new FormData($('#personalForm')[0]);
                setTimeout(function () {
                  $('#submit-btn').prop('disabled', false);
                  $('#submit-btn').text('Submit');
                }, 2000);
              }
            });
          }
         
        }
      }
    });
  });

  function validateForm() {
    var isValid = true;
    const fields = [
      {
        id: 'numberOfDependents',
        error: 'Number of Dependents is required.'
      },
      {
        id: 'marrigeStatus',
        error: 'Marriage Status is required.'
      },
      {
        id: 'educationalStatus',
        error: 'Educational Status is required.'
      },
      {
        id: 'criminalRecord',
        error: 'Criminal Record is required.'
      }
    ];
    const numberRegex = /^[0-9]+$/;
    $('#error-toast .toast-body').empty();
    for (const field of fields) {
      const input = document.getElementById(field.id);
      const value = input.value.trim();
      if (!value && !/^ *$/.test(value)) {
        isValid = false;
        $('#error-toast .toast-body').append('<p>' + field.error + '</p>');
        showErrorMessage();
        break;
      }
      if (field.id === 'numberOfDependents') {
        if (!numberRegex.test(value)) {
          isValid = false;
          $('#error-toast .toast-body').append('<p>Dependetents must contain numbers.</p>');
          showErrorMessage();
          break;
        }
      }
      if (value > 10) {
        if (!numberRegex.test(value) || value.length !== 10) {
          isValid = false;
          $('#error-toast .toast-body').append('<p>Number of Dependetents must be less than ten.</p>');
          showErrorMessage();
          break;
        }
      }
    }
    return isValid;
  }
  function showErrorMessage() {
    var toastPlacement = new bootstrap.Toast($('#error-toast'));
    toastPlacement.show();
    hideLoader();
  }
});
