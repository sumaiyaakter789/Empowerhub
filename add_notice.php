<?php
session_start();
include("db_connection.php");
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$admin_id = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $notice_date = $_POST['notice_date'];
    $heading = $_POST['heading'];
    $description = $_POST['description'];
    $event_type = $_POST['event_type'];
    $event_detail = $_POST['event_detail'];

    $sql = "INSERT INTO notices (admin_id, notice_date, heading, description, event_type, event_detail) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $admin_id, $notice_date, $heading, $description, $event_type, $event_detail);

    if ($stmt->execute()) {
        echo "<script>alert('Notice added successfully!'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error adding notice: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Notice</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your consistent CSS here */
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('b6.jpg');
            background-size: cover;
            color: white;
        }
        .glass {
            background-color: rgba(50, 50, 54, 0.5);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin: 20px auto;
            width: 50%;
        }
        input, select, textarea, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: transparent;
            color: white;
        }
        .dropdown {
            color: black;
        }
        button {
            width: auto;
            padding: 10px 20px;
            background-color: rgba(80, 80, 90, 0.9);
            border: none;
        }
        button:hover {
            background-color: rgba(50, 50, 54, 0.8);
        }
    </style>
</head>
<body>
    <div class="glass">
        <h2>Add New Notice</h2>
        <form method="POST">
            <input type="date" name="notice_date" required>
            <input type="text" name="heading" placeholder="Heading" required>
            <textarea name="description" placeholder="Description" rows="4" required></textarea>
            <select name="event_type" required>
                <option value="online" class="dropdown">Online</option>
                <option value="offline" class="dropdown">Offline</option>
            </select>
            <input type="text" name="event_detail" placeholder="Zoom/Meet link or location" required>
            <button type="submit">Publish Notice</button>
        </form>
        <a href="my_notices.php">Go back to Notice</a>
    </div>
</body>
</html>
