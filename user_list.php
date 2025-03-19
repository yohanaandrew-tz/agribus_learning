<?php
session_start();
include 'dbconnect.php';

// Check if user is logged in as Admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "Admin") {
    header("Location: signin.php");
    exit();
}

// Fetch users from database
$query = "SELECT * FROM User_Table ORDER BY reg_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User List | Agribusiness Learning</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">📋 User List</h2>

    <!-- Search Bar -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search users...">
        </div>
        <div class="col-md-6 text-end">
            <a href="add_user.php" class="btn btn-success">➕ Add New User</a>
        </div>
    </div>

    <!-- User Table -->
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Registered On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTable">
            <?php $count = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= $row["name"] . " " . $row["surname"]; ?></td>
                    <td><?= $row["user_email"]; ?></td>
                    <td><?= $row["user_tel"]; ?></td>
                    <td><?= $row["user_role"]; ?></td>
                    <td><?= date("d M Y", strtotime($row["reg_date"])); ?></td>
                    <td>
                        <a href="view_user.php?id=<?= $row['user_id']; ?>" class="btn btn-info btn-sm">👁 View</a>
                        <a href="edit_user.php?id=<?= $row['user_id']; ?>" class="btn btn-warning btn-sm">✏ Edit</a>
                        <a href="delete_user.php?id=<?= $row['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">🗑 Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>

<script>
    // JavaScript for Search Functionality
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#userTable tr");

        rows.forEach(row => {
            let name = row.cells[1].textContent.toLowerCase();
            let email = row.cells[2].textContent.toLowerCase();
            let phone = row.cells[3].textContent.toLowerCase();
            let role = row.cells[4].textContent.toLowerCase();

            if (name.includes(filter) || email.includes(filter) || phone.includes(filter) || role.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

</body>
</html>
