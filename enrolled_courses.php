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
$sql = "SELECT name, email, location FROM signup WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $location);
$stmt->fetch();
$stmt->close();

// Fetch enrolled courses
$sql = "
    SELECT c.course_id, c.title, c.thumbnail, c.description, c.price, c.course_type, oi.status
    FROM courses c
    JOIN order_items oi ON c.course_id = oi.course_id
    JOIN orders o ON oi.order_id = o.id
    WHERE o.user_id = ?;
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$enrolled_courses = [];
while ($row = $result->fetch_assoc()) {
    $enrolled_courses[] = $row;
}
$stmt->close();

// Handle marking course as completed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_completed'])) {
    $course_id = $_POST['course_id'];

    $update_sql = "
        UPDATE order_items 
        SET status = 'completed' 
        WHERE course_id = ? AND order_id IN (
            SELECT id FROM orders WHERE user_id = ?
        );
    ";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $course_id, $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('Course marked as completed.'); window.location.href = 'enrolled_courses.php';</script>";
    } else {
        echo "<script>alert('Error updating course status: " . $stmt->error . "');</script>";
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
    <title>Enrolled Courses</title>
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
            margin-top: 25px;
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

        .course-card {
            background-color: rgba(50, 50, 54, 0.5);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .course-card img {
            border-radius: 10px;
            width: 120px;
            height: 80px;
            margin-right: 20px;
        }

        .course-card h4 {
            margin: 0;
            font-size: 20px;
        }

        .course-card p {
            margin: 5px 0;
        }

        .btn-mark-completed {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-mark-completed:hover {
            background-color: rgba(80, 80, 90, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        footer {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
        }
    </style>
</head>
<body>
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
                        <a class="nav-link" href="enrolled_courses.php">Enrolled Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="certificate.php">Certificate</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= $initials; ?></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="glass p-4">
            <h1 class="text-center">Enrolled Courses</h1>
            <?php if (!empty($enrolled_courses)): ?>
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="course-card">
                        <img src="<?= htmlspecialchars($course['thumbnail']); ?>" alt="Course Thumbnail">
                        <div>
                            <h4><?= htmlspecialchars($course['title']); ?></h4>
                            <p><?= $course['description']; ?></p>
                            <p>Type: <?= htmlspecialchars($course['course_type']); ?></p>
                            <p>Status: <?= htmlspecialchars($course['status'] ?? 'Not Completed'); ?></p>
                            <?php if (($course['status'] ?? 'not completed') !== 'completed'): ?>
                                <form method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="course_id" value="<?= $course['course_id']; ?>">
                                    <button type="submit" name="mark_completed" class="btn-mark-completed">Mark as Completed</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">You have not enrolled in any courses yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
