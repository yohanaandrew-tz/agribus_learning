<?php
include 'dbconnect.php'; // Database connection

// Fetch all courses
$course_query = mysqli_query($conn, "SELECT * FROM Course_Table ORDER BY course_id ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

    <h2 class="text-center mb-4">List of Courses</h2>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Course Title</th>
                <th>Instructor</th>
                <th>Category</th>
                <th>Lessons</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($course = mysqli_fetch_assoc($course_query)) { ?>
                <tr>
                    <td><?php echo $course['course_title']; ?></td>
                    <td>
                        <?php
                        // Fetch Instructor Name
                        $instructor_id = $course['instructor_id'];
                        $instructor_query = mysqli_query($conn, "SELECT name FROM User_Table WHERE user_id = $instructor_id");
                        $instructor = mysqli_fetch_assoc($instructor_query);
                        echo $instructor ? $instructor['name'] : "Unknown";
                        ?>
                    </td>
                    <td><?php echo $course['course_category']; ?></td>
                    <td>
                        <!-- Bootstrap Collapse for Lessons -->
                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#lessons-<?php echo $course['course_id']; ?>">View Lessons</button>
                        
                        <div class="collapse mt-2" id="lessons-<?php echo $course['course_id']; ?>">
                            <ul class="list-group">
                                <?php
                                // Fetch lessons for this course
                                $course_id = $course['course_id'];
                                $lesson_query = mysqli_query($conn, "SELECT * FROM Lesson_Table WHERE course_id = $course_id ORDER BY lesson_id ASC");
                                if (mysqli_num_rows($lesson_query) > 0) {
                                    while ($lesson = mysqli_fetch_assoc($lesson_query)) {
                                        echo "<li class='list-group-item d-flex justify-content-between'>
                                                {$lesson['lesson_title']} 
                                                <span>
                                                    <a href='edit_lesson.php?lesson_id={$lesson['lesson_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='delete_lesson.php?lesson_id={$lesson['lesson_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                                </span>
                                              </li>";
                                    }
                                } else {
                                    echo "<li class='list-group-item'>No lessons found</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <a href="edit_course.php?course_id=<?php echo $course['course_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_course.php?course_id=<?php echo $course['course_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>

</body>
</html>
