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
const randomAccountNamesStats = [
  'Amanuel Girma',
  'John Doe',
  'Jane Smith',
  'Alice Johnson',
  'Bob Wilson'
  // Add more names as needed
];

// Generate up to 100 records
const dummyDataStat = [];

for (let i = 0; i < 1000; i++) {
  const record = {
    accountName: randomAccountNamesStats[getRandomNumber(0, randomAccountNamesStats.length - 1)],
    id: `eb0${getRandomNumber(1000000, 9999999)}`,
    loanAmount: `$${getRandomNumber(1000, 5000)}`,
    paymentDate: getRandomDate(2020, 2023),
    status: getRandomStatus(),
    loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
    originalAmount: `$${getRandomNumber(3000, 10000)}`,
    amountPaid: `$${getRandomNumber(0, 5000)}`
  };
  dummyDataStat.push(record);
}

// Function to calculate the count of each status
function getStatusCounts(data) {
  const counts = {
    completed: 0,
    overdue: 0,
    scheduled: 0
  };

  data.forEach(record => {
    counts[record.status]++;
  });

  return counts;
}

// Calculate the status counts
const statusCounts = getStatusCounts(dummyDataStat);

let cardColor, headingColor, axisColor, shadeColor, borderColor;

cardColor = config.colors.white;
headingColor = config.colors.headingColor;
axisColor = config.colors.axisColor;
borderColor = config.colors.borderColor;

// Order Statistics Chart
// --------------------------------------------------------------------
const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
  orderChartConfig = {
    chart: {
      height: 165,
      width: 130,
      type: 'donut'
    },
    labels: ['Completed', 'Overdue', 'Scheduled'],
    series: [statusCounts.completed, statusCounts.overdue, statusCounts.scheduled],
    colors: [config.colors.primary, config.colors.secondary, config.colors.info],
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
                return parseInt(val);
              }
            },
            name: {
              offsetY: 20,
              fontFamily: 'Public Sans'
            },
            total: {
              show: true,
              fontSize: '0.8125rem',
              color: axisColor,
              label: 'total',
              formatter: function (w) {
                return dummyDataStat.length;
              }
            }
          }
        }
      }
    }
  };
if (typeof chartOrderStatistics !== undefined && chartOrderStatistics !== null) {
  const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
  statisticsChart.render();
}

// Get the current year and month
const currentDate = new Date();
const currentYear = currentDate.getFullYear();
const currentMonth = currentDate.getMonth() + 1; // Months are 0-based

// Calculate the start month and year for the last 6 months
let startMonth = currentMonth - 5;
let startYear = currentYear;

if (startMonth <= 0) {
  // Adjust for negative months by subtracting from the year
  startMonth += 12;
  startYear -= 1;
}

// Create an array of month names for x-axis labels
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// Initialize an array to store monthly data
const monthlyData = Array(6).fill(0);

// Filter the dummyDataStat array to include only records for the last 6 months
const recordsForLast6Months = dummyDataStat.filter(record => {
  const paymentDate = new Date(record.paymentDate);
  const paymentYear = paymentDate.getFullYear();
  const paymentMonth = paymentDate.getMonth() + 1; // Months are 0-based
  return paymentYear === currentYear && paymentMonth >= startMonth && paymentMonth <= currentMonth;
});

// Calculate the number of loan applications for each month
recordsForLast6Months.forEach(record => {
  const paymentDate = new Date(record.paymentDate);
  const paymentMonth = paymentDate.getMonth() + 1; // Months are 0-based
  monthlyData[paymentMonth - startMonth] += 1;
});

// Income Chart - Area chart
// --------------------------------------------------------------------
const incomeChartEl = document.querySelector('#incomeChart'),
  incomeChartConfig = {
    series: [
      {
        data: ['', ...monthlyData]
      }
    ],
    chart: {
      height: 215,
      parentHeightOffset: 0,
      parentWidthOffset: 0,
      toolbar: {
        show: false
      },
      type: 'area'
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      width: 2,
      curve: 'smooth'
    },
    legend: {
      show: false
    },
    markers: {
      size: 6,
      colors: 'transparent',
      strokeColors: 'transparent',
      strokeWidth: 4,
      discrete: [
        {
          fillColor: config.colors.white,
          seriesIndex: 0,
          dataPointIndex: 7,
          strokeColor: config.colors.primary,
          strokeWidth: 2,
          size: 6,
          radius: 8
        }
      ],
      hover: {
        size: 7
      }
    },
    colors: [config.colors.primary],
    fill: {
      type: 'gradient',
      gradient: {
        shade: shadeColor,
        shadeIntensity: 0.6,
        opacityFrom: 0.5,
        opacityTo: 0.25,
        stops: [0, 95, 100]
      }
    },
    grid: {
      borderColor: borderColor,
      strokeDashArray: 3,
      padding: {
        top: -20,
        bottom: -8,
        left: -10,
        right: 8
      }
    },
    xaxis: {
      categories: ['', ...months.slice(startMonth - 1, currentMonth)],
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      },
      labels: {
        show: true,
        style: {
          fontSize: '13px',
          colors: axisColor
        }
      }
    },
    yaxis: {
      labels: {
        show: false
      },
      min: 0,
      max: Math.max(...monthlyData) + 1,
      tickAmount: 4
    }
  };
