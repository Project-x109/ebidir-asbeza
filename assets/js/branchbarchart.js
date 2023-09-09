const chartData = {
    labels: ['Completed', 'Overdue', 'Scheduled'],
    datasets: [
      {
        label: 'Loan Status',
        data: [30, 20, 50], // Replace with your actual data
        backgroundColor: [
          'rgba(0, 128, 0, 0.5)', // Green for Completed
          'rgba(255, 0, 0, 0.5)', // Red for Overdue
          'rgba(0, 0, 255, 0.5)', // Blue for Scheduled
        ],
        borderWidth: 1,
      },
    ],
  };

  // Function to create and render the chart
  function createChart() {
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'bar', // You can change the chart type (e.g., 'pie', 'line') here
      data: chartData,
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  // Call the createChart function to render the chart
  createChart();