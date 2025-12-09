<?php
include("db_connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

$sql = "
    SELECT 
        c.course_id AS course_id, 
        c.course_type,
        c.title,
        c.thumbnail,
        c.price,
        i.name AS instructor_name
    FROM 
        courses c
    JOIN 
        signup i ON c.instructor_id = i.id
    WHERE 
        (c.title LIKE '%$search%' 
        OR c.course_type LIKE '%$search%' 
        OR i.name LIKE '%$search%')
";

if ($filter === 'price_high_low') {
    $sql .= " ORDER BY c.price DESC";
} elseif ($filter === 'price_low_high') {
    $sql .= " ORDER BY c.price ASC";
} elseif ($filter === 'a_to_z') {
    $sql .= " ORDER BY c.title ASC";
} elseif ($filter === 'z_to_a') {
    $sql .= " ORDER BY c.title DESC";
} elseif ($filter === 'free') {
    $sql .= " AND c.price = 0";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
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

        .filter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 20px;
            flex-wrap: wrap;
        }

        .search-bar, .filter-select {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 5px;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            width: 20px;
        }

        .search-bar {
            width: 60%;
        }

        .filter-select {
            width: 200px;
        }

        .filter-container button {
            background-color: #89ccc5;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-container button:hover {
            background-color: #2f5955;
            color: white;
        }

        .course-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .course-box {
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

        .course-box img {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .course-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .course-box p {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .price-cart-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .price-cart-container h3 {
            font-size: 18px;
            margin: 0;
        }

        .add-to-cart-btn {
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

        .add-to-cart-btn:hover {
            background-color: #2f5955;
            color: white;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>
    
    <div class="glass-container">
        <h1>Available Courses</h1>
    </div>

    <div class="filter-container">
        <form method="GET" action="courses.php" style="display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="Search courses, instructor or type..." class="search-bar" value="<?= htmlspecialchars($search) ?>">
            <select name="filter" class="filter-select">
                <option value="" disabled selected>Filter by</option>
                <option value="price_high_low" <?= $filter === 'price_high_low' ? 'selected' : '' ?>>Price: High to Low</option>
                <option value="price_low_high" <?= $filter === 'price_low_high' ? 'selected' : '' ?>>Price: Low to High</option>
                <option value="a_to_z" <?= $filter === 'a_to_z' ? 'selected' : '' ?>>A to Z</option>
                <option value="z_to_a" <?= $filter === 'z_to_a' ? 'selected' : '' ?>>Z to A</option>
                <option value="free" <?= $filter === 'free' ? 'selected' : '' ?>>Free Courses</option>
            </select>
            <button type="submit">Apply</button>
        </form>
    </div>

    <div class="course-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='course-box'>";
                echo "<img src='" . htmlspecialchars($row['thumbnail']) . "' alt='Course Thumbnail'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>Type: " . htmlspecialchars($row['course_type']) . "</p>";
                echo "<p>Instructor: " . htmlspecialchars($row['instructor_name']) . "</p>";
                echo "<div class='price-cart-container'>";
                echo "<h6>Price: $" . htmlspecialchars($row['price']) . "</h6>";
                echo "<form action='add_to_cart.php' method='POST' class='add-to-cart-form'>";
                echo "<input type='hidden' name='course_id' value='" . htmlspecialchars($row['course_id']) . "'>";
                echo "<input type='hidden' name='title' value='" . htmlspecialchars($row['title']) . "'>";
                echo "<input type='hidden' name='thumbnail' value='" . htmlspecialchars($row['thumbnail']) . "'>";
                echo "<input type='hidden' name='price' value='" . htmlspecialchars($row['price']) . "'>";
                echo "<button type='submit' class='add-to-cart-btn'>Add to Cart</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='text-center'>No courses available.</p>";
        }
        ?>
    </div>
    <?php include("footer.php"); ?>
</body>
</html>
