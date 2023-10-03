document.addEventListener('DOMContentLoaded', function () {
  const otpForm = document.getElementById('creditForm');
  const otpInputs = [
    document.getElementById('identificationNumber1'),
    document.getElementById('identificationNumber2'),
    document.getElementById('identificationNumber3'),
    document.getElementById('identificationNumber4'),
    document.getElementById('identificationNumber5'),
    document.getElementById('identificationNumber6')
  ];
  const errorToast = document.getElementById('errorToast');

  otpInputs.forEach((input, index) => {
    input.addEventListener('input', function (e) {
      if (e.target.value.trim() !== '' && index < otpInputs.length - 1) {
        otpInputs[index + 1].focus();
      }
    });

    input.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' && e.target.value.trim() === '' && index > 0) {
        otpInputs[index - 1].focus();
      }
    });
  });

  otpForm.addEventListener('submit', function (e) {
    e.preventDefault();
    let isValid = true;

    otpInputs.forEach(input => {
      if (input.value.trim() === '') {
        isValid = false;
        input.classList.add('is-invalid');
      } else {
        input.classList.remove('is-invalid');
      }
    });

    if (!isValid) {
      errorToast.style.display = 'block';
      errorToast.querySelector('.toast-body').textContent = 'Please fill in all OTP fields.';
    } else {
      errorToast.style.display = 'none';
      // Continue with your verification logic here
    }
  });
});
