// Define the credit limit ranges and their corresponding credit limits
const creditLimitRanges = [
  { minSalary: 0, maxSalary: 1000, creditLimit: 100 },
  { minSalary: 1000, maxSalary: 5000, creditLimit: 500 },
  { minSalary: 5000, maxSalary: 10000, creditLimit: 1000 },
  { minSalary: 10000, maxSalary: 15000, creditLimit: 1500 },
  { minSalary: 15000, maxSalary: 20000, creditLimit: 2000 },
  { minSalary: 20000, maxSalary: 25000, creditLimit: 2500 },
  { minSalary: 25000, maxSalary: 30000, creditLimit: 3000 },
  { minSalary: 30000, maxSalary: 35000, creditLimit: 3500 },
  { minSalary: 35000, maxSalary: 40000, creditLimit: 4000 },
  { minSalary: 40000, maxSalary: 45000, creditLimit: 4500 },
  { minSalary: 45000, maxSalary: 50000, creditLimit: 5000 },
  { minSalary: 50000, maxSalary: 55000, creditLimit: 5500 },
  { minSalary: 55000, maxSalary: 60000, creditLimit: 6000 },
  { minSalary: 60000, maxSalary: 65000, creditLimit: 6500 },
  { minSalary: 65000, maxSalary: 70000, creditLimit: 7000 },
  { minSalary: 70000, maxSalary: 80000, creditLimit: 8000 },
  { minSalary: 80000, maxSalary: 90000, creditLimit: 9000 },
  { minSalary: 90000, maxSalary: 100000, creditLimit: 10000 },
  { minSalary: 100000, maxSalary: Infinity, creditLimit: 10000 } // For salaries above 100,000
];

// Function to generate a random number within a range
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
  const statuses = ['paid', 'pending', 'unpaid'];
  return statuses[getRandomNumber(0, 2)];
}

// Define arrays for random account names, branch names, and other data
const randomAccountNamesLoan = [
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

const marriageStatuses = ['Single', 'Married', 'Divorced'];
const education = ['Below Highschool', 'Diploma', 'Degree', 'Masters', 'Phd'];
const jobStatuses = ['Employed', 'Unemployed', 'Self Employed'];

// Function to generate a random email address
function generateRandomEmail() {
  const emailProviders = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'example.com'];
  const randomProvider = emailProviders[Math.floor(Math.random() * emailProviders.length)];
  const randomUsername = Math.random().toString(36).substring(7); // Generate a random string for the username part
  const email = `${randomUsername}@${randomProvider}`;
  return email;
}

// Function to generate a random phone number
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

// Function to generate a random credit limit based on salary
function generateRandomCreditLimit(salary) {
  // Find the appropriate credit limit range based on the salary
  const creditLimitRange = creditLimitRanges.find(range => {
    return salary >= range.minSalary && salary <= range.maxSalary;
  });

  // If a matching range is found, return its credit limit, otherwise, return a default value
  if (creditLimitRange) {
    return creditLimitRange.creditLimit;
  } else {
    // You can set a default credit limit here if needed
    return 0;
  }
}

// Generate up to 100 records
const dummyDataLoan = [];
for (let i = 0; i < 100; i++) {
  const salary = getRandomNumber(1000, 5000); // Generate a random salary
  const record = {
    accountName: randomAccountNamesLoan[getRandomNumber(0, randomAccountNamesLoan.length - 1)],
    branchName: randomBranchNames[getRandomNumber(0, randomBranchNames.length - 1)],
    id: `eb0${getRandomNumber(1000000, 9999999)}`,
    age: getRandomNumber(18, 120),
    salary: `${salary.toLocaleString('en-US', {
      style: 'currency',
      currency: 'USD'
    })}`,
    creditscore: getRandomNumber(90, 500),
    creditlimit: generateRandomCreditLimit(salary), // Generate credit limit based on salary
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
  dummyDataLoan.push(record);
}

console.log(dummyDataLoan);

// Function to calculate the percentage change
function calculatePercentageChange(oldValue, newValue) {
  const percentageChange = ((newValue - oldValue) / oldValue) * 100;
  return percentageChange.toFixed(2); // Limit to 2 decimal places
}

// Choose one record randomly from dummyDataLoan
const selectedRecord = dummyDataLoan[Math.floor(Math.random() * dummyDataLoan.length)];

// Choose one credit limit randomly
const randomlyChosenCreditLimit = creditLimitRanges[Math.floor(Math.random() * creditLimitRanges.length)].creditLimit;

// Calculate the percentage change
const percentageChange = calculatePercentageChange(selectedRecord.creditlimit, randomlyChosenCreditLimit);

// Determine if the credit limit increased or decreased
const creditLimitIncreased = randomlyChosenCreditLimit > selectedRecord.creditlimit;

// Get the StatusIndicator element by its ID
const statusIndicator = document.getElementById('Statusindicator');

// Create a new div element to hold the card content
const cardContainer = document.createElement('div');
// Display the appropriate card based on the result
if (creditLimitIncreased) {
  // Credit limit increased
  const increasedCard = `
    
              <h5 class="card-title text-primary">Congratulations ${selectedRecord.accountName}! 🎉</h5>
              <p class="mb-4">
                Your Credit Limit has increased by <span class="fw-bold">${percentageChange}%</span>
                Check The table below
              </p>
              <a href="#table-striped" class="btn btn-sm btn-outline-primary">View Table</a>
     
    `;

  cardContainer.innerHTML = increasedCard;
  console.log(increasedCard);
} else {
  // Credit limit decreased
  const decreasedCard = `
      
           
              <h5 class="card-title text-primary">Too Bad ${selectedRecord.accountName}! </h5>
              <p class="mb-4">
                Your Credit Limit has decreased by <span class="fw-bold">${percentageChange}%</span>
                Check The table below
              </p>
              <a href="#table-striped" class="btn btn-sm btn-outline-primary">View Table</a>
        
         
    `;
  console.log(decreasedCard);
  cardContainer.innerHTML = decreasedCard;
}

// Append the card container to the StatusIndicator div
statusIndicator.appendChild(cardContainer);
