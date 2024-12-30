<?php
session_start();
// Check if user is logged in, if not redirect to sign-in page
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

include_once '../config.php';

// Fetch approved services
$query = "SELECT services.*, workers.username AS worker_username FROM services JOIN workers ON services.worker_id = workers.id WHERE approved = 1";
$result = mysqli_query($conn, $query);

// Fetch user bookings
$user_id = $_SESSION['user_id'];
$bookings_query = "SELECT bookings.*, services.name AS service_name, workers.username AS worker_username FROM bookings JOIN services ON bookings.service_id = services.id JOIN workers ON services.worker_id = workers.id WHERE bookings.user_id = ? AND bookings.status IN (0, 1)";
$bookings_stmt = $conn->prepare($bookings_query);
$bookings_stmt->bind_param("i", $user_id);
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();

// Fetch previous bookings (completed)
$previous_bookings_query = "SELECT bookings.*, services.name AS service_name, workers.username AS worker_username, bookings.rating, bookings.review FROM bookings JOIN services ON bookings.service_id = services.id JOIN workers ON services.worker_id = workers.id WHERE bookings.user_id = ? AND bookings.status = 2";
$previous_bookings_stmt = $conn->prepare($previous_bookings_query);
$previous_bookings_stmt->bind_param("i", $user_id);
$previous_bookings_stmt->execute();
$previous_bookings_result = $previous_bookings_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/userDash.css">
    <title>User Dashboard</title>
</head>

<body>
    <header>
        User Dashboard
        <a class="btn" href="sign-out.php">Sign Out</a>
    </header>
    <div class="container">
        <main>
            <h2>List of available services</h2>
            <table>
                <thead>
                    <tr>
                        <th>Service ID</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['price']; ?></td>
                                <td><?php echo $row['worker_username']; ?></td>
                                <td>
                                    <form action="avail_service.php" method="POST">
                                        <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                                        <button class="btn" type="submit">Avail Service</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No approved services found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h2>Active Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Service Name</th>
                        <th>Worker</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings_result->num_rows > 0): ?>
                        <?php while ($row = $bookings_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['service_name']; ?></td>
                                <td><?php echo $row['worker_username']; ?></td>
                                <td><?php echo $row['booking_date']; ?></td>
                                <td><?php echo $row['status'] == 1 ? 'Accepted by worker' : ($row['status'] == 2 ? 'Completed' : 'Pending'); ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] == 1): ?>
                                        <form action="complete_booking.php" method="POST">
                                            <div class="reviewBox">
                                                <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                                <label for="rating">Rating:</label>
                                                <select name="rating" required>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                                <label for="review">Review:</label>
                                                <textarea name="review" placeholder="Optional review"></textarea>
                                            </div>

                                            <button class="btn" type="submit">Mark as Completed</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h2>Previous Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Service Name</th>
                        <th>Worker</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Rating</th>
                        <th>Review</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($previous_bookings_result->num_rows > 0): ?>
                        <?php while ($row = $previous_bookings_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['service_name']; ?></td>
                                <td><?php echo $row['worker_username']; ?></td>
                                <td><?php echo $row['booking_date']; ?></td>
                                <td>Completed</td>
                                <td><?php echo $row['rating']; ?></td>
                                <td><?php echo $row['review']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No previous bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>

</body>

</html>