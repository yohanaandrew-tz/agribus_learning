<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in as a learner
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch learner's progress
$progress_query = "SELECT c.course_title, l.progress, l.status 
                   FROM Learning_Table l
                   JOIN Course_Table c ON l.course_id = c.course_id
                   WHERE l.user_id = $user_id
                   ORDER BY l.status, l.progress DESC";

$progress_result = mysqli_query($conn, $progress_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Learning Progress | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">📊 My Learning Progress</h2>

    <?php if (mysqli_num_rows($progress_result) > 0) : ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Course Title</th>
                        <th>Results(%)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($progress_result)) : ?>
                        <tr>
                            <td><?= $row["course_title"]; ?></td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar <?= $row['progress'] == 100 ? 'bg-success' : 'bg-info'; ?>" 
                                         role="progressbar" 
                                         style="width: <?= $row['progress']; ?>%;" 
                                         aria-valuenow="<?= $row['progress']; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?= $row['progress']; ?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?= $row['status'] == 'Completed' ? 'bg-success' : 'bg-warning'; ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p class="text-center text-muted">You haven't started any courses yet.</p>
    <?php endif; ?>

</div>

</body>
</html>
