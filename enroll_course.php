<?php
session_start();
include "dbconnect.php"; // Database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);

    // Check if user is already enrolled
    $check_query = "SELECT * FROM learning_table WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Enroll user and start with the first lesson
        $enroll_query = "INSERT INTO learning_table (user_id, course_id, progress, status) VALUES (?, ?, 0.00, 'Completed')";
        $stmt = $conn->prepare($enroll_query);
        $stmt->bind_param("ii", $user_id, $course_id);
        if ($stmt->execute()) {
            // Get the first lesson
            $lesson_query = "SELECT lesson_id FROM lesson_table WHERE course_id = ? ORDER BY lesson_id ASC LIMIT 1";
            $stmt = $conn->prepare($lesson_query);
            $stmt->bind_param("i", $course_id);
            $stmt->execute();
            $lesson_result = $stmt->get_result();
            $first_lesson = $lesson_result->fetch_assoc();

            if ($first_lesson) {
                $lesson_id = $first_lesson['lesson_id'];
                header("Location: lesson_view.php?lesson_id=$lesson_id&course_id=$course_id");
                exit();
            }
        }
    } else {
        // User already enrolled, continue from last lesson
        $lesson_query = "SELECT lesson_id FROM lesson_table WHERE course_id = ? ORDER BY lesson_id ASC LIMIT 1";
        $stmt = $conn->prepare($lesson_query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $lesson_result = $stmt->get_result();
        $first_lesson = $lesson_result->fetch_assoc();

        if ($first_lesson) {
            $lesson_id = $first_lesson['lesson_id'];
            header("Location: lesson_view.php?lesson_id=$lesson_id&course_id=$course_id");
            exit();
        }
    }
}
?>
