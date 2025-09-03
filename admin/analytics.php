<?php
// Start output buffering and session
ob_start();
session_start();

// Database connection settings
$host = "localhost"; // Hostname
$port = "3306"; // Change this if MySQL uses a different port (e.g., 3307)
$username = "root"; // Default username
$password = ""; // Default password for XAMPP
$database = "demo"; // Your database name

// Establish connection to MySQL
$connection = mysqli_connect($host, $username, $password, $database, $port);

// Check connection and handle errors
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Verify session and redirect if not logged in
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    header("Location: signin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Admin Dashboard Panel</title> 
</head>
<body>
    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
                <li><a href="#"><i class="uil uil-chart"></i><span class="link-name">Analytics</span></a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i><span class="link-name">Donations</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
            </ul>
        </div>
    </nav>
    <section class="dashboard">
        <div class="top">
            <p class="logo">Waste<b style="color: #06C167;">Collectors</b></p>
        </div>
        <div class="dash-content">
            <div class="overview">
                <div class="title"><i class="uil uil-chart"></i><span class="text">Analytics</span></div>
                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total users</span>
                        <?php
                        // Fetch total users count
                        $query = "SELECT COUNT(*) AS count FROM login";
                        $result = mysqli_query($connection, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<span class=\"number\">" . htmlspecialchars($row['count']) . "</span>";
                        } else {
                            echo "<span class=\"number\">0</span>";
                        }
                        ?>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                        // Fetch feedbacks count
                        $query = "SELECT COUNT(*) AS count FROM user_feedback";
                        $result = mysqli_query($connection, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<span class=\"number\">" . htmlspecialchars($row['count']) . "</span>";
                        } else {
                            echo "<span class=\"number\">0</span>";
                        }
                        ?>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total donations</span>
                        <?php
                        // Fetch total donations count
                        $query = "SELECT COUNT(*) AS count FROM food_donations";
                        $result = mysqli_query($connection, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            echo "<span class=\"number\">" . htmlspecialchars($row['count']) . "</span>";
                        } else {
                            echo "<span class=\"number\">0</span>";
                        }
                        ?>
                    </div>
                </div>
                <!-- Charts Section -->
                <canvas id="genderChart" style="width:100%;max-width:600px"></canvas>
                <canvas id="wasteChart" style="width:100%;max-width:600px"></canvas>
                <script>
                <?php
                // Fetch gender distribution
                $male_query = "SELECT COUNT(*) AS count FROM login WHERE gender='male'";
                $female_query = "SELECT COUNT(*) AS count FROM login WHERE gender='female'";
                $male_result = mysqli_query($connection, $male_query);
                $female_result = mysqli_query($connection, $female_query);
                $male_count = ($male_result) ? mysqli_fetch_assoc($male_result)['count'] : 0;
                $female_count = ($female_result) ? mysqli_fetch_assoc($female_result)['count'] : 0;

                // Fetch waste type distribution
                $dry_waste_query = "SELECT COUNT(*) AS count FROM waste_data WHERE waste_type='dry'";
                $wet_waste_query = "SELECT COUNT(*) AS count FROM waste_data WHERE waste_type='wet'";
                $dry_waste_result = mysqli_query($connection, $dry_waste_query);
                $wet_waste_result = mysqli_query($connection, $wet_waste_query);
                $dry_waste_count = ($dry_waste_result) ? mysqli_fetch_assoc($dry_waste_result)['count'] : 0;
                $wet_waste_count = ($wet_waste_result) ? mysqli_fetch_assoc($wet_waste_result)['count'] : 0;
                ?>
                var genderLabels = ["Male", "Female"];
                var genderData = [<?php echo $male_count; ?>, <?php echo $female_count; ?>];
                var wasteLabels = ["Dry Waste", "Wet Waste"];
                var wasteData = [<?php echo $dry_waste_count; ?>, <?php echo $wet_waste_count; ?>];

                // Chart for gender distribution
                new Chart("genderChart", {
                    type: "bar",
                    data: {
                        labels: genderLabels,
                        datasets: [{
                            backgroundColor: ["#06C167", "blue"],
                            data: genderData
                        }]
                    },
                    options: {
                        legend: { display: false },
                        title: { display: true, text: "User Gender Distribution" }
                    }
                });

                // Chart for waste type distribution
                new Chart("wasteChart", {
                    type: "pie",
                    data: {
                        labels: wasteLabels,
                        datasets: [{
                            backgroundColor: ["#FFD700", "#06C167"],
                            data: wasteData
                        }]
                    },
                    options: {
                        legend: { display: true },
                        title: { display: true, text: "Waste Type Distribution (Dry vs Wet)" }
                    }
                });
                </script>
            </div>
        </div>
    </section>
    <script src="admin.js"></script>
</body>
</html>
