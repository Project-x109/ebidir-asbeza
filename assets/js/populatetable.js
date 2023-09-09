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
            <td><span class="badge bg-label-${
              data.status === 'completed'
                ? 'success'
                : data.status === 'overdue'
                ? 'danger'
                : data.status === 'scheduled'
                ? 'info'
                : 'warning'
            } me-1">${data.status}</span></td>
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
