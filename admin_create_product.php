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
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $product_description = $conn->real_escape_string($_POST['description']);
    $product_price = floatval($_POST['product_price']);
    $product_type = $conn->real_escape_string($_POST['product_type']);

    // Handle file upload for product image
    $image_url = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['product_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $image_url = $target_file;
        }
    }

    $sql = "INSERT INTO products (name, description, price, product_type, image_url, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $product_name, $product_description, $product_price, $product_type, $image_url);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location.href = 'admin_my_product.php';</script>";
    } else {
        echo "<script>alert('Error adding product: " . $conn->error . "');</script>";
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
    <title>Add Product</title>
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
    background-color: rgba(50, 50, 54, 0.5); /* Semi-transparent color */
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3); /* Adds a subtle border */
    padding: 20px;
    border-radius: 10px; /* Smooth corners */
    backdrop-filter: blur(2px); /* Creates the blur effect */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Adds a slight shadow for depth */
}
/* Glass style for input fields, dropdowns, and textareas */
input[type="text"],
input[type="number"],
input[type="file"],
select,
/* Quill description editor background and text styles */
/* Quill editor container styles */
#description_editor {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: rgba(255, 255, 255, 0.9); /* White text */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 10px; /* Inner spacing */
    backdrop-filter: blur(8px); /* Glass effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Slight shadow */
}

/* Quill editor placeholder styling */






/* Quill editor toolbar adjustments for glass theme */

input[type="file"] {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background to match other fields */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded edges */
    padding: 10px; /* Padding for consistency */
    font-size: 16px; /* Font size */
}

input[type="file"]:focus {
    outline: none;
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}



/* Buttons styling (already implemented) */
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

        select {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background to match other fields */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded edges */
    padding: 10px; /* Padding for consistency */
    font-size: 16px; /* Font size */
}

select:focus {
    outline: none;
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}

/* Remove custom background for option elements */
select option {
    background-color: #545c56; /* Keep the option background as you want */
    color: white; /* Text color for the options */
}


    </style>
</head>
<body class="py-10 px-5">



    <div class="max-w-4xl mx-auto p-8 rounded glass">
        <h1 class="text-4xl font-bold mb-6 text-center">Add New Product</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-4 half-width">
                <label for="product_type" class="block text-sm font-medium">Product Type</label>
                <select name="product_type" id="product_type" required class="mt-1 p-2 w-full rounded">
    <option value="software">Software</option>
    <option value="device">Device</option>
    <option value="stationary">Stationary</option>
</select>

            </div>
            <div class="mb-4 half-width">
                <label for="product_name" class="block text-sm font-medium">Product Name</label>
                <input type="text" name="product_name" id="product_name" required class="mt-1 p-2 w-full rounded">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium">Description</label>
                <div id="description_editor" class="mt-1 p-2 w-full border rounded"></div>
                <input type="hidden" name="description" id="description">
            </div>
            <div class="mb-4 half-width">
                <label for="product_price" class="block text-sm font-medium">Price (Taka)</label>
                <input type="number" name="product_price" id="product_price" step="0.01" min="0" required class="mt-1 p-2 w-full rounded">
            </div>
            <div class="mb-4 half-width">
                <label for="product_image" class="block text-sm font-medium">Product Image</label>
                <input type="file" name="product_image" id="product_image" class="mt-1 p-2 w-full rounded">
            </div>
            <div class="mt-6">
            <button type="submit" class="custom-button">Add Product</button>

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
