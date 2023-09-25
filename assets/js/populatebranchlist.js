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
  'Purposeblack ETH',
  'Purposeblack ETH2',
  'Purposeblack ETH3',
  'Purposeblack ETH4',
  'Purposeblack ETH5'
  // Add more names as needed
];
// Array of random location
const randomLocation = [
  'Bole',
  'Piassa',
  'Jemo1',
  'Jemo2',
  'Jemo3'
  // Add more names as needed
];

// Function to generate a random image URL
function getRandomImageUrl() {
  // Replace this with an array of image URLs or an API call to fetch random images.
  const imageUrls = [
    'https://th.bing.com/th/id/R.b50cd04e1d7e07e79105c41c565b08f3?rik=EI1jWPFgmMisfw&pid=ImgRaw&r=0',
    'https://images.designtrends.com/wp-content/uploads/2016/09/06140150/logo1.gif',
    'https://2.bp.blogspot.com/-4lVV2tG7Nr8/T-7Oi9or2qI/AAAAAAAAAJA/tBP_w824PUc/s1600/car+company+logos+10.jpg'
    // Add more image URLs as needed
  ];
  const randomImageUrl = imageUrls[Math.floor(Math.random() * imageUrls.length)];
  return randomImageUrl;
}

// Generate up to 100 records
const dummyData = [];

for (let i = 0; i < 100; i++) {
  const record = {
    accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
    id: `eb0${getRandomNumber(1000000, 9999999)}`,
    location: randomLocation[getRandomNumber(0, randomAccountNames.length - 1)],
    status: getRandomStatus(),
    email: generateRandomEmail(),
    phone: generateRandomPhoneNumber(),
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
                <td>${data.location}</td>
                <td><span class="badge bg-label-${
                  data.status === 'active'
                    ? 'success'
                    : data.status === 'inactive'
                    ? 'danger'
                    : data.status === 'waiting'
                    ? 'info'
                    : 'warning'
                } me-1">${data.status}</span></td>
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
                    <a href="branchdetails.php" class="menu-link">
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
