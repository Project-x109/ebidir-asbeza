function showLoader() {
  $('#loader').fadeIn();
}

// Hide the loader when the response is received
function hideLoader() {
  $('#loader').fadeOut();
}
$(document).ready(function () {
  var button = $('#updateButton'); // Cache the button elemen

  function storeOriginalValues() {
    // Store the original values of the fields as data attributes
    $('#formAccountSettings :input').each(function () {
      $(this).data('original-value', $(this).val());
    });
  }

  function toggleButtonState() {
    var formFields = $('#formAccountSettings :input');
    var anyFieldEdited = false;

    // Check if any of the fields have been edited
    formFields.each(function () {
      var value = $(this).val().trim();
      if ($(this).val() !== $(this).data('original-value')) {
        anyFieldEdited = true;
        return false; // Exit the loop early if any field is edited
      }

      if (value === '') {
        anyFieldEmpty = true;
      }
    });

    // Enable the "Save Changes" button if any field is edited
    button.prop('disabled', !anyFieldEdited);
  }

  // Store original values when the page loads
  storeOriginalValues();

  $('#formAccountSettings :input').on('input', toggleButtonState);
  $('#updateButton').on('click', function () {
    if (button.text() === 'Update') {
      // Switch to edit mode
      button.text('Save Changes');
      $('#formAccountSettings :input').removeAttr('readonly');
    } else {
      // Save changes and switch back to update mode
      if (validateForm()) {
        saveChanges(); // Only save changes if the form is valid
      }
      button.text('Update');
      $('#formAccountSettings :input').attr('readonly', true);
      // Re-check button state after switching back to update mode
    }
  });
  $('#cancelButton').on('click', function () {
    // Change the button text to "Update"
    button.text('Update');
    // Make the fields readonly
    $('#formAccountSettings :input').attr('readonly', true);
    // Reset the form to its original values
  });

  // Function to save changes when "Save Changes" is clicked
  function saveChanges() {
    showLoader();
    var formData = new FormData($('#formAccountSettings')[0]);
    $.ajax({
      url: 'backend.php',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      error: function (jqXHR, textStatus, errorThrown) {
        console.log('AJAX request error:', textStatus, errorThrown);
        hideLoader();
        $('#error-toast .toast-body').text('Backend Error: ' + errorThrown);
        showErrorMessage();
      },
      success: function (response) {
        console.log(response);
        hideLoader();
        if (response.errors) {
          var errorContainer = $('#error-toast .toast-body');
          errorContainer.empty();
          $.each(response.errors, function (key, value) {
            errorContainer.append('<p>' + value + '</p>');
          });
          showErrorMessage();
        } else if (response.success) {
          console.log(response.success);
          // Update original values with the newly saved values
          $('#originalNumberOfDependents').val($('#numberOfDependents').val());
          $('#originalMarrigeStatus').val($('#marrigeStatus').val());
          $('#originalEducationalStatus').val($('#educationalStatus').val());
          $('#originalCriminalRecord').val($('#criminalRecord').val());

          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.success
          }).then(result => {
            if (result.isConfirmed) {
              // Switch back to update mode
              $('#updateButton').text('Update');
              $('#formAccountSettings :input').attr('readonly', true);
            }
          });
        }
      }
    });
  }

  // ... Your form validation code here ...
  function validateForm() {
    let isValid = true;
    // An array of field IDs and their corresponding error messages
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
    // Iterate through the fields and check their values
    const numberRegex = /^[0-9]+$/;
    for (const field of fields) {
      const input = document.getElementById(field.id);
      const value = input.value.trim();

      if (value === '') {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent = field.error;
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        isValid = fals;
        return false; // Stop further validation on the first empty field
      }

      if (field.id === 'numberOfDependents') {
        // Check if Dependetents Number field contains only numbers
        if (!numberRegex.test(value)) {
          const toastPlacementExample = document.querySelector('.toast-placement-ex');
          toastPlacementExample.querySelector('.toast-body').textContent = 'Dependetents must contain numbers.';
          toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
          const toastPlacement = new bootstrap.Toast(toastPlacementExample);
          toastPlacement.show();
          isValid = fals;
          return false; // Stop further validation if Dependetents Number is invalid
        }
      }
    }
    // If all fields are valid, return true to indicate success
    return isValid;
  }

  // Function to display the error toast
  function showErrorMessage() {
    var toastPlacement = new bootstrap.Toast($('#error-toast'));
    toastPlacement.show();
  }
});
