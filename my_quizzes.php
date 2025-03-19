<?php
session_start();
include 'dbconnect.php';

// Check if the user is logged in as a learner
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch quizzes for enrolled courses
$quiz_query = "SELECT q.quizze_id, q.course_id, q.question, q.option_A, q.option_B, q.option_C, q.option_D, c.course_title 
               FROM Quizze_Table q
               JOIN Course_Table c ON q.course_id = c.course_id
               JOIN Learning_Table l ON l.course_id = c.course_id
               WHERE l.user_id = $user_id
               ORDER BY c.course_title, q.quizze_id";

$quiz_result = mysqli_query($conn, $quiz_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Quizzes | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">📝 My Quizzes</h2>

    <?php if (mysqli_num_rows($quiz_result) > 0) : ?>
        <div class="accordion" id="quizAccordion">
            <?php 
            $current_course = "";
            while ($quiz = mysqli_fetch_assoc($quiz_result)) : 
                // Display course title only once
                if ($current_course != $quiz['course_title']) {
                    $current_course = $quiz['course_title'];
                    echo "<h4 class='mt-3'>$current_course</h4>";
                }
            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#quiz<?= $quiz['quizze_id']; ?>">
                            <?= $quiz['question']; ?>
                        </button>
                    </h2>
                    <div id="quiz<?= $quiz['quizze_id']; ?>" class="accordion-collapse collapse" data-bs-parent="#quizAccordion">
                        <div class="accordion-body">
                            <form action="submit_quiz.php" method="POST">
                                <input type="hidden" name="quiz_id" value="<?= $quiz['quizze_id']; ?>">
                                <input type="hidden" name="course_id" value="<?= $quiz['course_id']; ?>">

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answer" value="A" required>
                                    <label class="form-check-label"><?= $quiz['option_A']; ?></label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answer" value="B">
                                    <label class="form-check-label"><?= $quiz['option_B']; ?></label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answer" value="C">
                                    <label class="form-check-label"><?= $quiz['option_C']; ?></label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answer" value="D">
                                    <label class="form-check-label"><?= $quiz['option_D']; ?></label>
                                </div>

                                <button type="submit" class="btn btn-primary mt-2">Submit Answer</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p class="text-center text-muted">You have no quizzes available.</p>
    <?php endif; ?>

</div>

</body>
</html>
