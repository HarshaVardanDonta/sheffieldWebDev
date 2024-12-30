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

// Fetch services created by the logged-in worker
$worker_id = $_SESSION["worker_id"];
$sql = "SELECT * FROM services WHERE worker_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $worker_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Prepare failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Services</title>
    <link rel="stylesheet" href="../css/workerDash.css">
</head>

<body>
    <header>
        My Services <a class="btn" href="sign-out.php">
            <?php echo htmlspecialchars($_SESSION["worker_username"]); ?> Sign Out</a>
    </header>
    <div class="container">
        <h2>Services Created by You</h2>
        <table>
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Service Price</th>
                <th>Service Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['approved'] ? 'Approved' : 'Pending'; ?></td>
                        <td>
                            <?php if (!$row['approved']): ?>
                                <form action="edit_service.php" method="GET">
                                    <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                                    <button class="btn" type="submit">Edit</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No services found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>