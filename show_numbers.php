<?php
include "db_connection.php";

$totalCoursesSql = "SELECT COUNT(*) AS total_courses FROM courses";
$totalCoursesResult = $conn->query($totalCoursesSql);
$totalCourses = $totalCoursesResult->fetch_assoc()['total_courses'];

$totalStudentsSql = "SELECT COUNT(*) AS total_students FROM signup WHERE user_type = 'student'";
$totalStudentsResult = $conn->query($totalStudentsSql);
$totalStudents = $totalStudentsResult->fetch_assoc()['total_students'];

$totalInstructorsSql = "SELECT COUNT(*) AS total_instructors FROM signup WHERE user_type = 'instructor'";
$totalInstructorsResult = $conn->query($totalInstructorsSql);
$totalInstructors = $totalInstructorsResult->fetch_assoc()['total_instructors'];

$totalCounselorsSql = "SELECT COUNT(*) AS total_counselors FROM counselors";
$totalCounselorsResult = $conn->query($totalCounselorsSql);
$totalCounselors = $totalCounselorsResult->fetch_assoc()['total_counselors'];

$totalProductsSql = "SELECT COUNT(*) AS total_products FROM products";
$totalProductsResult = $conn->query($totalProductsSql);
$totalProducts = $totalProductsResult->fetch_assoc()['total_products'];
?>

<div class="container my-5">
    <div class="d-flex justify-content-between text-center">
        <!-- Total Courses -->
        <div class="card bg-dark text-white shadow rounded flex-item">
            <div class="card-body">
                <h2><?php echo $totalCourses; ?></h2>
                <p>Total Courses</p>
            </div>
        </div>

        <!-- Total Students -->
        <div class="card bg-dark text-white shadow rounded flex-item">
            <div class="card-body">
                <h2><?php echo $totalStudents; ?></h2>
                <p>Happy Students</p>
            </div>
        </div>

        <!-- Total Instructors -->
        <div class="card bg-dark text-white shadow rounded flex-item">
            <div class="card-body">
                <h2><?php echo $totalInstructors; ?></h2>
                <p>Our Instructors</p>
            </div>
        </div>

        <!-- Total Mental Counselors -->
        <div class="card bg-dark text-white shadow rounded flex-item">
            <div class="card-body">
                <h2><?php echo $totalCounselors; ?></h2>
                <p>Mental Counselors</p>
            </div>
        </div>

        <!-- Total Products -->
        <div class="card bg-dark text-white shadow rounded flex-item">
            <div class="card-body">
                <h2><?php echo $totalProducts; ?></h2>
                <p>Store Products</p>
            </div>
        </div>
    </div>
</div>

<style>
    .container {
        max-width: 90%;
        padding: 0 20px;
    }

    .d-flex {
        display: flex;
        gap: 15px;
        flex-wrap: nowrap;
    }

    .flex-item {
        flex: 1;
        padding: 20px;
    }

    .card {
        padding: 20px;
        border-radius: 15px;
        transition: transform 0.3s ease;
        width: 100%;
        height: auto;
    }

    .card:hover {
        transform: scale(1.08);
    }

    .card-body h2 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .card-body p {
        font-size: 1.2rem;
    }
</style>
