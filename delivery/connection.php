<?php
$server = "localhost:3307"; // Update if using a different port or server.
$username = "root";
$password = "";
$database = "demo";

// Create a connection
$connection = mysqli_connect($server, $username, $password, $database);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
