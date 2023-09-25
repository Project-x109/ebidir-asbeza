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

function getRandomItem() {
  const items = ['item1', 'item2', 'item3', 'item4', 'item5']; // Add more items as needed
  return items[getRandomNumber(0, items.length - 1)];
}

// Function to generate a random quantity for an item
function getRandomQuantity() {
  return getRandomNumber(1, 10); // Adjust the range as needed
}

// Array of random account names
const randomAccountNamesAll = [
  'Amanuel Girma',
  'Sophia Johnson',
  'Elijah Williams',
  'Olivia Smith',
  'Liam Davis',
  'Emma Brown',
  'Noah Wilson',
  'Ava Jones',
  'Mason Lee',
  'Isabella Martinez'
  // Add more names as needed
];

// Function to generate random cart data
function generateRandomCartData() {
  const cartData = [];
  const numberOfItems = getRandomNumber(1, 10); // Adjust the range as needed

  for (let i = 0; i < numberOfItems; i++) {
    const item = getRandomItem();
    const quantity = getRandomQuantity();
    const pricePerItem = getRandomNumber(5, 50); // Adjust the price range as needed
    const totalPriceForItem = quantity * pricePerItem;

    const cartItem = {
      item,
      quantity,
      pricePerItem: `$${pricePerItem.toFixed(2)}`,
      totalPriceForItem: `$${totalPriceForItem.toFixed(2)}`
    };

    cartData.push(cartItem);
  }

  return cartData;
}

// Function to calculate totalSpent from cartData
function calculateTotalSpent(cartData) {
  let total = 0;
  cartData.forEach(cartItem => {
    total += parseFloat(cartItem.totalPriceForItem.replace('$', ''));
  });
  return `$${total.toFixed(2)}`;
}
const creditLimitsAll = [
  0, 100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000, 9000, 10000
];
// Generate up to 100 records with cart data
const dummyData = [];

// Function to calculate credit repayment date (one month ahead)
function calculateCreditRepaymentDate(paymentDate) {
  const [month, day, year] = paymentDate.split('-').map(Number);
  const nextMonth = month === 12 ? 1 : month + 1;
  const nextYear = month === 12 ? year + 1 : year;
  return `${nextMonth.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}-${nextYear}`;
}
for (let i = 0; i < 10; i++) {
  // Calculate loanAmount ensuring loanAmount >= 100
  let loanAmount;
  do {
    loanAmount = creditLimitsAll[getRandomNumber(0, creditLimitsAll.length - 1)]; // Adjust the loanAmount range as needed
  } while (loanAmount < 100);

  let remainingLoan = loanAmount;
  const cartData = generateRandomCartData();
  let totalSpent = 0;

  // Ensure totalSpent <= loanAmount
  for (let j = 0; j < cartData.length; j++) {
    const cartItem = cartData[j];
    const itemTotal = parseFloat(cartItem.totalPriceForItem.replace('$', ''));

    if (totalSpent + itemTotal <= loanAmount) {
      totalSpent += itemTotal;
    } else {
      cartItem.quantity = Math.floor((loanAmount - totalSpent) / parseFloat(cartItem.pricePerItem.replace('$', '')));
      cartItem.totalPriceForItem = `$${(cartItem.quantity * parseFloat(cartItem.pricePerItem.replace('$', ''))).toFixed(
        2
      )}`;
      totalSpent = loanAmount;
      break;
    }
  }
  const paymentDate = getRandomDate(2020, 2023);
  // Calculate creditLeft as loanAmount - totalSpent
  const creditLeft = `$${(loanAmount - totalSpent).toFixed(2)}`;

  const record = {
    accountName: randomAccountNamesAll[getRandomNumber(0, randomAccountNamesAll.length - 1)],
    id: `eb0${getRandomNumber(1000000, 9999999)}`,
    loanAmount: `$${loanAmount.toFixed(2)}`,
    totalSpent: `$${totalSpent.toFixed(2)}`,
    creditLeft: creditLeft,
    paymentDate: paymentDate,
    creditrepaymentdate: calculateCreditRepaymentDate(paymentDate), // Calculate credit repayment date
    status: getRandomStatus(),
    loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
    originalAmount: `$${getRandomNumber(3000, 10000)}`,
    amountPaid: `$${getRandomNumber(0, 5000)}`,
    cartData: cartData
  };
  dummyData.push(record);
}

// Function to populate the table with dummy data
function populateTable() {
  var tbody = document.querySelector('#table-striped tbody');

  dummyData.forEach(function (data, index) {
    var row = document.createElement('tr');

    row.innerHTML = `
              <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>${data.accountName}</strong></td>
              <td>${data.id}</td>
              <td>${data.loanAmount}</td>
              <td>${data.totalSpent}</td>
              <td>${data.creditLeft}</td>
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
                          onclick="populateModal(${index})"
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
              <a href="transactionrecordDetail.php" class="menu-link" onclick="showDetails(${index})">
                  <div data-i18n="Without menu">Details</div>
              </a>
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
function populateModal(index) {
  var modalContent = document.getElementById('modalContent');
  const data = dummyData[index];
  modalContent.innerHTML = `
          <p class="card-text"><strong>Loan ID:</strong> ${data.loanID}</p>
          <p class="card-text"><strong>Original Amount:</strong> ${data.originalAmount}</p>
          <p class="card-text"><strong>Payment Date:</strong> ${data.paymentDate}</p>
          <p class="card-text"><strong>Amount Paid:</strong> ${data.amountPaid}</p>
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


function showDetails(index) {
  // Get the selected record's data from the `dummyData` array
  const selectedRecord = dummyData[index];
  // Convert the selected record to a JSON string
  const selectedRecordJSON = JSON.stringify(selectedRecord);

  // Store the selected record data in localStorage
  localStorage.setItem('selectedRecord', selectedRecordJSON);
}

