// Function to generate a random number between min and max (inclusive)
function getRandomNumber(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
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
      fieldOfEmployment:gerrandomfieldOfEmployment(),
      numberOfIncome:getRandomNumber(1,5),
      yearOfEmployment:'2020',
      companyName:'BEAEKA',
      position:'CEO'
    };
    dummyData.push(record);
  }
  document.getElementById('fieldOfEmployment').value = fieldOfEmployment;
  document.getElementById('numberOfIncome').value = dummyData[0].numberOfIncome;
  document.getElementById('yearOfEmployment').value = dummyData[0].yearOfEmployment;
  document.getElementById('companyName').value = dummyData[0].companyName;
  document.getElementById('position').value = dummyData[0].position;
  
  console.log(fullname);
  