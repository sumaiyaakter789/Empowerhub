<?php
session_start();
include("db_connection.php");

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['id'];
$sql = "SELECT name, email FROM signup WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Fetch cart items (assuming stored in session)
$cart_items = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['qty'];
}

$delivery_charge = 5;
//foreach ($cart_items as $item) {
//    if ($item['type'] === 'product') {
//        $delivery_charge = 5;
//        break;
//    }
//}

$voucher_discount = 0;
if (isset($_POST['voucher_code'])) {
    $voucher_code = $_POST['voucher_code'];
    $voucher_query = "SELECT discount_percentage FROM vouchers WHERE code = ? AND expiry_date >= CURDATE()";
    $voucher_stmt = $conn->prepare($voucher_query);
    $voucher_stmt->bind_param("s", $voucher_code);
    $voucher_stmt->execute();
    $voucher_result = $voucher_stmt->get_result();
    if ($voucher_result->num_rows > 0) {
        $voucher = $voucher_result->fetch_assoc();
        $voucher_discount = ($total * $voucher['discount_percentage']) / 100;
    } else {
        $error_message = "Invalid or expired voucher code.";
    }
}

$final_total = $total + $delivery_charge - $voucher_discount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
            display: flex;
            gap: 30px;
            
            max-width: 90%;
        }

        .glass-container {
            background-color: rgba(50, 50, 54, 0.6);
            backdrop-filter: blur(2px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 10px;
            margin-top: 150px;
        }

        .summary, .checkout-form {
            flex: 1;
        }

        h2 {
            color: #89ccc5;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .voucher-container {
            margin: 20px 0;
        }

        .voucher-container input {
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 70%;
            margin-right: 10px;
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
        }

        .voucher-container button {
            padding: 10px;
            background-color: #89ccc5;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
            font-size: 14px;
        }

        .form-group.card-info {
            display: flex;
            gap: 10px;
        }

        .form-group.card-info input {
            flex: 1;
        }

        .submit-btn {
            background-color: #89ccc5;
            color: black;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #2f5955;
            color: white;
        }

        hr {
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="container">
        <div class="glass-container summary">
            <h2>Order Summary</h2>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <span><?php echo htmlspecialchars($item['title']); ?> (x<?php echo $item['qty']; ?>)</span>
                    <span>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></span>
                </div>
            <?php endforeach; ?>
            <hr>
            <div class="cart-item">
                <span>Subtotal</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>
            <div class="cart-item">
                <span>Delivery Charge</span>
                <span>$<?php echo number_format($delivery_charge, 2); ?></span>
            </div>
            <?php if ($voucher_discount > 0): ?>
                <div class="cart-item">
                    <span>Voucher Discount</span>
                    <span>-$<?php echo number_format($voucher_discount, 2); ?></span>
                </div>
            <?php endif; ?>
            <hr>
            <div class="cart-item">
                <span>Total</span>
                <span>$<?php echo number_format($final_total, 2); ?></span>
            </div>
            <div class="voucher-container">
                <form method="POST">
                    <input type="text" name="voucher_code" placeholder="Enter Voucher or Coupon (if you have any)">
                    <button type="submit">Apply</button>
                </form>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="glass-container checkout-form">
            <h2>Checkout</h2>
            <form action="process_checkout.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <label for="card-info">Card Information (Visa, Mastercard, AMEX etc.)</label>
                <div class="form-group card-info">
                    <input type="text" name="card_number" placeholder="Card Number" required>
                    <input type="text" name="cvc" placeholder="CVC" required>
                    <input type="text" name="expiry" placeholder="Expiry Date (MM/YY)" required>
                </div>
                <button type="submit" class="submit-btn">Proceed to Payment</button>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>