<?php
include('db_connection.php');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle voucher deletion
if (isset($_GET['delete'])) {
    $voucher_id = $_GET['delete'];

    // Delete voucher record from the database
    $conn->query("DELETE FROM vouchers WHERE id = $voucher_id");

    // Redirect back to the vouchers page
    header("Location: my_voucher.php");
    exit();
}

// Fetch vouchers from the database
$sql = "SELECT * FROM vouchers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vouchers - Admin</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #424c4d !important;
            color: #f8fafc !important;
            padding: 10px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        td {
            background-color: rgba(50, 50, 54, 0.8) !important;
            color: #e5e7eb !important;
            padding: 10px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        .btn-warning {
            background-color: #7a2d45 !important;
            border: none;
            color: #fff !important;
        }

        .btn-warning:hover {
            background-color: #42222c !important;
        }

        .btn-danger {
            background-color: #cc2735 !important;
            border: none;
            color: #fff !important;
        }

        .btn-danger:hover {
            background-color: #822129 !important;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center text-white mb-4">Manage Vouchers</h1>
    <a href="add_voucher.php" class="btn btn-success mb-4">Add New Voucher</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Voucher Code</th>
                <th>Discount (%)</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($voucher = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($voucher['code']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($voucher['discount_percentage'], 2)); ?>%</td>
                        <td><?php echo htmlspecialchars($voucher['expiry_date']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $voucher['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this voucher?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No vouchers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
