document.addEventListener('DOMContentLoaded', function () {
  let cardColor, headingColor, axisColor, shadeColor, borderColor;

  cardColor = config.colors.white;
  headingColor = config.colors.headingColor;
  axisColor = config.colors.axisColor;
  borderColor = config.colors.borderColor;

  // Calculate the number of loans for each month in the last 2 years
  const currentYear = new Date().getFullYear();
  const lastTwoYears = [currentYear - 1, currentYear - 2]; // Get the last 2 years
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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
    const statuses = ['declined', 'approved', 'pending'];
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
  const randomBranchNames = [
    'Purposeblack ETH',
    'Purposeblack ETH2',
    'Purposeblack ETH3',
    'Purposeblack ETH4',
    'Purposeblack ETH5'
    // Add more names as needed
  ];
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

  // Generate up to 100 records

  const creditLimits = [
    0, 100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000, 9000, 10000
  ];
  const marriageStatuses = ['Single', 'Married', 'Divorced'];
  const education = ['Below Highschool', 'Diploma', 'Degree', 'Masters', 'Phd'];
  const jobStatuses = ['Employed', 'Unemployed', 'Self Employed'];
  const dummyData2s = [];
  for (let i = 0; i < 1000; i++) {
    const record = {
      accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
      branchName: randomBranchNames[getRandomNumber(0, randomBranchNames.length - 1)],
      id: `eb0${getRandomNumber(1000000, 9999999)}`,
      age: getRandomNumber(18, 120),
      creditscore: getRandomNumber(90, 500),
      creditlimit: creditLimits[getRandomNumber(0, creditLimits.length - 1)],
      tinNumber: getRandomNumber(100000000, 2000000000),
      loanAmount: `${getRandomNumber(1000, 5000).toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD'
      })}`,
      requesteddate: getRandomDate(2020, 2023),
      status: getRandomStatus(),
      email: generateRandomEmail(),
      phoneNo: generateRandomPhoneNumber(),
      loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
      originalAmount: `$${getRandomNumber(3000, 10000)}`,
      numberOfDependents: `${getRandomNumber(0, 20)}`,
      criminalRecords: `${getRandomNumber(0, 20)}`,
      marriageStatus: marriageStatuses[getRandomNumber(0, marriageStatuses.length - 1)],
      jobStatus: jobStatuses[getRandomNumber(0, jobStatuses.length - 1)],
      educationalStatus: education[getRandomNumber(0, education.length - 1)]
    };
    dummyData2s.push(record);
  }


  // Initialize an object to store loan counts for each year
  const loanCountsByYear = {};
  lastTwoYears.forEach(year => {
    loanCountsByYear[year] = Array(12).fill(0); // Initialize each month count to 0
  });

  // Loop through your dummy data and count loans for each month and year
  dummyData2s.forEach(record => {
    const requestedDate = new Date(record.requesteddate);
    const year = requestedDate.getFullYear();
    const month = requestedDate.getMonth();

    // Check if the year is one of the last 2 years
    if (loanCountsByYear.hasOwnProperty(year)) {
      loanCountsByYear[year][month] += 1; // Increment the count for that month
    }
  });

  // Convert the loan counts into series data
  const seriesData = lastTwoYears.map((year, index) => ({
    name: year.toString(),
    data: loanCountsByYear[year]
  }));

  // Total Revenue Report Chart - Bar Chart
  // --------------------------------------------------------------------
  const totalRevenueChartEl = document.querySelector('#totalRevenueChart'),
    totalRevenueChartOptions = {
      series: seriesData,

      chart: {
        height: 300,
        with:800,
        stacked: true,
        type: 'bar',
        toolbar: { show: true }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '33.3.%',
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
        categories: months,
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
  if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
    const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
    totalRevenueChart.render();
  }
});





