<?php
session_start();
include 'db_connection.php';

$captcha_error = "";
$result = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $captcha = $_POST['captcha'];
    $captcha_solution = $_SESSION['captcha_solution'];

    if ($captcha != $captcha_solution) {
        $captcha_error = "Invalid captcha solution.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM signup WHERE user_type = ? AND name = ? AND email = ?");
        $stmt->bind_param("sss", $user_type, $name, $email);
        $stmt->execute();
        $stmt->bind_result($user_id);
        if ($stmt->fetch()) {
            $_SESSION['reset_user_id'] = $user_id;
            header("Location: reset_password.php");
            exit;
        } else {
            $result = "No user found with the provided details.";
        }
        $stmt->close();
    }
}
$num1 = rand(1, 20);
$num2 = rand(1, 20);
$operators = ['+', '-', '*', '/'];
$operator = $operators[array_rand($operators)];
$_SESSION['captcha_solution'] = eval("return $num1 $operator $num2;");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .link {
            color: #d1c4e9;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="glass">
    <h2 class="text-center text-2xl font-bold mb-6">Forgot Password</h2>
    <form method="POST" action="" class="space-y-4">
        <div>
            <label for="user_type" class="block font-medium mb-1">User Type</label>
            <div class="flex space-x-4">
                <label>
                    <input type="radio" name="user_type" value="student" required> Student
                </label>
                <label>
                    <input type="radio" name="user_type" value="instructor" required> Instructor
                </label>
                <label>
                    <input type="radio" name="user_type" value="organization" required> Organization
                </label>
            </div>
        </div>
        <div class="floating-label-group">
            <input type="text" id="name" name="name" placeholder=" " required>
            <label for="name">Name</label>
        </div>
        <div class="floating-label-group">
            <input type="email" id="email" name="email" placeholder=" " required>
            <label for="email">Email</label>
        </div>
        <div class="floating-label-group">
            <input type="number" id="captcha" name="captcha" placeholder=" " required>
            <label for="captcha">Captcha: <?php echo "$num1 $operator $num2 = ?"; ?></label>
        </div>
        <?php if ($captcha_error): ?>
            <p class="error"><?php echo $captcha_error; ?></p>
        <?php endif; ?>
        <button type="submit" class="text-white py-2 rounded mt-2 custom-button">Submit</button>
    </form>
    <?php if ($result): ?>
        <p class="error mt-4"><?php echo htmlspecialchars($result); ?></p>
    <?php endif; ?>
    <div class="text-center mt-4">
        <a href="login.php" class="link">Back to Login</a>
    </div>
</div>

</body>
</html>
