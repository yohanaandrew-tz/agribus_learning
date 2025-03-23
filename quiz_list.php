<?php
session_start();
require_once 'dbconnect.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access Denied! Please log in.");
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch Courses based on role
if ($user_role == 'Admin') {
    $courseQuery = "SELECT * FROM course_table";
} elseif ($user_role == 'Instructor') {
    $courseQuery = "SELECT * FROM course_table WHERE instructor_id = $user_id";
} else {
    $courseQuery = "SELECT * FROM course_table WHERE course_id IN (SELECT course_id FROM learning_table WHERE user_id = $user_id)";
}
$courseResult = mysqli_query($conn, $courseQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz List</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Quiz List</h2>

    <?php while ($course = mysqli_fetch_assoc($courseResult)): ?>
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><?= htmlspecialchars($course['course_title']); ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $course_id = $course['course_id'];
                        $quizQuery = "SELECT * FROM quizze_table WHERE course_id = $course_id ORDER BY quizze_id ASC";
                        $quizResult = mysqli_query($conn, $quizQuery);
                        $count = 1;
                        while ($quiz = mysqli_fetch_assoc($quizResult)):
                        ?>
                            <tr>
                                <td><?= $count++; ?></td>
                                <td><?= htmlspecialchars($quiz['question']); ?></td>
                                <td>
                                    A) <?= htmlspecialchars($quiz['option_A']); ?><br>
                                    B) <?= htmlspecialchars($quiz['option_B']); ?><br>
                                    C) <?= htmlspecialchars($quiz['option_C']); ?><br>
                                    D) <?= htmlspecialchars($quiz['option_D']); ?>
                                </td>
                                <td>
                                    <a href="view_quiz.php?id=<?= $quiz['quizze_id']; ?>" class="btn btn-info btn-sm">View</a>

                                    <?php if ($user_role == 'Admin' || $user_role == 'Instructor'): ?>
                                        <a href="edit_quiz.php?id=<?= $quiz['quizze_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete_quiz.php?id=<?= $quiz['quizze_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                    <?php endif; ?>
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
