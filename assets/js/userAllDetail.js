// Get the selected record data from localStorage
const selectedRecordJSON = localStorage.getItem('selectedRecord');

// Parse the JSON data back to an object
const selectedRecord = JSON.parse(selectedRecordJSON);

document.getElementById('fullname').textContent = selectedRecord.accountName;
document.getElementById('email').textContent = selectedRecord.email;
document.getElementById('phone').textContent = selectedRecord.phone;

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
  const statuses = ['completed', 'scheduled', 'overdue'];
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
const randomAccountNamesuserall = [
  'Amanuel Girma',
  'John Doe',
  'Jane Smith',
  'Alice Johnson',
  'Bob Wilson'
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
function getRandomDate1(startYear, endYear) {
  const year = getRandomNumber(startYear, endYear);
  const month = getRandomNumber(1, 12);
  const day = getRandomNumber(1, 28); // Assuming all months have up to 28 days
  return `${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}-${year}`;
}

for (let i = 0; i < 1000; i++) {
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
    accountName: selectedRecord.accountName,
    id: selectedRecord.id,
    loanprovideddate: getRandomDate1(2020, 2023),
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
    jobStatus: selectedRecord.jobStatus,
    email: selectedRecord.email,
    phone: selectedRecord.phone
  };
  dummyData.push(record);
}

// Sort the dummyData array by creditrepaymentdate in descending order
dummyData.sort((a, b) => new Date(b.creditrepaymentdate) - new Date(a.creditrepaymentdate));

// The record with the latest creditrepaymentdate will be the first record in the sorted array
const latestRecord = dummyData[0];

