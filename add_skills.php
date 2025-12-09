<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['id'])) {
    echo "<script>alert('Please log in to add skills.'); window.location.href = 'login.php';</script>";
    exit();
}

$logged_in_user_id = $_SESSION['id'];
$sql = "SELECT s.id, s.name, s.email FROM signup s WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $logged_in_user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
} else {
    echo "<script>alert('User not found. Please log in again.'); window.location.href = 'login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skill_name = $_POST['skill_name'];

    if (!empty($skill_name)) {
        // Insert data into the skills table
        $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
        $stmt->bind_param("is", $logged_in_user_id, $skill_name);

        if ($stmt->execute()) {
            echo "<script>alert('Skill added successfully!');</script>";
            header("Location: student_dashboard.php");
        } else {
            echo "<script>alert('Error adding skill. Please try again.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please enter a skill name.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Skills</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('b6.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }

        .glass-container {
            background-color: rgba(50, 50, 54, 0.6);
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            backdrop-filter: blur(2px);
        }

        .glass-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #e4e4e4;
        }

        .glass-container p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #d6d6d6;
        }

        .glass-container input, .glass-container button {
            width: 350px;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 16px;
            outline: none;
        }

        .glass-container input {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .glass-container input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .glass-container button {
            background-color: #89ccc5;
            color: black;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .glass-container button:hover {
            background-color: #2f5955;
            color: white;
        }
    </style>
</head>
<body>
    
    <div class="glass-container">
        <h2>Add Your Skills</h2>
        <p>Welcome, <?= htmlspecialchars($user_data['name']); ?> (<?= htmlspecialchars($user_data['email']); ?>)</p>
        <form method="POST" action="">
            <input type="text" name="skill_name" placeholder="Enter your Skill" required>
            <button type="submit">Add Skill</button>
        </form>
    </div>
</body>
</html>
