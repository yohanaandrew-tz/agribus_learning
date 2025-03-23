<?php
include 'dbconnect.php'; // Include your database connection file

if (isset($_GET['lesson_id'])) {
    $lesson_id = intval($_GET['lesson_id']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete the lesson
        $stmt = $conn->prepare("DELETE FROM lesson_table WHERE lesson_id = ?");
        $stmt->bind_param("i", $lesson_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo "Lesson deleted successfully.";
    } catch (Exception $e) {
        // Rollback if any error occurs
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "Invalid request. No lesson ID provided.";
}
?>
