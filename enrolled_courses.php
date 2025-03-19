<?php
session_start();
include 'dbconnect.php';

// Check if the user is logged in as a learner
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch enrolled courses for the learner
$enrolled_query = "SELECT c.course_id, c.course_title, c.course_category, c.course_price, 
                          u.name AS instructor_name, u.surname, l.progress, l.status
                   FROM Learning_Table l
                   JOIN Course_Table c ON l.course_id = c.course_id
                   JOIN User_Table u ON c.instructor_id = u.user_id
                   WHERE l.user_id = $user_id
                   ORDER BY l.status ASC, c.course_title ASC";

$enrolled_result = mysqli_query($conn, $enrolled_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Enrolled Courses | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">🎓 My Enrolled Courses</h2>

    <?php if (mysqli_num_rows($enrolled_result) > 0) : ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($enrolled_result)) : ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= $row['course_title']; ?></h5>
                            <p><strong>Instructor:</strong> <?= $row['instructor_name'] . " " . $row['surname']; ?></p>
                            <p><strong>Category:</strong> <?= $row['course_category']; ?></p>
                            <p><strong>Price:</strong> <?= $row['course_price'] == 0.00 ? 'Free' : '$' . $row['course_price']; ?></p>
                            
                            <!-- Progress Bar -->
                            <p><strong>Progress:</strong> <?= $row['progress']; ?>%</p>
                            <div class="progress mb-2">
                                <div class="progress-bar" role="progressbar" style="width: <?= $row['progress']; ?>%;" 
                                     aria-valuenow="<?= $row['progress']; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= $row['progress']; ?>%
                                </div>
                            </div>
                            
                            <p><strong>Status:</strong> <?= $row['status']; ?></p>

                            <a href="course_details.php?id=<?= $row['course_id']; ?>" class="btn btn-primary">Continue Learning</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p class="text-center">😔 You haven't enrolled in any courses yet.</p>
        <div class="text-center">
            <a href="browse_courses.php" class="btn btn-success">Browse Courses</a>
        </div>
    <?php endif; ?>

    <a href="learner_dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

</body>
</html>
