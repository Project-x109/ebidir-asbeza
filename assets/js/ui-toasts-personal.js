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
    // Perform form validation here
    if (!validateForm()) {
      return; // Stop further processing if validation fails
    }

    // Serialize the form data
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
        console.log('AJAX request error:', textStatus, errorThrown);
        hideLoader(); // Hide the loader on error
        // Display a backend error message in the error toast
        $('#error-toast .toast-body').text('Backend Error: ' + errorThrown);
        showErrorMessage();
      },
      success: function (response) {
        console.log(response);
        hideLoader(); // Hide the loader on success
        if (response.errors) {
          console.log(response.error);
          var errorContainer = $('#error-toast .toast-body');
          errorContainer.empty();
          $.each(response.errors, function (key, value) {
            errorContainer.append('<p>' + value + '</p>');
          });
          showErrorMessage();
        } else {
          if (response.success) {
            console.log(response.success);
            // Clear and reset the form
            $('#personalForm')[0].reset();
            // Show success message using SweetAlert2 modal
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.success
            }).then(result => {
              if (result.isConfirmed) {
                // Redirect or perform any other action here
                window.location.href = 'economic.php';
                var formData = new FormData($('#personalForm')[0]);
                // Re-enable the submit button after a delay (e.g., 2 seconds)
                setTimeout(function () {
                  $('#submit-btn').prop('disabled', false);
                  $('#submit-btn').text('Submit');
                }, 2000);
              }
            });
          }
          console.log('AJAX request initiated');
        }
      }
    });
  });

  function validateForm() {
    // Add your form validation logic here
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
    // Clear previous toast error messages
    $('#error-toast .toast-body').empty();

    // Iterate through the fields and check their values
    for (const field of fields) {
      const input = document.getElementById(field.id);
      const value = input.value.trim();

      if (value === '') {
        isValid = false;
        // Display an error message in the toast
        $('#error-toast .toast-body').append('<p>' + field.error + '</p>');
        showErrorMessage();
        break; // Stop further validation on the first empty field
      }

      if (field.id === 'numberOfDependents') {
        // Check if TIN Number field contains only numbers
        if (!numberRegex.test(value)) {
          isValid = false;
          // Display an error message in the toast
          $('#error-toast .toast-body').append('<p>Dependetents must contain numbers.</p>');
          showErrorMessage();
          break; // Stop further validation if TIN Number is invalid
        }
      }
      if (value > 10) {
        // Check if TIN Number field contains only numbers
        if (!numberRegex.test(value) || value.length !== 10) {
          isValid = false;
          // Display an error message in the toast
          $('#error-toast .toast-body').append('<p>Number of Dependetents must be less than ten.</p>');
          showErrorMessage();
          break; // Stop further validation if TIN Number is invalid
        }
      }
    }
    return isValid;
  }
  // Function to display the error toast
  function showErrorMessage() {
    var toastPlacement = new bootstrap.Toast($('#error-toast'));
    toastPlacement.show();
    hideLoader(); // Hide the loader on error
  }
});
