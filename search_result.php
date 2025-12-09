<?php
session_start();
include("db_connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_results = [];
$search_query = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_query'])) {
    $search_query = trim($_POST['search_query']);

    if (!empty($search_query)) {
        $like_query = "%" . $conn->real_escape_string($search_query) . "%";

        // Search in courses table
        $query_courses = $conn->prepare("SELECT title AS name, description, 'course' AS type FROM courses WHERE title LIKE ? OR description LIKE ?");
        $query_courses->bind_param("ss", $like_query, $like_query);
        $query_courses->execute();
        $results_courses = $query_courses->get_result();
        while ($row = $results_courses->fetch_assoc()) {
            $search_results[] = $row;
        }

        // Search in products table
        $query_products = $conn->prepare("SELECT name, description, 'product' AS type FROM products WHERE name LIKE ? OR description LIKE ?");
        $query_products->bind_param("ss", $like_query, $like_query);
        $query_products->execute();
        $results_products = $query_products->get_result();
        while ($row = $results_products->fetch_assoc()) {
            $search_results[] = $row;
        }

        // Search in notices table
        $query_notices = $conn->prepare("SELECT heading AS name, description, 'notice' AS type FROM notices WHERE heading LIKE ? OR description LIKE ?");
        $query_notices->bind_param("ss", $like_query, $like_query);
        $query_notices->execute();
        $results_notices = $query_notices->get_result();
        while ($row = $results_notices->fetch_assoc()) {
            $search_results[] = $row;
        }

        // Search in posts table
        $query_posts = $conn->prepare("SELECT post_content AS name, '' AS description, 'post' AS type FROM posts WHERE post_content LIKE ?");
        $query_posts->bind_param("s", $like_query);
        $query_posts->execute();
        $results_posts = $query_posts->get_result();
        while ($row = $results_posts->fetch_assoc()) {
            $search_results[] = $row;
        }

        $query_courses->close();
        $query_products->close();
        $query_notices->close();
        $query_posts->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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

        .search-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .search-result-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 20px auto;
            max-width: 80%;
        }

        .search-result-card {
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

        .search-result-card h5 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .search-result-card p {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .search-result-card span {
            font-size: 12px;
            font-weight: bold;
            color: #89ccc5;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="glass-container">
        <h1>Search Results for: "<?= htmlspecialchars($search_query); ?>"</h1>
    </div>

    <div class="search-result-container">
        <?php if (!empty($search_results)): ?>
            <?php foreach ($search_results as $result): ?>
                <?php
                    // Determine the redirect URL based on the category type
                    $category = strtolower($result['type']);
                    $redirect_url = "#"; // Default to "#" if no valid type is found

                    switch ($category) {
                        case 'course':
                            $redirect_url = "courses.php";
                            break;
                        case 'product':
                            $redirect_url = "stores.php";
                            break;
                        case 'notice':
                            $redirect_url = "notices.php";
                            break;
                        case 'post':
                            $redirect_url = "forums.php";
                            break;
                    }
                ?>
                <div class="search-result-card">
                    <h5>
                        <a href="<?= htmlspecialchars($redirect_url); ?>"><?= htmlspecialchars($result['name']); ?></a>
                    </h5>
                    <p><?= htmlspecialchars($result['description'] ?? 'No description available'); ?></p>
                    <span>Category: <?= ucfirst($result['type']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No results found for your search.</p>
        <?php endif; ?>
    </div>


    <?php include("footer.php"); ?>
</body>
</html>
