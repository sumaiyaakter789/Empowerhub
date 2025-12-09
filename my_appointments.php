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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $sql = "DELETE FROM appointments WHERE appointment_id = ? AND student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $appointment_id, $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('Appointment cancelled successfully');</script>";
    } else {
        echo "<script>alert('Error cancelling appointment: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

$sql = "
    SELECT 
        a.appointment_id, 
        a.appointment_date, 
        a.status,
        a.meeting_link,
        a.special_note, 
        c.counselor_name AS counselor_name
    FROM 
        appointments a
    LEFT JOIN 
        counselors c 
    ON 
        a.counselor_id = c.counselor_id
    WHERE 
        a.student_id = ?
    ORDER BY 
        a.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
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
    <title>My Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url(b6.jpg);
        }
        .container {
            max-width: 900px;
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
            text-align: center;
        }
        table {
            width: 100%;
            color: white;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
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
            <h2>My Appointments</h2>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Appointment Date</th>
                            <th>Status</th>
                            <th>Counselor</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                                <td><?= htmlspecialchars(ucfirst($row['status'])); ?></td>
                                <td><?= htmlspecialchars($row['counselor_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php if (!empty($row['meeting_link'])): ?>
                                        <a href="<?= htmlspecialchars($row['meeting_link']); ?>" target="_blank" class="custom-button">Join Session</a>
                                    <?php else: ?>
                                        <p style="color: white;">Link not available</p>
                                    <?php endif; ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="appointment_id" value="<?= $row['appointment_id']; ?>">
                                        <button type="submit" name="cancel_appointment" class="custom-button">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: white; text-align: center;">No appointments found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
