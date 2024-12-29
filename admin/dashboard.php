<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: sign-in.php");
    exit;
}

// Include database connection
require_once '../config.php';

// Fetch services created by workers
$sql = "SELECT * FROM services WHERE approved = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <h1>Admin Dashboard</h1>
    <a href="./sign-out.php">Sign Out</a>

    <h2>Pending Services to be approved</h2>
    <table>
        <tr>
            <th>Service ID</th>
            <th>Service Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['approved'] == 0 ? 'Pending' : 'Approved'; ?></td>
                    <td>
                        <form action="approve_service.php" method="POST">
                            <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No pending services.</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php $conn->close(); ?>
</body>

</html>