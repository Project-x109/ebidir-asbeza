// Get the selected record data from localStorage
const selectedRecordJSON = localStorage.getItem('selectedRecord');

// Parse the JSON data back to an object
const selectedRecord = JSON.parse(selectedRecordJSON);
console.log(selectedRecord);
// Display the selected record's data on the detail.html page as needed
// For example:
document.getElementById('UserfullName').textContent = selectedRecord.accountName;
document.getElementById('PurchaseDate').textContent = selectedRecord.paymentDate;
document.getElementById('TotalcreditLeft').textContent = selectedRecord.creditLeft;
document.getElementById('totalspent').textContent = selectedRecord.totalSpent;
document.getElementById('paymentdate').textContent = selectedRecord.creditrepaymentdate;
document.getElementById('Status').textContent = selectedRecord.status;

// Function to set the Status text and badge
function setStatusAndBadge(status) {
  const statusElement = document.getElementById('Status');
  const badgeElement = document.createElement('span');
  badgeElement.className = `badge bg-label-${
    status === 'completed' ? 'success' : status === 'overdue' ? 'danger' : status === 'scheduled' ? 'info' : 'warning'
  } me-1`;
  badgeElement.textContent = status;

  statusElement.textContent = ''; // Clear existing text
  statusElement.appendChild(badgeElement); // Append the badge
}

// Example: Call the function to set Status and Badge (assuming selectedRecord contains the status)
const selectedStatus = selectedRecord.status;

setStatusAndBadge(selectedStatus);

// Display the cartData in the <ul> element
const topItemsList = document.getElementById('topItemsList');
const itemsPerPage = 3; // Adjust the number of items per page as needed
let currentPage = 1; // Initialize the current page

// Function to display a specific page of items
function displayItems(page) {
  // Clear the existing items
  topItemsList.innerHTML = '';

  const startIndex = (page - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;

  selectedRecord.cartData.slice(startIndex, endIndex).forEach(cartItem => {
    const li = document.createElement('li');
    li.className = 'd-flex mb-4 pb-1';

    li.innerHTML = `
      <div class="avatar flex-shrink-0 me-3">
        <span class="avatar-initial rounded bg-label-primary">
          <i class="bx bx-mobile-alt"></i>
        </span>
      </div>
      <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
        <div class="me-2">
          <h6 class="mb-0">${cartItem.item}</h6>
          <small class="text-muted">Quantity: ${cartItem.quantity}</small>
        </div>
        <div class="user-progress">
          <small class="fw-semibold">${cartItem.totalPriceForItem}</small>
        </div>
      </div>
    `;

    topItemsList.appendChild(li);
  });
}

// Call the function to display the initial page
displayItems(currentPage);

// Pagination controls
const paginationControls = document.getElementById('paginationControls');

// Calculate the total number of pages
const totalPages = Math.ceil(selectedRecord.cartData.length / itemsPerPage);

// Function to update pagination buttons
function updatePaginationButtons() {
  paginationControls.innerHTML = '';

  const prevButton = document.createElement('li');
  prevButton.className = 'page-item prev';
  prevButton.innerHTML = `
    <a class="page-link" href="javascript:void(0);">
      <i class="tf-icon bx bx-chevrons-left"></i>
    </a>
  `;
  prevButton.addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      displayItems(currentPage);
      updatePaginationButtons();
    }
  });

  paginationControls.appendChild(prevButton);

  for (let page = 1; page <= totalPages; page++) {
    const pageButton = document.createElement('li');
    pageButton.className = 'page-item';
    if (page === currentPage) {
      pageButton.className += ' active';
    }
    pageButton.innerHTML = `
      <a class="page-link" href="javascript:void(0);">${page}</a>
    `;
    pageButton.addEventListener('click', () => {
      currentPage = page;
      displayItems(currentPage);
      updatePaginationButtons();
    });

    paginationControls.appendChild(pageButton);
  }

  const nextButton = document.createElement('li');
  nextButton.className = 'page-item next';
  nextButton.innerHTML = `
    <a class="page-link" href="javascript:void(0);">
      <i class="tf-icon bx bx-chevrons-right"></i>
    </a>
  `;
  nextButton.addEventListener('click', () => {
    if (currentPage < totalPages) {
      currentPage++;
      displayItems(currentPage);
      updatePaginationButtons();
    }
  });

  paginationControls.appendChild(nextButton);
}

// Initialize pagination buttons
updatePaginationButtons();
let cardColor, headingColor, axisColor, shadeColor, borderColor;

cardColor = config.colors.white;
headingColor = config.colors.headingColor;
axisColor = config.colors.axisColor;
borderColor = config.colors.borderColor;

// Helper function to format numbers with commas and abbreviations
function formatNumberWithAbbreviation(value) {
  const abbreviations = ['K', 'M', 'B', 'T'];

  for (let i = abbreviations.length - 1; i >= 0; i--) {
    const factor = Math.pow(10, (i + 1) * 3);
    if (value >= factor) {
      return (value / factor).toFixed(1).replace(/\.0$/, '') + abbreviations[i];
    }
  }

  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Function to update the donut chart data
function updateDonutChart(data) {
  const chartOrderStatistics = document.querySelector('#valuechecker');

  if (typeof chartOrderStatistics !== 'undefined' && chartOrderStatistics !== null) {
    // Parse the data values without the '$' sign
    const loanAmount = parseFloat(data.loanAmount.replace('$', ''));
    const creditLeft = parseFloat(data.creditLeft.replace('$', ''));
    const totalSpent = parseFloat(data.totalSpent.replace('$', ''));

    // Create the Donut Chart configuration
    const orderChartConfig = {
      chart: {
        height: 165,
        width: 130,
        type: 'donut'
      },
      labels: ['Original Credit', 'Current Credit', 'Total Spent'],
      series: [loanAmount, creditLeft, totalSpent],
      colors: [config.colors.primary, config.colors.secondary, config.colors.info, config.colors.success],
      stroke: {
        width: 5,
        colors: cardColor
      },
      dataLabels: {
        enabled: false,
        formatter: function (val, opt) {
          return parseInt(val) + '%';
        }
      },
      legend: {
        show: false
      },
      grid: {
        padding: {
          top: 0,
          bottom: 0,
          right: 15
        }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '75%',
            labels: {
              show: true,
              value: {
                fontSize: '1.5rem',
                fontFamily: 'Public Sans',
                color: headingColor,
                offsetY: -15,
                formatter: function (val) {
                  return formatNumberWithAbbreviation(val);
                }
              },
              name: {
                offsetY: 20,
                fontFamily: 'Public Sans'
              },
              total: {
                show: true,
                fontSize: '0.65rem',
                color: axisColor,
                label: 'Original Amount',
                formatter: function (w) {
                  return formatNumberWithAbbreviation(loanAmount);
                }
              }
            }
          }
        }
      }
    };

    // Create or update the Donut Chart
    const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
    statisticsChart.render();
  }
}

// Example: Call the function to update the Donut Chart with data from a clicked row (assuming selectedRecord contains the data)

updateDonutChart(selectedRecord);
