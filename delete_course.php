<?php
include 'dbconnect.php'; // Include your database connection file

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete from learning_table
        $stmt = $conn->prepare("DELETE FROM learning_table WHERE course_id = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $stmt->close();

        // Delete from lesson_table
        $stmt = $conn->prepare("DELETE FROM lesson_table WHERE course_id = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $stmt->close();

        // Delete from quizze_table
        $stmt = $conn->prepare("DELETE FROM quizze_table WHERE course_id = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $stmt->close();

        // Finally, delete from course_table
        $stmt = $conn->prepare("DELETE FROM course_table WHERE course_id = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo "Course and all related details deleted successfully.";
    } catch (Exception $e) {
        // Rollback if any error occurs
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "Invalid request. No course ID provided.";
}
?>
