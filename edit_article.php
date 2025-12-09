<?php
include('db_connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM articles WHERE id = $article_id");

    if ($result->num_rows == 1) {
        $article = $result->fetch_assoc();
    } else {
        echo "Article not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $cover_image = $article['image_path'];

    if (!empty($_FILES['cover_image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
        
        if (getimagesize($_FILES["cover_image"]["tmp_name"])) {
            $safe_filename = basename($_FILES["cover_image"]["name"]);
            move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_dir . $safe_filename);
            $cover_image = $target_dir . $safe_filename;
        } else {
            echo "The uploaded file is not a valid image.";
            exit();
        }
    }

    $description = strip_tags($description, '<b><i><u><a><ul><ol><li><img>');

    $sql = "UPDATE articles SET 
                title = '$title',
                category = '$category',
                description = '$description',
                content = '$content',
                image_path = '$cover_image'
            WHERE id = $article_id";

    if ($conn->query($sql) === TRUE) {
        echo "Article updated successfully!";
        header("Location: my_articles.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
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

        #description {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
        }

        #description .ql-editor {
            background-color: transparent;
            color: white;
        }

        #description .ql-toolbar {
            background-color: rgba(50, 50, 54, 0.5);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px 8px 0 0;
        }

        #description:focus {
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        select {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
        }

        select:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        select option {
            background-color: #545c56;
            color: white;
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
<div class="container mt-5 glass">
    <h1 class="text-center mb-4">Edit Article</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-4 half-width">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
        </div>
        <div class="mb-4 half-width">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($article['category']); ?>" required>
        </div>
        <div class="mb-4 half-width">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" name="cover_image" id="cover_image">
            <?php if ($article['image_path']): ?>
                <p>Current Image: <img src="<?php echo $article['image_path']; ?>" alt="Cover Image" width="100"></p>
            <?php endif; ?>
        </div>
        <div class="mb-4">
            <label for="description" class="form-label">Description</label>
            <div id="description" class="form-control" style="height: 200px;">
                <?php echo htmlspecialchars($article['description']); ?>
            </div>
            <textarea name="description" id="description-input" style="display:none;"><?php echo htmlspecialchars($article['description']); ?></textarea>
        </div>
        <div class="mb-4">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" id="content" rows="6" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>
        <div>
            <button type="submit" class="custom-button">Update Article</button>
        </div>
    </form>
</div>

<script>
    var quill = new Quill('#description', {
        theme: 'snow',
        modules: {
            toolbar: [['bold', 'italic', 'underline'], [{ 'align': [] }], ['link', 'image']]
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        var description = document.querySelector('textarea#description-input');
        description.value = quill.root.innerHTML;
    });
</script>
</body>
</html>

<?php
$conn->close();
?>
