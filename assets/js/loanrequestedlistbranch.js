const dummyDataLoan = [];
document.addEventListener('DOMContentLoaded', function () {
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
    const statuses = ['declined', 'approved', 'pending'];
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
  const randomBranchNames = [
    'Purposeblack ETH',
    'Purposeblack ETH2',
    'Purposeblack ETH3',
    'Purposeblack ETH4',
    'Purposeblack ETH5'
    // Add more names as needed
  ];
  function generateRandomEmail() {
    const emailProviders = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'example.com'];
    const randomProvider = emailProviders[Math.floor(Math.random() * emailProviders.length)];
    const randomUsername = Math.random().toString(36).substring(7); // Generate a random string for the username part
    const email = `${randomUsername}@${randomProvider}`;
    return email;
  }
  function generateRandomPhoneNumber() {
    const countryCode = '+1'; // Change this to your desired country code
    const areaCode = Math.floor(Math.random() * 1000)
      .toString()
      .padStart(3, '0');
    const firstPart = Math.floor(Math.random() * 1000)
      .toString()
      .padStart(3, '0');
    const secondPart = Math.floor(Math.random() * 10000)
      .toString()
      .padStart(4, '0');
    const phoneNumber = `${countryCode} ${areaCode}-${firstPart}-${secondPart}`;
    return phoneNumber;
  }

  // Generate up to 100 records

  const creditLimits = [
    0, 100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000, 9000, 10000
  ];
  const marriageStatuses = ['Single', 'Married', 'Divorced'];
  const education = ['Below Highschool', 'Diploma', 'Degree', 'Masters', 'Phd'];
  const jobStatuses = ['Employed', 'Unemployed', 'Self Employed'];
  for (let i = 0; i < 100; i++) {
    const record = {
      accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
      id: `eb0${getRandomNumber(1000000, 9999999)}`,
      age: getRandomNumber(18, 120),
      creditscore: getRandomNumber(90, 500),
      creditlimit: creditLimits[getRandomNumber(0, creditLimits.length - 1)],
      tinNumber: getRandomNumber(100000000, 2000000000),
      loanAmount: `${getRandomNumber(1000, 5000).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD'
      })}`,
      requesteddate: getRandomDate(2020, 2023),
      status: getRandomStatus(),
      email: generateRandomEmail(),
      phoneNo: generateRandomPhoneNumber(),
      loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
      originalAmount: `$${getRandomNumber(3000, 10000)}`,
      numberOfDependents: `${getRandomNumber(0, 20)}`,
      criminalRecords: `${getRandomNumber(0, 20)}`,
      marriageStatus: marriageStatuses[getRandomNumber(0, marriageStatuses.length - 1)],
      jobStatus: jobStatuses[getRandomNumber(0, jobStatuses.length - 1)],
      educationalStatus: education[getRandomNumber(0, education.length - 1)]
    };
    dummyDataLoan.push(record);
  }


  // Function to populate the MUI DataTable with dummy data

  function populateTable() {
    let table = new DataTable('#table-striped', {
      columns: [
        { title: 'User Name' },
        { title: 'User ID' },
        { title: 'Credit Limit' },
        { title: 'Credit Score' },
        { title: 'Age' },
        { title: 'TIN Number' },
        { title: 'Loan Amount' },
        { title: 'Status'},
        { title: 'Requsted Date' },
        { title: 'Email' },
        { title: 'Phone Number' },
        { title: 'Details' },
        { title: 'Update Status' },
        { title: 'Actions' }
      ]
    });
    function renderStatus(data, type, full, meta) {
      return `<span class="badge bg-label-${getStatusBadgeClass(data)}">${data}</span>`;
    }

    for (let i = 0; i < dummyDataLoan.length; i++) {
      const data = dummyDataLoan[i];
      const rowData = [
        data.accountName,
        data.id,
        data.creditlimit,
        data.creditscore,
        data.age,
        data.tinNumber,
        data.loanAmount,
        `<span class="badge bg-label-${getStatusBadgeClass(data.status)}">${data.status}</span>`,
        data.requesteddate,
        data.email,
        data.phoneNo,
        `<button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalToggle" onclick="populateModal('${data.loanID}', '${data.originalAmount}', '${data.requesteddate}', '${data.jobStatus}','${data.numberOfDependents}','${data.criminalRecords}','${data.marriageStatus}','${data.educationalStatus}')"><i class='bx bx-link-external'></i></button>`,
        `<button type="button" class="btn btn-icon btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal" data-loan-id="${data.loanID}" onclick="updateStatus('${data.loanID}')"><i class='bx bx-transfer'></i></button>`,
        `<div class="dropdown"><button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button><div class="dropdown-menu"><a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a><a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a></div></div>`
      ];

      // Add the row to the table
      table.row.add(rowData);

      // Add a click event listener to the row to handle row clicks
      table.rows().every(function () {
        if (this.data().id === data.id) {
          this.nodes().to$().attr('data-loan-id', data.loanID); // Add data-loan-id attribute
          this.nodes()
            .to$()
            .on('click', function () {
              // Retrieve the Loan ID from the clicked row
              const loanID = $(this).attr('data-loan-id');
              updateStatus(loanID);
            });
        }
      });
    }

    // Redraw the table to ensure all rows are displayed
    table.draw();
  }

  // Call the function to populate the MUI DataTable
  populateTable();
});

