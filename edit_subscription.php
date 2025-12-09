<?php
include('db_connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$subscription_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($subscription_id) {
    // Fetch the existing subscription data
    $result = $conn->query("SELECT * FROM subscriptions WHERE id = '$subscription_id'");

    if ($result->num_rows == 1) {
        $subscription = $result->fetch_assoc();

        // Handling form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $name = $_POST['name'];
            $price = $_POST['price'];
            $thumbnail = $_FILES['thumbnail'];

            // Check if a new thumbnail is uploaded
            if ($thumbnail['name']) {
                // Delete old thumbnail file if it exists
                if (file_exists($subscription['thumbnail'])) {
                    unlink($subscription['thumbnail']);
                }

                // Upload new thumbnail
                $target_dir = "uploads/thumbnails/";
                $thumbnail_path = $target_dir . basename($thumbnail['name']);
                if (move_uploaded_file($thumbnail['tmp_name'], $thumbnail_path)) {
                    $subscription['thumbnail'] = $thumbnail_path;
                } else {
                    echo "Error uploading the thumbnail image.";
                }
            }

            // Update subscription in the database
            $update_query = "UPDATE subscriptions SET name = '$name', price = '$price', thumbnail = '$subscription[thumbnail]' WHERE id = '$subscription_id'";

            if ($conn->query($update_query) === TRUE) {
                echo "Subscription updated successfully.";
                header("Location: my_subscription.php"); // Redirect after update
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "Subscription not found.";
    }
} else {
    echo "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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

        .glass {
            background-color: rgba(50, 50, 54, 0.5);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .container{
            width:60%;
        }

        input, textarea, select {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            width: 100%;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        input[type="file"] {
            padding: 10px;
        }

        label {
            color: white;
        }

        .custom-button {
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            padding: 10px 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .custom-button:hover {
            background-color: rgba(80, 80, 90, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transform: scale(1.05);
        }

        .half-width {
            width: 48%;
            display: inline-block;
            margin-right: 2%;
        }

        .full-width {
            width: 100%;
            display: inline-block;
        }

    </style>
</head>
<body>
<div class="container mt-5 glass ">
    <h1 class="text-center mb-4">Edit Subscription</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-4 half-width">
            <label for="name" class="form-label">Subscription Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($subscription['name']); ?>" required>
        </div>
        <div class="mb-4 half-width">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($subscription['price']); ?>" required step="0.01">
        </div>
        <div class="mb-4 half-width">
            <label for="thumbnail" class="form-label">Thumbnail Image</label>
            <input type="file" name="thumbnail" id="thumbnail">
            <?php if ($subscription['thumbnail']): ?>
                <p>Current Image: <img src="<?php echo $subscription['thumbnail']; ?>" alt="Subscription Thumbnail" width="100"></p>
            <?php endif; ?>
        </div>
        <div>
            <button type="submit" class="custom-button">Update Subscription</button>
        </div>
    </form>
</div>
</body>
</html>
