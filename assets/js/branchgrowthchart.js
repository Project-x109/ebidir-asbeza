'use strict';

// Define a function to handle the data fetching and chart update
let loansData = []; // Declare loansData here

async function fetchDataAndRenderChart(year) {
  try {
    // Include year in the fetch URL
    const response = await fetch(`chart.php?year=${year}`);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const responseData = await response.json();
    loansData = responseData.data; // Update loansData

    // Extract percentages from the response
    const pendingPercentage = responseData.pendingPercentage;
    const paidPercentage = responseData.paidPercentage;
    const unpaidPercentage = responseData.unpaidPercentage;

    // Update the chart with the new data and percentages
    updateGrowthChart(year, pendingPercentage, paidPercentage, unpaidPercentage);
  } catch (error) {
    console.error('Error fetching or processing data:', error);
  }
}

// Function to update the growth chart
function updateGrowthChart(year, pendingPercentage, paidPercentage, unpaidPercentage) {
  // Update the chart with the new data
  const growthChartEl = document.getElementById('growthChart');

  const growthChartOptions = {
    series: [pendingPercentage.toFixed(2), paidPercentage.toFixed(2), unpaidPercentage.toFixed(2)],
    labels: ['Pending', 'Paid', 'Unpaid'],
    subtitle: {
      text: `Year: ${year}`
    },
    chart: {
      height: 240,
      type: 'radialBar'
    },
    plotOptions: {
      radialBar: {
        size: 150,
        offsetY: 10,
        startAngle: -150,
        endAngle: 150,
        hollow: {
          size: '55%'
        },
        track: {
          background: '#e6e6e6',
          strokeWidth: '100%'
        },
        dataLabels: {
          name: {
            offsetY: 15,
            color: '#888888',
            fontSize: '15px',
            fontWeight: '600',
            fontFamily: 'Public Sans'
          },
          value: {
            offsetY: -25,
            color: '#333',
            fontSize: '22px',
            fontWeight: '500',
            fontFamily: 'Public Sans'
          }
        }
      }
    },
    colors: ['#F39C12', '#2ECC71', '#E74C3C'],
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'dark',
        shadeIntensity: 0.5,
        gradientToColors: ['#F39C12', '#2ECC71', '#E74C3C'],
        inverseColors: false,
        opacityFrom: 1,
        opacityTo: 0.6,
        stops: [30, 70, 100]
      }
    },
    stroke: {
      dashArray: 5
    },
    grid: {
      padding: {
        top: -35,
        bottom: -10
      }
    },
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

  const growthChart = new ApexCharts(growthChartEl, growthChartOptions);
  growthChart.render();
}

// Function to populate the year dropdown
function populateYearDropdown(uniqueYears) {
  const growthReportSelect = document.getElementById('growthReportSelect');

  // Clear existing options
  growthReportSelect.innerHTML = '';

  // Add an "All" option
  const allOption = document.createElement('option');
  allOption.value = 'all';
  allOption.text = 'All';
  growthReportSelect.appendChild(allOption);

  uniqueYears.forEach(year => {
    const option = document.createElement('option');
    option.value = year;
    option.text = year;
    growthReportSelect.appendChild(option);
  });
}

// Initialize the application by fetching initial data
async function initializeApp() {
  try {
    const response = await fetch('chart.php?year=all');
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const responseData = await response.json();
    loansData = responseData.data;

    // Extract unique years from the data
    const uniqueYears = [...new Set(loansData.map(record => new Date(record.createdOn).getFullYear()))];

    // Populate the year dropdown with unique years
    populateYearDropdown(uniqueYears);

    // Initialize the chart with default percentages
    updateGrowthChart('all', 0, 0, 0);
  } catch (error) {
    console.error('Error fetching initial data:', error);
  }
}

// Event listener for the year dropdown
const growthReportSelect = document.getElementById('growthReportSelect');

growthReportSelect.addEventListener('change', () => {
  const selectedYear = growthReportSelect.value;

  // Call fetchDataAndRenderChart with selectedYear
  fetchDataAndRenderChart(selectedYear);
});

// Initialize the application when the page loads
initializeApp();
