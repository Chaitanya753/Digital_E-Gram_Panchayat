<?php
session_start();
include("db_connection.php");
$message = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: user_dashboard.php");
            exit();
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .box {
            width: 400px; margin: 80px auto; padding: 25px;
            background: white; box-shadow: 0 0 10px #aaa;
        }
        h2 { text-align: center; color: #0a4d68; }
        input, button {
            width: 100%; padding: 10px; margin: 8px 0;
        }
        button {
            background: #0a4d68; color: white; border: none;
            cursor: pointer;
        }
        button:hover { background: #088395; }
        .msg { text-align: center; color: red; }
        a { text-decoration: none; color: #0a4d68; }
    </style>
</head>
<body>

<div class="box">
    <h2>User Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p class="msg"><?php echo $message; ?></p>
    <p style="text-align:center;">
        New user? <a href="user_registration.php">Register</a>
    </p>
</div>

</body>
</html>
