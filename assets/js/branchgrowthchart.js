/**
 * Dashboard Analytics
 */

'use strict';

(function () {
  function getRandomNumber1(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  // Function to generate a random date within a range of years
  function getRandomDate(startYear, endYear) {
    const year = getRandomNumber1(startYear, endYear);
    const month = getRandomNumber1(1, 12);
    const day = getRandomNumber1(1, 28); // Assuming all months have up to 28 days
    return `${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}-${year}`;
  }

  // Function to generate random status
  function getRandomStatus() {
    const statuses = ['overdue', 'completed', 'scheduled'];
    return statuses[getRandomNumber1(0, 2)];
  }

  // Array of random account names
  const randomAccountNames1 = [
    'Amanuel Girma',
    'John Doe',
    'Jane Smith',
    'Alice Johnson',
    'Bob Wilson'
    // Add more names as needed
  ];

  // Generate up to 100 records
  const dummyData1 = [];

  for (let i = 0; i < 1000; i++) {
    const record = {
      accountName: randomAccountNames1[getRandomNumber1(0, randomAccountNames1.length - 1)],
      id: `eb0${getRandomNumber1(1000000, 9999999)}`,
      loanAmount: `$${getRandomNumber1(1000, 5000)}`,
      paymentDate: getRandomDate(2020, 2023),
      status: getRandomStatus(),
      loanID: `eb0${getRandomNumber1(1000000, 9999999)}`,
      originalAmount: `$${getRandomNumber1(3000, 10000)}`,
      amountPaid: `$${getRandomNumber1(0, 5000)}`
    };
    dummyData1.push(record);
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
  const statusCounts = getStatusCounts(dummyData1);

  let cardColor, headingColor, axisColor, shadeColor, borderColor;

  cardColor = config.colors.white;
  headingColor = config.colors.headingColor;
  axisColor = config.colors.axisColor;
  borderColor = config.colors.borderColor;

  const growthReportSelect = document.getElementById('growthReportSelect');
  const statusSelect = document.getElementById('statusSelect');
  const uniqueYears = getUniqueYears(dummyData1); // Get unique years from the dummy data

  // Populate the dropdown with unique years
  uniqueYears.forEach(year => {
    const option = document.createElement('option');
    option.value = year;
    option.text = year;
    growthReportSelect.appendChild(option);
  });

  // Event listeners for the select elements
  growthReportSelect.addEventListener('change', () => {
    const selectedYear = parseInt(growthReportSelect.value); // Parse the selected year
    const selectedStatus = statusSelect.value; // Get the selected status
    updateGrowthChart(selectedYear, selectedStatus);
  });

  statusSelect.addEventListener('change', () => {
    const selectedYear = parseInt(growthReportSelect.value); // Parse the selected year
    const selectedStatus = statusSelect.value; // Get the selected status
    updateGrowthChart(selectedYear, selectedStatus);
  });

  // Function to update the growth chart based on the selected year and status
  function updateGrowthChart(selectedYear, selectedStatus) {

    // Filter the dummyData1 based on the selected year and status
    const filteredData = dummyData1.filter(record => {
      const paymentYear = new Date(record.paymentDate).getFullYear();
      return paymentYear === selectedYear;
    });


    // Calculate the status counts for the filtered data
    const filteredStatusCounts = getStatusCounts(filteredData);


    // Calculate the percentage for the selected status
    let percentage;
    if (selectedStatus === 'completed') {
      percentage = (filteredStatusCounts.completed /
        (filteredStatusCounts.completed + filteredStatusCounts.overdue + filteredStatusCounts.scheduled)) * 100;
    } 
    else if (selectedStatus === 'overdue') {
      percentage = (filteredStatusCounts.overdue /
        (filteredStatusCounts.completed + filteredStatusCounts.overdue + filteredStatusCounts.scheduled)) * 100;
    }
    else if (selectedStatus === 'scheduled') {
      percentage = (filteredStatusCounts.scheduled /
        (filteredStatusCounts.completed + filteredStatusCounts.overdue + filteredStatusCounts.scheduled)) * 100;
    }
    else {
      // Handle other statuses here as needed
      // Example: calculate percentage for overdue or scheduled
      // percentage = ...
    }


    // Destroy the previous chart instance
    if (growthChart) {
      growthChart.destroy();
    }

    // Create and render a new chart
    const growthChartEl = document.querySelector('#growthChart');
    const growthChartOptions = {
      series: [percentage.toFixed(2)],
      labels: [selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1)], // Capitalize the first letter
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
            background: cardColor,
            strokeWidth: '100%'
          },
          dataLabels: {
            name: {
              offsetY: 15,
              color: headingColor,
              fontSize: '15px',
              fontWeight: '600',
              fontFamily: 'Public Sans'
            },
            value: {
              offsetY: -25,
              color: headingColor,
              fontSize: '22px',
              fontWeight: '500',
              fontFamily: 'Public Sans'
            }
          }
        }
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'dark',
          shadeIntensity: 0.5,
          gradientToColors: [config.colors.primary],
          inverseColors: true,
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

    const newGrowthChart = new ApexCharts(growthChartEl, growthChartOptions);
    newGrowthChart.render();

    // Update the global growthChart variable with the new instance
    growthChart = newGrowthChart;
  }

  // Initialize the chart with the default value (the first year in uniqueYears) and status (completed)
  let growthChart = null;
  if (uniqueYears.length > 0) {
    updateGrowthChart(uniqueYears[0], 'completed');
  } else {
  }

  // Function to extract unique years from the dummy data
  function getUniqueYears(data) {
    const uniqueYears = new Set();
    data.forEach(record => {
      const paymentYear = new Date(record.paymentDate).getFullYear();
      uniqueYears.add(paymentYear);
    });
    return Array.from(uniqueYears);
  }
})();

