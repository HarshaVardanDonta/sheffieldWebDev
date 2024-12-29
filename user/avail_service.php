<?php
session_start();

// Check if user is logged in, if not redirect to sign-in page
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

// Include database connection
require_once '../config.php';

// Check if the connection is established
if ($conn === null) {
    die("Database connection failed.");
}

// Check if service_id is set in POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
    $user_id = $_SESSION['user_id'];

    // Insert the availed service into the bookings table with status 0 (pending)
    $sql = "INSERT INTO bookings (user_id, service_id, booking_date, status) VALUES (?, ?, NOW(), 0)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $user_id, $service_id);
        if ($stmt->execute()) {
            $success_message = "Service availed successfully and is pending approval.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Prepare failed: " . $conn->error;
    }

    // Redirect to the dashboard page
    header("Location: dashboard.php");
    exit();
} else {
    // Redirect to the dashboard page if service_id is not set
    header("Location: dashboard.php");
    exit();
}
?>