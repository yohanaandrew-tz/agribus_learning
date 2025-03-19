<?php
session_start();
include("dbconnect.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Learner") {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch total enrolled courses
$query_courses = "SELECT COUNT(*) AS total_courses FROM Learning_table WHERE user_id = $user_id";
$result_courses = $conn->query($query_courses);
$total_courses = $result_courses->fetch_assoc()["total_courses"];

// Fetch total completed lessons
$query_lessons = "SELECT COUNT(*) AS completed_lessons FROM Learning_table WHERE user_id = $user_id AND progress = 100";
$result_lessons = $conn->query($query_lessons);
$completed_lessons = $result_lessons->fetch_assoc()["completed_lessons"];

// Fetch total quizzes taken
$query_quizzes = "SELECT COUNT(*) AS total_quizzes FROM Quizze_table WHERE course_id IN (SELECT course_id FROM Learning_table WHERE user_id = $user_id)";
$result_quizzes = $conn->query($query_quizzes);
$total_quizzes = $result_quizzes->fetch_assoc()["total_quizzes"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Learner Dashboard | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 250px;
            background: #198754;
            color: white;
            height: 100vh;
            overflow-y: auto;
            padding-top: 10px;
        }
        .sidebar a {
            color: white;
            padding: 10px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #145a32;
        }
        .sidebar .dropdown-menu {
            background: #145a32;
            border: none;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            width: calc(100% - 250px);
        }
        .header {
            background: #28a745;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center py-3">Learner Panel</h4>
    <a href="learner_dashboard.php">🏠 Dashboard</a>

    <!-- Courses Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">📚 My Courses</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="enrolled_courses.php">Enrolled Courses</a></li>
            <li><a class="dropdown-item" href="browse_courses.php">Browse Courses</a></li>
        </ul>
    </div>

    <!-- Learning Progress -->
    <a href="learning_progress.php">📊 Learning Progress</a>

    <!-- Quizzes Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">📝 Quizzes</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="my_quizzes.php">My Quizzes</a></li>
            <li><a class="dropdown-item" href="take_quiz.php">Take a Quiz</a></li>
            <li><a class="dropdown-item" href="quiz_history.php">Quiz History</a></li>
        </ul>
    </div>

    <!-- Settings -->
    <a href="#">⚙️ Settings</a>

    <!-- Logout -->
    <a href="signout.php" class="bg-danger text-center py-2">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="header">
        <h3>Welcome, <?= $_SESSION["name"]; ?> 🎉</h3>
        <a href="signout.php" class="btn btn-light">Sign Out</a>
    </div>

    <div class="container mt-4">
        <h4>📌 Dashboard Overview</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Enrolled Courses</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_courses; ?> Active Courses</h5>
                        <p class="card-text">Keep learning and growing!</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Completed Lessons</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $completed_lessons; ?> Lessons Completed</h5>
                        <p class="card-text">Great job! Keep going!</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Quizzes Taken</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_quizzes; ?> Quizzes</h5>
                        <p class="card-text">Improve your knowledge with more quizzes!</p>
                    </div>
                </div>
            </div>
        </div>

        <h4>🚀 Quick Actions</h4>
        <div class="row">
            <div class="col-md-4">
                <a href="browse_courses.php" class="btn btn-outline-success w-100">Browse Courses</a>
            </div>
            <div class="col-md-4">
                <a href="learning_progress.php" class="btn btn-outline-primary w-100">View Progress</a>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-outline-warning w-100">Take a Quiz</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
