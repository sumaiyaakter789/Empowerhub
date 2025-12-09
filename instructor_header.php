<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header- Instructor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #344054;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #2e203b;
        }

        .navbar .nav-link,
        .navbar .navbar-brand {
            color: #ffffff;
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: #d1c4e9;
        }

        .dropdown-menu {
            background-color: #4a148c;
            border: none;
        }

        .dropdown-item {
            color: #ffffff;
        }

        .dropdown-item:hover {
            background-color: #6a1b9a;
        }

        .dashboard-heading {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
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

        .delete-btn {
            background-color: #e53935;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .delete-btn:hover {
            background-color: #c62828;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
            background-color: #4a148c;
            color: #ffffff;
        }
        </style>
        </head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="instructor_dashboard.php"><img src="CLogo.png" style="height: 100px; width: 100px; margin-right: 10px;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="instructor_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Courses</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="my_courses.php">My Courses</a></li>
                            <li><a class="dropdown-item" href="new_courses.php">New Course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Course Bundles</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Meetings</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Quizzes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Forums</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Articles</a>
                        <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                            <li><a class="dropdown-item" href="my_articles.php">My Articles</a></li>
                            <li><a class="dropdown-item" href="instructor_article.php">New Article</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">My Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>