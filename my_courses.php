<?php
session_start();
include('db_connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$instructor_id = $_SESSION['id'];

if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];
    $result = $conn->query("SELECT * FROM courses WHERE course_id = $course_id AND instructor_id = $instructor_id");
    $course = $result->fetch_assoc();
    if ($course) {
        if (file_exists($course['thumbnail'])) {
            unlink($course['thumbnail']);
        }
        if ($course['video_file'] && file_exists($course['video_file'])) {
            unlink($course['video_file']);
        }
        $conn->query("DELETE FROM courses WHERE course_id = $course_id AND instructor_id = $instructor_id");
    }

    header("Location: my_courses.php");
    exit();
}

$sql = "SELECT * FROM courses WHERE instructor_id = $instructor_id";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('b6.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed; /* Makes the background static */
    color: white;
}

.container {
    background-color: rgba(50, 50, 54, 0.6);
    backdrop-filter: blur;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 20px;
    border-radius: 10px;
    max-width: auto;
    margin: auto;
    margin-top: 30px;
    justify-content: center;
    align-items: center;
    width: 75%;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background-color: #424c4d !important;
    color: #f8fafc !important;
    padding: 10px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

td {
    background-color: rgba(50, 50, 54, 0.8) !important;
    color: #e5e7eb !important;
    padding: 10px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

.btn-warning {
    background-color: #7a2d45 !important;
    border: none;
    color: #fff !important;
}

.btn-warning:hover {
    background-color: #42222c !important;
}

.btn-danger {
    background-color: #cc2735 !important;
    border: none;
    color: #fff !important;
}

.btn-danger:hover {
    background-color: #822129 !important;
}

.rounded-box {
    background-color: rgba(50, 50, 54, 0.8);
    color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    text-align: center;
    max-width: 600px;
    margin: 20px auto;
}

</style>
</head>
<body>
<div class="container">
    <h1 class="text-center text-white mb-4">My Courses</h1>

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Course Title</th>
            <th>Course Type</th>
            <th>Thumbnail</th>
            <th>Price</th>
            <th>Class Time</th>
            <th>Class Platform</th>
            <th>Description</th>
            <th>Video</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($course = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($course['course_type'])); ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" alt="Thumbnail" width="100">
                    </td>
                    <td><?php echo "$" . number_format($course['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($course['class_time']); ?></td>
                    <td><?php echo htmlspecialchars($course['class_platform']); ?></td>
                    <td>
                        <?php 
                            // For text courses, show a shortened content preview
                            if ($course['course_type'] === 'text') {
                                echo substr(strip_tags($course['description']), 0, 50) . '...';
                            } else {
                                echo 'N/A';
                            }
                        ?>
                    </td>
                    <td>
                        <?php if ($course['course_type'] === 'video' && $course['video_file']): ?>
                            <a href="<?php echo htmlspecialchars($course['video_file']); ?>" target="_blank">Watch Video</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_courses.php?id=<?php echo $course['course_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?delete=<?php echo $course['course_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">No courses found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
