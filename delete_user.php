<?php
include 'dbconnect.php'; // Include your database connection file

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete user's enrolled courses from learning_table
        $stmt = $conn->prepare("DELETE FROM learning_table WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Delete the user from user_table
        $stmt = $conn->prepare("DELETE FROM user_table WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo "User deleted successfully.";
    } catch (Exception $e) {
        // Rollback if any error occurs
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "Invalid request. No user ID provided.";
}
?>
