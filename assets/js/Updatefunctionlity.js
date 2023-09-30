document.addEventListener('DOMContentLoaded', function () {
  const updateButton = document.getElementById('updateButton');
  const form = document.getElementById('formAccountSettings');
  const inputFields = form.querySelectorAll('input[readonly]');

  updateButton.addEventListener('click', function () {
    if (updateButton.innerText === 'Update') {
      // Switch to edit mode
      inputFields.forEach(function (input) {
        input.removeAttribute('readonly');
      });
      // Remove the readonly attribute from the dropdown
      const educationalStatusDropdown = document.getElementById('educationalStatus');
      const marrigeStatusDropdown = document.getElementById('marrigeStatus');
      educationalStatusDropdown.removeAttribute('readonly');
      marrigeStatusDropdown.removeAttribute('readonly');
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
        const toastPlacementExample = document.querySelector('.toast-placement-ex');
        toastPlacementExample.querySelector('.toast-body').textContent = field.error;
        toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
        const toastPlacement = new bootstrap.Toast(toastPlacementExample);
        toastPlacement.show();
        return; // Stop further validation on the first empty field
      }

      if (field.id === 'numberOfDependents') {
        // Check if Dependetents Number field contains only numbers
        if (!numberRegex.test(value)) {
          const toastPlacementExample = document.querySelector('.toast-placement-ex');
          toastPlacementExample.querySelector('.toast-body').textContent = 'Dependetents must contain numbers.';
          toastPlacementExample.querySelector('.toast-title ').textContent = 'Error.';
          const toastPlacement = new bootstrap.Toast(toastPlacementExample);
          toastPlacement.show();
          return; // Stop further validation if Dependetents Number is invalid
        }
      }
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
});
