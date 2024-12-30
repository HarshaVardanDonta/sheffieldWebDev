<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: sign-in.php");
    exit;
}

// Include database connection
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];

    // Update the service to unapprove it
    $sql = "UPDATE services SET approved = 0 WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $service_id);
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Error: Could not execute the query.";
        }
        $stmt->close();
    } else {
        echo "Error: Could not prepare the query.";
    }
}

$conn->close();
?>