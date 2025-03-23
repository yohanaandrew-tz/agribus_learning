<?php
include 'dbconnect.php'; // Database connection

// Fetch all users
$sql = "SELECT user_id, name, surname, user_email, user_tel, user_role, reg_date FROM user_table";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Users</title>
    <link href="bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="text-center mb-3">User List</h4>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name'] . " " . $row['surname']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_tel']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_role']); ?></td>
                            <td><?php echo date("d M Y", strtotime($row['reg_date'])); ?></td>
                            <td>
                                <a href="edit_user.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_user.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<script src="bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
