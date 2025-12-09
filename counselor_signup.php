<?php
include('db_connection.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $password = $_POST['password'];
    $retypePassword = $_POST['retypePassword'];
    $termsAccepted = isset($_POST['termsAccepted']) ? 1 : 0;

    if (!$termsAccepted) {
        echo "<script>alert('You must accept the Terms & Conditions to proceed.');</script>";
        exit;
    }

    if ($password !== $retypePassword) {
        echo "<script>alert('Passwords do not match.');</script>";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO counselors (counselor_name, email, mobile_number, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo "<script>alert('Database error: Unable to prepare statement.');</script>";
        exit;
    }

    $stmt->bind_param("ssss", $name, $email, $mobile_number, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Counselor account successfully created!');</script>";
    } else {
        if ($stmt->errno === 1062) {
            echo "<script>alert('The email is already registered. Please use a different email.');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    }
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Counselor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
       /* Overall Body Styling */
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

/* Glass Effect Container */
.glass {
    background-color: rgba(50, 50, 54, 0.7); /* Transparent dark background */
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(2px); /* Blur effect for the glass */
    width: 50%;
    margin-left: 380px;
    margin-top: 100px;
    padding: 30px;
    border-radius: 10px; /* Rounded corners */
}

/* Floating Label Input Group */
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

/* Custom Button Styling */
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

/* Terms and Conditions Styling */
.terms-condition {
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.7);
}

.terms-condition a {
    color: #ff6347;
    text-decoration: underline;
}

.flex {
    display: flex;
    justify-content: center;
    align-items: center;
}

    </style>
</head>
<body>
            <div class="flex flex-col justify-center items-center p-8 glass">
                <h2 class="text-2xl font-bold text-gray-100 mb-4">Register New Counselor</h2>
                <form class="w-full" method="POST" action="">
                    <div class="floating-label-group">
                        <input type="text" name="name" placeholder=" " required>
                        <label for="name">Full Name</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="text" name="mobile_number" placeholder=" " required>
                        <label for="mobile_number">Mobile Number</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" name="retypePassword" placeholder=" " required>
                        <label for="retypePassword">Retype Password</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="agreeTerms" name="termsAccepted" class="mr-2">
                        <label for="agreeTerms" class="text-white">I agree to the <a href="#" class="text-red-400">Terms & Conditions</a></label>
                    </div>
                    <button type="submit" class=" text-white py-2 rounded mt-2 custom-button">Signup as Counselor</button>
                    <p class="text-white text-sm mt-2"><a href="admin_dashboard.php" class="text-red-400">Go to Dashboard</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
