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

// Get lesson details
$lesson_query = "SELECT * FROM lesson_table WHERE lesson_id = ? AND course_id = ?";
$stmt = $conn->prepare($lesson_query);
$stmt->bind_param("ii", $lesson_id, $course_id);
$stmt->execute();
$lesson_result = $stmt->get_result();
$lesson = $lesson_result->fetch_assoc();

// Get next lesson
$next_lesson_query = "SELECT lesson_id FROM lesson_table WHERE course_id = ? AND lesson_id > ? ORDER BY lesson_id ASC LIMIT 1";
$stmt = $conn->prepare($next_lesson_query);
$stmt->bind_param("ii", $course_id, $lesson_id);
$stmt->execute();
$next_lesson_result = $stmt->get_result();
$next_lesson = $next_lesson_result->fetch_assoc();

// Check if quiz exists
$quiz_query = "SELECT quizze_id FROM quizze_table WHERE course_id = ? LIMIT 1";
$stmt = $conn->prepare($quiz_query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$quiz_result = $stmt->get_result();
$quiz = $quiz_result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $lesson['lesson_title'] ?></title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2><?= $lesson['lesson_title'] ?></h2>
<p><?= $lesson['lesson_description'] ?></p>

<?php if (!empty($lesson['lesson_file'])): ?>
    <p><a href="<?= $lesson['lesson_file'] ?>" download>Download Lesson Material</a></p>
<?php endif; ?>

<!-- Show Quiz Button -->
<?php if ($quiz): ?>
    <a href="quiz_view.php?course_id=<?= $course_id ?>&lesson_id=<?= $lesson_id ?>" class="btn btn-warning">Take Quiz</a>
<?php endif; ?>

<!-- Show Next Lesson Button After Quiz -->
<?php if ($next_lesson): ?>
    <a href="lesson_view.php?lesson_id=<?= $next_lesson['lesson_id'] ?>&course_id=<?= $course_id ?>" class="btn btn-primary">Next Lesson</a>
<?php else: ?>
    <p>Congratulations! You have completed this course.</p>
<?php endif; ?>

</body>
</html>
