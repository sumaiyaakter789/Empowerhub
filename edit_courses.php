<?php
include('db_connection.php');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM courses WHERE course_id = $course_id");

    if ($result->num_rows == 1) {
        $course = $result->fetch_assoc();
    } else {
        echo "Course not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_type = $_POST['course_type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $class_time = $_POST['class_time'];
    $class_platform = $_POST['class_platform'];

    // Initialize variables for thumbnail and video
    $thumbnail = $course['thumbnail'];
    $video = $course['video_file'];

    // Handle Thumbnail Upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $targetDir = "uploads/"; // Directory for thumbnails
        $fileName = basename($_FILES['thumbnail']['name']);
        $targetFilePath = $targetDir . $fileName;

        // Validate file type and size
        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
        if (in_array($_FILES['thumbnail']['type'], $allowedTypes) && $_FILES['thumbnail']['size'] <= 50000000) { // 5MB limit
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFilePath)) {
                $thumbnail = $fileName; // Update thumbnail variable
            } else {
                echo "Error uploading the thumbnail.";
            }
        } else {
            echo "Invalid thumbnail type or size.";
        }
    }

    // Handle Video Upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $targetDir = "uploads/videos/"; // Directory for videos
        $fileName = basename($_FILES['video']['name']);
        $targetFilePath = $targetDir . $fileName;

        // Validate file type and size
        $allowedTypes = array('video/mp4', 'video/avi', 'video/mkv');
        if (in_array($_FILES['video']['type'], $allowedTypes) && $_FILES['video']['size'] <= 5000000000) { // 50MB limit
            if (move_uploaded_file($_FILES['video']['tmp_name'], $targetFilePath)) {
                $video = $fileName; // Update video variable
            } else {
                echo "Error uploading the video.";
            }
        } else {
            echo "Invalid video type or size.";
        }
    }

    // Update the database
    $stmt = $conn->prepare("UPDATE courses SET course_type = ?, title = ?, description = ?, price = ?, class_time = ?, class_platform = ?, thumbnail = ?, video_file = ? WHERE course_id = ?");
    $stmt->bind_param("ssssssssi", $course_type, $title, $description, $price, $class_time, $class_platform, $thumbnail, $video, $course_id);

    if ($stmt->execute()) {
        echo "Course updated successfully!";
        header("Location: my_courses.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        /* Integrated CSS from your previous example */
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
            backdrop-filter: blur(2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 70%;
            margin-left:200px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select,
        input[type="datetime-local"] {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            width: 100%;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        input[type="datetime-local"]:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .custom-button {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            padding: 10px 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .custom-button:hover {
            background-color: rgba(80, 80, 90, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transform: scale(1.05);
        }

        .half-width {
            width: 48%;
            display: inline-block;
            margin-right: 2%;
        }

        .full-width {
            width: 100%;
            display: inline-block;
        }

        select {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
        }

        select#courseType {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            width: 100%;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        select#courseType:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        select#courseType option {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
        }

        #liveCourseFields, #videoCourseFields, #textCourseFields {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="glass">
        <h1 class="text-center text-white mb-4">Edit Course</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <!-- Course Type Selection -->
            <div class="mb-3 half-width">
                <label for="courseType" class="form-label">Course Type</label>
                <select name="course_type" id="courseType" class="form-select" onchange="showCourseFields()">
                    <option value="live" <?php echo $course['course_type'] == 'live' ? 'selected' : ''; ?>>Live Course</option>
                    <option value="video" <?php echo $course['course_type'] == 'video' ? 'selected' : ''; ?>>Video Course</option>
                    <option value="text" <?php echo $course['course_type'] == 'text' ? 'selected' : ''; ?>>Text Course</option>
                </select>
            </div>

            <!-- Course Title -->
            <div class="mb-3 half-width">
                <label for="title" class="form-label">Course Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $course['title']; ?>" required>
            </div>

            <!-- Thumbnail Upload -->
<div class="mb-3 half-width">
    <label for="thumbnail" class="form-label">Course Thumbnail</label>
    <input type="file" class="form-control" id="thumbnail" name="thumbnail">
</div>


            <!-- Description (Always visible) -->
            <div class="mb-3 full-width">
                <label for="description" class="form-label">Description</label>
                <div id="description_editor" class="text-editor" contenteditable="true"><?php echo $course['description']; ?></div>
                <input type="hidden" name="description" id="description">
            </div>

            <!-- Price -->
            <div class="mb-3 half-width">
                <label for="price" class="form-label">Course Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $course['price']; ?>" required>
            </div>

            <!-- Content Editor -->
            <!-- Text Course Fields -->
<div id="textCourseFields" class="mb-4" style="display: none;">
    <label for="content" class="form-label">Course Content (Text Course)</label>
    <div id="content_editor" contenteditable="true" class="text-editor"></div>
    <input type="hidden" name="content" id="content">
</div>


            <!-- Video Upload -->
            <div id="videoCourseFields" class="mb-4" style="display: none;">
                <label for="video" class="form-label">Upload Video</label>
                <input type="file" class="form-control" id="video" name="video">
            </div>

            <!-- Live Course Fields -->
            <div id="liveCourseFields" style="display: none;">
                <div class="mb-3 half-width">
                    <label for="classTime" class="form-label">Class Time</label>
                    <input type="datetime-local" class="form-control" id="classTime" name="class_time" value="<?php echo $course['class_time']; ?>">
                </div>
                <div class="mb-3 half-width">
                    <label for="classPlatform" class="form-label">Class Platform</label>
                    <input type="text" class="form-control" id="classPlatform" name="class_platform" value="<?php echo $course['class_platform']; ?>">
                </div>
            </div>

            


            <button type="submit" class="btn custom-button">Update Course</button>
        </form>
    </div>
</div>

<script>
    function showCourseFields() {
        var courseType = document.getElementById('courseType').value;

        // Hide all optional fields by default
        document.getElementById('liveCourseFields').style.display = 'none';
        document.getElementById('videoCourseFields').style.display = 'none';
        document.getElementById('textCourseFields').style.display = 'none';

        // Show fields based on selected course type
        if (courseType === 'live') {
            document.getElementById('liveCourseFields').style.display = 'block';
        } else if (courseType === 'video') {
            document.getElementById('videoCourseFields').style.display = 'block';
        } else if (courseType === 'text') {
            document.getElementById('textCourseFields').style.display = 'block';
        }
    }

    var quillDescription = new Quill('#description_editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': [] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
            ]
        }
    });

    var quillContent = new Quill('#content_editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': [] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
            ]
        }
    });

    // Capture the content before form submission
    document.querySelector('form').onsubmit = function() {
        document.querySelector('#description').value = quillDescription.root.innerHTML;
        document.querySelector('#content').value = quillContent.root.innerHTML;
    };

    window.onload = function() {
        showCourseFields(); // Call to show the relevant fields based on the course type
    }
</script>

</body>
</html>
