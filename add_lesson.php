<?php
session_start();
include 'dbconnect.php'; // Database connection

// Check if user is logged in and is an instructor
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Instructor") {
    header("Location: sign_in.php");
    exit();
}

$msg = ""; // To store success or error messages

// Fetch courses created by this instructor
$instructor_id = $_SESSION['user_id'];
$course_query = "SELECT course_id, course_title FROM Course_Table WHERE instructor_id = '$instructor_id'";
$course_result = mysqli_query($conn, $course_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $lesson_title = mysqli_real_escape_string($conn, $_POST['lesson_title']);
    $lesson_description = mysqli_real_escape_string($conn, $_POST['lesson_description']);
    $lesson_duration = mysqli_real_escape_string($conn, $_POST['lesson_duration']);
    $lesson_file = "";

    // File upload handling
    if (!empty($_FILES['lesson_file']['name'])) {
        $target_dir = "uploadedfiles/"; // Directory to store uploaded files
        $file_name = basename($_FILES["lesson_file"]["name"]);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ["pdf", "jpg", "jpeg", "png", "mp4", "avi", "mov"];

        if (in_array($file_type, $allowed_types)) {
            $lesson_file = $target_dir . time() . "_" . $file_name; // Unique filename
            move_uploaded_file($_FILES["lesson_file"]["tmp_name"], $lesson_file);
        } else {
            $msg = "<div class='alert alert-danger'>Invalid file type! Only PDF, images, and videos are allowed.</div>";
        }
    }

    // Insert lesson into database
    if (empty($msg)) {
        $sql = "INSERT INTO Lesson_Table (course_id, lesson_title, lesson_description, lesson_file, lesson_duration) 
                VALUES ('$course_id', '$lesson_title', '$lesson_description', '$lesson_file', '$lesson_duration')";

        if (mysqli_query($conn, $sql)) {
            $msg = "<div class='alert alert-success'>Lesson added successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Lesson | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">📖 Add a New Lesson</h2>
    <?= $msg; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label for="course_id" class="form-label">Select Course:</label>
            <select class="form-select" name="course_id" required>
                <option value="" selected disabled>-- Select Course --</option>
                <?php while ($row = mysqli_fetch_assoc($course_result)) : ?>
                    <option value="<?= $row['course_id']; ?>"><?= $row['course_title']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="lesson_title" class="form-label">Lesson Title:</label>
            <input type="text" class="form-control" name="lesson_title" required>
        </div>

        <div class="mb-3">
            <label for="lesson_description" class="form-label">Lesson Description:</label>
            <textarea class="form-control" name="lesson_description" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="lesson_file" class="form-label">Upload Lesson File (PDF, Image, or Video):</label>
            <input type="file" class="form-control" name="lesson_file" accept=".pdf,.jpg,.jpeg,.png,.mp4,.avi,.mov">
        </div>

        <div class="mb-3">
            <label for="lesson_duration" class="form-label">Lesson Duration (hh:mm:ss):</label>
            <input type="time" class="form-control" name="lesson_duration">
        </div>

        <button type="submit" class="btn btn-success w-100">Add Lesson</button>
    </form>

    <a href="instructor_dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
