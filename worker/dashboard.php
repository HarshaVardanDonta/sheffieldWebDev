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

// Handle form submission to create a new service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_service'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $worker_id = $_SESSION["worker_id"];

    $sql = "INSERT INTO services (name, description, price, approved, worker_id) VALUES (?, ?, ?, 0, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssdi", $name, $description, $price, $worker_id);
        if ($stmt->execute()) {
            $success_message = "Service created successfully and is pending approval.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Prepare failed: " . $conn->error;
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: dashboard.php");
    exit;
}

// Fetch services created by the logged-in worker
$worker_id = $_SESSION["worker_id"];
$sql = "SELECT services.*, workers.username AS worker_username FROM services JOIN workers ON services.worker_id = workers.id WHERE services.worker_id = ? AND approved = 0";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $worker_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Prepare failed: " . $conn->error);
}

// Fetch pending bookings for the worker's services
$worker_id = $_SESSION["worker_id"];
$booking_sql = "SELECT bookings.*, users.username AS user_username, services.name AS service_name FROM bookings JOIN users ON bookings.user_id = users.id JOIN services ON bookings.service_id = services.id WHERE services.worker_id = ? AND bookings.status = 0";
if ($booking_stmt = $conn->prepare($booking_sql)) {
    $booking_stmt->bind_param("i", $worker_id);
    $booking_stmt->execute();
    $booking_result = $booking_stmt->get_result();
} else {
    die("Prepare failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard</title>
    <link rel="stylesheet" href="../css/workerDash.css">
</head>

<body>
    <header>
        Welcome to the Worker Dashboard <a class="btn" href="sign-out.php">Sign Out</a>
    </header>
    <div class="container">

        <!-- <p>Hello, <?php echo htmlspecialchars($_SESSION["worker_username"]); ?>!</p> -->


        <h2>Create a New Service</h2>
        <?php
        if (!empty($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        }
        if (!empty($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Service Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn" name="create_service">Create Service</button>
            </div>
        </form>

        <h2>Pending Services to be approved by admin</h2>
        <table>
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Service Price</th>
                <th>Service Description</th>
                <th>Created By</th>
                <th>Status</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['worker_username']; ?></td>
                        <td><?php echo $row['approved']; ?> </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No pending services.</td>
                </tr>
            <?php endif; ?>
        </table>

        <h2>Pending Bookings by users</h2>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Service ID</th>
                <th>Booked By</th>
                <th>Action</th>
            </tr>
            <?php if ($booking_result->num_rows > 0): ?>
                <?php while ($row = $booking_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['service_id']; ?></td>
                        <td><?php echo $row['user_username']; ?></td>
                        <td>
                            <form action="approve_booking.php" method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                <button clas type="submit">Accept</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No pending bookings.</td>
                </tr>
            <?php endif; ?>
        </table>

        <?php $stmt->close(); ?>
        <?php $booking_stmt->close(); ?>
        <?php $conn->close(); ?>

    </div>

</body>

</html>