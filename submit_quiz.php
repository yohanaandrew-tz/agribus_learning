<?php
session_start();
include 'dbconnect.php';

// Ensure only learners can submit quizzes
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: sign_in.php");
    exit();
}

$learner_id = $_SESSION["user_id"];
$total_questions = count($_POST); // Number of submitted answers
$correct_answers = 0;
$lesson_id = null;
$course_id = null;

// Validate answers
foreach ($_POST as $quizze_id => $selected_answer) {
    // Fetch correct answer from the database
    $query = "SELECT lesson_id, correct_answer FROM Quizze_Table WHERE quizze_id = '$quizze_id'";
    $result = mysqli_query($conn, $query);
    $quiz = mysqli_fetch_assoc($result);

    if ($quiz) {
        if ($selected_answer == $quiz['correct_answer']) {
            $correct_answers++;
        }
        $lesson_id = $quiz['lesson_id'];
    }
}

// Fetch course ID from lesson
if ($lesson_id) {
    $query = "SELECT course_id FROM Lesson_Table WHERE lesson_id = '$lesson_id'";
    $result = mysqli_query($conn, $query);
    $lesson = mysqli_fetch_assoc($result);
    $course_id = $lesson['course_id'];
}

// Calculate score
$score = ($total_questions > 0) ? ($correct_answers / $total_questions) * 100 : 0;

// Check if the learner already took this quiz
$query = "SELECT progress FROM Learning_Table WHERE user_id = '$learner_id' AND course_id = '$course_id'";
$result = mysqli_query($conn, $query);
$learning = mysqli_fetch_assoc($result);

if ($learning) {
    // Fetch total lessons in the course
    $query = "SELECT COUNT(*) AS total_lessons FROM Lesson_Table WHERE course_id = '$course_id'";
    $result = mysqli_query($conn, $query);
    $lesson_count = mysqli_fetch_assoc($result);
    $total_lessons = $lesson_count['total_lessons'];

    // Progress increment based on lessons
    $lesson_weight = 100 / $total_lessons;
    
    // Update progress only for the first time taking the quiz
    if ($learning['progress'] < ($lesson_weight * ($total_questions / $total_lessons))) {
        $new_progress = min(100, $learning['progress'] + $lesson_weight);
        $query = "UPDATE Learning_Table SET progress = '$new_progress', status = CASE WHEN progress >= 100 THEN 'Completed' ELSE 'In Progress' END WHERE user_id = '$learner_id' AND course_id = '$course_id'";
        mysqli_query($conn, $query);
    }
}

// Display results
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Results</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">🎯 Quiz Results</h2>
    <div class="alert alert-info text-center">
        <h4>You Scored: <strong><?= round($score, 2); ?>%</strong></h4>
    </div>
    <div class="text-center">
        <a href="learner_dashboard.php" class="btn btn-success">Back to Dashboard</a>
        <a href="take_quiz.php" class="btn btn-primary">Take Another Quiz</a>
    </div>
</div>
</body>
</html>
