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
        return false; 
      }
      if (value === '') {
        anyFieldEmpty = true;
      }
    });
    button.prop('disabled', !anyFieldEdited);
  }
  /* storeOriginalValues();
  $('#formAccountSettings :input').on('input', toggleButtonState); */
  $('#updateButton').on('click', function () {
    if (button.text() === 'Update') {
      button.text('Save Changes');
      $('#formAccountSettings :input').removeAttr('readonly');
    } else {
      if (validateForm()) {
        saveChanges(); 
      }
      button.text('Update');
      $('#formAccountSettings :input').attr('readonly', true);
    }
  });
  $('#cancelButton').on('click', function () {
    button.text('Update');
    $('#formAccountSettings :input').attr('readonly', true);
  });

  function saveChanges() {
    showLoader();
    var csrfToken = document.getElementById('csrf-token').getAttribute('value');
    var formData = new FormData($('#formAccountSettings')[0]);
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
        hideLoader();
        $('#error-toast .toast-body').text('Backend Error: ' + errorThrown);
        showErrorMessage();
      },
      success: function (response) {
        hideLoader();
        if (response.errors) {
          var errorContainer = $('#error-toast .toast-body');
          errorContainer.empty();
          $.each(response.errors, function (key, value) {
            errorContainer.append('<p>' + value + '</p>');
          });
          showErrorMessage();
        } else if (response.success) {
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
  function validateForm() {
    let isValid = true;
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
    return isValid;
  }

  // Function to display the error toast
  function showErrorMessage() {
    var toastPlacement = new bootstrap.Toast($('#error-toast'));
    toastPlacement.show();
  }
});
