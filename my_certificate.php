<?php
session_start();
require 'dbconnect.php'; // Database connection

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Learner') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch learner info
$user_query = $conn->prepare("SELECT name, surname FROM user_table WHERE user_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
$full_name = $user['name'] . ' ' . $user['surname'];

// Fetch enrolled courses with progress >= 40%
$course_query = $conn->prepare("SELECT c.course_title, l.progress FROM learning_table l 
    JOIN course_table c ON l.course_id = c.course_id WHERE l.user_id = ? AND l.progress >= 40");
$course_query->bind_param("i", $user_id);
$course_query->execute();
$course_result = $course_query->get_result();

$courses = [];
$total_progress = 0;
$course_count = 0;

while ($row = $course_result->fetch_assoc()) {
    $courses[] = $row;
    $total_progress += $row['progress'];
    $course_count++;
}

// Calculate average progress and remark
$average_progress = ($course_count > 0) ? ($total_progress / $course_count) : 0;
$remark = "Failure";
if ($average_progress > 80) $remark = "Very Good";
elseif ($average_progress > 60) $remark = "Good";
elseif ($average_progress > 40) $remark = "Pass";
elseif ($average_progress > 20) $remark = "Satisfactory";

// Current year
$completion_year = date("Y");

// Default photo
$photo = "uploadedfiles/id.jpg";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certificate</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header text-center">
            <h2>Agribusiness Certificate</h2>
            <p>Online Agribusiness Center</p>
        </div>
        <div class="card-body text-center">
            <img src="<?php echo $photo; ?>" alt="Profile Photo" class="rounded-circle" width="120" height="120">
            <h3><?php echo $full_name; ?></h3>
            <p>Year of Completion: <strong><?php echo $completion_year; ?></strong></p>
            <p>Remark: <strong><?php echo $remark; ?></strong></p>
            <h4>Enrolled Courses:</h4>
            <ul class="list-group">
                <?php foreach ($courses as $course) { ?>
                    <li class="list-group-item">
                        <?php echo $course['course_title']; ?> - Progress: <?php echo $course['progress']; ?>%
                    </li>
                <?php } ?>
            </ul>
            <a href="download_certificate.php" class="btn btn-success mt-3">Download PDF</a>
        </div>
    </div>
</body>
</html>
