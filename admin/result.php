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
    <title>Voting Results</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 800px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        input[type="number"] {
            min-width: 50px;
        }

        .highcharts-description {
            margin: 0.3rem 10px;
        }
    </style>
</head>
<body>
<div class="hero-section d-flex justify-content-center align-items-center mt-5">
    <div class="container mt-5" style="height: 82vh;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2>Voting Results</h2>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                    <p class="highcharts-description text-white">
                        Pie charts are very popular for showing a compact overview of a
                        composition or comparison. While they can be harder to read than
                        column charts, they remain a popular choice for small datasets.
                    </p>
                </figure>
            </div>

            <div class="col-md-4">
                <h3>Results Table</h3>
                <div class="card-group flex-wrap">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM candidates ORDER BY votes DESC");
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($results as $result) {
                        ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $result['name']; ?></h5>
                                <p class="card-text">Votes: <?php echo $result['votes']; ?></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <a href="./dashboard.php" class="btn btn-info">Back</a>
            </div>
        </div>
    </div>
</div>

<script>
  Highcharts.chart("container", {
    chart: {
      type: "pie",
    },
    title: {
      text: "Voting Results",
    },
    tooltip: {
      valueSuffix: "%",
    },
    subtitle: {
      text: 'Source: Voting System',
    },
    plotOptions: {
      series: {
        allowPointSelect: true,
        cursor: "pointer",
        dataLabels: [
          {
            enabled: true,
            distance: 20,
          },
          {
            enabled: true,
            distance: -40,
            format: "{point.percentage:.1f}%",
            style: {
              fontSize: "1.2em",
              textOutline: "none",
              opacity: 0.7,
            },
            filter: {
              operator: ">",
              property: "percentage",
              value: 10,
            },
          },
        ],
      },
    },
    series: [
      {
        name: "Votes",
        colorByPoint: true,
        data: [
          <?php
          foreach ($results as $result) {
              echo "{ name: '" . $result['name'] . "', y: " . $result['votes'] . " },";
          }
          ?>
        ],
      },
    ],
  });
</script>

</body>
</html>

<?php
include '../includes/footer.php';
?>
