<?php
// Start session
session_start();

// Database connection
include('db_connection.php');

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle image upload for Quill editor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_name = time() . '_' . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        echo json_encode(['success' => true, 'url' => $target_file]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['image'])) {
    $subscription_name = $conn->real_escape_string($_POST['subscription_name']);
    $subscription_price = floatval($_POST['subscription_price']);

    // Handle file upload for subscription image
    $image_url = null;
    if (isset($_FILES['subscription_image']) && $_FILES['subscription_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['subscription_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['subscription_image']['tmp_name'], $target_file)) {
            $image_url = $target_file;
        }
    }

    // Insert subscription into the database
    $sql = "INSERT INTO subscriptions (name, price, thumbnail) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $subscription_name, $subscription_price, $image_url);

    if ($stmt->execute()) {
        // If insertion is successful, show an alert and redirect
        echo "<script>alert('Subscription added successfully!');</script>";
        // Use location.reload() to fully reload the page after submission
        echo "<script>window.location.href = window.location.href;</script>";
        exit;
    } else {
        echo "<script>alert('Error adding subscription: " . $conn->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
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
        input, select, #description_editor {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 10px;
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
        }
        .custom-button:hover {
            background-color: rgba(80, 80, 90, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="py-10 px-5">
    <div class="max-w-4xl mx-auto p-8 rounded glass">
        <h1 class="text-4xl font-bold mb-6 text-center">Add New Subscription</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="subscription_name" class="block text-sm font-medium">Subscription Name</label>
                <input type="text" name="subscription_name" id="subscription_name" required class="mt-1 p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label for="subscription_price" class="block text-sm font-medium">Price (Taka)</label>
                <input type="number" name="subscription_price" id="subscription_price" step="0.01" min="0" required class="mt-1 p-2 w-full rounded">
            </div>
            
            <div class="mb-4">
                <label for="subscription_image" class="block text-sm font-medium">Subscription Image</label>
                <input type="file" name="subscription_image" id="subscription_image" class="mt-1 p-2 w-full rounded">
            </div>
            <div class="mt-6">
                <button type="submit" class="custom-button">Add Subscription</button>
            </div>
        </form>
    </div>

    <script>
        var quill = new Quill('#description_editor', {
            theme: 'snow',
            modules: {
                toolbar: [['bold', 'italic', 'underline'], [{ 'align': [] }], ['link', 'image']]
            }
        });

        document.querySelector('form').onsubmit = function() {
            document.querySelector('#description').value = quill.root.innerHTML;
        };
    </script>
</body>
</html>
