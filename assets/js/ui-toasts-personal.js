/**
 * UI Toasts
 */
'use strict';
function validateForm() {
  // An array of field IDs and their corresponding error messages
  const fields = [
    { id: 'basic-icon-default-dependetents', error: 'Number of Dependents is required.' },
    { id: 'basic-icon-default-marriagestatus', error: 'Marriage Status is required.' },
    { id: 'basic-icon-default-educationalstatus', error: 'Educational Status is required.' },
    { id: 'basic-icon-default-criminalrecord', error: 'Criminal Record is required.' }
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
      return; // Stop further validation on the first empty field
    }

    if (field.id === 'basic-icon-default-dependetents') {
      // Check if Dependetents Number field contains only numbers
      if (!numberRegex.test(value)) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent =
          'Dependetents must contain numbers.';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if Dependetents Number is invalid
      }
    }

    if (field.id === 'basic-icon-default-criminalrecord') {
      // Check if Criminal Record field contains only numbers
      if (!numberRegex.test(value)) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent =
          'Criminal Record Number must contain numbers.';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if Criminal Record is invalid
      }
    }
  }
  // Disable all input fields
  const inputFields = document.querySelectorAll('input, select');
  for (const inputField of inputFields) {
    inputField.disabled = true;
  }

  // If all fields are valid, show a success message
  const toastPlacementExample = document.querySelector('.toast-placement-ex');
  toastPlacementExample.classList.add('bg-primary');
  toastPlacementExample.classList.remove('bg-danger');
  toastPlacementExample.querySelector('.toast-body').textContent = 'Form submitted successfully.';
  toastPlacementExample.querySelector('.toast-title ').textContent = 'Success.';
  const toastPlacement = new bootstrap.Toast(toastPlacementExample);
  toastPlacement.show();
}
