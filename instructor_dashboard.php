<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'instructor') {
    header("Location: login.php");
    exit;
}

include("db_connection.php");
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];
$sql = "SELECT name, email, location FROM signup WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $location);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission to update user info
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    $new_location = $_POST['location'];

    // Update query
    if (!empty($new_password)) {
        // If password is provided, update it as well
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE signup SET name = ?, email = ?, location = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $new_name, $new_email, $new_location, $new_password_hashed, $user_id);
    } else {
        // If no password is provided, update other fields only
        $update_sql = "UPDATE signup SET name = ?, email = ?, location = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $new_name, $new_email, $new_location, $user_id);
    }

    // Execute the update query
    if ($stmt->execute()) {
        echo "<script>alert('Information updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating information: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}


$conn->close();

$initials = implode('', array_map(function ($word) {
    return strtoupper($word[0]);
}, explode(' ', $name)));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
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
            width:60%;
            margin-left:250px;
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
            background-color: rgba(50, 50, 54, 0.8); /* Same color as the navbar/footer */
            color: white; /* Text color for better contrast */
            padding: 20px; /* Inner spacing */
            border-radius: 10px; /* Smooth corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Subtle shadow for depth */
            text-align: center; /* Optional: Center-align text */
            max-width: 600px; /* Optional: Restrict width */
            margin: 20px auto; /* Center horizontally with spacing */
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

        
        form input {
        display: block;
        width: 100%;
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color:transparent;
        backdrop-filter: 2px;
        color: white;      
    }

form button {
    display: block;
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    margin-top: 15px; /* Adds space between the last input and the button */
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
    width:100px;
}

.custom-button:hover {
    background-color: rgba(80, 80, 90, 0.9);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transform: scale(1.05);
}

.half-width {
            width: 48%;
            display: inline-block;
            margin-right: 2%;
        }
        .full-width {
            width: 100%;
            display: inline-block;
        }

        form input::placeholder {
            color: black;
            opacity: 10;
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
                        <a class="nav-link" href="instructor_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="articlesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Courses</a>
                        <ul class="dropdown-menu" aria-labelledby="articlesDropdown">
                            <li><a class="dropdown-item" href="my_courses.php">My courses</a></li>
                            <li><a class="dropdown-item" href="new_courses.php">New Course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="opportunities.php">Opportunities</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Meetings</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Quizzes</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="articlesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Articles</a>
                        <ul class="dropdown-menu" aria-labelledby="articlesDropdown">
                            <li><a class="dropdown-item" href="my_articles.php">My Articles</a></li>
                            <li><a class="dropdown-item" href="instructor_article.php">New Article</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= $initials; ?></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="instructor_logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="dashboard-heading">
            <h1>Welcome <?= htmlspecialchars($name); ?>! (Instructor)</h1>
            <p>Manage your courses, bundles, and more from here.</p>
        </div>
        <?php include 'instructor_status.php'; ?>
        <!-- Instructor Information Form -->
        <div class="glass">
            <h2 style="text-align: center;">Update Your Information</h2>
            <form method="POST">
                <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" placeholder="Full Name" required>
                <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" placeholder="Email" required>
                <input type="password" name="password" placeholder="New Password (Keep blank if not needed)">
                <input type="text" name="location" value="<?= htmlspecialchars($location); ?>" placeholder="Please update your Location to find you for the learners" required>
                <button type="submit" class="custom-button">Update</button>
            </form>
        </div>
        
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>