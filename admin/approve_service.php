<?php
session_start();

// Check if the admin is logged in, if not then redirect to sign-in page
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: sign-in.php");
    exit;
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

    // Update the approved status of the service
    $sql = "UPDATE services SET approved = 1 WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $service_id);
        if ($stmt->execute()) {
            $success_message = "Service approved successfully.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Prepare failed: " . $conn->error;
    }

    // Redirect to the dashboard page
    header("Location: dashboard.php");
    exit;
} else {
    // Redirect to the dashboard page if service_id is not set
    header("Location: dashboard.php");
    exit;
}
?>