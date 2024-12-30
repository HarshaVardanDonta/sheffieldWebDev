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
$sql_pending = "SELECT * FROM services WHERE approved = 0";
$result_pending = $conn->query($sql_pending);

$sql_approved = "SELECT * FROM services WHERE approved = 1";
$result_approved = $conn->query($sql_approved);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/adminDash.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <header>
        Admin Dashboard
        <a class="btn" href="./sign-out.php">Sign Out</a>
    </header>
    <div class="container">
        <h2>Pending Services to be approved</h2>
        <table>
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Service Description</th>
                <th>Worker Id</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php if ($result_pending->num_rows > 0): ?>
                <?php while ($row = $result_pending->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['worker_id']; ?></td>
                        <td><?php echo $row['approved'] == 0 ? 'Pending' : 'Approved'; ?></td>
                        <td>
                            <form action="approve_service.php" method="POST">
                                <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                                <button class="btn" type="submit">Approve</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No pending services to be approved.</td>
                </tr>
            <?php endif; ?>
        </table>

        <h2>Approved Services</h2>
        <table>
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Service Description</th>
                <th>Worker Id</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php if ($result_approved->num_rows > 0): ?>
                <?php while ($row = $result_approved->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['worker_id']; ?></td>
                        <td><?php echo $row['approved'] == 1 ? 'Approved' : 'Pending'; ?></td>
                        <td>
                            <form action="unapprove_service.php" method="POST">
                                <input type="hidden" name="service_id" value="<?php echo $row['id']; ?>">
                                <button class="btn" type="submit">Unapprove</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No approved services.</td>
                </tr>
            <?php endif; ?>
        </table>

        <?php $conn->close(); ?>

    </div>
</body>

</html>