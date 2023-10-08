/* document.addEventListener('DOMContentLoaded', function () {
  console.log('Form submitted');
  const creditForm = document.getElementById('creditForm');
  const identificationNumberInput = document.getElementById('identificationNumber');
  const errorToast = document.getElementById('errorToast');

  creditForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const identificationNumber = identificationNumberInput.value;

    // Perform your validation here
    if (!isValidIdentificationNumber(identificationNumber)) {
      // Display the error toast
      errorToast.style.display = 'block';

      // You can set a custom error message here
      errorToast.querySelector('.toast-body').textContent = 'Invalid identification number.';

      // Hide the toast after a delay
      setTimeout(function () {
        errorToast.style.display = 'none';
      }, 2000);
    } else {
      // Submit the form if validation passes
      // creditForm.submit();
    }
  });

  function isValidIdentificationNumber(number) {
    // Implement your validation logic here
    // For example, check if the number is exactly 6 digits
    return number.length === 6; // Fixed typo: "Length" should be "length"
  }
});
 */

function showLoader() {
  $('#loader').fadeIn();
}

// Hide the loader when the response is received
function hideLoader() {
  $('#loader').fadeOut();
}
$(document).ready(function () {
  $('#creditFormmain').on('submit', function (event) {
    var csrfToken = document.getElementById('csrf-token').getAttribute('value');
    showLoader();

    event.preventDefault();
    var user_id = $('#user').val();

    // Frontend validation for user_id
    if (!isValidUserId(user_id)) {
      hideLoader();
      // Display error message using Toast
      $('#errorToast .toast-body').text('Invalid user ID format. It should start with "EB" followed by four digits.');
      $('#errorToast').fadeIn();
      return; // Don't proceed with the AJAX request
    }

    $.ajax({
      url: 'backend.php',
      method: 'POST',
      data: { user: user_id },
      headers: {
        'X-CSRF-Token': csrfToken // Send the CSRF token as a header
      },
      dataType: 'json',
      success: function (response) {
        hideLoader();
        if (response.success) {
          // User found, show success message
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.success
          }).then(result => {
            if (result.isConfirmed) {
              // Redirect to userinfo.php
              window.location.href = 'userinfo.php';
            }
          });
        } else if (response.error) {
          // Display error message using Toast
          $('#errorToast .toast-body').text(response.error);
          $('#errorToast').fadeIn();
        }
      }
    });
  });
});

// Frontend validation function for user_id
function isValidUserId(user_id) {
  return /^EB\d{4}$/.test(user_id);
}
