<?php
include("db_connection.php");
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opportunity_name = $_POST['opportunity_name'];
    $description = $_POST['description'];
    $required_skill = $_POST['required_skill'];
    $requirements = $_POST['requirements'];
    $location = $_POST['location'];

    // Assume the logged-in user's email is stored in the session
    $user_email = $_SESSION['email'] ?? null;

    if ($user_email && !empty($opportunity_name) && !empty($required_skill)) {
        $stmt = $conn->prepare("
            INSERT INTO opportunities (opportunity_name, description, required_skill, requirements, location, user_id)
            SELECT ?, ?, ?, ?, ?, id FROM signup WHERE email = ?
        ");
        $stmt->bind_param("ssssss", $opportunity_name, $description, $required_skill, $requirements, $location, $user_email);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Opportunity added successfully!');</script>";
                header("Location: instructor_dashboard.php");
            } else {
                echo "<script>alert('No data inserted. Check the email or query logic.');</script>";
            }
        } else {
            echo "<script>alert('SQL Error: " . $conn->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill all required fields or log in.');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Opportunity</title>
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
            max-width: 500px;
            text-align: center;
        }

        .glass-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #e4e4e4;
        }

        .glass-container input, .glass-container textarea, .glass-container button {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 16px;
            outline: none;
        }

        .glass-container input, .glass-container textarea {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .glass-container input::placeholder, .glass-container textarea::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .glass-container button {
            background-color: #89ccc5;
            color: black;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
        }

        .glass-container button:hover {
            background-color: #2f5955;
            color: white;
        }
    </style>
</head>
<body>
    <div class="glass-container">
        <h2>Add Opportunity</h2>
        <form method="POST" action="opportunities.php">
            <input type="text" name="opportunity_name" placeholder="Opportunity Name" required>
            <textarea name="description" placeholder="Opportunity Description" rows="3"></textarea>
            <input type="text" name="required_skill" placeholder="I want to learn" required>
            <textarea name="requirements" placeholder="Requirements" rows="3"></textarea>
            <input type="text" name="location" placeholder="Location">
            <button type="submit">Add Opportunity</button>
        </form>
    </div>
</body>
</html>
