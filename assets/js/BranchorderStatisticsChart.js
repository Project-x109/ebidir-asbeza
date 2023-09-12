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
console.log(statusCounts);

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
console.log(formattedStatusCounts);
// Update the HTML elements with the total loan amounts
completedLoanAmountElement.textContent = `$${formattedStatusCounts.completed}`;
overdueLoanAmountElement.textContent = `$${formattedStatusCounts.overdue}`;
scheduledLoanAmountElement.textContent = `$${formattedStatusCounts.scheduled}`;
totalForAll.textContent = `$${formattedStatusCounts.total}`;
