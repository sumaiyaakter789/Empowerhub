<?php
include("db_connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the blog ID from the URL
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blog_id <= 0) {
    echo "Invalid blog ID.";
    exit;
}

// Fetch the blog details
$sql = "SELECT title, category, description, content, image_path, created_at 
        FROM articles 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Blog not found.";
    exit;
}

$blog = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
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
            max-width: 80%;
            margin: 30px auto;
            text-align: center;
        }

        .blog-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .blog-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .blog-category {
            font-size: 16px;
            color: #89ccc5;
            margin-bottom: 15px;
        }

        .blog-description {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .blog-content {
            font-size: 18px;
            text-align: justify;
            margin-bottom: 20px;
        }

        .back-btn {
            background-color: #89ccc5;
            color: black;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #2f5955;
            color: white;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="glass-container">
        <img src="<?php echo htmlspecialchars($blog['image_path']); ?>" alt="Blog Image" class="blog-image">
        <h1 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
        <p class="blog-category">Category: <?php echo htmlspecialchars($blog['category']); ?></p>
        <p class="blog-description"><?php echo htmlspecialchars($blog['description']); ?></p>
        <div class="blog-content">
            <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
        </div>
        <p><small>Published on: <?php echo date("F j, Y, g:i a", strtotime($blog['created_at'])); ?></small></p>
        <a href="blogs.php" class="back-btn">Back to Blogs</a>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
