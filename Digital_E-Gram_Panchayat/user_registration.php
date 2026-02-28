<?php
include("db_connection.php"); // Make sure this file connects to your DB
$message = "";

// Handle registration
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $home_number = mysqli_real_escape_string($conn, $_POST['home_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Email already registered!";
    } else {
        // Insert new user
        $query = "INSERT INTO users (name, home_number, email, password) 
                  VALUES ('$name', '$home_number', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $message = "Registration successful! Please login.";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Registration - Digital E-Gram Panchayat</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family: Arial, sans-serif; background: #f2f2f2; }
.box {
    width: 400px; 
    margin: 80px auto; 
    padding: 25px;
    background: white; 
    box-shadow: 0 0 10px #aaa;
    border-radius: 10px;
}
h2 { text-align: center; color: #0a4d68; }
input, button {
    width: 100%; 
    padding: 10px; 
    margin: 8px 0; 
    border-radius: 5px;
    border: 1px solid #ccc;
}
button {
    background: #0a4d68; 
    color: white; 
    border: none; 
    cursor: pointer;
    font-weight: bold;
}
button:hover { background: #088395; }
.msg { text-align: center; color: red; margin-bottom: 10px; }
a { text-decoration: none; color: #0a4d68; }
a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="box">
    <h2>User Registration</h2>
    <?php if($message != "") { echo "<p class='msg'>$message</p>"; } ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="home_number" placeholder="Home Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>

    <p style="text-align:center; margin-top:10px;">
        Already registered? <a href="user_login.php">Login here</a>
    </p>
</div>

</body>
</html>
