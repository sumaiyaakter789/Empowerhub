<?php
include('db_connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
    $article_id = $_GET['delete'];
    $result = $conn->query("SELECT * FROM articles WHERE id = $article_id");
    $article = $result->fetch_assoc();

    if ($article['image_path'] && file_exists($article['image_path'])) {
        unlink($article['image_path']);
    }

    $conn->query("DELETE FROM articles WHERE id = $article_id");

    header("Location: my_articles.php");
    exit();
}

$sql = "SELECT * FROM articles";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Articles - Instructor</title>
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
            width: 60%;
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
    <h1 class="text-center text-white mb-4">My Articles</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Description</th>
                <th>Thumbnail</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($article = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td><?php echo htmlspecialchars($article['category']); ?></td>
                        <td><?php echo htmlspecialchars(substr($article['description'], 0, 50)) . '...'; ?></td>
                        <td>
                            <?php if (!empty($article['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="Thumbnail" width="100">
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete=<?php echo $article['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No articles found.</td>
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
