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
  const statuses = ['active', 'inactive', 'waiting'];
  return statuses[getRandomNumber(0, 2)];
}
function getJobStatus() {
  const statuses = ['Employed', 'Unemployed', 'Self Employed'];
  return statuses[getRandomNumber(0, 2)];
}

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
// Array of random account names
const randomAccountNames = [
  'Amanuel Girma',
  'John Doe',
  'Jane Smith',
  'Alice Johnson',
  'Bob Wilson'
  // Add more names as needed
];
const randombranchname = [
  'Purposeblack ETH',
  'Purposeblack ETH2',
  'Purposeblack ETH3',
  'Purposeblack ETH4',
  'Purposeblack ETH5'
  // Add more names as needed
];
function getRandomItem() {
  const items = ['item1', 'item2', 'item3', 'item4', 'item5']; // Add more items as needed
  return items[getRandomNumber(0, items.length - 1)];
}
function getRandomQuantity() {
  return getRandomNumber(1, 10); // Adjust the range as needed
}

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

// Function to generate a random image URL
function getRandomImageUrl() {
  // Replace this with an array of image URLs or an API call to fetch random images.
  const imageUrls = [
    'https://th.bing.com/th/id/R.8e789e42f2f50ed4bc0c420c1c65d0f0?rik=uHrS11DPo4NbKg&pid=ImgRaw&r=0',
    'https://d2qp0siotla746.cloudfront.net/img/use-cases/profile-picture/template_3.jpg',
    'https://images.statusfacebook.com/profile_pictures/Awesome/Awesome_profile_picture2.jpg'
    // Add more image URLs as needed
  ];
  const randomImageUrl = imageUrls[Math.floor(Math.random() * imageUrls.length)];
  return randomImageUrl;
}

// Generate up to 100 records
const dummyData = [];

// Function to calculate credit repayment date (one month ahead)
function calculateCreditRepaymentDate(paymentDate) {
  const [month, day, year] = paymentDate.split('-').map(Number);
  const nextMonth = month === 12 ? 1 : month + 1;
  const nextYear = month === 12 ? year + 1 : year;
  return `${nextMonth.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}-${nextYear}`;
}

for (let i = 0; i < 100; i++) {
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
    accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
    branchname:randombranchname[getRandomNumber(0,randombranchname.length-1)],
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
    cartData: cartData,
    jobStatus: getJobStatus(),
    email: generateRandomEmail(),
    phone: generateRandomPhoneNumber(),
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
              <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>${data.branchname}</strong></td>
              <td>${data.id}</td>
              <td>${data.loanAmount}</td>
              <td>${data.paymentDate}</td>
              <td><span class="badge bg-label-${
                data.status === 'active'
                  ? 'success'
                  : data.status === 'inactive'
                  ? 'danger'
                  : data.status === 'waiting'
                  ? 'info'
                  : 'warning'
              } me-1">${data.status}</span></td>
              
              <td>${data.jobStatus}</td>
              <td>${data.email}</td>
              <td>${data.phone}</td>
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
                      onclick="populateModal('${getRandomImageUrl()}')" // Pass the random image URL to the function
                  >
                  <i class='bx bx-link-external'></i>
                  </button>
              </div>
              </td>
              <td>
              <a href="userAllDetail.html" class="menu-link" onclick="showDetails(${index})">
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
function populateModal(imageUrl) {
  var modalContent = document.getElementById('modalContent');
  modalContent.innerHTML = `
        <img src="${imageUrl}" alt="Random Image" class="img-fluid max-width-50 max-height-50">`;
}

// Call the function to populate the table
populateTable();

function showDetails(index) {
  // Get the selected record's data from the `dummyData` array
  const selectedRecord = dummyData[index];
  // Convert the selected record to a JSON string
  const selectedRecordJSON = JSON.stringify(selectedRecord);

  // Store the selected record data in localStorage
  localStorage.setItem('selectedRecord', selectedRecordJSON);
}
