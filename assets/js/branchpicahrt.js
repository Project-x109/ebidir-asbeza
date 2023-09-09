// Dummy data for the pie chart (replace with your data)
const pieChartData = {
  labels: ['Completed', 'Overdue', 'Scheduled'],
  datasets: [
    {
      data: [30, 20, 50], // Replace with your actual data
      backgroundColor: [
        'rgba(0, 128, 0, 0.5)', // Green for Completed
        'rgba(255, 0, 0, 0.5)', // Red for Overdue
        'rgba(0, 0, 255, 0.5)' // Blue for Scheduled
      ]
    }
  ]
};

// Function to create and render the pie chart
function createPieChart() {
  const ctx = document.getElementById('pieChart').getContext('2d');
  const myPieChart = new Chart(ctx, {
    type: 'pie',
    data: pieChartData,
    options: {
      responsive: true
    }
  });
}

// Call the createPieChart function to render the pie chart
createPieChart();
