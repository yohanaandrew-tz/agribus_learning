<?php
require_once "dbconnect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .hero {
            background: url('images/agribus.jpg') no-repeat center center/cover;
            color: white;
            padding: 80px 20px;
            text-align: center;
        }
        .course-card img {
            height: 200px;
            object-fit: cover;    
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="#">Agribusiness Learning</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="n-avitem"><a class="nav-link" href="#">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link btn btn-warning text-dark" href="signup.php">Sign Up</a></li>
                <li class="nav-item"><a class="nav-link btn btn-warning text-dark" href="signin.php">Sign In</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h3>Learn & Grow in Agribusiness</h3>
        <p>Join our platform and gain skills in farming, agribusiness finance, and more.</p>
        <a href="explore_course.php" class="btn btn-light btn-lg">Explore Courses</a>
    </div>
</section>

<!-- Courses Preview -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Popular Courses</h2>
    <div class="row">
        <?php
        $query = "SELECT * FROM Course_Table ORDER BY last_updated DESC LIMIT 3";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-md-4">
                <div class="card course-card">
                    <img src="' . $row["course_photo"] . ' " class="card-img-top" alt="Course Image">
                    <div class="card-body">
                        <h5 class="card-title">' . $row["course_title"] . '</h5>
                        <p class="card-text">' . substr($row["course_description"], 0, 100) . '...</p>
                        <a href="#" class="btn btn-success">Learn More</a>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<!-- Footer -->
<footer class="bg-success text-white text-center py-3 mt-5">
    <p>&copy; 2025 Agribusiness Learning. All Rights Reserved.</p>
</footer>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
