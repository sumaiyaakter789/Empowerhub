<?php
session_start();

$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['qty'];
    }
}
$_SESSION['cart_count'] = $cart_count;

function getShortForm($name)
{
    $words = explode(" ", $name);
    $shortForm = "";
    foreach ($words as $word) {
        $shortForm .= strtoupper($word[0]);
    }
    return $shortForm;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Base Styles */
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

        /* Glass Effect */
        .glass {
            background-color: rgba(50, 50, 54, 0.5);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* Navbar */
        .navbar {
            background-color: rgba(50, 50, 54, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .navbar .nav-link, 
        .navbar .navbar-brand {
            color: white;
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: #d1c4e9;
        }

        .navbar .dropdown-menu {
            background-color: rgba(50, 50, 54, 0.9);
            border: none;
        }

        .navbar .dropdown-item {
            color: white;
        }

        .navbar .dropdown-item:hover {
            background-color: rgba(80, 80, 90, 0.9);
        }

        /* Dashboard Heading */
        .dashboard-heading {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Rounded Box */
        .rounded-box {
            background-color: rgba(50, 50, 54, 0.8); 
            color: white; 
            padding: 20px;
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 600px;
            margin: 20px auto;
        }

        /* Card Styles */
        .card {
            background-color: rgba(50, 50, 54, 0.5);
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            color: white;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card img {
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            padding: 15px;
        }

        /* Footer */
        footer {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
            background-color: rgba(50, 50, 54, 0.8);
            color: white;
        }

        .top-bar {
            background-color:#1d242e;
            color: var(--white);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1050;
        }
        .top-bar .search-bar {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            width: 20%;
            border: white;
            background-color: #2d323b; 
        }
        .top-bar input[type="text"] {
            background-color: var(--white);
            border: 1px solid var(--light-purple);
            border-radius: 20px;
            padding: 5px 15px;
            width: 100%;
            padding-right: 35px;
        }
        .top-bar .search-bar svg {
            position: absolute;
            right: 10px;
            width: 19px;
            height: 24px;
            color: var(--dark-purple);
            cursor: pointer;
        }
        .top-bar .language-selector {
            margin-right: 15px;
            color: var(--white);
            font-weight: 500;
        }
        .top-bar .language-selector select {
            border: 1px solid var(--light-purple);
            background-color: #2d323b;
            color: var(--white);
            border-radius: 5px;
            padding: 3px 10px;
            font-size: 14px;
        }
        .top-bar .icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .top-bar .icons svg {
            width: 24px;
            height: 24px;
            cursor: pointer;
            color: var(--white);
            transition: color 0.3s ease-in-out;
        }
        .top-bar .icons svg:hover {
            color: var(--light-purple);
        }
        #cart-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #b3497a !important;
            color: white;
            border-radius: 50%;
            padding: 4px 6px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            line-height: 1;
            min-width: 20px;
            height: 20px;
        }
        

    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="language-selector">
                <form method="POST" action="">
                    <select name="language" id="languageSelector" onchange="this.form.submit()">
                        <option value="en" <?php if (isset($_POST['language']) && $_POST['language'] == 'en') echo 'selected'; ?>>English</option>
                        <option value="bn" <?php if (isset($_POST['language']) && $_POST['language'] == 'bn') echo 'selected'; ?>>বাংলা</option>
                    </select>
                </form>
            </div>
            <div class="search-bar">
                <form method="POST" action="search_result.php">
                    <input type="text" name="search_query" placeholder="Search...">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 10a6 6 0 110-12 6 6 0 010 12zm10 10l-4.35-4.35" />
                    </svg>
                </form>
            </div>

            <div class="icons" style="position: relative;">
    <a href="cart.php"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="cart-icon">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg></a>
    <span id="cart-count" class="badge bg-danger text-white">
        <?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?>
    </span>
</div>


        </div>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="margin-top: 43px;">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="CLogo.png" style="height: 100px; width: 100px; margin-right: 10px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="instructors.php">Instructors</a></li>
                    <li class="nav-item"><a class="nav-link" href="forums.php">Forums</a></li>
                    <li class="nav-item"><a class="nav-link" href="blogs.php">Blog & Articles</a></li>
                    <li class="nav-item"><a class="nav-link" href="stores.php">Stores</a></li>
                    <li class="nav-item"><a class="nav-link" href="notices.php">Notices</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact Us</a></li>
                </ul>
                <div class="d-flex">
                    <?php if (isset($_SESSION['name']) && isset($_SESSION['user_type'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= getShortForm($_SESSION['name']); ?> (<?= ucfirst($_SESSION['user_type']); ?>)
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                    <a class="dropdown-item" 
                                       href="<?php 
                                           if ($_SESSION['user_type'] === 'student') {
                                               echo 'student_dashboard.php';
                                           } elseif ($_SESSION['user_type'] === 'instructor') {
                                               echo 'instructor_dashboard.php';
                                           } elseif ($_SESSION['user_type'] === 'organization') {
                                               echo 'organization_dashboard.php';
                                           } else {
                                               echo '#';
                                           }
                                       ?>">
                                       Profile Dashboard
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a class="btn btn-outline-light me-2" href="login.php">Start Learning</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>