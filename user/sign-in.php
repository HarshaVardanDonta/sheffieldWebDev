<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include_once '../config.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styles.css">
    <title>User Sign In</title>
</head>

<body>
    <div class="container">
        <h2>User Sign In</h2>
        <?php if (isset($error)) {
            echo "<p class='error'>$error</p>";
        } ?>
        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Sign In</button>
        </form>
        <p>Don't have an account? <a href="sign-up.php">Sign Up</a></p>
    </div>
</body>

</html>