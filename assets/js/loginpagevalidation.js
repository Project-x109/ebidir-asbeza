// Function to create and show a toast notification
function showToast(message, type) {
  var toast = document.createElement('div');
  toast.className = 'bs-toast toast toast-placement-ex m-2 bg-danger top-0 end-0 bg-' + type;
  toast.setAttribute('role', 'alert');
  toast.setAttribute('aria-live', 'assertive');
  toast.setAttribute('aria-atomic', 'true');
  toast.setAttribute('data-delay', '2000');

  var toastHeader = document.createElement('div');
  toastHeader.className = 'toast-header';
  toastHeader.innerHTML =
    '<i class="bx bx-bell me-2"></i>' +
    '<div class="me-auto toast-title fw-semibold">' +
    (type == 'danger' ? 'Error' : 'Success') +
    '</div>' +
    '<small>Now</small>' +
    '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>';

  var toastBody = document.createElement('div');
  toastBody.className = 'toast-body';
  toastBody.textContent = message;

  toast.appendChild(toastHeader);
  toast.appendChild(toastBody);

  var container = document.getElementById('toast-container');
  container.appendChild(toast);

  var bsToast = new bootstrap.Toast(toast);
  bsToast.show();
}

// Modify your validateLoginForm function to show toast messages
function validateLoginForm() {
  var phone = document.getElementById('email').value;
  var password = document.getElementById('password').value;

  if (!isValidPhoneNumber(phone)) {
    showToast('Please enter a valid phone number.', 'danger');
    return false;
  }

  if (password.length < 3) {
    showToast('Password must be at least 6 characters long.', 'danger');
    return false;
  }

  return true;
}

// Define the isValidPhoneNumber function in the global scope
function isValidPhoneNumber(phone) {
  const validPhoneRegex = RegExp(
    /(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(0\s*9\s*(([0-9]\s*){8}))|(0\s*7\s*(([0-9]\s*){8}))/ // Your regex pattern
  );

  if (!validPhoneRegex.test(phone)) {
    showToast('Please enter a valid Ethiopian phone number.', 'danger');
    return false;
  }

  return true;
}

// Add an event listener to the form
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('formAuthentication').addEventListener('submit', function (event) {
    if (!validateLoginForm()) {
      event.preventDefault(); // Prevent form submission if validation fails
    }
  });
});

// Get references to the modal and close button
const modal = document.getElementById('modal');
const closeButton = document.getElementById('closeButton');

// Get a reference to the "Create an account" link
const createAccountLink = document.getElementById('createAccountLink');

// When the user clicks the link, show the modal
createAccountLink.addEventListener('click', () => {
  modal.style.display = 'block';
});

// When the user clicks the close button or outside the modal, hide the modal
closeButton.addEventListener('click', () => {
  modal.style.display = 'none';
});

window.addEventListener('click', event => {
  if (event.target === modal) {
    modal.style.display = 'none';
  }
});

// Function to save user credentials to localStorage
function saveUserCredentials(username, password) {
  localStorage.setItem('rememberedUsername', username);
  localStorage.setItem('rememberedPassword', password);
}

// Function to retrieve user credentials from localStorage
function getRememberedUserCredentials() {
  const rememberedUsername = localStorage.getItem('rememberedUsername');
  const rememberedPassword = localStorage.getItem('rememberedPassword');
  return { username: rememberedUsername, password: rememberedPassword };
}

// Function to clear user credentials from localStorage
function clearRememberedUserCredentials() {
  localStorage.removeItem('rememberedUsername');
  localStorage.removeItem('rememberedPassword');
}

// Modify your form submission handler
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formAuthentication');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const rememberMeCheckbox = document.getElementById('remember-me');

  // Load remembered credentials and populate the form fields
  const rememberedCredentials = getRememberedUserCredentials();
  if (rememberedCredentials.username && rememberedCredentials.password) {
    emailInput.value = rememberedCredentials.username;
    passwordInput.value = rememberedCredentials.password;
    rememberMeCheckbox.checked = true;
  }

  form.addEventListener('submit', function (event) {
    if (rememberMeCheckbox.checked) {
      // Save user credentials to localStorage
      saveUserCredentials(emailInput.value, passwordInput.value);
    } else {
      // Clear any previously remembered credentials
      clearRememberedUserCredentials();
    }
    // Continue with form submission
  });
});
