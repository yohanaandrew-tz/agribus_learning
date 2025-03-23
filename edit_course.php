<?php
include 'dbconnect.php'; // Include database connection

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Fetch course details
    $stmt = $conn->prepare("SELECT course_title, course_description, course_category, course_price, course_photo FROM course_table WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->bind_result($title, $description, $category, $price, $photo);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Invalid request");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = $_POST['course_title'];
    $new_description = $_POST['course_description'];
    $new_category = $_POST['course_category'];
    $new_price = $_POST['course_price'];

    // Handle image upload
    if (!empty($_FILES['course_photo']['name'])) {
        $target_dir = "uploadedfiles/";
        $target_file = $target_dir . basename($_FILES["course_photo"]["name"]);
        move_uploaded_file($_FILES["course_photo"]["tmp_name"], $target_file);
        $photo = $target_file; // Update photo
    }

    // Update course
    $stmt = $conn->prepare("UPDATE course_table SET course_title = ?, course_description = ?, course_category = ?, course_price = ?, course_photo = ? WHERE course_id = ?");
    $stmt->bind_param("sssisi", $new_title, $new_description, $new_category, $new_price, $photo, $course_id);

    if ($stmt->execute()) {
        $message = "Course updated successfully!";
        $alert_class = "alert-success";
    } else {
        $message = "Error updating course.";
        $alert_class = "alert-danger";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en"
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Course</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="text-center mb-3">Edit Course</h4>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $alert_class; ?> text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Course Title</label>
                <input type="text" class="form-control" name="course_title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="course_description" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" class="form-control" name="course_category" value="<?php echo htmlspecialchars($category); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Price (TZS)</label>
                <input type="number" class="form-control" name="course_price" value="<?php echo htmlspecialchars($price); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Course Photo</label>
                <input type="file" class="form-control" name="course_photo">
                <p class="mt-2">Current Photo: <img src="<?php echo $photo; ?>" width="100"></p>
            </div>
