// Function to fetch user data and populate the modal and AJAX data
new DataTable('#table-striped');
function showLoader() {
  $('#loader').fadeIn();
}

// Hide the loader when the response is received
function hideLoader() {
  $('#loader').fadeOut();
}
function validateForm() {
  // Add your form validation logic here
  var isValid = true;

  const fields = [
    {
      id: 'nameBackdrop',
      error: 'Name is required.'
    },
    {
      id: 'TIN_Number',
      error: 'TIN Number is required.'
    },
    {
      id: 'dobBackdrop',
      error: 'Date of Birth is required.'
    },
    {
      id: 'emailBackdrop',
      error: 'Email is required.'
    },
    {
      id: 'phoneBackdrop',
      error: 'Phone is required.'
    },
    {
      id: 'status',
      error: 'Status is Required'
    }
  ];

  // Clear previous toast error messages
  $('#error-toast .toast-body').empty();

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
      isValid = false;
      // Display an error message in the toast
      $('#error-toast .toast-body').append('<p>' + field.error + '</p>');
      showErrorMessage();
      break; // Stop further validation on the first empty field
    }

    if (field.id === 'TIN_Number') {
      // Check if TIN Number field contains only numbers
      if (!numberRegex.test(value) || value.length !== 10) {
        isValid = false;
        // Display an error message in the toast
        $('#error-toast .toast-body').append('<p>TIN Number must contain numbers and have a length of ten (0-9).</p>');
        showErrorMessage();
        break; // Stop further validation if TIN Number is invalid
      }
    }

    if (field.id === 'nameBackdrop') {
      // Check if Name field contains only alphabets
      if (!nameRegex.test(value)) {
        isValid = false;
        // Display an error message in the toast
        $('#error-toast .toast-body').append('<p>Name can only contain alphabets and spaces.</p>');
        showErrorMessage();
        break; // Stop further validation if Name is invalid
      }
    }

    if (field.id === 'phoneBackdrop') {
      // Check if Phone Number is valid
      if (!validPhoneRegex.test(value)) {
        isValid = false;
        // Display an error message in the toast
        $('#error-toast .toast-body').append('<p>Invalid Phone Number.</p>');
        showErrorMessage();
        break; // Stop further validation if Phone Number is invalid
      }
    }

    if (field.id === 'emailBackdrop') {
      // Check if Email is valid
      if (!validEmailRegex.test(value)) {
        isValid = false;
        // Display an error message in the toast
        $('#error-toast .toast-body').append('<p>Invalid Email Address.</p>');
        showErrorMessage();
        break; // Stop further validation if Email is invalid
      }
    }
    if (field.id === 'dobBackdrop') {
      // Check if Date of Birth is valid and indicates the user is at least 18 years old
      const dobDate = new Date(value);
      const todayDate = new Date();
      const eighteenYearsAgo = new Date(todayDate.getFullYear() - 18, todayDate.getMonth(), todayDate.getDate());

      if (isNaN(dobDate) || dobDate > eighteenYearsAgo) {
        isValid = false;
        // Display an error message in the toast
        $('#error-toast .toast-body').append('<p>Date of Birth must indicate you are at least 18 years old.</p>');
        showErrorMessage();
        break; // Stop further validation if Date of Birth is invalid
      }
    }
  }

  return isValid;
}

function showErrorMessage() {
  // Display the toast with error messages
  $('#error-toast').toast('show');
  hideLoader();
}
function editUser(userId) {
  showLoader();
  var csrfToken = document.getElementById('csrf-token').getAttribute('value');
  console.log(csrfToken);
 
  $.ajax({
    type: 'GET',
    url: 'get_user_data.php', // Create a PHP file to fetch user data
    data: {
      id: userId
    },
    headers: {
      'X-CSRF-Token': csrfToken // Send the CSRF token as a header
    },
    dataType: 'json',
    success: function (data) {
      hideLoader();
      // Populate the modal with user data
      $('#nameBackdrop').val(data.name);
      $('#emailBackdrop').val(data.email);
      $('#phoneBackdrop').val(data.phone);
      $('#dobBackdrop').val(data.dob);
      $('#TIN_Number').val(data.TIN_Number);
      $('#status').val(data.status);

      // Populate the AJAX data (for saving changes)
      $('#userIdToUpdate').val(data.id); // Assuming you have an input field with id="userIdToUpdate"

      // Set the modal title
      $('#backDropModalTitle').text('Edit User');

      // Show the modal
      $('#backDropModal').modal('show');
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    }
  });
}

