/**
 * UI Toasts
 */
'use strict';
function validateForm() {
  // An array of field IDs and their corresponding error messages
  const fields = [
    { id: 'basic-icon-default-branchname', error: 'Branch Name is required.' },
    { id: 'basic-icon-default-email', error: 'Email is required.' },
    { id: 'basic-icon-default-phone', error: 'Phone is required.' },
    { id: 'basic-icon-default-location', error: 'Location is required.' },
    { id: 'basic-icon-default-logo', error: 'logo is required.' }
  ];
  const nameRegex = /^[A-Za-z\s]+$/;
  const validPhoneRegex = RegExp(
    /(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(0\s*9\s*(([0-9]\s*){8}))|(0\s*7\s*(([0-9]\s*){8}))/
  );
  const validEmailRegex = RegExp(
    /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i
  );
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

    if (field.id === 'basic-icon-default-branchname') {
      // Check if TIN Number field contains only numbers
      if (!nameRegex.test(value)) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent = 'Name Can only contain Alphabets.';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if TIN Number is invalid
      }
    }

    if (field.id === 'basic-icon-default-phone') {
      // Check if TIN Number field contains only numbers
      if (!validPhoneRegex.test(value)) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent = 'Invalid Phone Number';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if TIN Number is invalid
      }
    }
    if (field.id === 'basic-icon-default-email') {
      // Check if TIN Number field contains only numbers
      if (!validEmailRegex.test(value)) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent = 'Invalid Email';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if Email is invalid
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
