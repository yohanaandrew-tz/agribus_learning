<?php
session_start();
include 'dbconnect.php';

// Ensure only learners can access
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: signin.php");
    exit();
}

$learner_id = $_SESSION["user_id"];

// Fetch quiz history for the learner
$query = "
    SELECT q.quizze_id, q.lesson_id, q.correct_answer, l.lesson_title, c.course_title
    FROM quizze_table q
    JOIN lesson_table l ON q.lesson_id = l.lesson_id
    JOIN course_table c ON l.course_id = c.course_id
    WHERE q.quizze_id IN (SELECT quizze_id FROM Learning_Table WHERE user_id = '$learner_id')
    ORDER BY q.quizze_id DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz History | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">📜 Quiz History</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Course</th>
                <th>Lesson</th>
                <th>Quiz ID</th>
                <th>Correct Answer</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): 
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= htmlspecialchars($row['course_title']); ?></td>
                    <td><?= htmlspecialchars($row['lesson_title']); ?></td>
                    <td><?= $row['quizze_id']; ?></td>
                    <td><?= $row['correct_answer']; ?></td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="5" class="text-center">No quizzes taken yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="text-center">
        <a href="learner_dashboard.php" class="btn btn-success">🏠 Back to Dashboard</a>
    </div>
</div>
</body>
</html>
