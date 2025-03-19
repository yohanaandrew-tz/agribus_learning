<?php
session_start();
include 'dbconnect.php';

// Ensure only learners can access
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: signin.php");
    exit();
}

$learner_id = $_SESSION["user_id"];

// Fetch quizzes for completed lessons
$query = "SELECT q.quizze_id, q.question, q.option_A, q.option_B, q.option_C, q.option_D, q.correct_answer, l.lesson_title, c.course_title
          FROM Quizze_Table q
          JOIN Lesson_Table l ON q.lesson_id = l.lesson_id
          JOIN Course_Table c ON l.course_id = c.course_id
          JOIN Learning_Table lt ON lt.course_id = c.course_id
          WHERE lt.user_id = '$learner_id' AND lt.status = 'Completed'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Take Quiz | Learner</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">📚 Take a Quiz</h2>

    <?php if (mysqli_num_rows($result) > 0) : ?>
        <form action="submit_quiz.php" method="POST">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <strong><?= $row['course_title']; ?> - <?= $row['lesson_title']; ?></strong>
                    </div>
                    <div class="card-body">
                        <p><strong><?= $row['question']; ?></strong></p>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer_<?= $row['quizze_id']; ?>" value="A" required>
                            <label class="form-check-label"><?= $row['option_A']; ?></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer_<?= $row['quizze_id']; ?>" value="B">
                            <label class="form-check-label"><?= $row['option_B']; ?></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer_<?= $row['quizze_id']; ?>" value="C">
                            <label class="form-check-label"><?= $row['option_C']; ?></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer_<?= $row['quizze_id']; ?>" value="D">
                            <label class="form-check-label"><?= $row['option_D']; ?></label>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <button type="submit" class="btn btn-primary w-100">Submit Quiz</button>
        </form>
    <?php else : ?>
        <div class="alert alert-warning text-center">
            No quizzes available for completed lessons.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
