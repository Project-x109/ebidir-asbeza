// Function to generate a random item
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
function getRandomItem() {
  const items = ['item1', 'item2', 'item3', 'item4', 'item5']; // Add more items as needed
  return items[getRandomNumber(0, items.length - 1)];
}

// Function to generate a random quantity for an item
function getRandomQuantity() {
  return getRandomNumber(1, 10); // Adjust the range as needed
}
// Array of random account names
const randomAccountNames = [
  'Amanuel Girma'

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

// Generate up to 100 records with cart data
const dummyDataWithCart = [];

for (let i = 0; i < 100; i++) {
  const record = {
    accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
    id: `eb0${getRandomNumber(1000000, 9999999)}`,
    loanAmount: `$${getRandomNumber(1000, 5000)}`,
    paymentDate: getRandomDate(2020, 2023),
    status: getRandomStatus(),
    loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
    originalAmount: `$${getRandomNumber(3000, 10000)}`,
    amountPaid: `$${getRandomNumber(0, 5000)}`,
    cartData: generateRandomCartData()
  };
  dummyDataWithCart.push(record);
}

console.log(dummyDataWithCart); // Display the data with cart information

// Function to populate the table with dummy data
function populateTable() {
  var tbody = document.querySelector('#table-striped tbody');

  dummyDataWithCart.forEach(function (data) {
    var row = document.createElement('tr');
    var main = JSON.stringify(data.cartData);
    console.log(main);
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
                        onclick="populateModal(
                          '${data.loanID}', 
                          '${data.originalAmount}', 
                          '${data.paymentDate}', 
                          '${data.amountPaid}',
                          ${data.cartData}  
                          )"
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
function populateModal(loanID, originalAmount, paymentDate, amountPaid, cartData) {
  var modalContent = document.getElementById('modalContent');
  // Create a string to hold the HTML representation of cart items
  var cartHTML = '';

  cartData.forEach(function (cartItem) {
    // Log each cart item
    console.log('Cart Item:', cartItem);

    cartHTML += `
        <div class="card mt-2">
          <div class="card-body">
            <p class="card-text"><strong>Item:</strong> ${cartItem.item}</p>
            <p class="card-text"><strong>Quantity:</strong> ${cartItem.quantity}</p>
            <p class="card-text"><strong>Price Per Item:</strong> ${cartItem.pricePerItem}</p>
            <p class="card-text"><strong>Total Price for Item:</strong> ${cartItem.totalPriceForItem}</p>
          </div>
        </div>
      `;
  });

  console.log(cartHTML);

  modalContent.innerHTML = `
        <p class="card-text"><strong>Loan ID:</strong> ${loanID}</p>
        <p class="card-text"><strong>Original Amount:</strong> ${originalAmount}</p>
        <p class="card-text"><strong>Payment Date:</strong> ${paymentDate}</p>
        <p class="card-text"><strong>Amount Paid:</strong> ${amountPaid}</p>
        <!-- Display cart items -->
        ${cartHTML}
  `;
}

// Call the function to populate the table
populateTable();