function getStatusBadgeClass(status) {
  switch (status) {
    case 'approved':
      return 'success';
    case 'declined':
      return 'danger';
    default:
      return 'warning';
  }
}
// Function to populate the modal with dynamic data
function populateModal(
  loanID,
  originalAmount,
  requesteddate,
  jobStatus,
  numberOfDependents,
  criminalRecords,
  marriageStatus,
  educationalStatus
) {
  var modalContent = document.getElementById('modalContent');
  modalContent.innerHTML = `
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="loanID" class="form-label">Loan ID</label>
            <input type="text" class="form-control" id="loanID" value="${loanID}" readonly>
          </div>
          <div class="mb-3">
            <label for="originalAmount" class="form-label">Original Amount</label>
            <input type="text" class="form-control" id="originalAmount" value="${originalAmount}" readonly>
          </div>
          <div class="mb-3">
            <label for="requesteddate" class="form-label">Requested Date</label>
            <input type="text" class="form-control" id="requesteddate" value="${requesteddate}" readonly>
          </div>
          <div class="mb-3">
            <label for="jobStatus" class="form-label">Job Status</label>
            <input type="text" class="form-control" id="jobStatus" value="${jobStatus}" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="numberOfDependents" class="form-label">Number of Dependents</label>
            <input type="text" class="form-control" id="numberOfDependents" value="${numberOfDependents}" readonly>
          </div>
          <div class="mb-3">
            <label for="criminalRecords" class="form-label">Criminal Records</label>
            <input type="text" class="form-control" id="criminalRecords" value="${criminalRecords}" readonly>
          </div>
          <div class="mb-3">
            <label for="marriageStatus" class="form-label">Marriage Status</label>
            <input type="text" class="form-control" id="marriageStatus" value="${marriageStatus}" readonly>
          </div>
          <div class="mb-3">
            <label for="educationalStatus" class="form-label">Educational Status</label>
            <input type="text" class="form-control" id="educationalStatus" value="${educationalStatus}" readonly>
          </div>
        </div>
      </div>
    `;
}

function updateStatus(loanID) {

  // Get the selected status from the radio buttons
  const selectedStatus = $("input[name='statusRadio']:checked").val();

  // Update the status in the dummyDataLoan array
  const loan = dummyDataLoan.find(item => item.loanID === loanID);

  if (loan) {
    loan.status = selectedStatus;

    // Update the DataTable cell with the new status
    const table = $('#table-striped').DataTable();
    const row = table.row(`[data-loan-id="${loanID}"]`);
    if (row) {
      row.data()[8] = `<span class="badge bg-label-${getStatusBadgeClass(selectedStatus)}">${selectedStatus}</span>`;
      row.invalidate(); // Mark the row as needing a redraw
      row.draw(); // Redraw the row
    } else {
      console.log('Row not found in DataTable.');
    }
  } else {
    console.log('Loan object not found in dummyDataLoan.');
  }

  // Close the status modal
  $('#statusModal').modal('hide');
}


// Populate the radio buttons in the status modal with the current status
$('#statusModal').on('show.bs.modal', function (event) {
  const button = $(event.relatedTarget); // Button that triggered the modal
  const loanID = button.data('loan-id'); // Extract loan ID from data-loan-id attribute
  // Find the loan with the given loanID
  const loan = dummyDataLoan.find(item => item.loanID === loanID);
  if (loan) {
    // Set the selected status based on the loan's status
    const selectedStatus = loan.status;

    // Populate the radio buttons with the selected status
    const statusRadioContainer = $('#statusRadioContainer');
    statusRadioContainer.empty();

    // Define the possible status options
    const statusOptions = ['approved', 'declined', 'pending'];

    // Create radio buttons for each status option
    statusOptions.forEach(statusOption => {
      const radioInput = `<div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="statusRadio" id="${statusOption}" value="${statusOption}" ${
        statusOption === selectedStatus ? 'checked' : ''
      }>
        <label class="form-check-label" for="${statusOption}">${statusOption}</label>
      </div>`;
      statusRadioContainer.append(radioInput);
    });

    // Set the loanID in a hidden input for later use
    $('#loanID').val(loanID);
  }
});