if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
  const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
  incomeChart.render();
}

// Update the credit score element with the total dummyDataStat length
const creditScoreElement = document.getElementById('creditScore');
creditScoreElement.textContent = dummyDataStat.length;

const todaysDate = document.getElementById('todaysdate');
const today = new Date();

const year = today.getFullYear();
const month = today.getMonth() + 1; // Months are zero-based, so add 1

// Create a formatted date string
const formattedDate = `${'As of '}${year}-${month.toString().padStart(2, '0')}`;

// Set the text content of the element
todaysDate.textContent = formattedDate;

function getTotalLoanAmountByStatus(data, status) {
  return data
    .filter(record => record.status === status)
    .reduce((total, record) => total + parseInt(record.loanAmount.replace('$', ''), 10), 0);
}

// Calculate total loan amount for each status
const totalCompletedLoanAmount = getTotalLoanAmountByStatus(dummyDataStat, 'completed');
const totalOverdueLoanAmount = getTotalLoanAmountByStatus(dummyDataStat, 'overdue');
const totalScheduledLoanAmount = getTotalLoanAmountByStatus(dummyDataStat, 'scheduled');
const totalForAllAmount = totalCompletedLoanAmount + totalOverdueLoanAmount + totalScheduledLoanAmount;

const completedLoanAmountElement = document.getElementById('completedLoanAmount');
const overdueLoanAmountElement = document.getElementById('overdueLoanAmount');
const scheduledLoanAmountElement = document.getElementById('scheduledLoanAmount');
const totalForAll = document.getElementById('totalforll');

function formatNumbersWithAbbreviations(values) {
  const formattedValues = {};
  for (const key in values) {
    if (values.hasOwnProperty(key)) {
      const number = values[key];
      if (number >= 1e12) {
        formattedValues[key] = (number / 1e12).toFixed(2) + 'T';
      } else if (number >= 1e9) {
        formattedValues[key] = (number / 1e9).toFixed(2) + 'B';
      } else if (number >= 1e6) {
        formattedValues[key] = (number / 1e6).toFixed(2) + 'M';
      } else if (number >= 1e3) {
        formattedValues[key] = (number / 1e3).toFixed(2) + 'K';
      } else {
        formattedValues[key] = number.toString();
      }
    }
  }
  return formattedValues;
}
const status1 = {
  completed: totalCompletedLoanAmount,
  overdue: totalOverdueLoanAmount,
  scheduled: totalScheduledLoanAmount,
  total: totalForAllAmount
};
// Example usage:

const formattedStatusCounts = formatNumbersWithAbbreviations(status1);
// Update the HTML elements with the total loan amounts
completedLoanAmountElement.textContent = `$${formattedStatusCounts.completed}`;
overdueLoanAmountElement.textContent = `$${formattedStatusCounts.overdue}`;
scheduledLoanAmountElement.textContent = `$${formattedStatusCounts.scheduled}`;
totalForAll.textContent = `$${formattedStatusCounts.total}`;

// 1. Filter the records for the last 6 months
const lastSixMonthsDate = new Date(currentDate);
lastSixMonthsDate.setMonth(currentDate.getMonth() - 5);

const lastSixMonthsRecords = dummyDataStat.filter(record => {
  const requestedDate = new Date(record.paymentDate);
  return requestedDate >= lastSixMonthsDate && requestedDate <= currentDate;
});

// 2. Calculate the total originalAmount for the last 6 months
const totalOriginalAmountLastSixMonths = lastSixMonthsRecords.reduce((total, record) => {
  return total + parseFloat(record.originalAmount.replace(/[^\d.-]/g, ''));
}, 0);

// 3. Filter the records for the first 6 months
const firstSixMonthsRecords = dummyDataStat.filter(record => {
  const requestedDate = new Date(record.paymentDate);
  return requestedDate.getMonth() < currentDate.getMonth() - 5;
});

// 4. Calculate the total originalAmount for the first 6 months
const totalOriginalAmountFirstSixMonths = firstSixMonthsRecords.reduce((total, record) => {
  return total + parseFloat(record.originalAmount.replace(/[^\d.-]/g, ''));
}, 0);

// 5. Calculate the percentage increase or decrease
const percentageChange =
  ((totalOriginalAmountLastSixMonths - totalOriginalAmountFirstSixMonths) / totalOriginalAmountFirstSixMonths) * 100;


// Display the values in your HTML elements
const totalOriginalAmountElement = document.getElementById('totalOriginalAmount');
const percentageChangeElement = document.getElementById('percentageChange');

totalOriginalAmountElement.textContent = `${totalOriginalAmountLastSixMonths.toLocaleString('en-US', {
    style: 'currency',
    currency: 'ETB'
  })}`;
percentageChangeElement.textContent = `${percentageChange.toFixed(2)}%`;

// Determine whether it's an increase or decrease and set the appropriate class
if (percentageChange > 0) {
  percentageChangeElement.classList.add('text-success');
  percentageChangeElement.innerHTML += '<i class="bx bx-chevron-up"></i>';
} else if (percentageChange < 0) {
  percentageChangeElement.classList.add('text-danger');
  percentageChangeElement.innerHTML += '<i class="bx bx-chevron-down"></i>';
}
