<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch quiz history for the logged-in user
$sql = "SELECT q.quizze_id, q.question, q.option_A, q.option_B, q.option_C, q.option_D, q.correct_answer, 
               l.lesson_title, c.course_title 
        FROM Quizze_Table q
        INNER JOIN Lesson_Table l ON q.lesson_id = l.lesson_id
        INNER JOIN Course_Table c ON l.course_id = c.course_id
        INNER JOIN Learning_Table lt ON lt.course_id = c.course_id
        WHERE lt.user_id = '$user_id'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz History</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Quiz History</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Lesson</th>
                <th>Question</th>
                <th>Options</th>
                <th>Correct Answer</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['course_title']; ?></td>
                    <td><?= $row['lesson_title']; ?></td>
                    <td><?= $row['question']; ?></td>
                    <td><?= "A) " . $row['option_A'] . "<br>B) " . $row['option_B'] . "<br>C) " . $row['option_C'] . "<br>D) " . $row['option_D']; ?></td>
                    <td><?= $row['correct_answer']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
