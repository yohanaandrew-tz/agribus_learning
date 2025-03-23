<?php
session_start();
include 'dbconnect.php'; // Ensure your database connection is included

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Learner') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$current_quiz = isset($_GET['quiz_no']) ? intval($_GET['quiz_no']) : 1;

// Verify if the learner completed the course
$check_completion = $conn->prepare("SELECT status FROM learning_table WHERE user_id = ? AND course_id = ?");
$check_completion->bind_param("ii", $user_id, $course_id);
$check_completion->execute();
$completion_result = $check_completion->get_result();
$course_status = $completion_result->fetch_assoc();

if (!$course_status || $course_status['status'] !== 'Completed') {
    echo "<p>You must complete the course before attempting quizzes.</p>";
    exit();
}

// Fetch total quizzes in this course
$total_quizzes_query = $conn->prepare("SELECT COUNT(*) AS total FROM quizze_table WHERE course_id = ?");
$total_quizzes_query->bind_param("i", $course_id);
$total_quizzes_query->execute();
$total_quizzes_result = $total_quizzes_query->get_result();
$total_quizzes = $total_quizzes_result->fetch_assoc()['total'];

if ($total_quizzes == 0) {
    echo "<p>No quizzes available for this course.</p>";
    exit();
}

// Fetch the current quiz question
$quiz_query = $conn->prepare("SELECT * FROM quizze_table WHERE course_id = ? ORDER BY quizze_id LIMIT ?, 1");
$offset = $current_quiz - 1;
$quiz_query->bind_param("ii", $course_id, $offset);
$quiz_query->execute();
$quiz_result = $quiz_query->get_result();
$quiz = $quiz_result->fetch_assoc();

if (!$quiz) {
    echo "<p>Quiz not found.</p>";
    exit();
}

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_answer = $_POST['answer'];
    $correct_answer = $quiz['correct_answer'];

    // Store result in session (or a database table for real tracking)
    if (!isset($_SESSION['quiz_score'][$course_id])) {
        $_SESSION['quiz_score'][$course_id] = 0;
    }

    if ($selected_answer === $correct_answer) {
        $_SESSION['quiz_score'][$course_id] += 1; // Increment correct answer count
    }

    // Redirect to next quiz or show final score
    if ($current_quiz < $total_quizzes) {
        header("Location: take_quiz.php?course_id=$course_id&quiz_no=" . ($current_quiz + 1));
        exit();
    } else {
        // Calculate final score percentage
        $final_score = ($_SESSION['quiz_score'][$course_id] / $total_quizzes) * 100;

        // Update progress in learning_table
        $update_progress = $conn->prepare("UPDATE learning_table SET progress = ? WHERE user_id = ? AND course_id = ?");
        $update_progress->bind_param("dii", $final_score, $user_id, $course_id);
        $update_progress->execute();

        echo "<p>Quiz completed! Your score: <strong>$final_score%</strong></p>";
        unset($_SESSION['quiz_score'][$course_id]); // Clear session score
        exit();
    }
}

// Display Quiz Question
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h2 class="mt-4">Quiz <?php echo $current_quiz; ?> of <?php echo $total_quizzes; ?></h2>
    <p><?php echo htmlspecialchars($quiz['question']); ?></p>

    <form method="post">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="answer" value="A" required>
            <label class="form-check-label"><?php echo htmlspecialchars($quiz['option_A']); ?></label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="answer" value="B">
            <label class="form-check-label"><?php echo htmlspecialchars($quiz['option_B']); ?></label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="answer" value="C">
            <label class="form-check-label"><?php echo htmlspecialchars($quiz['option_C']); ?></label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="answer" value="D">
            <label class="form-check-label"><?php echo htmlspecialchars($quiz['option_D']); ?></label>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit Answer</button>
    </form>
</body>
</html>
