<?php
session_start();
include("db_connection.php");

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Please log in to view opportunities.'); window.location.href = 'login.php';</script>";
    exit();
}

$logged_in_user_id = $_SESSION['id']; // Get the logged-in user's ID from the session

// SQL query to fetch logged-in user's skills and matched opportunities with the instructor's email
$sql = "
    SELECT 
        s.skill_name,
        s.user_id,
        u.name AS user_name,
        u.email AS user_email,
        o.opportunity_name,
        o.description,
        o.requirements,
        o.location,
        i.email AS instructor_email  -- Instructor email
    FROM 
        skills s
    JOIN 
        signup u ON s.user_id = u.id
    JOIN 
        opportunities o ON s.skill_name = o.required_skill
    JOIN 
        signup i ON o.user_id = i.id -- Assuming instructor_id is in opportunities
    WHERE 
        s.user_id = ?"; // Filter results for the logged-in user

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $logged_in_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Skill Matchmaking</title>
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

        .glass-container {
            background-color: rgba(50, 50, 54, 0.6);
            backdrop-filter: blur(2px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 60%;
            margin: 30px auto;
            text-align: center;
            margin-top: 40px;
        }

        .match-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .match-box {
            background-color: rgba(50, 50, 54, 0.6);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 300px;
            text-align: center;
        }

        .match-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .match-box p {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .contact-info {
            display: none;
            margin-top: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px;
            border-radius: 5px;
        }

        .contact-info.active {
            display: block;
        }

        .contact-button {
            background-color: #89ccc5;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .contact-button:hover {
            background-color: #2f5955;
            color:white;
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
    </style>
    <script>
        function toggleContactInfo(id) {
            var contactInfo = document.getElementById('contact-info-' + id);
            contactInfo.classList.toggle('active');
        }
    </script>
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
                        <a class="nav-link" href="skill_matchmaking.php">Opportunities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="enrolled_courses.php">Enrolled Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="certificate.php">Certificate</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
    <div class="glass-container">
        <h1>Skill Matchmaking</h1>
        <p>Find the best opportunities matched with your skills.</p>
    </div>

    <div class="match-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='match-box'>";
                echo "<h3>Opportunity: " . htmlspecialchars($row['opportunity_name']) . "</h3>";
                echo "<p>Skill: " . htmlspecialchars($row['skill_name']) . "</p>";
                echo "<p>By: " . htmlspecialchars($row['user_name']) . "</p>";
                echo "<p>Location: " . htmlspecialchars($row['location']) . "</p>";
                echo "<p>Requirements: " . htmlspecialchars($row['requirements']) . "</p>";

                // Add a button to toggle contact info
                echo "<button class='contact-button' onclick='toggleContactInfo(" . $row['user_id'] . ")'>Contact</button>";

                // Hidden contact info
                echo "<div id='contact-info-" . $row['user_id'] . "' class='contact-info'>";
                echo "<p>Email: " . htmlspecialchars($row['instructor_email']) . "</p>";
                echo "</div>"; // End contact info
                echo "</div>"; // End match box
            }
        } else {
            echo "<p class='text-center'>No matches found.</p>";
        }
        ?>
    </div>
</body>
</html>
