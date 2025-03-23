<?php
session_start();
include 'dbconnect.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT name, surname, user_email, user_tel, user_role FROM user_table WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $phone = htmlspecialchars($_POST['phone']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    $update_sql = "UPDATE user_table SET name = ?, surname = ?, user_tel = ?" . ($password ? ", password = ?" : "") . " WHERE user_id = ?";
    $stmt = $conn->prepare($update_sql);

    if ($password) {
        $stmt->bind_param("ssssi", $name, $surname, $phone, $password, $user_id);
    } else {
        $stmt->bind_param("sssi", $name, $surname, $phone, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: my_profile.php");
        exit();
    } else {
        $error = "Error updating profile!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="text-center">My Profile</h4>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Surname</label>
                <input type="text" name="surname" class="form-control" value="<?php echo htmlspecialchars($user['surname']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email (Read-Only)</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['user_email']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['user_tel']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password (Optional)</label>
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>
    </div>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
