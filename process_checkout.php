<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $card_number = trim($_POST['card_number']);
    $delivery_charge = 0;
    $voucher_discount = 0;
    $final_total = 0;

    $cart_items = $_SESSION['cart'] ?? [];
    if (empty($cart_items)) {
        die("Your cart is empty.");
    }

    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['qty'];
    }

    if (isset($_POST['voucher_code']) && !empty($_POST['voucher_code'])) {
        $voucher_code = trim($_POST['voucher_code']);
        $voucher_query = "SELECT discount_percentage FROM vouchers WHERE code = ? AND expiry_date >= CURDATE()";
        $voucher_stmt = $conn->prepare($voucher_query);
        $voucher_stmt->bind_param("s", $voucher_code);
        $voucher_stmt->execute();
        $voucher_result = $voucher_stmt->get_result();

        if ($voucher_result->num_rows > 0) {
            $voucher = $voucher_result->fetch_assoc();
            $voucher_discount = ($total * $voucher['discount_percentage']) / 100;
        }
    }

    $final_total = $total + $delivery_charge - $voucher_discount;

    $order_query = "INSERT INTO orders (user_id, total, delivery_charge, voucher_discount, final_total, address, phone, card_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_query);
    $order_stmt->bind_param("iddddsss", $user_id, $total, $delivery_charge, $voucher_discount, $final_total, $address, $phone, $card_number);

    if ($order_stmt->execute()) {
        $order_id = $order_stmt->insert_id;

        // Insert order items into `order_items` table
        $order_items_query = "INSERT INTO order_items (order_id, product_id, course_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $order_items_stmt = $conn->prepare($order_items_query);

        foreach ($cart_items as $item) {
            $product_id = $item['product_id'] ?? null;
            $course_id = $item['course_id'] ?? null;
            $quantity = $item['qty'];
            $price = $item['price'];

            $order_items_stmt->bind_param("iiidi", $order_id, $product_id, $course_id, $quantity, $price);
            $order_items_stmt->execute();
        }

        unset($_SESSION['cart']);

        header("Location: success.php?order_id=" . $order_id);
        exit();
    } else {
        die("Failed to process your order. Please try again.");
    }
} else {
    die("Invalid request.");
}
?>