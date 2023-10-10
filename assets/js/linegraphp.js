// Fetch data from PHP script
// Define the months and initialize an array to store monthly data
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const monthlyData = Array(12).fill(0); // Initialize for all months

// Define chart configuration colors
let cardColor, headingColor, axisColor, shadeColor, borderColor;
cardColor = config.colors.white;
headingColor = config.colors.headingColor;
axisColor = config.colors.axisColor;
borderColor = config.colors.borderColor;
fetch('chartline.php')
  .then(response => response.json())
  .then(data => {
    // Get the current year and month
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth() + 1; // Months are 0-based

    // Calculate the start month for the last 6 months
    let startMonth = currentMonth - 5;
    if (startMonth < 1) {
      startMonth += 12; // Adjust for negative start month
    }

    // Filter the loan data to include only records for the last 6 months
    const recordsForLast6Months = data.filter(record => {
      const createdDate = new Date(record.createdOn);
      const createdYear = createdDate.getFullYear();
      const createdMonth = createdDate.getMonth() + 1; // Months are 0-based
      return createdYear === currentYear && createdMonth >= startMonth && createdMonth <= currentMonth;
    });

    // Initialize an array to store monthly data for the last 6 months
    const monthlyData = Array(6).fill(0);

    // Calculate the total price change for each month
    recordsForLast6Months.forEach(record => {
      const createdDate = new Date(record.createdOn);
      const createdMonth = createdDate.getMonth() - startMonth; // Adjust for the start month
      monthlyData[createdMonth] += parseFloat(record.price);
    });

    // Create an array of month names for x-axis labels
    const xaxisCategories = months.slice(startMonth - 1, currentMonth);

    // Create the chart
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
          categories: ['', ...xaxisCategories],
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

    if (incomeChartEl !== null) {
      const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
      incomeChart.render();
    }
  })
  .catch(error => {
    console.error('Error fetching or processing data:', error);
  });


