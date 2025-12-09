<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
    header("Location: login.php");
    exit;
}

include("db_connection.php");
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];
$sql = "SELECT name, email FROM signup WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mobile = $_POST['mobile'];
    $appointment_date = $_POST['appointment_date'];
    $special_note = $_POST['special_note'];

    $sql = "INSERT INTO appointments (student_id, student_name, email, mobile_number, appointment_date, special_note, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $name, $email, $mobile, $appointment_date, $special_note);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully');</script>";
    } else {
        echo "<script>alert('Error booking appointment: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <style>
        /* General styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url(b6.jpg);
        }

        .container {
            max-width: 900px;
            position: left;
            margin-top: 20px;
        }

        .glass {
            background: rgba(50, 50, 54, 0.5);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        h2 {
            font-weight: 600;
            color: white;
            margin-bottom: 20px;
        }

        /* Form styling */
        form input,
        form textarea,
        form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        form textarea {
            resize: none;
        }

        .custom-button {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .custom-button:hover {
            background-color: rgba(80, 80, 90, 0.9);
        }

        /* Footer */
        footer {
            margin-top: 20px;
            text-align: center;
            color: #888;
            font-size: 14px;
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
            color: #ccc;
        }

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="CLogo.png" style="height: 100px; width: 100px; margin-right: 10px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_skills.php">Add skills</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="skill_matchmaking.php">Skills</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="enrolled_courses.php">Enrolled Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="certificate.php">Certificate</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="articlesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Mental Care</a>
                        <ul class="dropdown-menu" aria-labelledby="articlesDropdown">
                            <li><a class="dropdown-item" href="new_appointments.php">Book an Appoinment</a></li>
                            <li><a class="dropdown-item" href="my_appointments.php">My Appoinments</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="glass">
            <h2 style="text-align: center;">Book an Appointment today with our specialized "Mental Health Counselor"</h2>
            <form method="POST">
                <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" placeholder="Your Name" required>
                <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" placeholder="Your Email" required>
                <input type="text" name="mobile" placeholder="Mobile Number" required>
                <input type="date" name="appointment_date" required>
                <textarea name="special_note" placeholder="Special Note (Optional)" rows="4"></textarea>
                <button type="submit" class="custom-button">Book Appointment</button>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
