<?php
session_start();
if ($_SESSION['name'] == '') {
    header("location: signin.php");
    exit();
}

$emailid = $_SESSION['email'];
$connection = mysqli_connect("localhost", "root", "", "demo");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $foodname = mysqli_real_escape_string($connection, $_POST['foodname']);
    $category = $_POST['image-choice'];
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $phoneno = mysqli_real_escape_string($connection, $_POST['phoneno']);
    $district = mysqli_real_escape_string($connection, $_POST['district']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);

    $query = "INSERT INTO food_donations (email, waste, category, phoneno, location, address, name, quantity) 
              VALUES ('$emailid', '$foodname', '$category', '$phoneno', '$district', '$address', '$name', '$quantity')";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        echo '<script type="text/javascript">alert("Data saved successfully.")</script>';
        header("location:delivery.html"); 
        exit();
    } else {
        echo '<script type="text/javascript">alert("Failed to save data. Please try again.")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body style="background-color: #06C167;">
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Waste<b style="color: #06C167;">Collection</b></p>
                
                <div class="input">
                    <label for="foodname">Specify Waste:</label>
                    <input type="text" id="foodname" name="foodname" required />
                </div>

                <div class="input">
                    <label for="food">Select the Category:</label>
                    <br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="dry-waste" />
                        <label for="raw-food">
                            <img src="img/dry.jpg" alt="dry-waste" />
                        </label>
                        <input type="radio" id="cooked-food" name="image-choice" value="wet-waste" checked />
                        <label for="cooked-food">
                            <img src="img/wetwaste.jpg" alt="wet-waste" />
                        </label>
                        <input type="radio" id="ewaste" name="image-choice" value="E-waste" />
                        <label for="ewaste">
                            <img src="img/ewaste.jpg" alt="E-waste" />
                        </label>
                    </div>
                    <br>
                </div>

                <div class="input">
                    <label for="quantity">Quantity:</label>
                    <input type="text" id="quantity" name="quantity" 
                           required 
                           pattern="^\d+(\.\d+)?(kg|g|liters|ml)$" 
                           title="Enter a valid quantity (e.g., 1kg, 500g, 2.5liters, 750ml)" />
                </div>

                <b><p style="text-align: center;">Contact Details</p></b>
                <div class="input">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required />
                    </div>
                    <div>
                        <label for="phoneno">Phone No:</label>
                        <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" 
                               title="Enter a valid 10-digit phone number" required />
                    </div>
                </div>

                <div class="input">
                    <label for="district">District:</label>
                    <select id="district" name="district" style="padding:10px;">
                        <option value="Dakshina Kannada">Dakshina Kannada</option>
                        <option value="Bangalore Rural">Bangalore Rural</option>
                        <option value="Bangalore Urban">Bangalore Urban</option>
                        <option value="Belagavi">Belagavi</option>
                        <option value="Bellary">Bellary</option>
                        <option value="Chamarajanagar">Chamarajanagar</option>
                        <option value="Dharwad">Dharwad</option>
                        <option value="Hassan">Hassan</option>
                        <option value="Mandya">Mandya</option>
                        <option value="Shimoga">Shimoga</option>
                        <option value="Udupi">Udupi</option>
                    </select>

                    <label for="address" style="padding-left: 10px;">Address:</label>
                    <input type="text" id="address" name="address" required />
                </div>

                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
