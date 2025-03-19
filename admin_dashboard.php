<?php
session_start();
include("dbconnect.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Admin") {
    header("Location: signin.php");
    exit();
}

// Fetch total users
$query_users = "SELECT COUNT(*) AS total_users FROM User_table";
$result_users = $conn->query($query_users);
$total_users = $result_users->fetch_assoc()["total_users"];

// Fetch total instructors
$query_instructors = "SELECT COUNT(*) AS total_instructors FROM User_table WHERE user_role = 'Instructor'";
$result_instructors = $conn->query($query_instructors);
$total_instructors = $result_instructors->fetch_assoc()["total_instructors"];

// Fetch total learners
$query_learners = "SELECT COUNT(*) AS total_learners FROM User_table WHERE user_role = 'Learner'";
$result_learners = $conn->query($query_learners);
$total_learners = $result_learners->fetch_assoc()["total_learners"];

// Fetch total courses
$query_courses = "SELECT COUNT(*) AS total_courses FROM Course_table";
$result_courses = $conn->query($query_courses);
$total_courses = $result_courses->fetch_assoc()["total_courses"];

// Fetch total lessons
$query_lessons = "SELECT COUNT(*) AS total_lessons FROM Lesson_table";
$result_lessons = $conn->query($query_lessons);
$total_lessons = $result_lessons->fetch_assoc()["total_lessons"];

// Fetch total quizzes
$query_quizzes = "SELECT COUNT(*) AS total_quizzes FROM Quizze_table";
$result_quizzes = $conn->query($query_quizzes);
$total_quizzes = $result_quizzes->fetch_assoc()["total_quizzes"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Agribusiness Learning</title>
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
            background: #343a40;
            color: white;
            transition: 0.3s;
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
            background: #495057;
        }
        .sidebar .dropdown-menu {
            background: #495057;
            border: none;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }
        .header {
            background: #007bff;
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
<div class="sidebar bg-success">
    <h4 class="text-center py-3">Admin Panel</h4>
    <a href="admin_dashboard.php">🏠 Dashboard</a>

    <!-- User Management Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">👤 Manage Users</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="user_list.php">View Users</a></li>
            <li><a class="dropdown-item" href="add_user.php">Add User</a></li>
        </ul>
    </div>

    <!-- Course Management Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">📚 Manage Courses</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">View Courses</a></li>
            <li><a class="dropdown-item" href="add_new_course.php">Add New Course</a></li>
        </ul>
    </div>

    <!-- Lesson Management Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">📖 Manage Lessons</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">View Lessons</a></li>
            <li><a class="dropdown-item" href="#">Upload Lesson</a></li>
        </ul>
    </div>

    <!-- Quiz Management Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">📝 Manage Quizzes</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">View Quizzes</a></li>
            <li><a class="dropdown-item" href="#">Create Quiz</a></li>
        </ul>
    </div>

    <!-- Reports -->
    <a href="system_reports.php">📊 System Reports</a>

    <!-- Settings -->
    <a href="#">⚙️ Settings</a>

    <!-- Logout -->
    <a href="signout.php" class="bg-danger text-center py-2">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="header bg-success">
        <h3>Welcome, <?= $_SESSION["name"]; ?> 🎉</h3>
        <a href="signout.php" class="btn btn-light">Sign Out</a>
    </div>

    <div class="container mt-4">
        <h4>📌 Dashboard Overview</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Users</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_users; ?> Users</h5>
                        <p class="card-text">Manage all users in the system.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <a href="instructor_reports.php"><div class="card-header">Total Instructors</div></a>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_instructors; ?> Instructors</h5>
                        <p class="card-text">Manage instructor accounts.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Total Learners</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_learners; ?> Learners</h5>
                        <p class="card-text">Monitor learner progress.</p>
                    </div>
                </div>
            </div>
        </div>

        <h4>📚 System Statistics</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Total Courses</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_courses; ?> Courses</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Total Lessons</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_lessons; ?> Lessons</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header">Total Quizzes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_quizzes; ?> Quizzes</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
