// Function to generate a random number between min and max (inclusive)
function getRandomNumber(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Function to generate a random date within a range of years
function getRandomDate(startYear, endYear) {
  const year = getRandomNumber(startYear, endYear);
  const month = getRandomNumber(1, 12);
  const day = getRandomNumber(1, 28); // Assuming all months have up to 28 days
  return `${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}-${year}`;
}

// Function to generate random status
function getRandomStatus() {
  const statuses = ['overdue', 'completed', 'scheduled'];
  return statuses[getRandomNumber(0, 2)];
}

// Array of random account names
const randomAccountNames = [
  'Amanuel Girma',
  'John Doe',
  'Jane Smith',
  'Alice Johnson',
  'Bob Wilson'
  // Add more names as needed
];

// Generate up to 100 records
const dummyData = [];

for (let i = 0; i < 100; i++) {
  const record = {
    accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
    id: `eb0${getRandomNumber(1000000, 9999999)}`,
    loanAmount: `$${getRandomNumber(1000, 5000)}`,
    paymentDate: getRandomDate(2020, 2023),
    status: getRandomStatus(),
    loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
    originalAmount: `$${getRandomNumber(3000, 10000)}`,
    amountPaid: `$${getRandomNumber(0, 5000)}`
  };
  dummyData.push(record);
}

// Function to populate the table with dummy data
function populateTable() {
  var tbody = document.querySelector('#table-striped tbody');

  dummyData.forEach(function (data) {
    var row = document.createElement('tr');

    row.innerHTML = `
              <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>${data.accountName}</strong></td>
              <td>${data.id}</td>
              <td>${data.loanAmount}</td>
              <td>${data.paymentDate}</td>
              <td id="statusCell-${data.loanID}">
                <span class="badge bg-label-${
                  data.status === 'completed'
                    ? 'success'
                    : data.status === 'overdue'
                    ? 'danger'
                    : data.status === 'scheduled'
                    ? 'info'
                    : 'warning'
                } me-1">${data.status}</span>
              </td>
              <td>
                  <!-- Toggle Between Modals -->
                  <div class="mt-1">
                      <button
                          type="button"
                          class="btn rounded-pill btn-icon btn-outline-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#modalToggle"
                          data-bs-toggle="modal"
                          data-bs-target="#modalToggle"
                          onclick="populateModal('${data.loanID}', '${data.originalAmount}', '${data.paymentDate}', '${
      data.amountPaid
    }')"
                      >
                      <i class='bx bx-link-external'></i>
                      </button>
                  </div>
              </td>
              <td>
                <button
                    type="button"
                    class="btn btn-icon btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#statusModal"
                    onclick="updateStatus('${data.loanID}')"
                >
                <i class='bx bx-transfer'></i>
                </button>
              </td>
              
              <td>
                  <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                          <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                          <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                      </div>
                  </div>
              </td>
          `;

    tbody.appendChild(row);
  });
}

// Function to populate the modal with dynamic data
function populateModal(loanID, originalAmount, paymentDate, amountPaid) {
  var modalContent = document.getElementById('modalContent');
  modalContent.innerHTML = `
          <p class="card-text"><strong>Loan ID:</strong> ${loanID}</p>
          <p class="card-text"><strong>Original Amount:</strong> ${originalAmount}</p>
          <p class="card-text"><strong>Payment Date:</strong> ${paymentDate}</p>
          <p class="card-text"><strong>Amount Paid:</strong> ${amountPaid}</p>
      `;
}

// Call the function to populate the table
populateTable();

// Function to render the rows for the status update modal
function renderStatusModalRows() {
  const modalBody = document.querySelector('#statusModal .modal-body');

  const statusRows = [
    { label: 'Completed', value: 'completed' },
    { label: 'Overdue', value: 'overdue' },
    { label: 'Scheduled', value: 'scheduled' }
  ];

  modalBody.innerHTML = '';

  statusRows.forEach(row => {
    modalBody.innerHTML += `
      <div class="form-check form-check-inline">
        <input
          class="form-check-input"
          type="radio"
          name="statusRadio"
          id="${row.value}Radio"
          value="${row.value}"
        >
        <label class="form-check-label" for="${row.value}Radio">
          ${row.label}
        </label>
      </div>
    `;
  });
}

// Call the function to render the status modal rows
renderStatusModalRows();

function updateStatus(loanID) {
  // Get the modal's status radio buttons
  const statusRadioButtons = document.querySelectorAll('input[name="statusRadio"]');

  // Find the record with the specified loan ID in your dummy data
  const record = dummyData.find(data => data.loanID === loanID);
  if (record) {
    // Set the checked property based on the record's status
    statusRadioButtons.forEach(radio => {
      radio.checked = radio.value === record.status;
    });

    // Set the loanID in the hidden input field
    document.getElementById('loanID').value = loanID;
  }
}

function saveStatus() {
  // Get the selected status value from the radio buttons
  const selectedStatus = document.querySelector('input[name="statusRadio"]:checked');

  if (!selectedStatus) {
    alert('Please select a status.');
    return;
  }

  const newStatus = selectedStatus.value;

  // Find the record with the specified loan ID in your dummy data
  const loanID = document.getElementById('loanID').value;
  const record = dummyData.find(data => data.loanID === loanID);
  if (record) {
    // Update the status in the record
    record.status = newStatus;

    // Update the status cell in the table
    const statusCell = document.getElementById(`statusCell-${record.loanID}`);
    statusCell.innerHTML = `
      <span class="badge bg-label-${
        newStatus === 'completed'
          ? 'success'
          : newStatus === 'overdue'
          ? 'danger'
          : newStatus === 'scheduled'
          ? 'info'
          : 'warning'
      } me-1">${newStatus}</span>
    `;
  }

  // Close the status update modal
  const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
  statusModal.hide();

  // Display a success message or perform any other action as needed
  alert(`Status updated to: ${newStatus}`);
}
