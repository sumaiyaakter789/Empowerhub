<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p class='text-center mt-5'>Your cart is empty. Login & <a href='courses.php'>Browse Courses</a></p>";
    exit;
}

$total_cart_value = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_cart_value += $item['price'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
            margin-top: 150px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .cart-item img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
        }
        .quantity-control {
            display: flex;
            align-items: center;
        }
        .quantity-control button {
            background-color: #b3497a;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            text-align: center;
            font-size: 18px;
            margin: 0 10px;
            cursor: pointer;
        }
        .total-price {
            font-size: 1.2rem;
            font-weight: 600;
        }
        .remove-btn {
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .proceed-btn {
            background-color: #b3497a;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>
<div class="container glass">
    <h1 class="text-center mb-4 text-white"><u><b>Your Cart</b></u></h1>
    <?php foreach ($_SESSION['cart'] as $key => $item): ?>
        <div class="cart-item">
            <img src="<?= $item['thumbnail']; ?>" alt="Course Thumbnail">
            <div style="flex-grow: 1; margin-left: 20px;">
                <h5><?= $item['title']; ?></h5>
                <p class="text-white">Unit Price: $<?= number_format($item['price'], 2); ?></p>
            </div>
            <div class="quantity-control">
                <form action="update_cart.php" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="decrease">
                    <input type="hidden" name="product_id" value="<?= $key; ?>">
                    <button type="submit">-</button>
                </form>
                <span><?= $item['qty']; ?></span>
                <form action="update_cart.php" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="increase">
                    <input type="hidden" name="product_id" value="<?= $key; ?>">
                    <button type="submit">+</button>
                </form>
            </div>
            <div class="total-price">$<?= number_format($item['price'] * $item['qty'], 2);?></div>
            <form action="update_cart.php" method="POST" style="display: inline;">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?= $key; ?>">
                <button class="remove-btn" type="submit">Remove</button>
            </form>
        </div>
    <?php endforeach; ?>
    <hr>
    <div class="d-flex justify-content-between align-items-center">
        <h3>Total: $<?= number_format($total_cart_value, 2); ?></h3>
        <a href="checkout.php" class="proceed-btn">Proceed to Checkout</a>
    </div>
</div>

<?php include "footer.php"; ?>
</body>
</html>
