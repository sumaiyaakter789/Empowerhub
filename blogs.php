<?php
include("db_connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch blogs
$sql = "SELECT id, title, description, image_path FROM articles ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
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
            margin-top: 180px;
        }

        .blog-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .blog-box {
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

        .blog-box img {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .blog-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .blog-box p {
            font-size: 14px;
            margin-bottom: 8px;
            height: 50px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .read-more-btn {
            background-color: #89ccc5;
            color: black;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .read-more-btn:hover {
            background-color: #2f5955;
            color: white;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="glass-container">
        <h1>Our Blogs</h1>
    </div>

    <div class="blog-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='blog-box'>";
                echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Blog Image'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . htmlspecialchars(substr($row['description'], 0, 100)) . "...</p>";
                echo "<a href='blog_details.php?id=" . htmlspecialchars($row['id']) . "' class='read-more-btn'>Read More</a>";
                echo "</div>";
            }
        } else {
            echo "<p class='text-center'>No blogs available.</p>";
        }
        ?>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
