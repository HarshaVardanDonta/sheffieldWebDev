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

// Get service details
if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    $worker_id = $_SESSION["worker_id"];
    $sql = "SELECT * FROM services WHERE id = ? AND worker_id = ? AND approved = 0";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $service_id, $worker_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $service = $result->fetch_assoc();
        } else {
            die("Service not found or already approved.");
        }
    } else {
        die("Prepare failed: " . $conn->error);
    }
} else {
    die("Invalid request.");
}

// Handle form submission to update the service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_service'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $service_id = $_POST['service_id'];

    $sql = "UPDATE services SET name = ?, description = ?, price = ? WHERE id = ? AND worker_id = ? AND approved = 0";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssdii", $name, $description, $price, $service_id, $worker_id);
        if ($stmt->execute()) {
            $success_message = "Service updated successfully.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Prepare failed: " . $conn->error;
    }

    // Redirect to the my-services page
    header("Location: my-services.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link rel="stylesheet" href="../css/workerDash.css">
</head>

<body>
    <header>
        Edit Service <a class="btn" href="sign-out.php">
            <?php echo htmlspecialchars($_SESSION["worker_username"]); ?> Sign Out</a>
    </header>
    <div class="container">
        <h2>Edit Service</h2>
        <?php
        if (!empty($success_message)) {
            echo '<div class="success">' . $success_message . '</div>';
        }
        if (!empty($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        ?>
        <form method="POST" action="">
            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
            <div class="form-group">
                <label for="name">Service Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $service['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo $service['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" value="<?php echo $service['price']; ?>"
                    required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn" name="update_service">Update Service</button>
            </div>
        </form>
    </div>
</body>

</html>

<?php
$conn->close();
?>