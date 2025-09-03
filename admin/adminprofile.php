<?php
session_start();
include("connect.php"); 

// Redirect to signin.php if session name is not set
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    header("location:signin.php");
    exit(); // Ensure no further code is executed
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <!-- <img src="images/logo.png" alt="Logo"> -->
            </div>
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Dashboard</span>
                </a></li>
                <li><a href="analytics.php">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Analytics</span>
                </a></li>
                <li><a href="donate.php">
                    <i class="uil uil-heart"></i>
                    <span class="link-name">Donations</span>
                </a></li>
                <li><a href="feedback.php">
                    <i class="uil uil-comments"></i>
                    <span class="link-name">Feedbacks</span>
                </a></li>
                <li><a href="#">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Profile</span>
                </a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a></li>
                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                        <span class="link-name">Dark Mode</span>
                    </a>
                    <div class="mode-toggle">
                        <span class="switch"></span>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Your <b style="color: #06C167;">History</b></p>
        </div>
        <div class="activity">
            <div class="table-container">
                <!-- Dry Waste Section -->
                <h2>Dry Waste Donations</h2>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Waste</th>
                                <th>Category</th>
                                <th>Phone No.</th>
                                <th>Date/Time</th>
                                <th>Address</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $id = $_SESSION['Aid']; // Use the correct session variable for admin ID
                            $sql_dry = "SELECT * FROM food_donations WHERE assigned_to = $id AND category = 'dry-waste'";

                            $result_dry = mysqli_query($connection, $sql_dry);

                            if ($result_dry && mysqli_num_rows($result_dry) > 0) {
                                while ($row = mysqli_fetch_assoc($result_dry)) {
                                    echo "<tr>
                                        <td data-label='Name'>{$row['name']}</td>
                                        <td data-label='Waste'>{$row['waste']}</td>
                                        <td data-label='Category'>{$row['category']}</td>
                                        <td data-label='Phone No.'>{$row['phoneno']}</td>
                                        <td data-label='Date/Time'>{$row['date']}</td>
                                        <td data-label='Address'>{$row['address']}</td>
                                        <td data-label='Quantity'>{$row['quantity']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No Dry Waste data found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Wet Waste Section -->
                <h2>Wet Waste Donations</h2>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Waste</th>
                                <th>Category</th>
                                <th>Phone No.</th>
                                <th>Date/Time</th>
                                <th>Address</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_wet = "SELECT * FROM food_donations WHERE assigned_to = $id AND category = 'wet-waste'";

                            $result_wet = mysqli_query($connection, $sql_wet);

                            if ($result_wet && mysqli_num_rows($result_wet) > 0) {
                                while ($row = mysqli_fetch_assoc($result_wet)) {
                                    echo "<tr>
                                        <td data-label='Name'>{$row['name']}</td>
                                        <td data-label='Waste'>{$row['waste']}</td>
                                        <td data-label='Category'>{$row['category']}</td>
                                        <td data-label='Phone No.'>{$row['phoneno']}</td>
                                        <td data-label='Date/Time'>{$row['date']}</td>
                                        <td data-label='Address'>{$row['address']}</td>
                                        <td data-label='Quantity'>{$row['quantity']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No Wet Waste data found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- E-Waste Section -->
                <h2>E-Waste Donations</h2>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Waste</th>
                                <th>Category</th>
                                <th>Phone No.</th>
                                <th>Date/Time</th>
                                <th>Address</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_ewaste = "SELECT * FROM food_donations WHERE assigned_to = $id AND category = 'E-Waste'";

                            $result_ewaste = mysqli_query($connection, $sql_ewaste);

                            if ($result_ewaste && mysqli_num_rows($result_ewaste) > 0) {
                                while ($row = mysqli_fetch_assoc($result_ewaste)) {
                                    echo "<tr>
                                        <td data-label='Name'>{$row['name']}</td>
                                        <td data-label='Waste'>{$row['waste']}</td>
                                        <td data-label='Category'>{$row['category']}</td>
                                        <td data-label='Phone No.'>{$row['phoneno']}</td>
                                        <td data-label='Date/Time'>{$row['date']}</td>
                                        <td data-label='Address'>{$row['address']}</td>
                                        <td data-label='Quantity'>{$row['quantity']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No E-Waste data found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script src="admin.js"></script>
</body>
</html>
