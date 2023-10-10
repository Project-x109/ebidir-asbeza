document.addEventListener('DOMContentLoaded', function () {
  // Function to create and render the pie chart
  function createPieChart(data) {
    const ctx = document.getElementById('pieChart').getContext('2d');
    const myPieChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Paid', 'Unpaid', 'Pending'],
        datasets: [
          {
            data: [data.paid_count, data.unpaid_count, data.pending_count],
            backgroundColor: ['#29CCEF', '#7F82FF', 'rgba(0, 0, 255, 0.8)'],
            hoverBackgroundColor: ['#29CCEF', '#7F82FF', 'rgba(0, 0, 255, 1)'],
            borderColor: ['#29CCEF', '#7F82FF', 'rgba(0, 0, 255, 1)'],
            borderWidth: 2
          }
        ]
      },
      options: {
        responsive: true,
        animation: {
          animateScale: true,
          animateRotate: true
        },
        plugins: {
          legend: {
            position: 'right'
          },
          title: {
            display: true,
            text: '',
            fontSize: 28,
            fontColor: 'rgba(255, 255, 255, 0.9)'
          }
        },
        layout: {
          padding: 20
        },
        tooltips: {
          enabled: true,
          callbacks: {
            label: function (tooltipItem, data) {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              const percent = Math.round(
                (dataset.data[tooltipItem.index] / dataset.data.reduce((a, b) => a + b, 0)) * 100
              );
              return `${dataset.labels[tooltipItem.index]}: ${dataset.data[tooltipItem.index]} (${percent}%)`;
            }
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFontColor: 'rgba(255, 255, 255, 0.9)',
          bodyFontColor: 'rgba(255, 255, 255, 0.9)',
          bodySpacing: 10,
          titleSpacing: 10,
          cornerRadius: 8,
          caretSize: 10
        }
      }
    });
  }

  // Fetch data from the server using AJAX
  fetch('pie.php') // Replace 'pie.php' with the actual URL to your PHP script
    .then(response => response.json())
    .then(data => {
      // Call the createPieChart function to render the pie chart with the fetched data
      createPieChart(data);
    })
    .catch(error => {
      console.error('Error fetching data:', error);
    });
});