function formatNumberWithAbbreviation(value) {
  const abbreviations = ['', 'K', 'M', 'B', 'T'];

  for (let i = abbreviations.length - 1; i >= 0; i--) {
    const factor = Math.pow(10, i * 3);
    if (value >= factor) {
      return (value / factor).toFixed(1).replace(/\.0$/, '') + abbreviations[i];
    }
  }

  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

const latestRecord1 = formatNumberWithAbbreviation(latestRecord.creditLeft);

document.getElementById('availablelimit').textContent = latestRecord1;

// Find the index of the latest record in the sorted array
const latestRecordIndex = 0;

// Find the closest record to the latest record based on creditrepaymentdate
let closestRecordIndex = -1;
let closestDateDifference = Infinity;

for (let i = 1; i < dummyData.length; i++) {
  const currentDate = new Date(dummyData[i].creditrepaymentdate);
  const latestDate = new Date(dummyData[latestRecordIndex].creditrepaymentdate);
  const dateDifference = Math.abs(currentDate - latestDate);

  if (dateDifference < closestDateDifference) {
    closestRecordIndex = i;
    closestDateDifference = dateDifference;
  }
}

// Calculate the percentage change
const latestCreditLeft1 = parseFloat(dummyData[latestRecordIndex].creditLeft.replace('$', ''));
const closestCreditLeft = parseFloat(dummyData[closestRecordIndex].creditLeft.replace('$', ''));
const percentageChange = ((latestCreditLeft1 - closestCreditLeft) / closestCreditLeft) * 100;

const changeBetweenClosestRecordsElement = document.getElementById('changebetweenclosesrecords');

// Determine the arrow icon class based on the percentageChange
const arrowIconClass = percentageChange >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt';

// Determine the text class based on the percentageChange
const textClass = percentageChange >= 0 ? 'text-success' : 'text-danger';

// Create an <i> element with the appropriate arrow icon class
const arrowIconElement = document.createElement('i');
arrowIconElement.classList.add('bx', textClass, arrowIconClass);

// Create a <span> element for the percentageChange with the appropriate text class
const percentageChangeElement = document.createElement('span');
percentageChangeElement.id = 'percentage-change';
percentageChangeElement.textContent = `${percentageChange.toFixed(2)}%`;
percentageChangeElement.classList.add(textClass);

// Clear existing content
changeBetweenClosestRecordsElement.innerHTML = '';

// Add the arrow icon element and percentageChange element as children of the changeBetweenClosestRecordsElement
changeBetweenClosestRecordsElement.appendChild(arrowIconElement);
changeBetweenClosestRecordsElement.appendChild(document.createTextNode(' ')); // Add space
changeBetweenClosestRecordsElement.appendChild(percentageChangeElement);

// Step 1: Calculate totalSpent for all records
let totalSpentAllRecords = 0;

dummyData.forEach(record => {
  const totalSpentValue = parseFloat(record.amountPaid.replace('$', ''));
  totalSpentAllRecords += totalSpentValue;
});
const formattedTotalSpent = formatNumberWithAbbreviation(totalSpentAllRecords);

document.getElementById('alltotalspent').textContent = `$${formattedTotalSpent}`;

// calculate total paid

// Step 1: Calculate totalSpent for all records
let totalPaidAllRecords = 0;

dummyData.forEach(record => {
  const totalPaidValue = parseFloat(record.totalSpent.replace('$', ''));
  totalPaidAllRecords += totalPaidValue;
});
const formattedTotalPaid = formatNumberWithAbbreviation(totalPaidAllRecords);

document.getElementById('alltotalpaid').textContent = `$${formattedTotalPaid}`;

//calculate total borrowed

// Step 1: Calculate totalBorrowed for all records
let totalLoanAllRecords = 0;

dummyData.forEach(record => {
  const totalLoanValue = parseFloat(record.loanAmount.replace('$', ''));
  totalLoanAllRecords += totalLoanValue;
});
const formattedLoanSpent = formatNumberWithAbbreviation(totalLoanAllRecords);

document.getElementById('totalborrowed').textContent = `$${formattedLoanSpent}`;

const today = new Date();
const year = today.getFullYear();
const month = today.getMonth() + 1; // Months are zero-based, so add 1
const formattedDate = `${'As of '}${year}-${month.toString().padStart(2, '0')}`;
document.getElementById('latestdate').textContent = formattedDate;
document.getElementById('latestdate1').textContent = formattedDate;
document.getElementById('latestdate2').textContent = formattedDate;

let cardColor, headingColor, axisColor, shadeColor, borderColor;

cardColor = config.colors.white;
headingColor = config.colors.headingColor;
axisColor = config.colors.axisColor;
borderColor = config.colors.borderColor;

// Step 1: Calculate loanAmount data for the entire current year, split into two halves
const currentYear = new Date().getFullYear();

// Initialize data arrays for the first and second half of the year
const dataFirstHalf = Array(6).fill(0); // 6 months in the first half
const dataSecondHalf = Array(6).fill(0); // 6 months in the second half

dummyData.forEach(record => {
  const loanDate = new Date(record.loanprovideddate);
  const loanAmount = parseFloat(record.loanAmount.replace('$', ''));

  // Determine if the loan is in the first or second half of the year
  if (loanDate.getFullYear() === currentYear) {
    const monthIndex = loanDate.getMonth();
    if (monthIndex >= 0 && monthIndex < 6) {
      dataFirstHalf[monthIndex] += loanAmount;
    } else if (monthIndex >= 6 && monthIndex < 12) {
      dataSecondHalf[monthIndex - 6] += loanAmount;
    }
  }
});

const loanChartEl = document.querySelector('#totalRevenueChart');
const loanChartOptions = {
  series: [
    {
      name: `${currentYear}`,
      data: [...dataFirstHalf, ...dataSecondHalf]
    }
  ],
  chart: {
    height: 300,
    type: 'bar',
    toolbar: { show: false }
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '33%',
      borderRadius: 12,
      startingShape: 'rounded',
      endingShape: 'rounded'
    }
  },
  colors: [config.colors.primary, config.colors.info],
  dataLabels: {
    enabled: false
  },
  stroke: {
    curve: 'smooth',
    width: 6,
    lineCap: 'round',
    colors: [cardColor]
  },
  legend: {
    show: true,
    horizontalAlign: 'left',
    position: 'top',
    markers: {
      height: 8,
      width: 8,
      radius: 12,
      offsetX: -3
    },
    labels: {
      colors: axisColor
    },
    itemMargin: {
      horizontal: 10
    }
  },
  grid: {
    borderColor: borderColor,
    padding: {
      top: 0,
      bottom: -8,
      left: 20,
      right: 20
    }
  },
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    labels: {
      style: {
        fontSize: '13px',
        colors: axisColor
      }
    },
    axisTicks: {
      show: false
    },
    axisBorder: {
      show: false
    }
  },
  yaxis: {
    labels: {
      style: {
        fontSize: '13px',
        colors: axisColor
      }
    }
  },
  responsive: [
    {
      breakpoint: 1700,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '32%'
          }
        }
      }
    },
    {
      breakpoint: 1580,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '35%'
          }
        }
      }
    },
    {
      breakpoint: 1440,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '42%'
          }
        }
      }
    },
    {
      breakpoint: 1300,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '48%'
          }
        }
      }
    },
    {
      breakpoint: 1200,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '40%'
          }
        }
      }
    },
    {
      breakpoint: 1040,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 11,
            columnWidth: '48%'
          }
        }
      }
    },
    {
      breakpoint: 991,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '30%'
          }
        }
      }
    },
    {
      breakpoint: 840,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '35%'
          }
        }
      }
    },
    {
      breakpoint: 768,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '28%'
          }
        }
      }
    },
    {
      breakpoint: 640,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '32%'
          }
        }
      }
    },
    {
      breakpoint: 576,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '37%'
          }
        }
      }
    },
    {
      breakpoint: 480,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '45%'
          }
        }
      }
    },
    {
      breakpoint: 420,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '52%'
          }
        }
      }
    },
    {
      breakpoint: 380,
      options: {
        plotOptions: {
          bar: {
            borderRadius: 10,
            columnWidth: '60%'
          }
        }
      }
    }
  ],
  states: {
    hover: {
      filter: {
        type: 'none'
      }
    },
    active: {
      filter: {
        type: 'none'
      }
    }
  }
};
let loanChart;

if (typeof loanChartEl !== 'undefined' && loanChartEl !== null) {
  loanChart = new ApexCharts(loanChartEl, loanChartOptions);
  loanChart.render();
}

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
                <a href="transactionrecordDetail.html" class="menu-link" onclick="showDetails(${index})">
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
