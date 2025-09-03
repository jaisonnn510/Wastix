<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the user is logged in
if ($_SESSION['name'] == '') {
    header("location: signup.php");
    exit();
}

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "demo"); // Replace with your actual DB credentials

// Check for connection error
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Profile</title>
</head>
<body>
<header>
    <div class="logo">Waste <b style="color: #06C167;">Collection</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
        </ul>
    </nav>
</header>
<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function () {
        navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<div class="profile">
    <div class="profilebox">
        <p class="headingline" style="text-align: left; font-size: 30px;">
            <img src="" alt="" style="width: 40px; height: 25px; padding-right: 10px; position: relative;">Profile
        </p>
        <br>
        <div class="info" style="padding-left: 10px;">
            <p>Name: <?php echo $_SESSION['name']; ?></p><br>
            <p>Email: <?php echo $_SESSION['email']; ?></p><br>
            <p>Gender: <?php echo $_SESSION['gender']; ?></p><br>
            <a href="logout.php" style="float: left; margin-top: 6px; border-radius: 5px; background-color: #06C167; color: white; padding-left: 10px; padding-right: 10px;">
                Logout
            </a>
        </div>
        <br><br>

        <hr>
        <br>
        <p class="heading">Your Requests</p>
        <div class="table-container">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Waste</th>
                            <th>Category</th>
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch user-specific donation data from the database
                        $email = $_SESSION['email'];
                        $query = "SELECT waste, category, date FROM food_donations WHERE email='$email'";
                        $result = mysqli_query($connection, $query);

                        // Display the results in the table
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr><td>" . $row['waste'] . "</td><td>" . $row['category'] . "</td><td>" . $row['date'] . "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No data found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Close the database connection
mysqli_close($connection);
?>

</body>
</html>
