<?php
session_start();
include("db_connection.php");

if (isset($_SESSION['officer_id'])) {
    header("Location: officer_dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, name, password FROM officers WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $officer = $result->fetch_assoc();

        // ✅ PASSWORD HASH CHECK
        
        if (password_verify($password, $officer['password'])) {

            $_SESSION['officer_id'] = $officer['id'];
            $_SESSION['officer_name'] = $officer['name'];

            header("Location: officer_dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }

    } else {
        $error = "Officer not found!";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Officer Login - Digital E-Gram Panchayat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/login bg.jpg');
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #0a4d68;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #0a4d68;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Officer Login</h2>

    <?php if ($error) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
