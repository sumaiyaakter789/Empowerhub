<?php
session_start();

include("db_connection.php");
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['counselor_id'])) {
    header("Location: counselor_login.php");
    exit;
}

$counselor_id = $_SESSION['counselor_id'];

// Fetch counselor details
$sql = "SELECT counselor_name, email, mobile_number FROM counselors WHERE counselor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $counselor_id);
$stmt->execute();
$stmt->bind_result($counselor_name, $email, $mobile_number);
$stmt->fetch();
$stmt->close();

// Update counselor details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['counselor_name'];
    $new_email = $_POST['email'];
    $new_mobile = $_POST['mobile_number'];
    $new_password = $_POST['password'];

    if (!empty($new_password)) {
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE counselors SET counselor_name = ?, email = ?, mobile_number = ?, password = ? WHERE counselor_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $new_name, $new_email, $new_mobile, $new_password_hashed, $counselor_id);
    } else {
        $update_sql = "UPDATE counselors SET counselor_name = ?, email = ?, mobile_number = ? WHERE counselor_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $new_name, $new_email, $new_mobile, $counselor_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Information updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating information: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
$initials = implode('', array_map(function ($word) {
    return strtoupper($word[0]);
}, explode(' ', $counselor_name)));
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Dashboard</title>
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
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            width: 60%;
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

        form input {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: transparent;
            color: white;
        }

        form button {
            display: block;
            width: 25%;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
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
                        <a class="nav-link" href="#"><?= $initials; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="counselor_logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container">
        <div class="dashboard-heading">
            <h1>Welcome, <?= htmlspecialchars($counselor_name); ?>! (Counselor)</h1>
        </div>
        <div class="glass">
            <h2>Update Your Information</h2>
            <form method="POST">
                <input type="text" name="counselor_name" value="<?= htmlspecialchars($counselor_name); ?>" placeholder="Full Name" required>
                <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" placeholder="Email" required>
                <input type="text" name="mobile_number" value="<?= htmlspecialchars($mobile_number); ?>" placeholder="Mobile Number" required>
                <input type="password" name="password" placeholder="New Password (Keep it blank if not needed)">
                <button type="submit" class="custom-button">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
