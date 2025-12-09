<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit;
}

include("db_connection.php");
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];

// Fetch posts
$sql = "SELECT p.post_id, p.post_content, p.reaction_count, p.created_at, s.name AS poster_name
        FROM posts p
        JOIN signup s ON p.posted_by = s.id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);

// Function to check if user has reacted
function hasReacted($post_id, $user_id, $conn) {
    $query = "SELECT reaction_id FROM reactions WHERE post_id = ? AND reacted_by = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forums</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .container {
            max-width: 700px;
            margin: 20px auto;
        }
        .post {
            background: rgba(50, 50, 54, 0.6);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .post h5 {
            margin-bottom: 10px;
        }
        .post p {
            margin-bottom: 10px;
        }
        .reaction-btn {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
        }
        .reaction-btn:hover {
            background-color: rgba(80, 80, 90, 0.9);
        }
        .comment-section {
            margin-top: 10px;
        }
        .comment-section textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .comment {
            margin-top: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <button class="reaction-btn" style="margin-top: 20px; margin-left: 380px;" onclick="window.location.href='index.php'">Go To Home</button>
    <div class="container">
        <h2 class="text-center">Forums</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($post = $result->fetch_assoc()): ?>
                <div class="post">
                    <h5><?php echo htmlspecialchars($post['poster_name']); ?></h5>
                    <small>Posted on: <?php echo htmlspecialchars($post['created_at']); ?></small>
                    <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>

                    <!-- Reaction Button -->
                    <?php $reacted = hasReacted($post['post_id'], $user_id, $conn); ?>
                    <form method="POST" action="toggle_reaction.php" style="display: inline;">
                        <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                        <p><?php echo $post['reaction_count']; ?> reaction(s)</p>
                        <button type="submit" class="reaction-btn">
                            <?php echo $reacted ? '👎' : '👍'; ?>
                        </button>
                    </form>

                    <!-- Comment Section -->
                    <div class="comment-section">
                        <form method="POST" action="add_comment.php">
                            <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                            <textarea name="comment_content" rows="2" placeholder="Write a comment..."></textarea>
                            <button type="submit" class="reaction-btn">Post Comment</button>
                        </form>

                        <!-- Display Comments -->
                        <?php
                        $comment_query = "SELECT c.comment_content, c.created_at, s.name AS commenter_name
                                          FROM comments c
                                          JOIN signup s ON c.commented_by = s.id
                                          WHERE c.post_id = ?
                                          ORDER BY c.created_at ASC";
                        $comment_stmt = $conn->prepare($comment_query);
                        $comment_stmt->bind_param("i", $post['post_id']);
                        $comment_stmt->execute();
                        $comment_result = $comment_stmt->get_result();

                        if ($comment_result->num_rows > 0):
                            while ($comment = $comment_result->fetch_assoc()): ?>
                                <div class="comment">
                                    <strong><?php echo htmlspecialchars($comment['commenter_name']); ?>:</strong>
                                    <p><?php echo htmlspecialchars($comment['comment_content']); ?></p>
                                    <small>Posted on: <?php echo htmlspecialchars($comment['created_at']); ?></small>
                                </div>
                            <?php endwhile;
                        endif;
                        ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No posts available at the moment.</p>
        <?php endif; ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
include 'footer.php';
?>
