<?php
include "db_connection.php";
include "header.php";
include('slider.php');

$reviewsSql = "SELECT * FROM reviews WHERE status = 'approved' ORDER BY created_at DESC";
$reviewsResult = $conn->query($reviewsSql);

$sql = "SELECT courses.*, signup.name AS instructor_name 
        FROM courses 
        LEFT JOIN signup ON courses.instructor_id = signup.id
        LIMIT 3";

$result = $conn->query($sql);

$freeCoursesSql = "SELECT courses.*, signup.name AS instructor_name 
                   FROM courses 
                   LEFT JOIN signup ON courses.instructor_id = signup.id
                   WHERE price = 0 
                   LIMIT 3";
$freeCoursesResult = $conn->query($freeCoursesSql);

$subscriptionSql = "SELECT * FROM subscriptions LIMIT 3";
$subscriptionResult = $conn->query($subscriptionSql);


if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $userQuery = "SELECT * FROM signup WHERE id = '$userId'";
    $userResult = $conn->query($userQuery);

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $userType = $user['user_type'];
    } else {
        echo "<p>User not found.</p>";
    }
} else {
    $userType = null;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <title>EmpowerHUB - Where Skill Exchange meets Mental Wellness</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            margin-top: 50px;
        }

        .glass-container h1 {
            margin: 0;
        }

        .view-more-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #89ccc5;
            color: black;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 0;
        }

        .view-more-btn:hover {
            background-color: #2f5955;
            color: white;
        }

        .course-container, .subscription-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .course-box, .subscription-box {
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

        .course-box img, .subscription-box img {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .course-box h3, .subscription-box h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .course-box p, .subscription-box p {
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

        .view-more-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #89ccc5;
            color: black;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        .view-more-btn:hover {
            background-color: #2f5955;
            color: white;
        }
        .glass-container1 {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(50, 50, 54, 0.6);
            backdrop-filter: blur(2px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 70%;
            margin: 30px auto;
            text-align: center;
        }
        .glass-container2 {
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 80%;
            margin: 30px auto;
            text-align: center;
        }
        .reviews-slider {
            margin: 0 auto;
            text-align: center;
        }

        .swiper-container {
            width: 75%;
            overflow: hidden;
        }
        
        .swiper-wrapper {
            display: flex;
        }

        .swiper-slide {
            flex: 1 0 40%;
            box-sizing: border-box;
            padding: 10px;
            text-align: left;
            background: black;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px;
        }


        /* Review Form */
        .review-form {
            max-width: 600px;
            margin: 50px auto;
            background-color: rgba(50, 50, 54, 0.6);
            border-radius: 15px;
            padding: 20px;
            color: white;
        }

        .review-form input, .review-form textarea, .review-form select {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            
        }

        .review-form button {
            background-color: #89ccc5;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .review-form button:hover {
            background-color: #2f5955;
            color: white;
        }

        .star-rating {
            display: flex;
            gap: 5px;
            justify-content: left;
            cursor: pointer;
        }
        .star {
            font-size: 2rem;
            color: gray;
            transition: color 0.3s;
        }
        .star:hover,
        .star.active {
            color: gold;
        }

    </style>
</head>
<body>
    <?php include "show_numbers.php"; ?>
    <!-- Featured Courses Section -->
    <div class="glass-container">
        <h1>Featured Courses</h1>
        <a href="courses.php" class="view-more-btn">View More</a>
    </div>

    <div class="course-container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='course-box'>";
                echo "<img src='" . htmlspecialchars($row['thumbnail']) . "' alt='" . htmlspecialchars($row['title']) . " Thumbnail' />";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>Type: " . htmlspecialchars($row['course_type']) . "</p>";
                echo "<p>Instructor: " . (isset($row['instructor_name']) ? htmlspecialchars($row['instructor_name']) : 'Not Assigned') . "</p>";

                echo "<div class='price-cart-container'>";
                echo "<h3>$" . htmlspecialchars($row['price']) . "</h3>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No courses available.</p>";
        }
        ?>
    </div>

    <!-- Subscriptions Section -->
    <div class="glass-container">
        <h1>Choose Your Best Subscription Plans</h1>
    </div>

    <div class="subscription-container">
        <?php
        if ($subscriptionResult->num_rows > 0) {
            while($subscription = $subscriptionResult->fetch_assoc()) {
                echo "<div class='subscription-box'>";
                echo "<img src='" . htmlspecialchars($subscription['thumbnail']) . "' alt='" . htmlspecialchars($subscription['name']) . " Thumbnail' />"; // If a thumbnail exists
                echo "<h3>" . htmlspecialchars($subscription['name']) . "</h3>";
                echo "<p>Price: $" . htmlspecialchars($subscription['price']) . "</p>";
            
                echo "<a href='add_to_cart.php?id=" . $subscription['id'] . "' class='add-to-cart-btn'>Buy this plan</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No subscriptions available.</p>";
        }
        ?>
    </div>

    <!-- Free Courses Section -->
    <div class="glass-container">
        <h1>Free Courses</h1>
        <a href="courses.php?search=&filter=free" class="view-more-btn">View All Free Courses</a>
    </div>

    <div class="course-container">
        <?php
        if ($freeCoursesResult->num_rows > 0) {
            while($row = $freeCoursesResult->fetch_assoc()) {
                echo "<div class='course-box'>";
                echo "<img src='" . htmlspecialchars($row['thumbnail']) . "' alt='" . htmlspecialchars($row['title']) . " Thumbnail' />";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>Type: " . htmlspecialchars($row['course_type']) . "</p>";
                echo "<p>Instructor: " . (isset($row['instructor_name']) ? htmlspecialchars($row['instructor_name']) : 'Not Assigned') . "</p>";

                echo "<div class='price-cart-container'>";
                echo "<h3>FREE!" . "</h3>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No courses available.</p>";
        }
        ?>
    </div>

    <!-- Customer Reviews Section -->
    <div class="glass-container1 reviews-slider">
        <h2>Customer Reviews</h2>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                if ($reviewsResult->num_rows > 0) {
                    while ($review = $reviewsResult->fetch_assoc()) {
                        echo "<div class='swiper-slide review-item'>";
                        echo "<h5>" . htmlspecialchars($review['name']) . "</h5>";
                        echo "<div class='review-stars'>";
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $review['rating']) {
                                echo "<span class='star active'>&#9733;</span>";
                            } else {
                                echo "<span class='star'>&#9733;</span>";
                            }
                        }
                        echo "</div>";
                        echo "<p>" . htmlspecialchars($review['comment']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No reviews yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    
    <!-- Advertisement -->
    <div class="glass-container2">
        <a href="instructor_signup.php"><img src="hire_instructor.png" style="width: 100%; height: 600px; border-radius: 10px;"></a>
    </div>

    <?php include "organization_slider.php"; ?>

    <!-- Review Form Section -->
    <div class="review-form">
        <h2>Submit Your Review</h2>
        <form action="submit_review.php" method="POST">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            
            <label for="rating">Rating:</label>
            <div class="star-rating">
                <input type="hidden" name="rating" id="rating" value="0" required>
                <span class="star" data-value="1">&#9733;</span>
                <span class="star" data-value="2">&#9733;</span>
                <span class="star" data-value="3">&#9733;</span>
                <span class="star" data-value="4">&#9733;</span>
                <span class="star" data-value="5">&#9733;</span>
            </div>
            
            <textarea name="comment" id="comment" rows="3" placeholder="Write your comments here..." required></textarea>
            
            <button type="submit">Submit Review</button>
        </form>
    </div>


</body>
</html>

<?php
include "footer.php";
?>

<script>
    
    const swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 1000,
            disableOnInteraction: false,
        },
    });

    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            stars.forEach(s => s.classList.remove('active'));           
            star.classList.add('active');
            let value = star.getAttribute('data-value');
            ratingInput.value = value;
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.classList.add('active');
                }
            });
        });
    });
    
</script>