<?php
include('db_connection.php');

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $terms_agreed = isset($_POST['terms']);

    if (!$terms_agreed) {
        echo "<script>alert('You must agree to the Terms and Conditions to login.');</script>";
    } else {
        $query = $conn->prepare("SELECT * FROM admin WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                session_start();
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['name'] = $admin['name'];
                $_SESSION['email'] = $admin['email'];
                header("Location: admin_dashboard.php");
                exit;
            } else {
                echo "<script>alert('Incorrect password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid login credentials. Please try again.');</script>";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
            backdrop-filter: blur(10px);
            width: 50%;
            margin-left: 380px;
            margin-top: 100px;
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(1px);
        }

        .floating-label-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .floating-label-group input {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: transparent;
            color: white;
            padding: 10px;
            border-radius: 6px;
            transition: box-shadow 0.3s ease;
        }

        .floating-label-group input:focus {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0);
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

        .forgot-password {
            text-align: center;
            margin-top: 10px;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .terms-condition {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .terms-condition a {
            color: #ff6347;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="flex flex-col justify-center items-center p-6 glass">
        <h2 class="text-2xl font-bold text-gray-100 mb-4">Admin Login</h2>

        <form method="POST" action="admin_login.php" class="w-full">
            <div class="floating-label-group">
                <input type="email" id="email" name="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>
            <div class="floating-label-group">
                <input type="password" id="password" name="password" placeholder=" " required>
                <label for="password">Password</label>
            </div>

            <div class="terms-condition">
                <input type="checkbox" id="terms" name="terms">
                <label for="terms">I agree to the <a href="terms_and_conditions.php" target="_blank">Terms and Conditions</a></label>
            </div>

            <button type="submit" class=" text-white py-2 rounded mt-2  custom-button">Login</button>

            <div class="flex justify-between text-sm mt-2">
                <p class="text-white">
                    <a href="forget_password.php" class="text-red-400">Forgot password?</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
