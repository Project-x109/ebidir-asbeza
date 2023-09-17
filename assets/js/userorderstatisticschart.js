/**
 * Dashboard Analytics
 */

'use strict';

(function () {
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

  function getRandomItem() {
    const items = ['item1', 'item2', 'item3', 'item4', 'item5']; // Add more items as needed
    return items[getRandomNumber(0, items.length - 1)];
  }

  // Function to generate a random quantity for an item
  function getRandomQuantity() {
    return getRandomNumber(1, 10); // Adjust the range as needed
  }

  // Array of random account names
  const randomAccountNames = [
    'Amanuel Girma'
    // Add more names as needed
  ];

  // Function to generate random cart data
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

  const creditLimits = [
    0, 100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000, 9000, 10000
  ];

  // Function to calculate credit repayment date (one month ahead)
  function calculateCreditRepaymentDate(paymentDate) {
    const [month, day, year] = paymentDate.split('-').map(Number);
    const nextMonth = month === 12 ? 1 : month + 1;
    const nextYear = month === 12 ? year + 1 : year;
    return `${nextMonth.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}-${nextYear}`;
  }
  // Generate up to 100 records with cart data
  const dummyDataWithCartstat = [];

  for (let i = 0; i < 2000; i++) {
    // Calculate loanAmount ensuring loanAmount >= 100
    let loanAmount;
    do {
      loanAmount = creditLimits[getRandomNumber(0, creditLimits.length - 1)]; // Adjust the loanAmount range as needed
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
        cartItem.totalPriceForItem = `$${(
          cartItem.quantity * parseFloat(cartItem.pricePerItem.replace('$', ''))
        ).toFixed(2)}`;
        totalSpent = loanAmount;
        break;
      }
    }

    // Calculate creditLeft as loanAmount - totalSpent
    const creditLeft = `$${(loanAmount - totalSpent).toFixed(2)}`;
    const paymentDate = getRandomDate(2020, 2023);
    const record = {
      accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
      id: `eb0${getRandomNumber(1000000, 9999999)}`,
      loanAmount: `$${loanAmount.toFixed(2)}`,
      totalSpent: `$${totalSpent.toFixed(2)}`,
      creditLeft: creditLeft,
      creditrepaymentdate: calculateCreditRepaymentDate(paymentDate), // Calculate credit repayment date
      paymentDate: paymentDate,
      status: getRandomStatus(),
      loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
      originalAmount: `$${getRandomNumber(3000, 10000)}`,
      amountPaid: `$${getRandomNumber(0, 5000)}`,
      cartData: cartData
    };
    dummyDataWithCartstat.push(record);
  }

  // Adjust the calculation of x-axis categories and totalSpentLast6Months
  function calculateTotalSpentLast6Months(data) {
    const totalSpentLast6Months = new Array(6).fill(0);
    const today = new Date();

    // Loop through the payment data
    for (const payment of data) {
      const paymentDate = new Date(payment.paymentDate);
      const monthDiff =
        today.getMonth() - paymentDate.getMonth() + 12 * (today.getFullYear() - paymentDate.getFullYear());

      // Check if the payment date is within the last 6 months
      if (monthDiff >= 0 && monthDiff < 6) {
        totalSpentLast6Months[5 - monthDiff] += parseFloat(payment.totalSpent.replace('$', ''));
      }
    }

    return totalSpentLast6Months;
  }

  // Adjust the generation of x-axis categories
  function getXAxisCategories() {
    const today = new Date();
    const months = [];

    for (let i = 5; i >= 0; i--) {
      const monthDate = new Date(today.getFullYear(), today.getMonth() - i, 1);
      const monthName = monthDate.toLocaleString('default', { month: 'short' });
      months.push(`${monthName}`);
    }

    return months;
  }
  const xAxisCategories = getXAxisCategories();
  // Insert the totalSpentLast6Months array into the chart data
  const totalSpentLast6Months = calculateTotalSpentLast6Months(dummyDataWithCartstat);

  // Calculate total spent for the current 6 months and the past 6 months
  function calculateTotalSpentFor6Months(data) {
    const today = new Date();
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth();
    let current6MonthsSpent = 0;
    let past6MonthsSpent = 0;

    for (const payment of data) {
      const paymentDate = new Date(payment.paymentDate);
      const monthDiff = currentMonth - paymentDate.getMonth() + 12 * (currentYear - paymentDate.getFullYear());

      if (monthDiff >= 0 && monthDiff < 6) {
        // Current 6 months
        current6MonthsSpent += parseFloat(payment.totalSpent.replace('$', ''));
      } else if (monthDiff >= 6 && monthDiff < 12) {
        // Past 6 months
        past6MonthsSpent += parseFloat(payment.totalSpent.replace('$', ''));
      }
    }

    return { current6MonthsSpent, past6MonthsSpent };
  }

  // Get the total spent data
  const { current6MonthsSpent, past6MonthsSpent } = calculateTotalSpentFor6Months(dummyDataWithCartstat);

  // Get the HTML elements for total spent and change
  const totalSpentElement = document.getElementById('totalspent');
  const totalSpentChangeElement = document.getElementById('totalspentchange');

  // Compare current and past expenditures
  if (current6MonthsSpent > past6MonthsSpent) {
    totalSpentChangeElement.innerHTML = `
      <i class="bx bx-chevron-up"></i>
      ${((100 * (current6MonthsSpent - past6MonthsSpent)) / past6MonthsSpent).toFixed(1)}%
    `;
    totalSpentChangeElement.classList.add('text-success');
  } else if (current6MonthsSpent < past6MonthsSpent) {
    totalSpentChangeElement.innerHTML = `
      <i class="bx bx-chevron-down"></i>
      ${((100 * (past6MonthsSpent - current6MonthsSpent)) / past6MonthsSpent).toFixed(1)}%
    `;
    totalSpentChangeElement.classList.add('text-danger');
  }

  // Update the total spent value
  totalSpentElement.textContent = `$${current6MonthsSpent.toFixed(2)}`;

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

  // Group the dummy data by "status" and calculate the total spent for each status
  const statusTotals = {
    overdue: 0,
    completed: 0,
    scheduled: 0
  };

  dummyDataWithCartstat.forEach(record => {
    const status = record.status.toLowerCase();
    const totalSpent = parseFloat(record.totalSpent.replace('$', ''));
    statusTotals[status] += totalSpent;
  });
  // Order Statistics Chart
  // --------------------------------------------------------------------
  const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
    orderChartConfig = {
      chart: {
        height: 165,
        width: 130,
        type: 'donut'
      },
      labels: ['Overdue', 'Completed', 'Scheduled'],
      series: [statusTotals.overdue, statusTotals.completed, statusTotals.scheduled],
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
                  return formatNumberWithAbbreviation(val) ;
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
                  return formatNumberWithAbbreviation(statusTotals.overdue+statusTotals.completed+statusTotals.scheduled);
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

  const TotalCreditbystatus = document.getElementById('TotalCreditbystatus');
  TotalCreditbystatus.textContent = `$${formatNumberWithAbbreviation(statusTotals.overdue+statusTotals.completed+statusTotals.scheduled)}`;

  // Income Chart - Last six month purchase report
  // --------------------------------------------------------------------
  const incomeChartEl = document.querySelector('#incomeChart'),
    incomeChartConfig = {
      series: [
        {
          data: ['', ...totalSpentLast6Months]
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
        categories: ['', ...xAxisCategories],
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
        min: Math.min(...totalSpentLast6Months),
        max: Math.max(...totalSpentLast6Months),
        tickAmount: 4
      }
    };
  if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
    const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
    incomeChart.render();
  }

  function findLatestCreditLeft(data) {
    let latestCreditLeft = 0;
    let latestPaymentDate = new Date(0); // Initialize with the earliest date

    for (const payment of data) {
      const paymentDate = new Date(payment.paymentDate);
      if (paymentDate > latestPaymentDate) {
        latestPaymentDate = paymentDate;
        latestCreditLeft = parseFloat(payment.creditLeft.replace('$', ''));
      }
    }

    return latestCreditLeft;
  }

  // Get the latest credit left value
  const latestCreditLeft = findLatestCreditLeft(dummyDataWithCartstat);

  // Display the remaining credit limit
  const creditLimitElement = document.getElementById('creditLimit');
  creditLimitElement.textContent = `$${latestCreditLeft.toFixed(2)}`;

  // Expenses Mini Chart - Radial Chart
  // --------------------------------------------------------------------
  const weeklyExpensesEl = document.querySelector('#expensesOfWeek'),
    weeklyExpensesConfig = {
      series: [latestCreditLeft],
      chart: {
        width: 60,
        height: 60,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          startAngle: 0,
          endAngle: 360,
          strokeWidth: '8',
          hollow: {
            margin: 2,
            size: '45%'
          },
          track: {
            strokeWidth: '50%',
            background: borderColor
          },
          dataLabels: {
            show: true,
            name: {
              show: false
            },
            value: {
              formatter: function (val) {
                return '$' + parseInt(val);
              },
              offsetY: 5,
              color: '#697a8d',
              fontSize: '13px',
              show: true
            }
          }
        }
      },
      fill: {
        type: 'solid',
        colors: config.colors.primary
      },
      stroke: {
        lineCap: 'round'
      },
      grid: {
        padding: {
          top: -10,
          bottom: -15,
          left: -10,
          right: -10
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
  if (typeof weeklyExpensesEl !== undefined && weeklyExpensesEl !== null) {
    const weeklyExpenses = new ApexCharts(weeklyExpensesEl, weeklyExpensesConfig);
    weeklyExpenses.render();
  }
})();
