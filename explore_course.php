<?php
include 'dbconnect.php';

$sql = "SELECT c.course_id, c.course_title, c.course_description, c.course_category, c.course_photo, 
               u.name AS instructor_name, u.surname AS instructor_surname
        FROM Course_Table c
        INNER JOIN User_Table u ON c.instructor_id = u.user_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Explore Courses</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Explore Our Courses</h2>
    <p class="text-center">Sign up to access full course content.</p>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?= !empty($row['course_photo']) ? $row['course_photo'] : 'default.jpg'; ?>" class="card-img-top" alt="Course Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['course_title']; ?></h5>
                        <p class="card-text"><?= substr($row['course_description'], 0, 100); ?>...</p>
                        <p><strong>Category:</strong> <?= $row['course_category']; ?></p>
                        <p><strong>Instructor:</strong> <?= $row['instructor_name'] . ' ' . $row['instructor_surname']; ?></p>
                        <a href="signin.php" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
