document.addEventListener('DOMContentLoaded', function () {
 
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

// Array of random account names
const randomAccountNames = [
  'Amanuel Girma',
  'John Doe',
  'Jane Smith',
  'Alice Johnson',
  'Bob Wilson'
  // Add more names as needed
];

// Generate up to 100 records
const dummyData = [];

for (let i = 0; i < 1000; i++) {
  const record = {
    accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
    id: `eb0${getRandomNumber(1000000, 9999999)}`,
    loanAmount: `$${getRandomNumber(1000, 5000)}`,
    paymentDate: getRandomDate(2020, 2023),
    status: getRandomStatus(),
    loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
    originalAmount: `$${getRandomNumber(3000, 10000)}`,
    amountPaid: `$${getRandomNumber(0, 5000)}`
  };
  dummyData.push(record);
}

const pieChartData = {
  labels: ['Completed', 'Overdue', 'Scheduled'],
  datasets: [{
    data: [30, 15, 55],
    backgroundColor: [
      '#29CCEF', 
      '#7F82FF',
      'rgba(0, 0, 255, 0.8)'
    ],
    hoverBackgroundColor: [
      '#29CCEF',
      '#7F82FF', 
      'rgba(0, 0, 255, 1)'
    ],
    borderColor: [
      'rgba(0, 128, 0, 1)',
      '#7F82FF',
      'rgba(0, 0, 255, 1)' 
    ],
    borderWidth: 2
  }]
};
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
const statusCounts = getStatusCounts(dummyData);
console.log(statusCounts);


// Update the pieChartData with the calculated counts
pieChartData.datasets[0].data = [
  statusCounts.completed,
  statusCounts.overdue,
  statusCounts.scheduled
];

// Function to create and render the pie chart
function createPieChart() {
  const ctx = document.getElementById('pieChart').getContext('2d');
  const myPieChart = new Chart(ctx, {
    type: 'pie',
    data: pieChartData,
    options: {
      responsive: true,
      animation: {
        animateScale: true,
        animateRotate: true
      },
      plugins: {
        legend: {
          position: 'right' 
        },
        title: {
          display: true,
          text: 'Loan Status',
          fontSize: 28,
          fontColor: 'rgba(255, 255, 255, 0.9)'
        }
      },
      layout: {
        padding: 20
      }, 
      tooltips: {
        enabled: true,
        callbacks: {
          label: function(tooltipItem, data) {
            const dataset = data.datasets[tooltipItem.datasetIndex];
            const percent = Math.round((dataset.data[tooltipItem.index] / dataset.data.reduce((a, b) => a + b, 0)) * 100);  
            return `${dataset.labels[tooltipItem.index]}: ${dataset.data[tooltipItem.index]} (${percent}%)`;
          }
        },
        backgroundColor: 'rgba(0, 0, 0, 0.7)',
        titleFontColor: 'rgba(255, 255, 255, 0.9)',
        bodyFontColor: 'rgba(255, 255, 255, 0.9)',
        bodySpacing: 10,
        titleSpacing: 10,
        cornerRadius: 8,
        caretSize: 10
      }
    }
  });
}
// Call the createPieChart function to render the updated pie chart
createPieChart();


});