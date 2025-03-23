<?php
session_start();
require_once 'dbconnect.php'; // Include database connection

// Check if user is logged in and is an Admin or Instructor
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['Admin', 'Instructor'])) {
    die("Access Denied! Only Admins & Instructors can view this page.");
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch Courses (Admin sees all, Instructor sees own)
if ($user_role == 'Admin') {
    $courseQuery = "SELECT * FROM course_table";
} else {
    $courseQuery = "SELECT * FROM course_table WHERE instructor_id = $user_id";
}
$courseResult = mysqli_query($conn, $courseQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson List</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Lesson List</h2>

    <?php while ($course = mysqli_fetch_assoc($courseResult)): ?>
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?= htmlspecialchars($course['course_title']); ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Lesson Title</th>
                            <th>Description</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $course_id = $course['course_id'];
                        $lessonQuery = "SELECT * FROM lesson_table WHERE course_id = $course_id ORDER BY lesson_id ASC";
                        $lessonResult = mysqli_query($conn, $lessonQuery);
                        $count = 1;
                        while ($lesson = mysqli_fetch_assoc($lessonResult)):
                        ?>
                            <tr>
                                <td><?= $count++; ?></td>
                                <td><?= htmlspecialchars($lesson['lesson_title']); ?></td>
                                <td><?= htmlspecialchars($lesson['lesson_description']); ?></td>
                                <td><?= $lesson['lesson_duration'] ?? 'N/A'; ?></td>
                                <td>
                                    <a href="view_lesson.php?id=<?= $lesson['lesson_id']; ?>" class="btn btn-info btn-sm">View</a>
                                    <a href="edit_lesson.php?id=<?= $lesson['lesson_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_lesson.php?id=<?= $lesson['lesson_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
