<?php
include('db_connection.php');
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['user_type'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $termsAccepted = isset($_POST['termsAccepted']) ? 1 : 0;

    $query = $conn->prepare("SELECT * FROM signup WHERE email = ? AND user_type = ?");
    $query->bind_param("ss", $email, $userType);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            switch ($user['user_type']) {
                case 'student':
                    header("Location: index.php");
                    break;
                case 'instructor':
                    header("Location: instructor_dashboard.php");
                    break;
                case 'organization':
                    header("Location: oraganization_dashboard.php");
                    break;
                default:
                    echo "Invalid user type.";
                    exit;
            }
            exit;
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "Invalid login credentials. Please try again.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login- EmpowerHub</title>
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
                <h2 class="text-2xl font-bold text-gray-100 mb-4">Login to your account</h2>
                <div class="flex space-x-4 mb-6">
                    <input type="radio" id="student" name="toggle" class="hidden" checked>
                    <label for="student" class="px-4 py-2  text-white rounded  cursor-pointer custom-button" id="studentBtn">Student</label>

                    <input type="radio" id="instructor" name="toggle" class="hidden">
                    <label for="instructor" class="px-4 py-2  text-white rounded  cursor-pointer custom-button" id="instructorBtn">Instructor</label>

                    <input type="radio" id="organization" name="toggle" class="hidden">
                    <label for="organization" class="px-4 py-2  text-white rounded  cursor-pointer custom-button" id="organizationBtn">Organization</label>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="text-red-500 mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form id="studentForm" class="w-full " method="POST" action="login.php">
                    <input type="hidden" name="user_type" value="student">
                    <div class="floating-label-group">
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="agreeTerms" class="mr-2">
                        <label for="agreeTerms" class="text-white">I agree to the <a href="terms_and_conditions.php" class="text-red-600">Terms & Conditions</a></label>
                    </div>
                    <button type="submit" class="w-full text-white py-2 rounded mt-2 custom-button">Login as Student</button>
                    <div class="flex justify-between text-sm mt-2">
    <p class="text-white">
        New Here? <a href="signup.php" class="text-red-600">Sign Up</a>
    </p>
    <p class="text-white">
        <a href="forget_password.php" class="text-red-600">Forgot Password?</a>
    </p>
</div>
                </form>

                <!-- Instructor Form -->
                <form id="instructorForm" class="w-full hidden" method="POST" action="login.php">
                    <input type="hidden" name="user_type" value="instructor">
                    <div class="floating-label-group">
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="agreeTerms" class="mr-2">
                        <label for="agreeTerms" class="text-white">I agree to the <a href="terms_and_conditions.php" class="text-red-600">Terms & Conditions</a></label>
                    </div>
                    <button type="submit" class="w-full custom-button text-white py-2 rounded mt-2 ">Login as Instructor</button>
                    <div class="flex justify-between text-sm mt-2">
    <p class="text-white">
        New Here? <a href="signup.php" class="text-red-600">Sign Up</a>
    </p>
    <p class="text-white">
        <a href="forget_password.php" class="text-red-600">Forgot Password?</a>
    </p>
</div>
                </form>

                <!-- Organization Form -->
                <form id="organizationForm" class="w-full hidden" method="POST" action="login.php">
                    <input type="hidden" name="user_type" value="organization">
                    <div class="floating-label-group">
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                    <div class="floating-label-group">
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                    </div>
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="agreeTerms" class="mr-2">
                        <label for="agreeTerms" class="text-white">I agree to the <a href="terms_and_conditions.php" class="text-red-600">Terms & Conditions</a></label>
                    </div>
                    <button type="submit" class="w-full custom-button text-white py-2 rounded mt-2 ">Login as Organization</button>
                    <div class="flex justify-between text-sm mt-2">
    <p class="text-white">
        New Here? <a href="signup.php" class="text-red-600">Sign Up</a>
    </p>
    <p class="text-white">
        <a href="forget_password.php" class="text-red-600">Forgot Password?</a>
    </p>
</div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // Script to toggle forms based on selected user type
        const studentForm = document.getElementById('studentForm');
        const instructorForm = document.getElementById('instructorForm');
        const organizationForm = document.getElementById('organizationForm');
        const studentBtn = document.getElementById('studentBtn');
        const instructorBtn = document.getElementById('instructorBtn');
        const organizationBtn = document.getElementById('organizationBtn');

        studentBtn.addEventListener('click', () => {
            studentForm.classList.remove('hidden');
            instructorForm.classList.add('hidden');
            organizationForm.classList.add('hidden');
        });

        instructorBtn.addEventListener('click', () => {
            studentForm.classList.add('hidden');
            instructorForm.classList.remove('hidden');
            organizationForm.classList.add('hidden');
        });

        organizationBtn.addEventListener('click', () => {
            studentForm.classList.add('hidden');
            instructorForm.classList.add('hidden');
            organizationForm.classList.remove('hidden');
        });

        const forms = [studentForm, instructorForm, organizationForm];
        forms.forEach(form => {
            form.addEventListener('submit', (event) => {
                const agreeTerms = form.querySelector('#agreeTerms');
                if (!agreeTerms.checked) {
                    event.preventDefault(); // Prevent form submission
                    alert('Please agree to the Terms & Conditions before proceeding.');
                }
            });
        });

        
    </script>
</body>
</html>