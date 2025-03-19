<?php
session_start();
include 'dbconnect.php';

// Ensure only instructors can access
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Instructor") {
    header("Location: signin.php");
    exit();
}

$errorMsg = "";
$successMsg = "";

// Fetch courses taught by the instructor
$instructor_id = $_SESSION["user_id"];
$courses_query = "SELECT course_id, course_title FROM Course_Table WHERE instructor_id = '$instructor_id'";
$courses_result = mysqli_query($conn, $courses_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lesson_id = $_POST["lesson_id"];

    // Insert multiple questions
    for ($i = 1; $i <= 5; $i++) {
        $question = mysqli_real_escape_string($conn, $_POST["question_$i"]);
        $option_A = mysqli_real_escape_string($conn, $_POST["option_A_$i"]);
        $option_B = mysqli_real_escape_string($conn, $_POST["option_B_$i"]);
        $option_C = mysqli_real_escape_string($conn, $_POST["option_C_$i"]);
        $option_D = mysqli_real_escape_string($conn, $_POST["option_D_$i"]);
        $correct_answer = $_POST["correct_answer_$i"];

        if (!empty($question)) {
            $sql = "INSERT INTO Quizze_Table (lesson_id, question, option_A, option_B, option_C, option_D, correct_answer) 
                    VALUES ('$lesson_id', '$question', '$option_A', '$option_B', '$option_C', '$option_D', '$correct_answer')";
            mysqli_query($conn, $sql);
        }
    }
    $successMsg = "Quiz added successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Quiz | Instructor</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
    <script>
        function fetchLessons(courseId) {
            if (courseId) {
                fetch('fetch_lessons.php?course_id=' + courseId)
                .then(response => response.json())
                .then(data => {
                    let lessonSelect = document.getElementById("lesson_id");
                    lessonSelect.innerHTML = '<option value="">Select Lesson</option>';
                    data.forEach(lesson => {
                        lessonSelect.innerHTML += `<option value="${lesson.lesson_id}">${lesson.lesson_title}</option>`;
                    });
                });
            }
        }
    </script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">📝 Create New Quiz</h2>

    <!-- Error / Success Messages -->
    <?php if ($errorMsg) : ?>
        <div class="alert alert-danger"><?= $errorMsg; ?></div>
    <?php elseif ($successMsg) : ?>
        <div class="alert alert-success"><?= $successMsg; ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="mt-4">
        <!-- Course Selection -->
        <div class="mb-3">
            <label class="form-label">Select Course</label>
            <select name="course_id" class="form-control" onchange="fetchLessons(this.value)" required>
                <option value="">Select Course</option>
                <?php while ($row = mysqli_fetch_assoc($courses_result)) : ?>
                    <option value="<?= $row['course_id']; ?>"><?= $row['course_title']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Lesson Selection -->
        <div class="mb-3">
            <label class="form-label">Select Lesson</label>
            <select name="lesson_id" id="lesson_id" class="form-control" required>
                <option value="">Select Lesson</option>
            </select>
        </div>

        <!-- Dynamic Quiz Questions (5 Questions) -->
        <?php for ($i = 1; $i <= 5; $i++) : ?>
            <h5>Question <?= $i; ?></h5>
            <div class="mb-3">
                <label class="form-label">Question</label>
                <input type="text" name="question_<?= $i; ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Option A</label>
                <input type="text" name="option_A_<?= $i; ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Option B</label>
                <input type="text" name="option_B_<?= $i; ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Option C</label>
                <input type="text" name="option_C_<?= $i; ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Option D</label>
                <input type="text" name="option_D_<?= $i; ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Correct Answer</label>
                <select name="correct_answer_<?= $i; ?>" class="form-control">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <hr>
        <?php endfor; ?>

        <button type="submit" class="btn btn-primary">Add Quiz</button>
    </form>
</div>

</body>
</html>
