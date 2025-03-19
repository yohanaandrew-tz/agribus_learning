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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST["course_title"]);
    $description = mysqli_real_escape_string($conn, $_POST["course_description"]);
    $category = mysqli_real_escape_string($conn, $_POST["course_category"]);
    $price = $_POST["course_price"];
    $instructor_id = $_SESSION["user_id"];
    
    // File Upload Handling
    $targetDir = "uploadedfiles/";
    $fileName = basename($_FILES["course_photo"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check if file is an image
    $allowTypes = array("jpg", "png", "webp", "jpeg");
    if (!in_array(strtolower($fileType), $allowTypes)) {
        $errorMsg = "Only JPG, JPEG, and PNG files are allowed.";
    } else {
        if (move_uploaded_file($_FILES["course_photo"]["tmp_name"], $targetFilePath)) {
            // Insert course data into the database
            $sql = "INSERT INTO Course_Table (course_title, course_description, course_photo, instructor_id, course_category, course_price) 
                    VALUES ('$title', '$description', '$targetFilePath', '$instructor_id', '$category', '$price')";
            
            if (mysqli_query($conn, $sql)) {
                $successMsg = "Course added successfully!";
            } else {
                $errorMsg = "Error: " . mysqli_error($conn);
            }
        } else {
            $errorMsg = "Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Course | Instructor</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">📚 Add New Course</h2>

    <!-- Error / Success Messages -->
    <?php if ($errorMsg) : ?>
        <div class="alert alert-danger"><?= $errorMsg; ?></div>
    <?php elseif ($successMsg) : ?>
        <div class="alert alert-success"><?= $successMsg; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Course Title</label>
            <input type="text" name="course_title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Course Description</label>
            <textarea name="course_description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Course Photo</label>
            <input type="file" name="course_photo" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Course Category</label>
            <select name="course_category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="Crop Farming">Crop Farming</option>
                <option value="Animal Husbandry">Animal Husbandry</option>
                <option value="Agri-Business">Agri-Business</option>
                <option value="Agritech">Agritech</option>
                <option value="Sustainable Farming">Sustainable Farming</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Course Price (TZS)</label>
            <input type="number" name="course_price" class="form-control" step="100" min="0" required>
        </div>

        <button type="submit" class="btn btn-success">Add Course</button>
    </form>
</div>

</body>
</html>
