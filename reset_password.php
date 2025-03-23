<?php
include 'dbconnect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

    if (empty($email)) {
        $message = "Please enter your email.";
        $alert_class = "alert-danger";
    } else {
        // Check if the user exists
        $stmt = $conn->prepare("SELECT user_id FROM user_table WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            // Generate a random password if not provided
            if (empty($new_password)) {
                $new_password = substr(md5(uniqid(rand(), true)), 0, 8);
            }

            // Update password
            $stmt = $conn->prepare("UPDATE user_table SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_password, $user_id);
            if ($stmt->execute()) {
                $message = "Password reset successful. Your new password: <b>$new_password</b>";
                $alert_class = "alert-success";
            } else {
                $message = "Error updating password.";
                $alert_class = "alert-danger";
            }
            $stmt->close();
        } else {
            $message = "Email not found.";
            $alert_class = "alert-warning";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h4 class="text-center mb-3">Reset Password</h4>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $alert_class; ?> text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Enter your email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password (Leave blank for auto-generated)</label>
                <input type="text" class="form-control" name="new_password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
