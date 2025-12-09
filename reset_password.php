<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['reset_user_id'])) {
    header("Location: forget_password.php");
    exit;
}

$error = "";
$success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_id = $_SESSION['reset_user_id'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE signup SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        if ($stmt->execute()) {
            $success = "Password updated successfully.";
            unset($_SESSION['reset_user_id']);
        } else {
            $error = "Failed to update the password. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
       body {
    font-family: 'Poppins', sans-serif;
    background-color: #344054;
    color: white;
    margin: 0;
    padding: 0;
    background-image: url('b6.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

.glass {
    background-color: rgba(50, 50, 54, 0.7);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(2px);
    padding: 30px;
    border-radius: 10px;
    color: white;
    width: 200%;
    max-width: 700px;
    
}

.floating-label-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.floating-label-group input {
    width: 100%;
    border: 1px solid rgba(255, 255, 255, 0.5);
    background: rgba(50, 50, 54, 0.8); /* Semi-transparent dark background */
    color: white;
    padding: 10px;
    border-radius: 6px;
    transition: box-shadow 0.3s ease;
}

.floating-label-group input:focus {
    box-shadow: 0 0 10px rgba(80, 80, 90, 0.9); /* Subtle glowing effect */
    outline: none;
}

.floating-label-group label {
    position: absolute;
    top: 12px;
    left: 12px;
    color: rgba(255, 255, 255, 0.7);
    pointer-events: none;
    transition: 0.3s;
}

.floating-label-group input:focus + label,
.floating-label-group input:not(:placeholder-shown) + label {
    top: -17px;
    left: 12px;
    font-size: 12px;
    color: #f4f0f5;
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

.error {
    color: #ff6363;
    font-size: 0.875rem;
}

.success {
    color: #63ff93;
    font-size: 0.875rem;
}

    </style>
</head>
<body class="flex justify-center items-center min-h-screen">
<div class="glass">
    <h2 class="text-center text-2xl font-bold mb-6">Reset Password</h2>
    <form method="POST" action="" class="space-y-6">
        <div class="floating-label-group">
            <input type="password" id="new_password" name="new_password" placeholder=" " required>
            <label for="new_password">New Password</label>
        </div>
        <div class="floating-label-group">
            <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required>
            <label for="confirm_password">Confirm Password</label>
        </div>
        <button type="submit" class="custom-button">Reset Password</button>
    </form>
    <?php if ($error): ?>
        <p class="error mt-4"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success mt-4"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <div class="text-center mt-4">
        <a href="login.php">Back to Login</a>
    </div>
</div>

</body>
</html>
