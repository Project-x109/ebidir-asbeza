document.addEventListener('DOMContentLoaded', function () {
  const creditForm = document.getElementById('creditForm');
  const identificationNumberInput = document.getElementById('identificationNumber');
  const errorToast = document.getElementById('errorToast');

  creditForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const identificationNumber = identificationNumberInput.value;

    // Perform your validation here
    if (!isValidIdentificationNumber(identificationNumber)) {
      // Display the error toast
      errorToast.style.display = 'block';

      // You can set a custom error message here
       errorToast.querySelector(".toast-body").textContent = "Invalid identification number.";

      // Hide the toast after a delay
      setTimeout(function () {
        errorToast.style.display = 'none';
      }, 2000);
    } else {
      // Submit the form if validation passes
      // creditForm.submit();
    }
  });

  function isValidIdentificationNumber(number) {
    // Implement your validation logic here
    // For example, check if the number is exactly 6 digits
    return number.length==6;
  }
});
