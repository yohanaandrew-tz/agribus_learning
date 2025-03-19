<?php
require_once "dbconnect.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $surname = mysqli_real_escape_string($conn, $_POST["surname"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $tel = mysqli_real_escape_string($conn, $_POST["tel"]);
    $role = mysqli_real_escape_string($conn, $_POST["role"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]); // Store as plain text

    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT * FROM User_Table WHERE user_email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $error = "Email already registered!";
    } else {
        // Insert user into database
        $query = "INSERT INTO User_Table (name, surname, user_email, user_tel, user_role, password) 
                  VALUES ('$name', '$surname', '$email', '$tel', '$role', '$password')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Account created successfully! <a href='login.php'>Login here</a>";
        } else {
            $error = "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h4>Sign Up</h4>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success; ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">First Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" name="surname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="tel" class="form-label">Phone Number</label>
                            <input type="text" name="tel" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="Learner">Learner</option>
                                <option value="Instructor">Instructor</option>
                                <option value="Admin">Admin</option>
                                <option value="Supplier">Supplier</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>

                    <div class="text-center mt-3">
                        Already have an account? <a href="signin.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
