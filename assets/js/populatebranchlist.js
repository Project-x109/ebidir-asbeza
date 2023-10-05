new DataTable('#table-striped');

var dataTable; // Declare the dataTable variable outside of any function

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
            '<a class="dropdown-item" href="javascript:void(0);" onclick="editUser(' +
            data.id +
            ');"><i class="bx bx-edit-alt me-1"></i> Edit</a>' +
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
function showErrorMessage() {
  // Display the toast with error messages
  $('#error-toast').toast('show');
}
// Function to edit user data and display it in a modal
function editUser(userId) {
  $.ajax({
    type: 'GET',
    url: 'get_user_data.php',
    data: {
      id: userId
    },
    dataType: 'json',
    success: function (userData) {
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
        dataType: 'json',
        success: function (branchData) {
          $('#locationBackdrop').val(branchData.location);

          // Populate the AJAX data (for saving changes)
          $('#userIdToUpdate').val(userData.id);

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

function saveUser() {
  var userId = $('#userIdToUpdate').val();
  var name = $('#branchnameBackdrop').val();
  var email = $('#emailBackdrop').val();
  var status = $('#status').val();
  var phone = $('#phoneBackdrop').val();
  var location = $('#locationBackdrop').val();

  // Log the data before making the AJAX request
  console.log('Data before AJAX request:');
  console.log('userId:', userId);
  console.log('name:', name);
  console.log('email:', email);
  console.log('status:', status);
  console.log('phone:', phone);
  console.log('location:', location);

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
    dataType: 'json',
    success: function (userData) {
      // Log the response from the user data update API
      console.log('Response from User API:', userData);

      if (userData.status === 'success') {
        // User data updated successfully, now update branch data
        $.ajax({
          url: 'update_branch.php', // Replace with your branch data update API endpoint
          type: 'POST',
          data: {
            branch_id: userId, // Use branch_id as the identifier
            location: location
          },
          dataType: 'json',
          success: function (branchData) {
            // Log the response from the branch data update API
            console.log('Response from Branch API:', branchData);

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
              showErrorToast('Error updating branch data.');
            }
          },
          error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.log('Response Text:', xhr.responseText);
            showErrorToast('Error updating branch data.');
          }
        });
      } else {
        // User data update failed, display an error message
        showErrorToast('Error updating user data.');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX Error:', status, error);
      console.log('Response Text:', xhr.responseText);
      showErrorToast('Error updating user data.');
    }
  });
}

// Update the "Save" button's click event to call saveUser()
$('#saveButton').click(function () {
  saveUser();
});

// Helper function to show error toast
function showErrorToast(errorMessage) {
  var errorContainer = $('#error-toast .toast-body');
  errorContainer.empty(); // Clear any previous errors
  errorContainer.append('<p>' + errorMessage + '</p>');

  // Display the error toast
  showErrorMessage();
}

// ... (previous code)
