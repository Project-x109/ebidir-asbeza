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
    const statuses = ['active', 'inactive', 'waiting'];
    return statuses[getRandomNumber(0, 2)];
  }
  function getJobStatus() {
    const statuses = ['Employed', 'Unemployed', 'Self Employed'];
    return statuses[getRandomNumber(0, 2)];
  }
  
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
  // Array of random account names
  const randomAccountNamesdummy = [
    'Amanuel Girma',
    'John Doe',
    'Jane Smith',
    'Alice Johnson',
    'Bob Wilson'
    // Add more names as needed
  ];

 
  
  // Function to generate a random image URL
  function getRandomImageUrl() {
    // Replace this with an array of image URLs or an API call to fetch random images.
    const imageUrls = [
      'https://th.bing.com/th/id/R.8e789e42f2f50ed4bc0c420c1c65d0f0?rik=uHrS11DPo4NbKg&pid=ImgRaw&r=0',
      'https://d2qp0siotla746.cloudfront.net/img/use-cases/profile-picture/template_3.jpg',
      'https://images.statusfacebook.com/profile_pictures/Awesome/Awesome_profile_picture2.jpg'
      // Add more image URLs as needed
    ];
    const randomImageUrl = imageUrls[Math.floor(Math.random() * imageUrls.length)];
    return randomImageUrl;
  }
  
  // Generate up to 100 records
  const dummyData = [];
  
  // Function to calculate credit repayment date (one month ahead)
  function calculateCreditRepaymentDate(paymentDate) {
    const [month, day, year] = paymentDate.split('-').map(Number);
    const nextMonth = month === 12 ? 1 : month + 1;
    const nextYear = month === 12 ? year + 1 : year;
    return `${nextMonth.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}-${nextYear}`;
  }
  
  for (let i = 0; i < 100; i++) {
    const record = {
      accountName: randomAccountNamesdummy[getRandomNumber(0, randomAccountNamesdummy.length - 1)],
      id: `eb0${getRandomNumber(1000000, 9999999)}`,
      jobStatus: getJobStatus(),
      email: generateRandomEmail(),
      phone: generateRandomPhoneNumber(),
      image:getRandomImageUrl()
    };
    dummyData.push(record);
  }
  const fullname= dummyData[0].accountName;
  document.getElementById('fullName').value=fullname;
  document.getElementById('tinNumber').value=dummyData[0].id;
  document.getElementById('email').value=dummyData[0].email;
  document.getElementById('jobStatus').value=dummyData[0].jobStatus;
  document.getElementById('phoneNumber').value=dummyData[0].phone;
  document.getElementById('uploadedAvatar').src = dummyData[0].image;

