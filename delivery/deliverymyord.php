<?php
session_start();
include '../connection.php';

// Redirect to login if session is not active
if (empty($_SESSION['name'])) {
    header("Location: deliverylogin.php");
    exit();
}

$name = $_SESSION['name'];
$id = $_SESSION['Did'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
    <style>
        .table-container {
            padding: 20px;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
        }
        .table th, .table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #06C167;
            color: white;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .get {
            text-align: center;
            margin: 20px auto;
        }
        .get a {
            background-color: #06C167;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .get a:hover {
            background-color: #04A456;
        }
        .btn-collected {
            background-color: #06C167;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-collected:hover {
            background-color: #04A456;
        }
    </style>
    <script>
        // Function to generate Google Maps directions starting from a fixed location
        function getRoute(finalAddress) {
            const startPoint = "Canara Engineering College, Benjanapadav";
            const directionsLink = `https://www.google.com/maps/dir/?api=1&origin=${encodeURIComponent(startPoint)}&destination=${encodeURIComponent(finalAddress)}&travelmode=driving`;
            window.open(directionsLink, '_blank');
        }

        // Function to mark an order as collected
        function markAsCollected(orderId, button) {
            if (confirm("Are you sure you want to mark this order as collected?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "mark_collected.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert("Order marked as collected!");
                        // Disable the button after marking as collected
                        button.disabled = true;
                        button.textContent = "Collected";
                        button.style.backgroundColor = "grey";
                    } else {
                        alert("Failed to mark the order as collected. Please try again.");
                    }
                };

                xhr.send("orderId=" + orderId);
            }
        }
    </script>
</head>
<body>
<header>
    <div class="logo">Waste <b style="color: #06C167;">Collectors</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="delivery.php">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php" class="active">My Orders</a></li>
        </ul>
    </nav>
</header>

<script>
    const hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function () {
        const navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    };
</script>

<div class="itm" style="text-align: center;">
    <img src="../img/delivery.gif" alt="Delivery Illustration" width="400" height="400">
</div>

<div class="get">
    <p>Order assigned to you</p>
    <a href="delivery.php">Take orders</a>
</div>

<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Phone No.</th>
                <th>Date/Time</th>
                <th>Pickup Address</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Updated query to fetch data
            $query = "
                SELECT fd.Fid, fd.name, fd.phoneno, fd.date, fd.address AS From_address
                FROM food_donations fd
                WHERE fd.delivery_by = ?";
            $stmt = mysqli_prepare($connection, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                // Display data in table rows
                while ($row = mysqli_fetch_assoc($result)) {
                    $pickupAddress = htmlspecialchars($row['From_address']);
                    $orderId = $row['Fid'];

                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['phoneno']) . "</td>
                            <td>" . htmlspecialchars($row['date']) . "</td>
                            <td><a href='javascript:void(0)' onclick='getRoute(\"{$pickupAddress}\")'>{$pickupAddress}</a></td>
                            <td><button class='btn-collected' onclick='markAsCollected(\"{$orderId}\", this)'>Collected</button></td>
                          </tr>";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<tr><td colspan='5'>No orders found or query failed.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
