<?php
session_start();
include("dbconnect.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Instructor") {
    header("Location: signin.php");
    exit();
}

$instructor_id = $_SESSION["user_id"];

// Fetch total courses
$query_courses = "SELECT COUNT(*) AS total_courses FROM Course_table WHERE instructor_id = ?";
$stmt = $conn->prepare($query_courses);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result_courses = $stmt->get_result();
$total_courses = $result_courses->fetch_assoc()["total_courses"];

// Fetch total lessons
$query_lessons = "SELECT COUNT(*) AS total_lessons FROM Lesson_table WHERE course_id IN (SELECT course_id FROM Course_table WHERE instructor_id = ?)";
$stmt = $conn->prepare($query_lessons);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result_lessons = $stmt->get_result();
$total_lessons = $result_lessons->fetch_assoc()["total_lessons"];

// Fetch total quizzes
$query_quizzes = "SELECT COUNT(*) AS total_quizzes FROM Quizze_table WHERE course_id IN (SELECT course_id FROM Course_table WHERE instructor_id = ?)";
$stmt = $conn->prepare($query_quizzes);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result_quizzes = $stmt->get_result();
$total_quizzes = $result_quizzes->fetch_assoc()["total_quizzes"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instructor Dashboard | Agribusiness Learning</title>
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
    <h4 class="text-center py-3">Instructor Panel</h4>
    <a href="instructor_dashboard.php">🏠 Dashboard</a>

    <!-- Courses Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">📚 Manage Courses</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="my_course_list.php">My Courses</a></li>
            <li><a class="dropdown-item" href="add_new_course.php">Add New Course</a></li>
        </ul>
    </div>

    <!-- Lessons Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">📖 Manage Lessons</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="lesson_list.php">View Lessons</a></li>
            <li><a class="dropdown-item" href="add_lesson.php">Upload Lesson</a></li>
        </ul>
    </div>

    <!-- Quizzes Dropdown -->
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">📝 Manage Quizzes</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="quiz_list.php">My Quizzes</a></li>
            <li><a class="dropdown-item" href="create_quizze.php">Create Quiz</a></li>
        </ul>
    </div>

    <!-- Reports -->
    <a href="system_reports.php">📊 Course Reports</a>

    <!-- Settings -->
   <!-- <a href="#">⚙️ Settings</a> -->

    
    
    <!-- Profile Dropdown -->
<div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">My Profile</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="my_profile.php">View</a></li>
            <li><a class="dropdown-item" href="signout.php">🚪 Logout</a></li>
        </ul>
    </div>
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
                    <div class="card-header">Total Courses</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_courses; ?> Courses Created</h5>
                        <p class="card-text">Manage your courses effectively.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Total Lessons</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_lessons; ?> Lessons Uploaded</h5>
                        <p class="card-text">Keep updating your content.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Total Quizzes</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_quizzes; ?> Quizzes Created</h5>
                        <p class="card-text">Engage your learners with more quizzes.</p>
                    </div>
                </div>
            </div>
        </div>

        <h4>📚 My Courses</h4>
        <div class="row">
            <?php
            $query_courses_list = "SELECT * FROM Course_table WHERE instructor_id = ?";
            $stmt = $conn->prepare($query_courses_list);
            $stmt->bind_param("i", $instructor_id);
            $stmt->execute();
            $result_courses_list = $stmt->get_result();

            while ($row = $result_courses_list->fetch_assoc()) {
                echo "<div class='col-md-4'>
                        <div class='card mb-3'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$row['course_title']}</h5>
                                <p class='card-text'>{$row['course_description']}</p>
                                <a href='my_course_list.php' class='btn btn-primary'>Manage</a>
                            </div>
                        </div>
                      </div>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
