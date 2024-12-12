<?php
session_start();
include '../db.php';

// Check if the user is logged in and is a contributor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contributor') {
    header("Location: ../login.php");
    exit();
}

// Get status from GET parameters (optional filter for specific status)
$status = $_GET['status'] ?? '';

// Initialize the date ranges for daily, weekly, and monthly
$today = date('Y-m-d');
$week_start = date('Y-m-d', strtotime('last sunday', strtotime($today)));
$month_start = date('Y-m-01');

// Initialize variables for results
$daily_claimed = 0;
$daily_unclaimed = 0;
$weekly_claimed = 0;
$weekly_unclaimed = 0;
$monthly_claimed = 0;
$monthly_unclaimed = 0;

try {
    // Prepare SQL statement to count 'Claimed' and 'Unclaimed' statuses

    // Daily counts for 'Claimed' and 'Unclaimed'
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Innovation WHERE status = :status AND DATE(date) = :date");

    $stmt->execute(['status' => 'Claimed', 'date' => $today]);
    $daily_claimed = $stmt->fetchColumn();

    $stmt->execute(['status' => 'Unclaimed', 'date' => $today]);
    $daily_unclaimed = $stmt->fetchColumn();

    // Weekly counts for 'Claimed' and 'Unclaimed'
    $stmt->execute(['status' => 'Claimed', 'date' => $week_start]);
    $weekly_claimed = $stmt->fetchColumn();

    $stmt->execute(['status' => 'Unclaimed', 'date' => $week_start]);
    $weekly_unclaimed = $stmt->fetchColumn();

    // Monthly counts for 'Claimed' and 'Unclaimed'
    $stmt->execute(['status' => 'Claimed', 'date' => $month_start]);
    $monthly_claimed = $stmt->fetchColumn();

    $stmt->execute(['status' => 'Unclaimed', 'date' => $month_start]);
    $monthly_unclaimed = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - CollectHub</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #d6e3ea, #7aa6bc);
            font-family: 'Arial', sans-serif;
            color: black;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            padding: 10px 20px;
            background: linear-gradient(to right, #16222a, #3a6073);
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
            color: #ffffff;
        }

        header img {
            width: 300px;
            height: auto;
        }

        header nav {
            display: flex;
            justify-content: flex-end;
            flex-grow: 1;
        }

        header nav a {
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            margin-left: 25px;
            transition: all 0.3s;
        }

        header nav a:hover {
            color: #2ecc71;
        }

        .container {
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 1200px;
        }

        footer {
            margin-top: auto;
            background: linear-gradient(to right, #16222a, #3a6073);
            color: #ffffff;
            text-align: center;
            padding: 10px 20px;
            width: 100%;
            box-sizing: border-box;
        }

        h2 {
            font-family: 'Cooper Black', serif;
            color: #16222a;
            text-align: center;
            font-size: 36px;
            margin-bottom: 30px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #16222a;
            color: white;
        }

        .table tbody td {
            background-color: white;
        }

        .table th,
        .table td {
            padding: 12px;
        }

        /* Color classes for claimed and unclaimed */
        .claimed {
            color: blue;
        }

        .unclaimed {
            color: red;
        }
    </style>
</head>

<body>

    <header>
        <img src="collecthub-removebg-preview.png" alt="COLLECT HUB Logo">
        <nav>
            <!-- Ensure correct relative paths to your pages -->
            <a href="../index.php">Home</a>
            <a href="../aboutus.php">About Us</a>
            <a href="../logout.php">Log Out</a>
        </nav>
    </header>

    <div class="container">
        <h2>Report</h2>

        <!-- Table to display report data -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Time Period</th>
                    <th>Claimed</th>
                    <th>Unclaimed</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Daily</td>
                    <td class="claimed"><?= $daily_claimed ?></td>
                    <td class="unclaimed"><?= $daily_unclaimed ?></td>
                </tr>
                <tr>
                    <td>Weekly</td>
                    <td class="claimed"><?= $weekly_claimed ?></td>
                    <td class="unclaimed"><?= $weekly_unclaimed ?></td>
                </tr>
                <tr>
                    <td>Monthly</td>
                    <td class="claimed"><?= $monthly_claimed ?></td>
                    <td class="unclaimed"><?= $monthly_unclaimed ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Chart Section -->
        <canvas id="reportChart" width="400" height="200"></canvas>
    </div>

    <footer>
        <p>Contact us: <a href="mailto:uitm@collecthub.com" style="color: white;">uitm@collecthub.com</a></p>
    </footer>

    <script>
        // Prepare data for the chart
        const data = {
            labels: ['Daily', 'Weekly', 'Monthly'],
            datasets: [{
                label: 'Claimed',
                data: [<?= $daily_claimed ?>, <?= $weekly_claimed ?>, <?= $monthly_claimed ?>],
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 1
            }, {
                label: 'Unclaimed',
                data: [<?= $daily_unclaimed ?>, <?= $weekly_unclaimed ?>, <?= $monthly_unclaimed ?>],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 1
            }]
        };

        // Create the chart
        const ctx = document.getElementById('reportChart').getContext('2d');
        const reportChart = new Chart(ctx, {
            type: 'line', // You can change this to 'bar' for bar chart
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>

</body>

</html>
