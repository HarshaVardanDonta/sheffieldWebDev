<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Include database connection
    require_once '../config.php';
    $username = $password = "";

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $sql = "SELECT id, username, password FROM workers WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if username exists, if yes then verify password
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password);
            if ($stmt->fetch()) {
                if (password_verify($password, $hashed_password)) {
                    // Password is correct, so start a new session
                    session_start();

                    // Store data in session variables
                    $_SESSION["worker_logged_in"] = true;
                    $_SESSION["worker_id"] = $id;
                    $_SESSION["worker_username"] = $username;

                    // Redirect user to dashboard
                    header("location: dashboard.php");
                    exit;
                } else {
                    // Display an error message if password is not valid
                    $error = "The password you entered was not valid. $username";
                }
            }
        } else {
            // Display an error message if username doesn't exist
            $error = "No account found with that username. $username";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Sign In</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container">
        <h2>Worker Sign In</h2>
        <?php
        if (!empty($error)) {
            echo '<div class="error">' . $error . '</div>';
        }
        ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Sign In</button>
            </div>
        </form>
        <p>Don't have an account? <a href="sign-up.php">Sign Up</a></p>
    </div>
</body>

</html>