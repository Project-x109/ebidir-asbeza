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
    const statuses = ['pending', 'active', 'closed', 'defaulted'];
    return statuses[getRandomNumber(0, 3)];
  }

  // Array of random account names
  const randomAccountNames = [
    'Amanuel Girma',
    // Add more names as needed
  ];
  const randomBranchNames = [
    'Purposeblack ETH',
    // Add more names as needed
  ];
  for (let i = 0; i < 100; i++) {
    const record = {
      accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
      branchName: randomBranchNames[getRandomNumber(0, randomBranchNames.length - 1)],
      id: `eb0${getRandomNumber(1000000, 9999999)}`,
      loanAmount: `${getRandomNumber(1000, 5000).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD'
      })}`,
      amountdue: `${getRandomNumber(1000, 5000).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD'
      })}`,
      actualamountpaid: `${getRandomNumber(1000, 5000).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD'
      })}`,
      requesteddate: getRandomDate(2020, 2023),
      duedate: getRandomDate(2020, 2023),
      status: getRandomStatus(),
      loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
      originalAmount: `$${getRandomNumber(3000, 10000)}`
    };
    dummyDataLoan.push(record);
  }
  console.log(dummyDataLoan);
  // Function to populate the MUI DataTable with dummy data

  function populateTable() {
    let table = new DataTable('#table-striped', {
      columns: [
        { title: 'User Name' },
        { title: 'Branch Name' },
        { title: 'User ID' },
        { title: 'Loan ID' },
        { title: 'Loan Amount' },
        { title: 'Due Date' },
        { title: 'Amount Due' },
        { title: 'Actual Amount paid' },
        { title: 'Date Paid' },
        { title: 'Current Status' }
      ]
    });
    function renderStatus(data, type, full, meta) {
      return `<span class="badge bg-label-${getStatusBadgeClass(data)}">${data}</span>`;
    }

    for (let i = 0; i < dummyDataLoan.length; i++) {
      const data = dummyDataLoan[i];
      const rowData = [
        data.accountName,
        data.branchName,
        data.id,
        data.loanID,
        data.loanAmount,
        data.duedate,
        data.amountdue,
        data.actualamountpaid,
        data.requesteddate,
        `<span class="badge bg-label-${getStatusBadgeClass(data.status)}">${data.status}</span>`,
        data.status
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
    case 'closed':
      return 'success';
    case 'defaulted':
      return 'danger';
    case 'active':
      return 'primary';
    default:
      return 'warning';
  }
}
