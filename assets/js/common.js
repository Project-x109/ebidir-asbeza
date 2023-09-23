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

// Function to calculate totalSpent from cartData
function calculateTotalSpent(cartData) {
  let total = 0;
  cartData.forEach(cartItem => {
    total += parseFloat(cartItem.totalPriceForItem.replace('$', ''));
  });
  return `$${total.toFixed(2)}`;
}
const creditLimits = [
  0, 100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000, 9000, 10000
];
// Generate up to 100 records with cart data
const dummyDataWithCartstatcommon = [];

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
      cartItem.totalPriceForItem = `$${(cartItem.quantity * parseFloat(cartItem.pricePerItem.replace('$', ''))).toFixed(
        2
      )}`;
      totalSpent = loanAmount;
      break;
    }
  }

  // Calculate creditLeft as loanAmount - totalSpent
  const creditLeft = `$${(loanAmount - totalSpent).toFixed(2)}`;

  const record = {
    accountName: randomAccountNames[getRandomNumber(0, randomAccountNames.length - 1)],
    id: `eb0${getRandomNumber(1000000, 9999999)}`,
    loanAmount: `$${loanAmount.toFixed(2)}`,
    totalSpent: `$${totalSpent.toFixed(2)}`,
    creditLeft: creditLeft,
    paymentDate: getRandomDate(2020, 2023),
    status: getRandomStatus(),
    loanID: `eb0${getRandomNumber(1000000, 9999999)}`,
    originalAmount: `$${getRandomNumber(3000, 10000)}`,
    amountPaid: `$${getRandomNumber(0, 5000)}`,
    cartData: cartData
  };
  dummyDataWithCartstatcommon.push(record);
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
const latestCreditLeft = findLatestCreditLeft(dummyDataWithCartstatcommon);

// Display the remaining credit limit
const creditLimitElement = document.getElementById('creditLimit');
creditLimitElement.textContent = `$${latestCreditLeft.toFixed(2)}`;
