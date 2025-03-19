<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in as Admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Admin") {
    header("Location: signin.php");
    exit();
}

// Check if instructor_id is provided
if (!isset($_GET['instructor_id'])) {
    header("Location: instructor_reports.php");
    exit();
}

$instructor_id = $_GET['instructor_id'];

// Fetch instructor details
$query = "SELECT * FROM User_Table WHERE user_id = $instructor_id AND user_role = 'Instructor'";
$result = mysqli_query($conn, $query);
$instructor = mysqli_fetch_assoc($result);

// Fetch instructor's courses
$course_query = "
    SELECT c.course_id, c.course_title, c.course_category, c.course_price, c.last_updated,
           (SELECT COUNT(*) FROM Learning_Table WHERE course_id = c.course_id) AS enrolled_learners
    FROM Course_Table c
    WHERE c.instructor_id = $instructor_id
";
$courses = mysqli_query($conn, $course_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instructor Details | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">👤 Instructor Details</h2>

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h4><?= htmlspecialchars($instructor['name'] . " " . $instructor['surname']); ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> <?= htmlspecialchars($instructor['user_email']); ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($instructor['user_tel']); ?></p>
            <p><strong>Profile Info:</strong> <?= htmlspecialchars($instructor['pro_info']); ?></p>
        </div>
    </div>

    <h3 class="mt-5">📚 Instructor's Courses</h3>
    
    <table class="table table-striped table-bordered mt-3">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Course Title</th>
                <th>Category</th>
                <th>Enrolled Learners</th>
                <th>Price (TZS)</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            while ($course = mysqli_fetch_assoc($courses)) : 
            ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= htmlspecialchars($course['course_title']); ?></td>
                    <td><?= htmlspecialchars($course['course_category']); ?></td>
                    <td><?= $course['enrolled_learners']; ?></td>
                    <td><?= number_format($course['course_price'], 2); ?></td>
                    <td><?= $course['last_updated']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="instructor_reports.php" class="btn btn-secondary">⬅ Back to Instructors</a>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
