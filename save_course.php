<?php
include('db_connection.php');
session_start(); // Start the session to retrieve the logged-in instructor's ID

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the instructor is logged in and their ID is available
if (!isset($_SESSION['id'])) {
    die("Instructor not logged in.");
}

$instructor_id = $_SESSION['id']; // Get the instructor's ID from the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_type = $_POST['course_type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if (isset($_FILES['thumbnail'])) {
        $thumbnailDir = 'uploads/thumbnails/';

        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }

        $thumbnailFileName = 'thumbnail_' . uniqid() . '.' . pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        $thumbnailFilePath = $thumbnailDir . $thumbnailFileName;

        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailFilePath)) {
            $videoFilePath = NULL;
            if ($course_type == 'video' && isset($_FILES['video_file'])) {
                $videoDir = 'uploads/videos/';

                if (!is_dir($videoDir)) {
                    mkdir($videoDir, 0755, true);
                }

                $videoFileName = 'video_' . uniqid() . '.' . pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);
                $videoFilePath = $videoDir . $videoFileName;

                if (!move_uploaded_file($_FILES['video_file']['tmp_name'], $videoFilePath)) {
                    echo "Failed to upload the video file.";
                    exit();
                }
            }

            $class_time = NULL;
            $class_platform = NULL;
            if ($course_type == 'live') {
                $class_time = $_POST['class_time'];
                $class_platform = $_POST['class_platform'];
            }

            // Insert the course details along with the instructor ID
            $sql = "INSERT INTO courses (course_type, title, description, price, thumbnail, class_time, class_platform, video_file, instructor_id)
                    VALUES ('$course_type', '$title', '$description', '$price', '$thumbnailFilePath', '$class_time', '$class_platform', '$videoFilePath', '$instructor_id')";

            if ($conn->query($sql) === TRUE) {
                echo "Course added successfully!";
                header("Location: my_courses.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Failed to upload the thumbnail.";
        }
    } else {
        echo "No thumbnail uploaded.";
    }
}

$conn->close();
?>
