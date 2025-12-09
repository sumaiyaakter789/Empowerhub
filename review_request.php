<?php
session_start();
include "db_connection.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
$sql = "SELECT id, user_id, name, email, rating, comment, created_at FROM reviews WHERE status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('b6.jpg');
            background-size: cover;
            background-position: center;
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
            width: 100%;
            margin-top: 50px;
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
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .review-card {
            background-color: rgba(50, 50, 54, 0.5);
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 15px 0;
            border-radius: 10px;
            color: white;
            transition: transform 0.3s;
            width: 30%;
        }

        .review-card:hover {
            transform: translateY(-10px);
        }

        footer {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
        }

        @media (max-width: 768px) {
            .review-card {
                width: 45%; /* For smaller screens, show 2 cards per row */
            }
        }

        @media (max-width: 480px) {
            .review-card {
                width: 100%; /* For very small screens, show 1 card per row */
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php"><img src="CLogo.png" style="height: 100px; width: 100px; margin-right: 10px;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="review_request.php">Reviews</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Review Requests Section -->
    <div class="container">
        <div class="glass">
            <h1>Review Requests</h1>
            <p>Manage and review user feedback here.</p>
        </div>

        <!-- Display Pending Reviews -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="review-card">
                    <h5>User: <?= htmlspecialchars($row['name']); ?> (<?= htmlspecialchars($row['email']); ?>)</h5>
                    <p>Rating: <?= htmlspecialchars($row['rating']); ?> / 5</p>
                    <p>"<?= htmlspecialchars($row['comment']); ?>"</p>
                    <small>Submitted on: <?= htmlspecialchars($row['created_at']); ?></small>
                    <br>
                    <form method="POST" action="update_review_status.php" class="mt-3">
                        <input type="hidden" name="review_id" value="<?= $row['id']; ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                        <button type="submit" name="action" value="decline" class="btn btn-danger">Decline</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="glass mt-4">
                <p>No pending reviews at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 EmpowerHub. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
