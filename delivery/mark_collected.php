<?php
include '../connection.php';

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderId'])) {
    $orderId = intval($_POST['orderId']); // Convert orderId to integer to prevent SQL injection

    // Check if orderId is valid
    if ($orderId <= 0) {
        echo "Invalid Order ID.";
        exit();
    }

    // Check if the order is already marked as 'collected'
    $checkQuery = "SELECT status FROM food_donations WHERE Fid = ?";
    $stmt = mysqli_prepare($connection, $checkQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $status);
        mysqli_stmt_fetch($stmt);

        if ($status === 'collected') {
            echo "This order has already been marked as collected.";
            mysqli_stmt_close($stmt);
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error checking order status: " . mysqli_error($connection);
        exit();
    }

    // Prepare the query to mark the order as collected
    $query = "UPDATE food_donations SET status = 'collected' WHERE Fid = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Order successfully marked as collected.";  // Successfully marked as collected
        } else {
            echo "Error executing query: " . mysqli_error($connection);  // Database error
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing query: " . mysqli_error($connection);  // Query preparation error
    }
} else {
    echo "Invalid request. Order ID is missing.";
}
?>
