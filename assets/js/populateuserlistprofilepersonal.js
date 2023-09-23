// Function to generate a random number between min and max (inclusive)
function getRandomNumber(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }
  function getJobStatus() {
    const statuses = ['Employed', 'Unemployed', 'Self Employed'];
    return statuses[getRandomNumber(0, 2)];
  }
  function getMarrigeStatus() {
    const statuses = ['Single', 'Married', 'Divorced'];
    return statuses[getRandomNumber(0, 2)];
  }
  function getcriminalRecord(){
    const statuses1 = ['Yes', 'No'];
    return statuses1[getRandomNumber(0, 1)];
  }
  function geteducationalRecord(){
    const statuses1 = ['Degree', 'Diploma','PHD','Masters'];
    return statuses1[getRandomNumber(0, 1)];
  }

  // Generate up to 100 records
  const dummyData = [];
  
  for (let i = 0; i < 100; i++) {

    const record = {
      numberofdependet:getRandomNumber(0,5),
      marrigeStatus:getMarrigeStatus(),
      criminalRecord:getcriminalRecord(),
      educationalStatus:geteducationalRecord(),
    };
    dummyData.push(record);
  }
  document.getElementById('numberOfDependents').value=dummyData[0].numberofdependet;
  document.getElementById('marrigeStatus').value=dummyData[0].marrigeStatus;
  document.getElementById('criminalRecord').value=dummyData[0].criminalRecord;
  document.getElementById('educationalStatus').value=dummyData[0].educationalStatus;