<?php
include 'dbconnect.php'; // Include database connection

if (isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id'];

    // Fetch lesson details
    $stmt = $conn->prepare("SELECT lesson_title, lesson_description, lesson_file, lesson_duration FROM lesson_table WHERE lesson_id = ?");
    $stmt->bind_param("i", $lesson_id);
    $stmt->execute();
    $stmt->bind_result($title, $description, $file, $duration);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Invalid request");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = $_POST['lesson_title'];
    $new_description = $_POST['lesson_description'];
    $new_duration = $_POST['lesson_duration'];

    // Handle file upload
    if (!empty($_FILES['lesson_file']['name'])) {
        $target_dir = "uploadedfiles/";
        $target_file = $target_dir . basename($_FILES["lesson_file"]["name"]);
        move_uploaded_file($_FILES["lesson_file"]["tmp_name"], $target_file);
        $file = $target_file; // Update file
    }

    // Update lesson
    $stmt = $conn->prepare("UPDATE lesson_table SET lesson_title = ?, lesson_description = ?, lesson_file = ?, lesson_duration = ? WHERE lesson_id = ?");
    $stmt->bind_param("ssssi", $new_title, $new_description, $file, $new_duration, $lesson_id);

    if ($stmt->execute()) {
        $message = "Lesson updated successfully!";
        $alert_class = "alert-success";
    } else {
        $message = "Error updating lesson.";
        $alert_class = "alert-danger";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Lesson</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="text-center mb-3">Edit Lesson</h4>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $alert_class; ?> text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Lesson Title</label>
                <input type="text" class="form-control" name="lesson_title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="lesson_description" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Lesson Duration</label>
                <input type="time" class="form-control" name="lesson_duration" value="<?php echo htmlspecialchars($duration); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Lesson File</label>
                <input type="file" class="form-control" name="lesson_file">
                <p class="mt-2">Current File: <a href="<?php echo $file; ?>" target="_blank">View</a></p>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Lesson</button>
        </form>
    </div>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