var dataTable; // Declare the dataTable variable outside of any function

// Function to update the DataTable after saving a user
function updateDataTable(userId, name, email, TIN_Number, status, dob, phone) {
  var table = dataTable;

  // Find the row with the user's ID and update its data
  var row = table.row('#row-' + userId);

  var rowData = row.data();

  if (row.length > 0) {
    var badgeClass = '';
    if (status === 'active') {
      badgeClass = 'success';
    } else if (status === 'inactive') {
      badgeClass = 'danger';
    } else if (status === 'waiting') {
      badgeClass = 'info';
    } else {
      badgeClass = 'warning';
    }

    // Update only the properties that have changed
    rowData.id = userId;
    rowData.name = name;
    rowData.email = email;
    rowData.TIN_Number = TIN_Number;
    rowData.status = `<span class="badge bg-label-${badgeClass}">${status}</span>`;
    rowData.dob = dob;
    rowData.phone = phone;

    // Set the updated data back to the row
    row.data(rowData).draw(false);
  }
}

function saveUser() {
  var csrfToken = document.getElementById('csrf-token').getAttribute('value');
  showLoader();
  if (validateForm()) {
    var userId = $('#userIdToUpdate').val();
    var name = $('#nameBackdrop').val();
    var email = $('#emailBackdrop').val();
    var TIN_Number = $('#TIN_Number').val();
    var status = $('#status').val();
    var dob = $('#dobBackdrop').val();
    var phone = $('#phoneBackdrop').val();

    // Assuming you have an API endpoint for updating user data
    $.ajax({
      url: 'update_user.php', // Replace with your API endpoint
      type: 'POST',
      data: {
        id: userId,
        name: name,
        email: email,
        TIN_Number: TIN_Number,
        status: status,
        dob: dob,
        phone: phone
      },
      headers: {
        'X-CSRF-Token': csrfToken // Send the CSRF token as a header
      },
      dataType: 'json',
      success: function (data) {
        hideLoader();
        if (data.status === 'success') {
          // Show success message using SweetAlert
          $('#backDropModal').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'User updated successfully!'
          }).then(result => {
            if (result.isConfirmed) {
              // Update the DataTable with the new user data
              updateDataTable(userId, name, email, TIN_Number, status, dob, phone);
            }
          });
        } else {
          var errorContainer = $('#error-toast .toast-body');
          errorContainer.empty(); // Clear any previous errors
          console.log('AJAX request initiated');
          // Loop through the validation errors and display them in the toast
          if (data.errors) {
            $.each(data.errors, function (key, value) {
              errorContainer.append('<p>' + value + '</p>');
            });
          } else {
            errorContainer.append('<p>An error occurred on the server.</p>');
          }
          console.log('AJAX request initiated');

          // Display the error toast for frontend validation errors
          showErrorMessage();
        }
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  }
}
// Call this function once when the page is loaded to initialize DataTable
function initializeDataTable() {
  dataTable = $('#table-striped').DataTable({
    // DataTable configuration options
    // DataTable configuration options
    columns: [
      {
        data: 'id'
      }, // Map 'id' column from your data
      {
        data: 'profile'
      }, // Map 'profile' column from your data
      {
        data: 'name'
      }, // Map 'name' column from your data
      {
        data: 'phone'
      },
      {
        data: 'email'
      },
      {
        data: 'TIN_Number'
      },
      {
        data: 'dob'
      },
      {
        data: 'status'
      },
      {
        data: 'credit_limit'
      },
      {
        data: 'level'
      },
      {
        data: 'createdOn'
      },
      {
        data: 'user_id'
      },
      // Define columns for other data you have
      {
        // Define 'Actions' column
        data: null,
        render: function (data, type, row, meta) {
          return (
            '<div class="dropdown">' +
            '<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
            '<i class="bx bx-dots-vertical-rounded"></i>' +
            '</button>' +
            '<div class="dropdown-menu">' +
            '<a class="dropdown-item" href="javascript:void(0);" onclick="editUser(\'' +
            data.id +
            '\');"><i class="bx bx-edit-alt me-1"></i> Edit</a>' +
            '<a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>' +
            '</div>' +
            '</div>'
          );
        }
      }
    ],
    // ...
    destroy: true // This allows reinitialization of DataTable
  });
}
// Call the initializeDataTable() function once when the page is loaded
$(document).ready(function () {
  initializeDataTable();
});
