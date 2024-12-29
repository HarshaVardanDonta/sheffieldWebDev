<?php
session_start();

// Check if the user is already logged in and redirect accordingly
if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'admin':
            header("Location: admin/dashboard.php");
            exit;
        case 'worker':
            header("Location: worker/dashboard.php");
            exit;
        case 'user':
            header("Location: user/dashboard.php");
            exit;
        default:
            break;
    }
}

// Display the main landing page with options to sign in or sign up
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Service Management System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h1>Welcome to the Service Management System</h1>
    <p>Please choose your role to proceed:</p>
    <ul>
        <li><a href="admin/sign-in.php">Admin Sign In</a></li>
        <li><a href="worker/sign-in.php">Worker Sign In</a></li>
        <li><a href="user/sign-in.php">User Sign In</a></li>
    </ul>
    <h2>New Here?</h2>
    <p>Sign up as:</p>
    <ul>
        <li><a href="admin/sign-up.php">Admin</a></li>
        <li><a href="worker/sign-up.php">Worker</a></li>
        <li><a href="user/sign-up.php">User</a></li>
    </ul>
</body>

</html>