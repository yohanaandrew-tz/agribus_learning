<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in as Admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Admin") {
    header("Location: signin.php");
    exit();
}

// Fetch instructors and their course statistics
$query = "
    SELECT u.user_id, u.name, u.surname, u.user_email, u.user_tel,
           COUNT(c.course_id) AS total_courses,
           COALESCE(SUM((SELECT COUNT(*) FROM Learning_Table WHERE course_id = c.course_id)), 0) AS total_learners,
           MAX(c.last_updated) AS last_course_update
    FROM User_Table u
    LEFT JOIN Course_Table c ON u.user_id = c.instructor_id
    WHERE u.user_role = 'Instructor'
    GROUP BY u.user_id
    ORDER BY total_courses DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instructor Reports | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">📊 Instructor Reports</h2>
    
    <table class="table table-striped table-bordered mt-4">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Instructor Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Total Courses</th>
                <th>Total Enrolled Learners</th>
                <th>Last Course Update</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) : 
            ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= htmlspecialchars($row['name'] . " " . $row['surname']); ?></td>
                    <td><?= htmlspecialchars($row['user_email']); ?></td>
                    <td><?= htmlspecialchars($row['user_tel']); ?></td>
                    <td><?= $row['total_courses']; ?></td>
                    <td><?= $row['total_learners']; ?></td>
                    <td><?= $row['last_course_update'] ? $row['last_course_update'] : 'N/A'; ?></td>
                    <td>
                        <a href="view_instructor.php?instructor_id=<?= $row['user_id']; ?>" class="btn btn-info btn-sm">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
