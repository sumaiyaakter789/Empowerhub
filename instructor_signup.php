<?php
include('db_connection.php');

$error = ""; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $retypePassword = $_POST['retypePassword'];
    $termsAccepted = isset($_POST['termsAccepted']) ? 1 : 0;

    if (isset($_POST['user_type'])) {
        $userType = $_POST['user_type'];
    } else {
        $userType = 'student';
    }

    // Check if terms are accepted
    if (!$termsAccepted) {
        $error = "You must accept the Terms & Conditions to proceed.";
    }

    // Check if passwords match
    elseif ($password !== $retypePassword) {
        $error = "Passwords do not match.";
    }

    // Validate email domain
    elseif (!in_array(substr(strrchr($email, "@"), 1), ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'])) {
        $error = "Invalid email domain. Please use a valid email.";
    }

    // If no errors, proceed with insertion
    if (empty($error)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Signup (user_type, name, email, password) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            $error = "Database error: Unable to prepare statement.";
        } else {
            $stmt->bind_param("ssss", $userType, $name, $email, $hashedPassword);
            if ($stmt->execute()) {
                echo "<script>alert('You have successfully joined us!');</script>";
            } else {
                if ($stmt->errno === 1062) {
                    $error = "The email is already registered. Please use a different email.";
                } else {
                    $error = "Error: " . $stmt->error;
                }
            }
            $stmt->close();
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
    <title>Signup Page - EmpowerHub</title>
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



.hidden {
    display: none;
}

input[type="radio"]:checked + label {
    background-color: #6a1b9a;
    color: white;
}

label {
    transition: background-color 0.3s, color 0.3s;
}



input[type="radio"] + label {
    background-color: #4a4a4a; /* Default button color */
    color: white; /* Default text color */
}

input[type="radio"]:checked + label {
    background-color: #2b2626; /* Background color when selected */
    color: white; /* Text color when selected */
}

input[type="checkbox"]:checked + label {
    color: #ffff;
}

input[type="checkbox"] {
    accent-color: #ffff; /* Checkbox color */
}

a {
    text-decoration: underline;
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



.glass {
    background-color: rgba(30, 30, 40, 0.7); /* Darker transparent background */
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3); /* Darker shadow for more depth */
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(2px); /* Increased blur for a stronger glass effect */
    padding: 2rem;
    border-radius: 10px;
}
    
    </style>
</head>
<body>
    <div class="h-screen flex items-center justify-center">
        <div class="grid grid-cols-1 md:grid-cols-2 w-11/12 lg:w-3/4 bg-opacity-20 glass rounded-lg overflow-hidden">
            <div class="hidden md:flex items-center justify-center relative bg-cover bg-center"
                style="background-image: url('CLogo1-Photoroom.png');">
                <a href="index.php"><div class="absolute inset-0 bg-black bg-opacity-50"></div></a>
                
            </div>

            <div class="flex flex-col justify-center items-center p-6">
                <h2 class="text-2xl font-bold text-gray-100 mb-4">Create your account</h2>
                <div class="flex space-x-4 mb-6">
                    <input type="radio" id="student" name="toggle" class="hidden">
                    <label for="student" class="px-4 py-2  text-white rounded  cursor-pointer custom-button" id="studentBtn">Student</label>

                    <input type="radio" id="instructor" name="toggle" class="hidden" checked>
                    <label for="instructor" class="px-4 py-2  text-white rounded  cursor-pointer custom-button" id="instructorBtn">Instructor</label>

                    <input type="radio" id="organization" name="toggle" class="hidden">
                    <label for="organization" class="px-4 py-2  text-white rounded  cursor-pointer custom-button" id="organizationBtn">Organization</label>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="text-red-500 mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Student Form -->
                <form id="studentForm" class="w-full hidden" method="POST" action="signup.php">
                    <input type="hidden" name="user_type" value="student">
                    <div class="floating-label-group">
                        <input type="text" name="name" placeholder=" " required>
                        <label for="name">Full Name</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" name="retypePassword" placeholder=" " required>
                        <label for="retypePassword">Retype Password</label>
                    </div>

                    <div class="flex flex-col items-center space-y-2 mb-4">
                        <button type="button" class="flex items-center justify-center bg-white text-black py-2 px-4 rounded-full hover:bg-red-100 w-60 h-9">
                            <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Icon" class="w-5 h-5 mr-2"> Continue with Google
                        </button>
                        <button type="button" class="flex items-center justify-center bg-white text-black py-2 px-4 rounded-full hover:bg-red-100 w-60 h-9">
                            <img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook Icon" class="w-5 h-5 mr-2"> Continue with Facebook
                        </button>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="agreeTerms" name="termsAccepted" class="mr-2">
                        <label for="agreeTerms" class="text-white">I agree to the <a href="terms_and_conditions.php" class="text-red-600">Terms & Conditions</a></label>
                    </div>
                    <button type="submit" class="w-full  text-white py-2 rounded mt-2 custom-button">Signup as Student</button>
                    <p class="text-white text-sm mt-2">Do you have an account? <a href="login.php" class="text-red-600">Login</a></p>
                </form>

                <!-- Instructor Form -->
                <form id="instructorForm" class="w-full" method="POST" action="signup.php">
                    <input type="hidden" name="user_type" value="instructor">
                    <div class="floating-label-group">
                        <input type="text" name="name" placeholder=" " required>
                        <label for="name">Full Name</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" name="retypePassword" placeholder=" " required>
                        <label for="retypePassword">Retype Password</label>
                    </div>

                    <div class="flex flex-col items-center space-y-2 mb-4">
                        <button type="button" class="flex items-center justify-center bg-white text-black py-2 px-4 rounded-full hover:bg-red-100 w-60 h-9">
                            <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Icon" class="w-5 h-5 mr-2"> Continue with Google
                        </button>
                        <button type="button" class="flex items-center justify-center bg-white text-black py-2 px-4 rounded-full hover:bg-red-100 w-60 h-9">
                            <img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook Icon" class="w-5 h-5 mr-2"> Continue with Facebook
                        </button>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="agreeTerms" name="termsAccepted" class="mr-2">
                        <label for="agreeTerms" class="text-white">I agree to the <a href="terms_and_conditions.php" class="text-red-400">Terms & Conditions</a></label>
                    </div>
                    <button type="submit" class="w-full  text-white py-2 rounded mt-2 custom-button">Signup as Instructor</button>
                    <p class="text-white text-sm mt-2">Do you have an account? <a href="login.php" class="text-red-400">Login</a></p>
                </form>

                <!-- Organization Form -->
                <form id="organizationForm" class="w-full hidden" method="POST" action="signup.php">
                    <input type="hidden" name="user_type" value="organization">
                    <div class="floating-label-group">
                        <input type="text" name="name" placeholder=" " required>
                        <label for="name">Full Name</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" name="retypePassword" placeholder=" " required>
                        <label for="retypePassword">Retype Password</label>
                    </div>

                    <div class="flex flex-col items-center space-y-2 mb-4">
                        <button type="button" class="flex items-center justify-center bg-white text-black py-2 px-4 rounded-full hover:bg-red-100 w-60 h-9">
                            <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Icon" class="w-5 h-5 mr-2"> Continue with Google
                        </button>
                        <button type="button" class="flex items-center justify-center bg-white text-black py-2 px-4 rounded-full hover:bg-red-100 w-60 h-9">
                            <img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook Icon" class="w-5 h-5 mr-2"> Continue with Facebook
                        </button>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="agreeTerms" name="termsAccepted" class="mr-2">
                        <label for="agreeTerms" class="text-white">I agree to the <a href="terms_and_conditions.php" class="text-red-400">Terms & Conditions</a></label>
                    </div>
                    <button type="submit" class="w-full  text-white py-2 rounded mt-2 custom-button">Signup as Organization</button>
                    <p class="text-white text-sm mt-2">Do you have an account? <a href="login.php" class="text-red-400">Login</a></p>
                </form>
            </div>
        </div>
    </div>

    <script>
        const studentForm = document.getElementById("studentForm");
        const instructorForm = document.getElementById("instructorForm");
        const organizationForm = document.getElementById("organizationForm");

        const studentBtn = document.getElementById("studentBtn");
        const instructorBtn = document.getElementById("instructorBtn");
        const organizationBtn = document.getElementById("organizationBtn");

        studentBtn.addEventListener("click", function () {
            studentForm.classList.remove("hidden");
            instructorForm.classList.add("hidden");
            organizationForm.classList.add("hidden");
        });

        instructorBtn.addEventListener("click", function () {
            studentForm.classList.add("hidden");
            instructorForm.classList.remove("hidden");
            organizationForm.classList.add("hidden");
        });

        organizationBtn.addEventListener("click", function () {
            studentForm.classList.add("hidden");
            instructorForm.classList.add("hidden");
            organizationForm.classList.remove("hidden");
        });
    </script>
</body>
</html>