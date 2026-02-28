<?php
session_start();
include("db_connection.php"); // Make sure this path is correct

// Redirect if already logged in
if (isset($_SESSION['staff_id'])) {
    header("Location: staff_dashboard.php");
    exit();
}

$error = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare query to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, name, password FROM staff WHERE email = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $staff = $result->fetch_assoc();

            // ✅ Use password_verify to check hashed password
            if (password_verify($password, $staff['password'])) {
                $_SESSION['staff_id'] = $staff['id'];
                $_SESSION['staff_name'] = $staff['name'];

                header("Location: staff_dashboard.php");
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "No staff account found with this email!";
        }

        $stmt->close();
    } else {
        $error = "Database error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Staff Login - Digital E-Gram Panchayat</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-container {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    width: 350px;
}
h2 {
    text-align: center;
    color: #0a4d68;
    margin-bottom: 25px;
}
input[type="email"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0 20px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}
button {
    width: 100%;
    padding: 12px;
    background: #0a4d68;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}
button:hover {
    background: #088395;
}
.error {
    color: red;
    text-align: center;
    margin-bottom: 15px;
}
.back-home {
    display: block;
    text-align: center;
    margin-top: 15px;
    text-decoration: none;
    color: #0a4d68;
}
.back-home:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="login-container">
<h2>Staff Login</h2>

<?php if($error != "") { echo "<div class='error'>$error</div>"; } ?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<a class="back-home" href="../index.php">← Back to Home</a>
</div>

</body>
</html>
