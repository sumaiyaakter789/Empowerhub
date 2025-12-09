<?php
session_start();
include('db_connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['admin_id'])) {
    die("You must be logged in as an admin to post an article.");
}

$instructor_id = $_SESSION['admin_id'];
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['image'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $content = $conn->real_escape_string($_POST['content']);
    $image_path = null;
    
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['cover_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }

    $sql = "INSERT INTO articles (title, category, content, image_path, instructor_id, description, admin_id, counselor_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssii", $title, $category, $content, $image_path, $instructor_id, $description, $admin_id, $counselor_id);

    if ($stmt->execute()) {
        echo "<script>alert('Article saved successfully!'); window.location.href = 'instructor_article.php';</script>";
    } else {
        echo "<script>alert('Error saving article: " . $conn->error . "');</script>";
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
    <title>Add Article- Instructor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <link href="instructor_article.css" rel="stylesheet">
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

/* Input fields, select dropdowns, and textareas with glassmorphism style */
input[type="text"],
input[type="number"],
input[type="file"],
select {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* White text */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 10px; /* Inner spacing */
    font-size: 16px; /* Font size for input fields */
    width: 100%; /* Ensure full width */
    backdrop-filter: blur(8px); /* Glass effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Slight shadow */
}

input[type="text"]:focus,
input[type="number"]:focus,
select:focus {
    outline: none;
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}

/* File input field style */
input[type="file"] {
    padding: 10px; /* Padding for consistency */
}

input[type="file"]:focus {
    background-color: rgba(255, 255, 255, 0.2); /* Darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}

/* Buttons styling */
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
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
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

/* Remove the white background for the description editor */
#description_editor {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 10px; /* Padding for consistency */
    font-size: 16px; /* Font size */
    backdrop-filter: blur(8px); /* Glass effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Slight shadow */
}

/* Apply the same glassmorphism effect to the content textarea */
textarea#content {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Rounded corners */
    padding: 10px; /* Padding for consistency */
    font-size: 16px; /* Font size */
    width: 100%; /* Ensure full width */
    backdrop-filter: blur(8px); /* Glass effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Slight shadow */
}

textarea#content:focus {
    outline: none;
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* Focus shadow */
}



    </style>
</head>
<body class="py-10 px-5">
    

    <div class="max-w-4xl mx-auto p-8 rounded glass">
        <h1 class="text-4xl font-bold mb-6 text-center">Create New Article</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-4 half-width">
                <label for="title" class="block text-sm font-medium">Title</label>
                <input type="text" name="title" id="title" placeholder="Enter the title of your article" required class="mt-1 p-2 w-full border rounded">
            </div>
            <div class="mb-4 half-width">
                <label for="category" class="block text-sm font-medium">Category</label>
                <input type="text" name="category" id="category" placeholder="Enter the category of your article" required class="mt-1 p-2 w-full border rounded">
            </div>
            <div class="mb-4 half-width">
                <label for="cover_image" class="block text-sm font-medium">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image" class="mt-1 p-2 w-full border rounded">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium">Description</label>
                <div id="description_editor" class="mt-1 p-2 w-full border rounded bg-white"></div>
                <input type="hidden" name="description" id="description">
            </div>
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium">Content</label>
                <textarea name="content" id="content" placeholder="Write the full content of your article" rows="6" required class="mt-1 p-2 w-full border rounded"></textarea>
            </div>
            <div class="mt-6 ">
                <button type="submit" class=" text-white px-4 py-2 rounded custom-button">Create Article</button>
            </div>
        </form>
    </div>

    <script>
        var quill = new Quill('#description_editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': [] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                ]
            }
        });

        quill.getModule('toolbar').addHandler('image', function() {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = function() {
                var file = input.files[0];
                if (file) {
                    var formData = new FormData();
                    formData.append('image', file);

                    fetch('', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            var range = quill.getSelection();
                            quill.insertEmbed(range.index, 'image', data.url);
                        } else {
                            alert('Failed to upload image.');
                        }
                    })
                    .catch(() => alert('Failed to upload image.'));
                }
            };
        });

        document.querySelector('form').onsubmit = function() {
            document.querySelector('#description').value = quill.root.innerHTML;
        };
    </script>
</body>
</html>
