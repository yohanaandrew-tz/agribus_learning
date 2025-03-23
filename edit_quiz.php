<?php
include 'dbconnect.php'; // Include database connection

if (isset($_GET['quizze_id'])) {
    $quizze_id = $_GET['quizze_id'];

    // Fetch quiz details
    $stmt = $conn->prepare("SELECT question, option_A, option_B, option_C, option_D, correct_answer FROM quizze_table WHERE quizze_id = ?");
    $stmt->bind_param("i", $quizze_id);
    $stmt->execute();
    $stmt->bind_result($question, $option_A, $option_B, $option_C, $option_D, $correct_answer);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Invalid request");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_question = $_POST['question'];
    $new_option_A = $_POST['option_A'];
    $new_option_B = $_POST['option_B'];
    $new_option_C = $_POST['option_C'];
    $new_option_D = $_POST['option_D'];
    $new_correct_answer = $_POST['correct_answer'];

    // Update quiz
    $stmt = $conn->prepare("UPDATE quizze_table SET question = ?, option_A = ?, option_B = ?, option_C = ?, option_D = ?, correct_answer = ? WHERE quizze_id = ?");
    $stmt->bind_param("ssssssi", $new_question, $new_option_A, $new_option_B, $new_option_C, $new_option_D, $new_correct_answer, $quizze_id);

    if ($stmt->execute()) {
        $message = "Quiz updated successfully!";
        $alert_class = "alert-success";
    } else {
        $message = "Error updating quiz.";
        $alert_class = "alert-danger";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Quiz</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="text-center mb-3">Edit Quiz</h4>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $alert_class; ?> text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Question</label>
                <textarea class="form-control" name="question" required><?php echo htmlspecialchars($question); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Option A</label>
                <input type="text" class="form-control" name="option_A" value="<?php echo htmlspecialchars($option_A); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Option B</label>
                <input type="text" class="form-control" name="option_B" value="<?php echo htmlspecialchars($option_B); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Option C</label>
                <input type="text" class="form-control" name="option_C" value="<?php echo htmlspecialchars($option_C); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Option D</label>
                <input type="text" class="form-control" name="option_D" value="<?php echo htmlspecialchars($option_D); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correct Answer</label>
                <select class="form-select" name="correct_answer" required>
                    <option value="A" <?php echo ($correct_answer == 'A') ? 'selected' : ''; ?>>A</option>
                    <option value="B" <?php echo ($correct_answer == 'B') ? 'selected' : ''; ?>>B</option>
                    <option value="C" <?php echo ($correct_answer == 'C') ? 'selected' : ''; ?>>C</option>
                    <option value="D" <?php echo ($correct_answer == 'D') ? 'selected' : ''; ?>>D</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Quiz</button>
        </form>
    </div>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
