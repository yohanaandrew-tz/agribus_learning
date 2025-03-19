<?php
include 'dbconnect.php';

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    $query = "SELECT lesson_id, lesson_title FROM Lesson_Table WHERE course_id = '$course_id'";
    $result = mysqli_query($conn, $query);

    $lessons = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $lessons[] = $row;
    }

    echo json_encode($lessons);
}
?>
