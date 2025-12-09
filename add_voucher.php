<?php
include('db_connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $discount_percentage = $_POST['discount_percentage'];
    $expiry_date = $_POST['expiry_date'];

    $stmt = $conn->prepare("INSERT INTO vouchers (code, discount_percentage, expiry_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $code, $discount_percentage, $expiry_date);
    $stmt->execute();

    header("Location: my_voucher.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Voucher</title>
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

        .glass {
            background-color: rgba(50, 50, 54, 0.5);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .container {
            background-color: rgba(50, 50, 54, 0.6);
            backdrop-filter: blur;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: auto;
            margin-top: 30px;
            width: 60%;
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
<body>
<div class="container glass">
    <h1 class="text-center text-white mb-4">Add New Voucher</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="code" class="block text-sm font-medium">Voucher Code</label>
            <input type="text" required class="mt-1 p-2 w-full rounded" id="code" name="code" required>
        </div>
        <div class="mb-4">
            <label for="discount_percentage" class="form-label">Discount Percentage</label>
            <input type="number" step="0.01" class="block text-sm font-medium" id="discount_percentage" name="discount_percentage" required>
        </div>
        <div class="mb-4">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="date" class="block text-sm font-medium" id="expiry_date" name="expiry_date" required>
        </div>
        <button type="submit" class="custom-button">Add Voucher</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
