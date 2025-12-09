<?php
include("db_connection.php");

session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['id'];
$sql_student = "SELECT location FROM signup WHERE id = $student_id AND user_type = 'student'";
$result_student = $conn->query($sql_student);

if ($result_student->num_rows > 0) {
    $student_data = $result_student->fetch_assoc();
    $student_location = $student_data['location'];
} else {
    die("Student information not found or invalid user type.");
}

    $sql_instructors = "
    SELECT 
        i.id AS instructor_id,
        i.name AS instructor_name,
        i.location AS instructor_location,
        i.email AS instructor_email,
        c.title AS course_title
    FROM 
        signup i
    LEFT JOIN 
        courses c ON i.id = c.instructor_id
    WHERE 
        i.user_type = 'instructor'
";

    $result_instructors = $conn->query($sql_instructors);

    $nearby_instructors = [];

    if ($result_instructors->num_rows > 0) {
        while ($row = $result_instructors->fetch_assoc()) {
            $instructor_location = $row['instructor_location'];
            if (stripos($instructor_location, $student_location) !== false) {
                $nearby_instructors[] = $row;
            }
        }
    } else {
        echo "No instructors found.";
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nearest Instructors</title>
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
            margin-top: 150px;
        }

        .instructor-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .instructor-box {
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

        .instructor-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .instructor-box p {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .contact-btn {
            background-color: #89ccc5;
            color: black;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .contact-btn:hover {
            background-color: #2f5955;
            color: white;
        }

        .contact-email {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }

        .show-email {
            opacity: 1;
            max-height: 100px;
        }
    </style>
    <script>
        function toggleEmail(instructorId) {
            const emailDiv = document.getElementById('email-' + instructorId);
            if (emailDiv) {
                emailDiv.classList.toggle('show-email');
            }
        }
    </script>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="glass-container">
        <h1>Nearest Instructors</h1>
    </div>

    <div class="instructor-container">
        <?php
        if (count($nearby_instructors) > 0) {
            foreach ($nearby_instructors as $instructor) {
                echo "<div class='instructor-box'>";
                echo "<h3>" . htmlspecialchars($instructor['instructor_name']) . "</h3>";
                echo "<p>Course: " . htmlspecialchars($instructor['course_title'] ?? 'Not assigned') . "</p>";
                echo "<p>Location: " . htmlspecialchars($instructor['instructor_location']) . "</p>";
                echo "<button class='contact-btn' onclick='toggleEmail(" . $instructor['instructor_id'] . ")'>Contact</button>";
                echo "<div id='email-" . $instructor['instructor_id'] . "' class='contact-email'>" . htmlspecialchars($instructor['instructor_email']) . "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No instructors found nearby.</p>";
        }
        ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
