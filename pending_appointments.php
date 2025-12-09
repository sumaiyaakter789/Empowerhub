<?php
session_start();


include("db_connection.php");
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$counselor_id = $_SESSION['counselor_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $meeting_link = $_POST['meeting_link'] ?? null;
    $update_sql = "UPDATE appointments SET status = 'received', counselor_id = ?, meeting_link = ? WHERE appointment_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("isi", $counselor_id, $meeting_link, $appointment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment marked as received.');</script>";
    } else {
        echo "<script>alert('Error updating appointment: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

$sql = "SELECT appointment_id, student_name, email, mobile_number, appointment_date, special_note, status 
        FROM appointments 
        WHERE status = 'pending' 
        AND (counselor_id IS NULL OR counselor_id = ?) 
        ORDER BY appointment_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $counselor_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Appointments</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('b6.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }

        .glass {
            background-color: rgba(50, 50, 54, 0.5);
            padding: 10px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            width: 90%;
            margin: 30px auto;
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

        .dashboard-heading {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
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
        }

        .custom-button:hover {
            background-color: rgba(80, 80, 90, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transform: scale(1.05);
        }

        .dashboard-heading {
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 300px;
            text-align: center;
            background-color: rgba(50, 50, 54, 0.8);
            padding: 20px;
            border-radius: 10px;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width:50%;
        }
        form input::placeholder {
            color: hidden;
            opacity: 10;
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
            color: #ccc;
        }

        h2 {
            font-weight: 600;
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="CLogo.png" alt="Logo" style="height: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="counselor_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pending_appointments.php">Pending Appointments</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="articlesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Articles</a>
                        <ul class="dropdown-menu" aria-labelledby="articlesDropdown">
                            <li><a class="dropdown-item" href="new_article.php">New Article</a></li>
                            <li><a class="dropdown-item" href="my_articles.php">My Articles</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Pending Appointments -->
    <div class="container">
        <div class="glass">
            <h2>Pending Appointments</h2>
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Appointment Date</th>
                        <th>Special Note</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['appointment_id']); ?></td>
                                <td><?= htmlspecialchars($row['student_name']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['mobile_number']); ?></td>
                                <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                                <td><?= htmlspecialchars($row['special_note']); ?></td>
                                <td><?= ucfirst(htmlspecialchars($row['status'])); ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="appointment_id" value="<?= $row['appointment_id']; ?>">
                                        <input type="text" name="meeting_link" placeholder="Meeting Link" required>
                                        <button type="submit" class="custom-button">Received</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No pending appointments.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
