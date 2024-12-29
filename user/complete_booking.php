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

// Check if booking_id is set in POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];

    // Update the status of the booking to 2 (completed)
    $sql = "UPDATE bookings SET status = 2 WHERE id = ? AND user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $booking_id, $user_id);
        if ($stmt->execute()) {
            $success_message = "Booking marked as completed.";
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
    // Redirect to the dashboard page if booking_id is not set
    header("Location: dashboard.php");
    exit();
}
?>