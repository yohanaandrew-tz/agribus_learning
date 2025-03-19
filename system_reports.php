<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in as Admin or Instructor
if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] != "Admin" && $_SESSION["user_role"] != "Instructor")) {
    header("Location: sign_in.php");
    exit();
}

// Fetch all courses with enrollment count
$query = "
    SELECT c.course_id, c.course_title, c.course_category, c.course_price, c.last_updated,
           u.name AS instructor_name, u.surname AS instructor_surname,
           (SELECT COUNT(*) FROM Learning_Table WHERE course_id = c.course_id) AS enrolled_learners
    FROM Course_Table c
    JOIN User_Table u ON c.instructor_id = u.user_id
    ORDER BY c.last_updated DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course Reports | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">📊 Course Reports</h2>
    
    <table class="table table-striped table-bordered mt-4">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Course Title</th>
                <th>Category</th>
                <th>Instructor</th>
                <th>Enrolled Learners</th>
                <th>Price (TZS)</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) : 
            ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= htmlspecialchars($row['course_title']); ?></td>
                    <td><?= htmlspecialchars($row['course_category']); ?></td>
                    <td><?= htmlspecialchars($row['instructor_name'] . " " . $row['instructor_surname']); ?></td>
                    <td><?= $row['enrolled_learners']; ?></td>
                    <td><?= number_format($row['course_price'], 2); ?></td>
                    <td><?= $row['last_updated']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
