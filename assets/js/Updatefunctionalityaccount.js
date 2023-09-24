document.addEventListener('DOMContentLoaded', function () {
  const updateButton = document.getElementById('updateButton');
  const form = document.getElementById('formAccountSettings');
  const inputFields = form.querySelectorAll('input[readonly]');
  const selectFields = form.querySelectorAll('select[readonly]');
  // Initialize Flatpickr for the date picker input
  flatpickr('.date-picker', {
    enableTime: false, // Disable time selection
    dateFormat: 'd-m-Y', // Display only the year
    readOnly: true // Initially, the input is readonly
  });

  updateButton.addEventListener('click', function () {
    if (updateButton.innerText === 'Update') {
      // Switch to edit mode for input fields
      inputFields.forEach(function (input) {
        input.removeAttribute('readonly');
      });

      // Switch to edit mode for select fields
      selectFields.forEach(function (select) {
        select.removeAttribute('readonly');
      });
      const datePickers = document.querySelectorAll('.date-picker');
      datePickers.forEach(function (picker) {
        picker._flatpickr.set('readOnly');
      });
      // Remove the readonly attribute from the "Date of Birth" field
      const dateOfBirthInput = document.getElementById('dateOfBirth');
      dateOfBirthInput.removeAttribute('readonly');

      updateButton.innerText = 'Save changes';
    } else {
      validateForm();

      // Check if the form is valid
      const toastPlacementExample = document.querySelector('.toast-placement-ex');
      if (toastPlacementExample.classList.contains('bg-danger')) {
        // Form is not valid, don't submit
        return;
      }
      // Submit the form
      form.submit();
    }
  });

  // Rest of your validation and form submission code...
});

function validateForm() {
  // An array of field IDs and their corresponding error messages
  const fields = [
    { id: 'fullName', error: 'Name is required.' },
    { id: 'tinNumber', error: 'TIN Number is required.' },
    { id: 'dateOfBirth', error: 'Date of Birth is required.' },
    { id: 'phoneNumber', error: 'Phone is required.' }
    //{ id: 'basic-icon-default-photo', error: 'Image is required.' }
  ];
  const numberRegex = /^[0-9]+$/;
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

    if (field.id === 'tinNumber') {
      // Check if TIN Number field contains only numbers
      if (!numberRegex.test(value) || value.length !== 10) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent =
          'TIN Number must contain numbers length should be Ten(0-9).';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if TIN Number is invalid
      }
    }

    if (field.id === 'fullName') {
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

    if (field.id === 'phoneNumber') {
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
    /*     if (field.id === 'basic-icon-default-email') {
      // Check if TIN Number field contains only numbers
      if (!validEmailRegex.test(value)) {
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent = 'Invalid Email';
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation if Email is invalid
      }
    } */
  }

  /* // Disable all input fields
  const inputFields = document.querySelectorAll('input, select');
  for (const inputField of inputFields) {
    inputField.disabled = true;
  } */

  // If all fields are valid, show a success message
  const toastPlacementExample = document.querySelector('.toast-placement-ex');
  toastPlacementExample.classList.add('bg-primary');
  toastPlacementExample.classList.remove('bg-danger');
  toastPlacementExample.querySelector('.toast-body').textContent = 'Form submitted successfully.';
  toastPlacementExample.querySelector('.toast-title ').textContent = 'Success.';
  const toastPlacement = new bootstrap.Toast(toastPlacementExample);
  toastPlacement.show();
}
