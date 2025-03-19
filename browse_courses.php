<?php
session_start();
include 'dbconnect.php';

// Check if the user is logged in as a learner
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: signin.php");
    exit();
}

// Fetch course categories
$category_query = "SELECT DISTINCT course_category FROM Course_Table";
$category_result = mysqli_query($conn, $category_query);

// Fetch all courses
$course_query = "SELECT c.*, u.name AS instructor_name, u.surname 
                 FROM Course_Table c 
                 JOIN User_Table u ON c.instructor_id = u.user_id 
                 ORDER BY last_updated DESC";
$course_result = mysqli_query($conn, $course_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Browse Courses | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">📚 Browse Courses</h2>

    <!-- Search Bar & Category Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search courses...">
        </div>
        <div class="col-md-4">
            <select id="categoryFilter" class="form-select">
                <option value="">📂 Filter by Category</option>
                <?php while ($row = mysqli_fetch_assoc($category_result)) : ?>
                    <option value="<?= $row['course_category']; ?>"><?= $row['course_category']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Course List -->
    <div class="row" id="courseContainer">
        <?php while ($row = mysqli_fetch_assoc($course_result)) : ?>
            <div class="col-md-4 course-item" data-category="<?= $row['course_category']; ?>">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['course_title']; ?></h5>
                        <p class="card-text"><?= substr($row['course_description'], 0, 100); ?>...</p>
                        <p><strong>Instructor:</strong> <?= $row['instructor_name'] . " " . $row['surname']; ?></p>
                        <p><strong>Category:</strong> <?= $row['course_category']; ?></p>
                        <p><strong>Price:</strong> <?= $row['course_price'] == 0.00 ? 'Free' : 'TZS' . $row['course_price']; ?></p>
                        <a href="course_details.php?id=<?= $row['course_id']; ?>" class="btn btn-primary">View Course</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="learner_dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<script>
    // Search and filter functionality
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll(".course-item").forEach(course => {
            let title = course.querySelector(".card-title").textContent.toLowerCase();
            let category = course.dataset.category.toLowerCase();
            course.style.display = (title.includes(filter) || category.includes(filter)) ? "" : "none";
        });
    });

    document.getElementById("categoryFilter").addEventListener("change", function() {
        let selectedCategory = this.value.toLowerCase();
        document.querySelectorAll(".course-item").forEach(course => {
            let category = course.dataset.category.toLowerCase();
            course.style.display = (selectedCategory === "" || category === selectedCategory) ? "" : "none";
        });
    });
</script>

</body>
</html>
