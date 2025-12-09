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

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM notices WHERE notice_id = ? AND admin_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $delete_id, $admin_id);
    if ($stmt->execute()) {
        echo "<script>alert('Notice deleted successfully!'); window.location.href = 'my_notices.php';</script>";
    } else {
        echo "<script>alert('Error deleting notice: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

$sql = "SELECT notice_id, notice_date, heading, description, event_type, event_detail FROM notices WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notices</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
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
            width: 80%;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            color: white;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: rgba(80, 80, 90, 0.9);
        }
        table tr:hover {
            background-color: rgba(80, 80, 90, 0.6);
        }
        button, a {
            color: white;
            background-color: rgba(80, 80, 90, 0.9);
            padding: 4px 8px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
        }
        button:hover, a:hover {
            background-color: rgba(50, 50, 54, 0.8);
        }
    </style>
</head>
<body>
    <div class="glass">
        <h2>My Notices</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heading</th>
                    <th>Description</th>
                    <th>Event Type</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['notice_date']); ?></td>
                        <td><?= htmlspecialchars($row['heading']); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td><?= htmlspecialchars($row['event_type']); ?></td>
                        <td><?= htmlspecialchars($row['event_detail']); ?></td>
                        <td>
                            <a href="edit_notice.php?notice_id=<?= $row['notice_id']; ?>">Edit</a>
                            <a href="my_notices.php?delete_id=<?= $row['notice_id']; ?>" onclick="return confirm('Are you sure you want to delete this notice?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php">Go back to Dashboard</a>
    </div>
</body>
</html>
