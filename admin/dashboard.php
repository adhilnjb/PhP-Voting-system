<?php
include '../includes/header.php';
include '../includes/db.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/Chart.min.css">
  <link rel="stylesheet" href="../css/style.css"> </head>
<body>
  <div class="hero-section d-flex justify-content-center align-items-center">
    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <h2 style="color: white;">Admin Dashboard</h2>
          <a href="./manage_candidates.php" class="btn btn-primary">Manage Candidates</a>
          <a href="./result.php" class="btn btn-success">View Results</a>
        </div>
      </div>

    </div>
  </div>

  <div class="container ">
    <div class="row">
      <div class="col-md-8">
        <canvas id="myChart"></canvas>
      </div>
    </div>
  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/Chart.min.js"></script>
  <script>
    $(document).ready(function() {
      $.ajax({
        url: 'get_results.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          const labels = data.map(item => item.name);
          const votes = data.map(item => item.votes);

          const ctx = document.getElementById('myChart').getContext('2d');
          const myChart = new Chart(ctx, {
            type: 'bar', // or 'pie'
            data: {
              labels: labels,
              datasets: [{
                label: 'Votes',
                data: votes,
                backgroundColor: [
                  'rgba(255, 99, 132, 0.8)',
                  'rgba(54, 162, 235, 0.8)',
                  'rgba(255, 206, 86, 0.8)',
                  'rgba(75, 192, 192, 0.8)',
                  'rgba(153, 102, 255, 0.8)',
                  'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
              }]
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
        },
        error: function(xhr, status, error) {
          console.error("Error fetching data:", error);
        }
      });
    });
  </script>
</body>
</html>

<?php
include '../includes/footer.php';
?>