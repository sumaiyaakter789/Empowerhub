<?php
session_start();
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id'])) {
        header('Location: login.php');
        exit;
    }

    $post_id = $_POST['post_id'];
    $comment_content = $_POST['comment_content'];
    $commented_by = $_SESSION['id'];

    if (empty($comment_content)) {
        echo json_encode(['status' => 'error', 'message' => 'Comment content cannot be empty.']);
        exit;
    }

    $sql = "INSERT INTO comments (post_id, commented_by, comment_content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $commented_by, $comment_content);

    if ($stmt->execute()) {
        header('Location: forums.php');
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add comment.']);
    }
    $stmt->close();
    $conn->close();
}
?>
