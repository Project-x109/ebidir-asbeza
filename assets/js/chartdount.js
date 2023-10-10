// Function to convert numbers to abbreviated form
function abbreviateNumber(value) {
    const abbreviations = ['', 'K', 'M', 'B', 'T', 'Q'];
    const num = parseFloat(value);
  
    if (num < 1000) {
      return num.toFixed(2); // No abbreviation needed for values less than 1000
    }
  
    const tier = Math.floor(Math.log10(num) / 3);
  
    const scaled = num / Math.pow(1000, tier);
    return scaled.toFixed(2) + abbreviations[tier];
  }
  
  document.addEventListener('DOMContentLoaded', function () {
    // Fetch data from aman.php
    fetch('chartdount.php')
      .then(response => response.json())
      .then(data => {
        // Update credit score
        const creditScoreEl = document.querySelector('#creditScore');
        creditScoreEl.textContent = abbreviateNumber(data.total); // Convert and update the value
  
        const creditScoreE2 = document.querySelector('#totalforll');
        creditScoreE2.textContent = abbreviateNumber(data.total); // Convert and update the value
  
        // Update unpaid, pending, and paid amounts
        const unpaidLoanAmountEl = document.querySelector('#unpaidLoanAmount');
        unpaidLoanAmountEl.textContent = abbreviateNumber(data.unpaid);
  
        const pendingLoanAmountEl = document.querySelector('#pendingLoanAmount');
        pendingLoanAmountEl.textContent = abbreviateNumber(data.pending);
  
        const paidLoanAmountEl = document.querySelector('#paidLoanAmount');
        paidLoanAmountEl.textContent = abbreviateNumber(data.paid);
  
        // Create the chartOrderStatistics
        const chartOrderStatistics = document.querySelector('#orderStatisticsChart');
        const orderChartConfig = {
          chart: {
            height: 165,
            width: 130,
            type: 'donut'
          },
          labels: ['Pending', 'Unpaid', 'Unpaid'],
          series: [data.pending, data.paid, data.unpaid], // Assuming data contains the correct values
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
                      return abbreviateNumber(data.total); // Convert and update the value
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
                    label: 'Total',
                    formatter: function (w) {
                      return abbreviateNumber(data.total); // Convert and update the value
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
      })
      .catch(error => {
        console.error('Error fetching or processing data:', error);
      });
  });
  