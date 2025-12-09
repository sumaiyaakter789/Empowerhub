<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_content'])) {
    $post_content = trim($_POST['post_content']);
    if (!empty($post_content)) {
        $stmt = $conn->prepare("INSERT INTO posts (posted_by, post_content, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param('is', $user_id, $post_content);
        $stmt->execute();
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT post_id, post_content, reaction_count, created_at FROM posts WHERE posted_by = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$comments = [];
foreach ($user_posts as $post) {
    $post_id = $post['post_id'];
    $stmt = $conn->prepare("SELECT comments.comment_content, signup.name FROM comments JOIN signup ON comments.commented_by = signup.id WHERE comments.post_id = ? ORDER BY comments.created_at ASC");
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments[$post_id] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Forum</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 60%;
            margin: 0 auto;
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

        .dropdown-menu {
            background-color: rgba(50, 50, 54, 0.9);
            border: none;
        }

        .dropdown-item {
            color: white;
        }

        .dropdown-item:hover {
            background-color: rgba(80, 80, 90, 0.9);
            color: #ccc;
        }
        .post-form textarea {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: transparent;
            color: white;
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
    </style>
</head>
<body>
    <!-- Navbar -->
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Skill Exchange</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="add_skills.php">Add Skills</a></li>
                            <li><a class="dropdown-item" href="skill_matchmaking.php">Opportunities</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="enrolled_courses.php">Enrolled Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="certificate.php">Certificate</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="articlesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Mental Care</a>
                        <ul class="dropdown-menu" aria-labelledby="articlesDropdown">
                            <li><a class="dropdown-item" href="new_appointments.php">Book an Appoinment</a></li>
                            <li><a class="dropdown-item" href="my_appointments.php">My Appoinments</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_forum.php">My Forum</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="glass">
            <h1 class="text-center">Welcome to the Forum</h1>
            <form method="POST" class="post-form mb-4">
                <textarea name="post_content" rows="2" class="form-control mb-2" placeholder="What's on your mind?" required></textarea>
                <button type="submit" class="custom-button">Post</button>
            </form>
            <h2 class="text-center">Your Posts</h2>
            <?php if (!empty($user_posts)) { ?>
                <?php foreach ($user_posts as $post) { ?>
                    <div class="glass mb-3">
                        <p><?= htmlspecialchars($post['post_content']) ?></p>
                        <p><small>Posted on <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></small></p>
                        <p><strong>Reactions:</strong> <?= $post['reaction_count'] ?></p>
                        <div>
                            <h5>Comments</h5>
                            <?php if (!empty($comments[$post['post_id']])) { ?>
                                <?php foreach ($comments[$post['post_id']] as $comment) { ?>
                                    <p><strong><?= htmlspecialchars($comment['name']) ?>:</strong> <?= htmlspecialchars($comment['comment_content']) ?></p>
                                <?php } ?>
                            <?php } else { ?>
                                <p>No comments yet.</p>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>You haven't posted anything yet.</p>
            <?php } ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
