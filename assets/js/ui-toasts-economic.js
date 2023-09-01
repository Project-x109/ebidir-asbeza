/**
 * UI Toasts
 */
'use strict';
function validateForm() {
  // An array of field IDs and their corresponding error messages
  const fields = [
    { id: 'basic-icon-default-fieldofEmployment', error: 'field of Employment is required.' },
    { id: 'basic-icon-default-numberofincome', error: 'Number of Income Number is required.' },
    { id: 'html5-datetime-local-input-YearofEmployment', error: 'Year of Employment is required.' },
    { id: 'basic-icon-default-companyname', error: 'Company Name is required.' },
    { id: 'basic-icon-default-position', error: 'Position is required.' }
  ];
  const numberRegex = /^[0-9]+$/;
  // Iterate through the fields and check their values
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

    if (field.id === 'basic-icon-default-numberofincome') {
      // Check if TIN Number field contains only numbers
      if (!numberRegex.test(value)) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent = 'Number of Income should be Number only';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if TIN Number is invalid
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
