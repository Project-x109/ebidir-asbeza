function showLoader() {
  $('#loader').fadeIn();
}

// Hide the loader when the response is received
function hideLoader() {
  $('#loader').fadeOut();
}

$(document).ready(function () {
  console.log('Form submit event binding');
  $('#userForm').on('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission behavior
    showLoader();
    // Perform form validation here
    if (!validateForm()) {
      return; // Stop further processing if validation fails
    }

    // Serialize the form data
    var formData = new FormData(this);
    console.log('AJAX request initiated');

    $.ajax({
      url: 'backend.php', // URL to send the form data
      method: 'POST',
      data: formData,
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
        // Check if the response contains validation errors
        if (response.errors) {
          var errorContainer = $('#error-toast .toast-body');
          errorContainer.empty(); // Clear any previous errors
          console.log('AJAX request initiated');
          // Loop through the validation errors and display them in the toast
          $.each(response.errors, function (key, value) {
            errorContainer.append('<p>' + value + '</p>');
          });
          console.log('AJAX request initiated');

          // Display the error toast for frontend validation errors
          showErrorMessage();
        } else {
          // If no errors, you can redirect or show a success message as needed
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.success
            }).then(result => {
              // You can add additional actions after the user clicks "OK"
              if (result.isConfirmed) {
                // Clear and reset the form fields
                $('#userForm')[0].reset();

                // Create a new FormData object with the cleared form data
                var formData = new FormData($('#userForm')[0]);

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

  // Function to perform form validation
  function validateForm() {
    // Add your form validation logic here
    var isValid = true;

    const fields = [
      {
        id: 'basic-icon-default-fullname',
        error: 'Name is required.'
      },
      {
        id: 'basic-icon-default-TIN',
        error: 'TIN Number is required.'
      },
      {
        id: 'basic-icon-default-Job',
        error: 'Job Status is required.'
      },
      {
        id: 'basic-icon-default-dateOfBirth',
        error: 'Date of Birth is required.'
      },
      {
        id: 'basic-icon-default-email',
        error: 'Email is required.'
      },
      {
        id: 'basic-icon-default-phone',
        error: 'Phone is required.'
      },
      {
        id: 'basic-icon-default-photo',
        error: 'Image is required.'
      }
    ];
    const numberRegex = /^[0-9]+$/;
    const nameRegex = /^[A-Za-z\s]+$/;
    const validPhoneRegex = RegExp(
      /(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(0\s*9\s*(([0-9]\s*){8}))|(0\s*7\s*(([0-9]\s*){8}))/
    );
    const validEmailRegex = RegExp(
      /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i
    );

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

      if (field.id === 'basic-icon-default-TIN') {
        // Check if TIN Number field contains only numbers
        if (!numberRegex.test(value) || value.length !== 10) {
          isValid = false;
          // Display an error message in the toast
          $('#error-toast .toast-body').append(
            '<p>TIN Number must contain numbers and have a length of ten (0-9).</p>'
          );
          showErrorMessage();
          break; // Stop further validation if TIN Number is invalid
        }
      }

      if (field.id === 'basic-icon-default-fullname') {
        // Check if Name field contains only alphabets
        if (!nameRegex.test(value)) {
          isValid = false;
          // Display an error message in the toast
          $('#error-toast .toast-body').append('<p>Name can only contain alphabets and spaces.</p>');
          showErrorMessage();
          break; // Stop further validation if Name is invalid
        }
      }

      if (field.id === 'basic-icon-default-phone') {
        // Check if Phone Number is valid
        if (!validPhoneRegex.test(value)) {
          isValid = false;
          // Display an error message in the toast
          $('#error-toast .toast-body').append('<p>Invalid Phone Number.</p>');
          showErrorMessage();
          break; // Stop further validation if Phone Number is invalid
        }
      }

      if (field.id === 'basic-icon-default-email') {
        // Check if Email is valid
        if (!validEmailRegex.test(value)) {
          isValid = false;
          // Display an error message in the toast
          $('#error-toast .toast-body').append('<p>Invalid Email Address.</p>');
          showErrorMessage();
          break; // Stop further validation if Email is invalid
        }
      }
    }

    return isValid;
  }

  // Function to display the error toast for frontend validation errors
  function showErrorMessage() {
    var toastPlacement = new bootstrap.Toast($('#error-toast'));
    toastPlacement.show();
    hideLoader(); // Hide the loader on error
  }

  // Function to display the success toast
  /*  function showSuccessMessage() {
    var toastPlacement = new bootstrap.Toast($('#success-toast'));
    toastPlacement.show();
  } */
});
