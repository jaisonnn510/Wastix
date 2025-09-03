<?php
ob_start(); 

// Database connection
$connection = mysqli_connect("localhost", "root", "", "demo");  // Adjust host and credentials if needed

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session and validate
session_start();
if (!isset($_SESSION['name']) || !isset($_SESSION['location']) || !isset($_SESSION['Aid'])) {
    header("location:signin.php");
    exit;
}

$name = $_SESSION['name'];
$loc = $_SESSION['location'];
$aid = $_SESSION['Aid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Admin Dashboard Panel</title> 
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image"></div>
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
                <li><a href="analytics.php"><i class="uil uil-chart"></i><span class="link-name">Analytics</span></a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i><span class="link-name">Collections</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
                <li class="mode">
                    <a href="#"><i class="uil uil-moon"></i><span class="link-name">Dark Mode</span></a>
                    <div class="mode-toggle"><span class="switch"></span></div>
                </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Waste <b style="color: #06C167;">Collector</b></p>
        </div>
        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>
                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total Users</span>
                        <?php
                        $query = "SELECT COUNT(*) as count FROM login";
                        $result = mysqli_query($connection, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<span class=\"number\">" . $row['count'] . "</span>";
                        } else {
                            echo "<span class=\"number\">0</span>";
                        }
                        ?>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                        $query = "SELECT COUNT(*) as count FROM user_feedback";
                        $result = mysqli_query($connection, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<span class=\"number\">" . $row['count'] . "</span>";
                        } else {
                            echo "<span class=\"number\">0</span>";
                        }
                        ?>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total Collections</span>
                        <?php
                        $query = "SELECT COUNT(*) as count FROM food_donations";
                        $result = mysqli_query($connection, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<span class=\"number\">" . $row['count'] . "</span>";
                        } else {
                            echo "<span class=\"number\">0</span>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Recent Donations</span>
                </div>
                <div class="get">
                    <?php
                    $stmt = $connection->prepare("SELECT * FROM food_donations WHERE assigned_to IS NULL AND location=?");
                    $stmt->bind_param("s", $loc);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    ?>
                    <div class="table-container">
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Food</th>
                                        <th>Category</th>
                                        <th>Phone No</th>
                                        <th>Date/Time</th>
                                        <th>Address</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['waste']) ?></td>
                                        <td><?= htmlspecialchars($row['category']) ?></td>
                                        <td><?= htmlspecialchars($row['phoneno']) ?></td>
                                        <td><?= htmlspecialchars($row['date']) ?></td>
                                        <td><?= htmlspecialchars($row['address']) ?></td>
                                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['Fid']) ?>">
                                                <input type="hidden" name="delivery_person_id" value="<?= htmlspecialchars($aid) ?>">
                                                <button type="submit" name="assign">Get Waste</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    if (isset($_POST['assign']) && isset($_POST['order_id'])) {
                        $order_id = $_POST['order_id'];
                        $delivery_person_id = $_POST['delivery_person_id'];

                        $stmt = $connection->prepare("UPDATE food_donations SET assigned_to=? WHERE Fid=? AND assigned_to IS NULL");
                        $stmt->bind_param("ii", $delivery_person_id, $order_id);
                        if ($stmt->execute()) {
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit;
                        } else {
                            echo "<p>Error assigning waste: " . $stmt->error . "</p>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <script src="admin.js"></script>
</body>
</html>
