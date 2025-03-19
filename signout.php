<?php
session_start();
session_destroy(); // Destroy all session data
header("Location: signin.php"); // Redirect to sign-in page
exit();
?>