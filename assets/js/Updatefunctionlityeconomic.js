document.addEventListener('DOMContentLoaded', function () {
  const updateButton = document.getElementById('updateButton');
  const form = document.getElementById('formAccountSettings');
  const inputFields = form.querySelectorAll('input[readonly]');

  // Initialize Flatpickr for the date picker input
  flatpickr('.date-picker', {
    enableTime: false, // Disable time selection
    dateFormat: 'Y', // Display only the year
    readOnly: true // Initially, the input is readonly
  });

  updateButton.addEventListener('click', function () {
    if (updateButton.innerText === 'Update') {
      // Switch to edit mode
      inputFields.forEach(function (input) {
        input.removeAttribute('readonly');
      });
      const datePickers = document.querySelectorAll('.date-picker');
      datePickers.forEach(function (picker) {
        picker._flatpickr.set('readOnly');
      });
      // Remove the readonly attribute from the dropdown
      const yearOfEmployment = document.getElementById('yearOfEmployment');
      const branchDropdown = document.getElementById('branch');
      yearOfEmployment.removeAttribute('readonly');
      branchDropdown.removeAttribute('readonly');
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

  function validateForm() {
    // An array of field IDs and their corresponding error messages
    const fields = [
      { id: 'fieldOfEmployment', error: 'field of Employment is required.' },
      { id: 'numberOfIncome', error: 'Number of Income Number is required.' },
      { id: 'yearOfEmployment', error: 'Year of Employment is required.' },
      { id: 'position', error: 'Position is required.' },
      { id: 'branch', error: 'Branch is required.' }
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

      if (field.id === 'numberOfIncome') {
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

    /*   // Disable all input fields
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
});
