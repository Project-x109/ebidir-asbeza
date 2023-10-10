new DataTable('#table-striped');

var dataTable; // Declare the dataTable variable outside of any function
function showLoader() {
  $('#loader').fadeIn();
}

// Hide the loader when the response is received
function hideLoader() {
  $('#loader').fadeOut();
}
// Function to initialize the DataTable
function initializeDataTable() {
  dataTable = $('#table-striped').DataTable({
    // DataTable configuration options
    columns: [
      {
        data: 'id'
      }, // Map 'id' column from your data
      {
        data: 'branch_name'
      },
      {
        data: 'branch_id'
      },
      {
        data: 'location'
      },
      {
        data: 'email'
      },
      {
        data: 'phone'
      },
      {
        data: 'status'
      },
      {
        data: 'Detail'
      },
      {
        data: 'createdOn'
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

// Function to edit user data and display it in a modal
function editUser(userId) {
  showLoader();
  var csrfToken = document.getElementById('csrf-token').getAttribute('value');
  $.ajax({
    type: 'GET',
    url: 'get_user_data.php',
    data: {
      user_id: userId
    },
    headers: {
      'X-CSRF-Token': csrfToken // Send the CSRF token as a header
    },
    dataType: 'json',
    success: function (userData) {
      hideLoader();
      // Populate the modal with user data
      $('#branchnameBackdrop').val(userData.name);
      $('#emailBackdrop').val(userData.email);
      $('#phoneBackdrop').val(userData.phone);
      $('#status').val(userData.status);
      
      // Fetch branch data using user_id
      $.ajax({
        type: 'GET',
        url: 'get_branch_data.php',
        data: {
          user_id: userData.user_id
        },
        headers: {
          'X-CSRF-Token': csrfToken // Send the CSRF token as a header
        },
        dataType: 'json',
        success: function (branchData) {
          $('#locationBackdrop').val(branchData.location);

          // Populate the AJAX data (for saving changes)
          $('#userIdToUpdate').val(userData.user_id);
          $('#userIdToUpdatebranch').val(branchData.branch_id);

          // Set the modal title
          $('#backDropModalTitle').text('Edit User');

          // Show the modal
          $('#backDropModal').modal('show');
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    }
  });
}
// Function to update the DataTable after saving a user
function updateDataTable(userId, name, email, status, phone, location) {
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

    // Update all the properties
    rowData.id = userId;
    rowData.branch_name = name;
    rowData.email = email;
    rowData.status = `<span class="badge bg-label-${badgeClass}">${status}</span>`;
    rowData.phone = phone;
    rowData.location = location; // Update location

    // Set the updated data back to the row
    row.data(rowData).draw(false);
  }
}

function validateForm() {
  const errors = []; // Array to store error messages

  // An array of field IDs and their corresponding error messages
  const fields = [
    { id: 'branchnameBackdrop', error: 'Branch Name is required.' },
    { id: 'emailBackdrop', error: 'Email is required.' },
    { id: 'phoneBackdrop', error: 'Phone is required.' },
    { id: 'locationBackdrop', error: 'Location is required.' },
    { id: 'status', error: 'Status is required.' }
  ];

  const nameRegex = /^[A-Za-z\s]+$/;
  const validPhoneRegex = RegExp(
    /(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(\+\s*2\s*5\s*1\s*9\s*(([0-9]\s*){8}\s*))|(0\s*9\s*(([0-9]\s*){8}))|(0\s*7\s*(([0-9]\s*){8}))/i
  );
  const validEmailRegex = RegExp(
    /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i
  );

  // Iterate through the fields and check their values
  for (const field of fields) {
    const input = document.getElementById(field.id);
    const value = input.value.trim();

    if (value === '') {
      errors.push(field.error); // Add error message to the array
    } else if (field.id === 'branchnameBackdrop' && !nameRegex.test(value)) {
      errors.push('Name can only contain alphabets and spaces.'); // Add specific error message
    } else if (field.id === 'phoneBackdrop' && !validPhoneRegex.test(value)) {
      errors.push('Invalid Phone Number.'); // Add specific error message
    } else if (field.id === 'emailBackdrop' && !validEmailRegex.test(value)) {
      errors.push('Invalid Email Address.'); // Add specific error message
    }
  }

  // Check if there are validation errors
  if (errors.length > 0) {
    showErrorToast(errors); // Display error messages in the toast
    return false; // Form is not valid
  }

  return true; // Form is valid
}

function showErrorToast(errorMessages) {
  $('#error-toast').toast('show');
  var errorContainer = $('#error-toast .toast-body');
  errorContainer.empty(); // Clear any previous errors

  // Create an unordered list to display errors
  var errorList = $('<ul></ul>');

  // Iterate through the error messages and append them as list items
  errorMessages.forEach(function (errorMessage) {
    errorList.append('<li>' + errorMessage + '</li>');
  });

  // Append the list of errors to the error container
  errorContainer.append(errorList);
}
function saveUser() {
  showLoader();
  var csrfToken = document.getElementById('csrf-token').getAttribute('value');
  if (validateForm()) {
    var userId = $('#userIdToUpdate').val();
    var branchId = $('#userIdToUpdatebranch').val();
    var name = $('#branchnameBackdrop').val();
    var email = $('#emailBackdrop').val();
    var status = $('#status').val();
    var phone = $('#phoneBackdrop').val();
    var location = $('#locationBackdrop').val();
    // Assuming you have API endpoints for updating user and branch data
    $.ajax({
      url: 'bupdate_branch_user.php', // Replace with your user data update API endpoint
      type: 'POST',
      data: {
        id: userId,
        name: name,
        email: email,
        status: status,
        phone: phone
      },
      headers: {
        'X-CSRF-Token': csrfToken // Send the CSRF token as a header
      },
      dataType: 'json',
      success: function (userData) {
        hideLoader();
        if (userData.status === 'success') {
          // User data updated successfully, now update branch data
          $.ajax({
            url: 'update_branch.php', // Replace with your branch data update API endpoint
            type: 'POST',
            data: {
              branch_id: branchId, // Use branch_id as the identifier
              location: location
            },
            headers: {
              'X-CSRF-Token': csrfToken // Send the CSRF token as a header
            },
            dataType: 'json',
            success: function (branchData) {
              if (branchData.status === 'success') {
                $('#backDropModal').modal('hide');
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'User and branch data updated successfully!'
                }).then(result => {
                  if (result.isConfirmed) {
                    // Update the DataTable with the new user data
                    updateDataTable(userId, name, email, status, phone, location);
                  }
                });
              } else {
                // Branch data update failed, display an error message
                showErrorToast(branchData.errors); // Display the specific error message
              }
            },
            error: function (xhr, status, error) {
              showErrorToast('Error updating branch data: ' + error); // Display the generic error message
            }
          });
        } else {
          // User data update failed, display an error message
          showErrorToast(userData.errors); // Display the specific error message
        }
      },
      error: function (xhr, status, error) {
        showErrorToast('Error updating user data: ' + error); // Display the generic error message
      }
    });
  }
}

// Update the "Save" button's click event to call saveUser()
$('#saveButton').click(function () {
  saveUser();
});
