<?php
session_start();

// Check if user is logged in, if not redirect to sign-in page
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

include_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id']) && isset($_POST['rating'])) {
    $booking_id = $_POST['booking_id'];
    $rating = $_POST['rating'];
    $review = isset($_POST['review']) ? $_POST['review'] : '';

    // Update booking status to completed and record rating and review
    $sql = "UPDATE bookings SET status = 2, rating = ?, review = ? WHERE id = ? AND user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isii", $rating, $review, $booking_id, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Booking marked as completed.";
        } else {
            $_SESSION['error_message'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Prepare failed: " . $conn->error;
    }

    // Redirect to the user dashboard
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: dashboard.php");
    exit();
}
?>