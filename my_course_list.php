<?php
session_start();
require_once 'dbconnect.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'Instructor') {
    die("Access Denied! Only instructors can access this page.");
}

$instructor_id = $_SESSION['user_id'];

// Fetch Courses taught by this instructor
$courseQuery = "SELECT * FROM course_table WHERE instructor_id = $instructor_id ORDER BY last_updated DESC";
$courseResult = mysqli_query($conn, $courseQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">My Courses</h2>

    <a href="add_course.php" class="btn btn-primary mb-3">+ Add New Course</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Course Title</th>
                <th>Category</th>
                <th>Price</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            while ($course = mysqli_fetch_assoc($courseResult)):
            ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= htmlspecialchars($course['course_title']); ?></td>
                    <td><?= htmlspecialchars($course['course_category']); ?></td>
                    <td><?= number_format($course['course_price'], 2); ?> TZS</td>
                    <td><?= $course['last_updated']; ?></td>
                    <td>
                        <a href="view_course.php?id=<?= $course['course_id']; ?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit_course.php?id=<?= $course['course_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_course.php?id=<?= $course['course_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
