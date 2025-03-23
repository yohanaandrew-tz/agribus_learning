<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in as Admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Admin") {
    header("Location: signin.php");
    exit();
}

// Handle form submission
$success = "";
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $surname = mysqli_real_escape_string($conn, $_POST["surname"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]); // Not hashed as per request
    $role = mysqli_real_escape_string($conn, $_POST["role"]);
    $pro_info = mysqli_real_escape_string($conn, $_POST["pro_info"]);

    // Check if email or phone already exists
    $check_query = "SELECT * FROM User_Table WHERE user_email = '$email' OR user_tel = '$phone'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "Email or phone number already registered!";
    } else {
        // Insert user into database
        $query = "INSERT INTO User_Table (name, surname, user_email, user_tel, user_role, pro_info, password) 
                  VALUES ('$name', '$surname', '$email', '$phone', '$role', '$pro_info', '$password')";

        if (mysqli_query($conn, $query)) {
            $success = "User added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">👤 Add New User</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="mt-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">First Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name:</label>
                <input type="text" name="surname" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password:</label>
                <input type="text" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">User Role:</label>
                <select name="role" class="form-control" required>
                    <!--  <option value="Admin">Admin</option> -->
                    <option value="Instructor">Instructor</option>
                    <option value="Learner">Learner</option>
                    <!-- <option value="Supplier">Supplier</option> -->
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Information:</label>
            <textarea name="pro_info" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success w-100">Add User</button>
    </form>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
