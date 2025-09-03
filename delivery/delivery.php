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
$city = $_SESSION['city'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id']; // Sanitize input to prevent SQL injection

    $query = "UPDATE food_donations SET delivery_by = ? WHERE Fid = ? AND delivery_by IS NULL";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $id, $order_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Order assigned successfully!');</script>";
        } else {
            echo "<script>alert('Failed to assign order. It may have been taken already.');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Database query failed. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Orders</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
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
            background-color: #f9f9f9;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }
        .table button {
            background-color: #06C167;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .table button:hover {
            background-color: #04A456;
        }
        .no-orders {
            text-align: center;
            font-size: 1.2em;
            color: #666;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">Waste <b style="color: #06C167;">Collector</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="delivery.php" class="active">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php">My Orders</a></li>
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

<div class="itm" style="text-align: center; margin: 20px;">
    <img src="../img/delivery.gif" alt="Delivery Illustration" width="400" height="400">
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
            if (!empty($city)) {
                $query = "
                    SELECT fd.Fid, fd.name, fd.phoneno, fd.date, fd.address AS From_address
                    FROM food_donations fd
                    WHERE fd.delivery_by IS NULL AND fd.location = ?";
                $stmt = mysqli_prepare($connection, $query);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "s", $city);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $from_address = htmlspecialchars($row['From_address']);
                            $name = htmlspecialchars($row['name']);
                            $phoneno = htmlspecialchars($row['phoneno']);
                            $date = htmlspecialchars($row['date']);

                            echo "<tr>
                                    <td>{$name}</td>
                                    <td>{$phoneno}</td>
                                    <td>{$date}</td>
                                    <td>{$from_address}</td>
                                    <td>
                                        <form method='post'>
                                            <input type='hidden' name='order_id' value='{$row['Fid']}'>
                                            <button type='submit'>Take Order</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='no-orders'>No orders available in your city.</td></tr>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<tr><td colspan='5' class='no-orders'>Failed to fetch orders. Please try again later.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='no-orders'>City information is missing. Please contact admin.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
