<?php
session_start();

// Check if the worker is logged in, if not then redirect to sign-in page
if (!isset($_SESSION["worker_logged_in"]) || $_SESSION["worker_logged_in"] !== true) {
    header("location: sign-in.php");
    exit;
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

    // Update the status of the booking to 1 (approved)
    $sql = "UPDATE bookings SET status = 1 WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $booking_id);
        if ($stmt->execute()) {
            $success_message = "Booking approved successfully.";
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