<?php
session_start();
include "dbconnect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = intval($_GET['course_id']);
$lesson_id = intval($_GET['lesson_id']);

// Fetch quiz
$quiz_query = "SELECT * FROM quizze_table WHERE course_id = ? ORDER BY quizze_id ASC LIMIT 1";
$stmt = $conn->prepare($quiz_query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$quiz_result = $stmt->get_result();
$quiz = $quiz_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_answer = $_POST['answer'] ?? '';
    if ($selected_answer === $quiz['correct_answer']) {
        $_SESSION['quiz_passed'] = true;
        header("Location: lesson_view.php?lesson_id=" . ($lesson_id + 1) . "&course_id=$course_id");
        exit();
    } else {
        $_SESSION['quiz_passed'] = false;
        $_SESSION['message'] = "Incorrect answer! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Quiz</h4>
        </div>
        <div class="card-body">
            <h5 class="mb-4"><?= $quiz['question'] ?></h5>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['message'] ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <form method="POST">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" value="A" required>
                    <label class="form-check-label"><?= $quiz['option_A'] ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" value="B">
                    <label class="form-check-label"><?= $quiz['option_B'] ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" value="C">
                    <label class="form-check-label"><?= $quiz['option_C'] ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" value="D">
                    <label class="form-check-label"><?= $quiz['option_D'] ?></label>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="lesson_view.php?lesson_id=<?= $lesson_id ?>&course_id=<?= $course_id ?>" class="btn btn-secondary">Back to Lesson</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>

</body>
</html>
