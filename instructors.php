<?php
include("db_connection.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "
    SELECT 
        i.id AS instructor_id,
        i.name AS instructor_name,
        i.user_type,
        c.title AS course_title,
        c.thumbnail,
        c.price,
        i.email AS instructor_email
    FROM 
        signup i
    LEFT JOIN 
        courses c ON i.id = c.instructor_id
    WHERE 
        i.user_type = 'instructor'
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructors</title>
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
            margin-top: 200px;
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

        .instructor-box img {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 15px;
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
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
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

        .show-email .contact-email {
            opacity: 1;
            max-height: 100px;
        }

        .find-instructor-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto;
            text-align: center;
            width: 100%;
        }

        .custom-button {
            background-color: #89ccc5;
            color: black;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .custom-button:hover {
            background-color: #2f5955;
            color: white;
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
        <h1>Meet Our Instructors</h1>
    </div>

    <div class="instructor-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='instructor-box'>";
                echo "<h3>" . htmlspecialchars($row['instructor_name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['user_type']) . "</p>";
                echo "<p>Course: " . htmlspecialchars($row['course_title'] ?? 'No course assigned') . "</p>";
                echo "<p>Email: <span id='email-" . $row['instructor_id'] . "'>" . htmlspecialchars($row['instructor_email']) . "</span></p>";
                
                echo "</div>";
            }
        } else {
            echo "<p class='text-center'>No instructors found.</p>";
        }
        ?>
    </div>

    <div class="find-instructor-container">
        <form action="find_nearest_instructor.php" method="GET">
            <button class="custom-button" type="submit">Find Your Nearest Instructor</button>
        </form>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>
