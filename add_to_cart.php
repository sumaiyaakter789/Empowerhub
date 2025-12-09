<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Initialize the cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$course_id = htmlspecialchars($_POST['course_id']);
$title = htmlspecialchars($_POST['title']);
$thumbnail = htmlspecialchars($_POST['thumbnail']);
$price = htmlspecialchars($_POST['price']);
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

// Check if the course already exists in the cart
$course_found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['course_id'] == $course_id) {
        $item['qty'] += $qty;
        $course_found = true;
        break;
    }
}

// If the course is not found, add it as a new entry
if (!$course_found) {
    $_SESSION['cart'][] = array(
        'course_id' => $course_id,
        'title' => $title,
        'thumbnail' => $thumbnail,
        'price' => $price,
        'qty' => $qty
    );
}

// Redirect back to the courses page
header("Location: courses.php");
exit();
?>
