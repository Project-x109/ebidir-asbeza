// Dummy data
var dummyData = [
  {
    accountName: 'Amanuel Girma',
    id: 'eb01234442',
    loanAmount: '$2,200',
    paymentDate: 'Jan,23 2023',
    status: 'overdue',
    loanID: 'eb01234442',
    originalAmount: '$3,000',
    amountPaid: '$2,000'
  },
  {
    accountName: 'Amanuel Girma',
    id: 'eb01234442',
    loanAmount: '$2,200',
    paymentDate: 'Jan,23 2023',
    status: 'completed',
    loanID: 'eb01234442',
    originalAmount: '$3,000',
    amountPaid: '$2,000'
  },
  {
    accountName: 'Amanuel Girma',
    id: 'eb01234442',
    loanAmount: '$2,200',
    paymentDate: 'Jan,23 2023',
    status: 'overdue',
    loanID: 'eb01234442',
    originalAmount: '$3,000',
    amountPaid: '$2,000'
  },
  {
    accountName: 'Amanuel Girma',
    id: 'eb01234442',
    loanAmount: '$2,200',
    paymentDate: 'Jan,23 2023',
    status: 'scheduled',
    loanID: 'eb01234442',
    originalAmount: '$3,000',
    amountPaid: '$2,000'
  },
  {
    accountName: 'Amanuel Girma',
    id: 'eb01234442',
    loanAmount: '$2,200',
    paymentDate: 'Jan,23 2023',
    status: 'scheduled',
    loanID: 'eb01234442',
    originalAmount: '$3,000',
    amountPaid: '$2,000'
  },
  {
    accountName: 'Amanuel Girma',
    id: 'eb01234442',
    loanAmount: '$2,200',
    paymentDate: 'Jan,23 2023',
    status: 'completed',
    loanID: 'eb01234442',
    originalAmount: '$3,000',
    amountPaid: '$520,000'
  }

  // Add more data objects as needed
];

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
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalToggle"
                        data-bs-toggle="modal"
                        data-bs-target="#modalToggle"
                        onclick="populateModal('${data.loanID}', '${data.originalAmount}', '${data.paymentDate}', '${
      data.amountPaid
    }')"
                    >
                        See Detail
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
