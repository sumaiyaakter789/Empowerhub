<?php
session_start();
include("db_connection.php");
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Fetch counselor details
$sql = "SELECT name, email FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];

    if (!empty($new_password)) {
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE admin SET name = ?, email = ? password = ? WHERE admin_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $new_name, $new_email, $new_password_hashed, $admin_id);
    } else {
        $update_sql = "UPDATE admin SET name = ?, email = ? WHERE admin_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $new_name, $new_email, $admin_id);
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
}, explode(' ', $name)));
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            width: 50%;
            text-align: center;
            margin: 20px auto;
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
            margin-left: 300px;
            text-align: center;
            background-color: rgba(50, 50, 54, 0.8);
            padding: 20px;
            border-radius: 10px;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width:50%;
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

        .card img {
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            padding: 15px;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
        }
        .rounded-box {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 600px;
            margin: 20px auto;
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

        form input::placeholder {
            color: hidden;
            opacity: 10;
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Courses</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="my_courses.php">My Courses</a></li>
                            <li><a class="dropdown-item" href="#">Course Bundles</a></li>
                            <li><a class="dropdown-item" href="new_courses.php">New Course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Products</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="admin_my_product.php">Our products</a></li>
                            <li><a class="dropdown-item" href="admin_create_product.php">New Product</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Subscriptions</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="my_subscription.php">Existing Subscriptions</a></li>
                            <li><a class="dropdown-item" href="add_subscription.php">Create Subscriptions</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Vouchers</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="my_voucher.php">Existing Vouchers</a></li>
                            <li><a class="dropdown-item" href="add_voucher.php">Create Vouchers</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Notices</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="my_notices.php">Existing Notices</a></li>
                            <li><a class="dropdown-item" href="add_notice.php">Create New Notice</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Articles</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="#">My Articles</a></li>
                            <li><a class="dropdown-item" href="admin_article.php">New Article</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="review_request.php">Reviews</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Recruit</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="admin_signup.php">Register Admin</a></li>
                            <li><a class="dropdown-item" href="counselor_signup.php">Register Counselor</a></li>
                        </ul>
                    </li>
                </ul>
                
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= $initials; ?></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Placeholder -->
    <div class="container">
        <div class="dashboard-heading rounded-box">
            <h1>Welcome, <?= htmlspecialchars($name); ?>! (Admin)</h1>
            <p>Manage your courses, bundles, and more from here.</p>
        </div>
        <div class="glass">
            <h2>Update Your Information</h2>
            <form method="POST">
                <input type="text" name="counselor_name" value="<?= htmlspecialchars($name); ?>" placeholder="Full Name" required>
                <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" placeholder="Email" required>
                <input type="password" name="password" placeholder="New Password (Keep it blank if not needed)">
                <button type="submit" class="custom-button">Update</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 EmpowerHub. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
