<?php
session_start();
include 'dbconnect.php';

// Check if the user is logged in as a learner
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get course ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: browse_course.php");
    exit();
}
$course_id = $_GET['id'];

// Fetch course details
$course_query = "SELECT c.*, u.name AS instructor_name, u.surname 
                 FROM Course_Table c
                 JOIN User_Table u ON c.instructor_id = u.user_id
                 WHERE c.course_id = $course_id";
$course_result = mysqli_query($conn, $course_query);
$course = mysqli_fetch_assoc($course_result);

if (!$course) {
    echo "<p class='text-center text-danger'>Course not found.</p>";
    exit();
}

// Check if the learner is already enrolled
$learning_query = "SELECT * FROM Learning_Table WHERE user_id = $user_id AND course_id = $course_id";
$learning_result = mysqli_query($conn, $learning_query);
$learning = mysqli_fetch_assoc($learning_result);

// Fetch lessons for this course
$lesson_query = "SELECT * FROM Lesson_Table WHERE course_id = $course_id ORDER BY last_updated ASC";
$lesson_result = mysqli_query($conn, $lesson_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course Details | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center"><?= $course['course_title']; ?></h2>
    <p class="text-muted text-center"><?= $course['course_category']; ?></p>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Course Description</h5>
            <p><?= $course['course_description']; ?></p>
            <p><strong>Instructor:</strong> <?= $course['instructor_name'] . " " . $course['surname']; ?></p>
            <p><strong>Price:</strong> <?= $course['course_price'] == 0.00 ? 'Free' : 'TZS' . $course['course_price']; ?></p>

            <!-- Start/Continue Learning Button -->
            <?php if ($learning) : ?>
                <a href="learning_page.php?course_id=<?= $course_id; ?>" class="btn btn-success">Continue Learning</a>
            <?php else : ?>
                <a href="enroll_course.php?course_id=<?= $course_id; ?>" class="btn btn-primary">Start Learning</a>
            <?php endif; ?>

            <a href="browse_course.php" class="btn btn-secondary">Back to Courses</a>
        </div>
    </div>

    <h4 class="mt-4">📚 Course Lessons</h4>
    <?php if (mysqli_num_rows($lesson_result) > 0) : ?>
        <ul class="list-group">
            <?php while ($lesson = mysqli_fetch_assoc($lesson_result)) : ?>
                <li class="list-group-item">
                    <strong><?= $lesson['lesson_title']; ?></strong>
                    <p><?= $lesson['lesson_description']; ?></p>
                    <a href="<?= $lesson['lesson_file']; ?>" class="btn btn-info btn-sm" target="_blank">View Lesson</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p class="text-muted">No lessons added yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
