<?php
include('db_connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM products WHERE product_id = '$product_id'");

    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $product_type = $_POST['product_type'];
    $image_url = $product['image_url'];

    if (!empty($_FILES['image_url']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image_url"]["name"]);
        
        if (getimagesize($_FILES["image_url"]["tmp_name"])) {
            $safe_filename = basename($_FILES["image_url"]["name"]);
            move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_dir . $safe_filename);
            $image_url = $target_dir . $safe_filename;
        } else {
            echo "The uploaded file is not a valid image.";
            exit();
        }
    }

    $sql = "UPDATE products SET 
                name = '$name',
                description = '$description',
                price = $price,
                product_type = '$product_type',
                image_url = '$image_url'
            WHERE product_id = '$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully!";
        header("Location: admin_my_product.php");
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
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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

        /* Style the Quill editor to match the glass theme */
#description {
    background-color: rgba(255, 255, 255, 0.1); /* Light semi-transparent background */
    color: white; /* Text color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px; /* Smooth corners */
    padding: 10px; /* Consistent padding */
    font-size: 16px; /* Font size */
}

#description .ql-editor {
    background-color: transparent; /* Transparent background for the editable area */
    color: white; /* Text color for the editable area */
}

#description .ql-toolbar {
    background-color: rgba(50, 50, 54, 0.5); /* Semi-transparent background for the toolbar */
    color: white; /* Toolbar icons color */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border */
    border-radius: 8px 8px 0 0; /* Smooth corners at the top */
}

/* Optional: Style the Quill editor's hover and focus states */
#description:focus {
    outline: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
}

#description .ql-editor:focus {
    background-color: rgba(255, 255, 255, 0.2); /* Slightly darker background on focus */
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
    <h1 class="text-center mb-4">Edit Product</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-4 half-width">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-4">
            <label for="description" class="form-label">Description</label>
            <div id="description" class="form-control" style="height: 200px;">
                <?php echo htmlspecialchars($product['description']); ?>
            </div>
            <textarea name="description" id="description-input" style="display:none;"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="mb-4 half-width">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required step="0.01">
        </div>
        <div class="mb-4 half-width">
            <label for="product_type" class="form-label">Product Type</label>
            <select name="product_type" id="product_type" required>
                <option value="Software" <?php echo ($product['product_type'] == 'Software') ? 'selected' : ''; ?>>Software</option>
                <option value="Device" <?php echo ($product['product_type'] == 'Device') ? 'selected' : ''; ?>>Device</option>
                <option value="Stationary" <?php echo ($product['product_type'] == 'Stationary') ? 'selected' : ''; ?>>Stationary</option>
            </select>
        </div>
        <div class="mb-4 half-width">
            <label for="image_url" class="form-label">Product Image</label>
            <input type="file" name="image_url" id="image_url">
            <?php if ($product['image_url']): ?>
                <p>Current Image: <img src="<?php echo $product['image_url']; ?>" alt="Product Image" width="100"></p>
            <?php endif; ?>
        </div>
        <div>
            <button type="submit" class="custom-button">Update Product</button>
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
