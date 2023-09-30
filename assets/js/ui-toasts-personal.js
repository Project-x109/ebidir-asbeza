/**
 * UI Toasts
 */
'use strict';

const form = document.querySelector('form');
const submitBtn = document.getElementById('submit-btn');

function validateForm(event) {
  // An array of field IDs and their corresponding error messages
  const fields = [
    { id: 'numberOfDependents', error: 'Number of Dependents is required.' },
    { id: 'marrigeStatus', error: 'Marriage Status is required.' },
    { id: 'educationalStatus', error: 'Educational Status is required.' },
    { id: 'criminalRecord', error: 'Criminal Record is required.' }
  ];
  // Iterate through the fields and check their values
  const numberRegex = /^[0-9]+$/;
  for (const field of fields) {
    const input = document.getElementById(field.id);
    const value = input.value.trim();

    if (value === '') {
      event.preventDefault();
      const toastPlacementExample = document.querySelector('.toast-placement-ex');
      toastPlacementExample.querySelector('.toast-body').textContent = field.error;
      toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
      const toastPlacement = new bootstrap.Toast(toastPlacementExample);
      toastPlacement.show();
      return; // Stop further validation on the first empty field
    }

    if (field.id === 'numberOfDependents') {
      event.preventDefault();
      // Check if Dependetents Number field contains only numbers
      if (!numberRegex.test(value)) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent = 'Dependetents must contain numbers.';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if Dependetents Number is invalid
      }
      if (value > 10) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent =
          'Number of Dependetents must be less than ten.';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if Dependetents Number is invalid
      }
    }
  }
  form.submit();
}
submitBtn.addEventListener('click', validateForm);
