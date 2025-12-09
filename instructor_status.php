<?php
include("db_connection.php");
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];

// Total Income Query
$totalIncomeSql = "SELECT SUM(final_total) AS total_income FROM orders 
                    JOIN order_items ON orders.id = order_items.order_id
                    JOIN courses ON order_items.course_id = courses.course_id
                    WHERE courses.instructor_id = ?";
$stmt = $conn->prepare($totalIncomeSql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($totalIncome);
$stmt->fetch();
$stmt->close();

// Daily Sales Query
$dailySalesSql = "SELECT SUM(final_total) AS daily_sales 
                  FROM orders 
                  JOIN order_items ON orders.id = order_items.order_id
                  JOIN courses ON order_items.course_id = courses.course_id
                  WHERE courses.instructor_id = ? AND DATE(orders.created_at) = CURDATE()";

// Monthly Sales Query
$monthlySalesSql = "SELECT SUM(final_total) AS monthly_sales 
                    FROM orders 
                    JOIN order_items ON orders.id = order_items.order_id
                    JOIN courses ON order_items.course_id = courses.course_id
                    WHERE courses.instructor_id = ? 
                    AND YEAR(orders.created_at) = YEAR(CURDATE()) 
                    AND MONTH(orders.created_at) = MONTH(CURDATE())";

// Yearly Sales Query
$yearlySalesSql = "SELECT SUM(final_total) AS yearly_sales 
                   FROM orders 
                   JOIN order_items ON orders.id = order_items.order_id
                   JOIN courses ON order_items.course_id = courses.course_id
                   WHERE courses.instructor_id = ? 
                   AND YEAR(orders.created_at) = YEAR(CURDATE())";


$dailySalesResult = $conn->prepare($dailySalesSql);
$dailySalesResult->bind_param("i", $user_id);
$dailySalesResult->execute();
$dailySalesResult->bind_result($dailySales);
$dailySalesResult->fetch();
$dailySalesResult->close();

$monthlySalesResult = $conn->prepare($monthlySalesSql);
$monthlySalesResult->bind_param("i", $user_id);
$monthlySalesResult->execute();
$monthlySalesResult->bind_result($monthlySales);
$monthlySalesResult->fetch();
$monthlySalesResult->close();

$yearlySalesResult = $conn->prepare($yearlySalesSql);
$yearlySalesResult->bind_param("i", $user_id);
$yearlySalesResult->execute();
$yearlySalesResult->bind_result($yearlySales);
$yearlySalesResult->fetch();
$yearlySalesResult->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('b6.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }

        .glass {
            background-color: rgba(50, 50, 54, 0.5);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 60%;
            margin-left: 250px;
        }

        .navbar {
            background-color: rgba(50, 50, 54, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .navbar .nav-link, .navbar .navbar-brand {
            color: white;
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: #d1c4e9;
        }

        .dropdown-menu {
            background-color: rgba(50, 50, 54, 0.9);
            border: none;
        }

        .dropdown-item {
            color: white;
        }

        .dropdown-item:hover {
            background-color: rgba(80, 80, 90, 0.9);
        }

        .dashboard-heading {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
            background-color: rgba(50, 50, 54, 0.8);
            padding: 20px;
            border-radius: 10px;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width:50%;
            margin-left: 315px;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
        }

        .custom-button {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            padding: 10px 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100px;
        }

        .custom-button:hover {
            background-color: rgba(80, 80, 90, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transform: scale(1.05);
        }

        .card {
            background-color: rgba(50, 50, 54, 0.5);
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            color: white;
        }

        .card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>

<body>

    <!-- Main Content -->
    <div class="container">

        <!-- Total Income Display -->
        <div class="glass mb-4 text-center">
            <h2>Total Earning: $<?= number_format($totalIncome, 2); ?></h2>
        </div>

        <!-- Sales Graphs -->
        <div class="glass mb-4">
            <h2 style="text-align: center; margin-bottom: 15px;">Sales Overview</h2>
            <div class="row">
                <div class="col-md-4">
                    <h5>Daily Sales</h5>
                    <canvas id="dailySalesChart"></canvas>
                </div>
                <div class="col-md-4">
                    <h5>Monthly Sales</h5>
                    <canvas id="monthlySalesChart"></canvas>
                </div>
                <div class="col-md-4">
                    <h5>Yearly Sales</h5>
                    <canvas id="yearlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctxDaily = document.getElementById('dailySalesChart').getContext('2d');
        var dailySalesChart = new Chart(ctxDaily, {
            type: 'bar',
            data: {
                labels: ['Today'],
                datasets: [{
                    label: 'Daily Sales',
                    data: [<?php echo $dailySales ?? 0; ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        var ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
        var monthlySalesChart = new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: ['This Month'],
                datasets: [{
                    label: 'Monthly Sales',
                    data: [<?php echo $monthlySales ?? 0; ?>],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            }
        });

        var ctxYearly = document.getElementById('yearlySalesChart').getContext('2d');
        var yearlySalesChart = new Chart(ctxYearly, {
            type: 'bar',
            data: {
                labels: ['This Year'],
                datasets: [{
                    label: 'Yearly Sales',
                    data: [<?php echo $yearlySales ?? 0; ?>],
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
