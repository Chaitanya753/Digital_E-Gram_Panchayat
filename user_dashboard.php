<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? "User";

$success_msg = "";
$error_msg   = "";

/* APPLY FOR SERVICE */
if (isset($_POST['apply_service'])) {
    $service_id  = intval($_POST['service_id']);
    $home_number = mysqli_real_escape_string($conn, $_POST['home_number']);

    if (mysqli_query($conn,"
        INSERT INTO applications (user_id, service_id, home_number, status)
        VALUES ($user_id,$service_id,'$home_number','Pending')
    ")) {
        $success_msg = "Application submitted successfully!";
    } else {
        $error_msg = "Error submitting application!";
    }
}

/* COUNTS */
$total_services = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM services")
)['total'];

$total_apps = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) total FROM applications WHERE user_id=$user_id")
)['total'];

/* DATA */
$services = mysqli_query($conn,"SELECT * FROM services ORDER BY id DESC");

$applications = mysqli_query($conn,"
    SELECT a.*, s.service_name, d.document_file
    FROM applications a
    JOIN services s ON s.id=a.service_id
    LEFT JOIN application_documents d ON d.application_id=a.id
    WHERE a.user_id=$user_id
    ORDER BY a.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI;}
body{background:#f1f5f9;}

.sidebar{
    position:fixed;
    width:220px;
    height:100vh;
    background:#020617;
    padding:20px;
}
.sidebar h2{color:white;text-align:center;margin-bottom:25px;}
.sidebar button{
    width:100%;
    background:none;
    border:none;
    color:#c7d2fe;
    padding:12px;
    text-align:left;
    cursor:pointer;
    border-radius:6px;
}
.sidebar button:hover{background:#2563eb;color:white;}
.logout{background:#dc2626!important;color:white!important;}

.main{
    margin-left:220px;
    padding:20px;
}

.header{
    background:white;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
    display:flex;
    justify-content:space-between;
}

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}
.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
}
.card h3{color:#475569;}
.card p{font-size:32px;color:#2563eb;margin-top:10px;}

.section{display:none;}

table{
    width:100%;
    background:white;
    border-collapse:collapse;
    border-radius:10px;
    overflow:hidden;
}
th{background:#2563eb;
   color:white;
   padding:10px;}

td{padding:45px;
   border-bottom:1px solid #e5e7eb;}

input,select,button{
    padding:8px;
    width:100%;
    margin:5px 0;
}
button.submit{
    background:#2563eb;
    color:white;
    border:none;
    border-radius:6px;
}

.success{color:green;margin-bottom:10px;}
.error{color:red;margin-bottom:10px;}
</style>

<script>
function showSection(id){
    document.querySelectorAll('.section').forEach(s=>s.style.display='none');
    document.getElementById(id).style.display='block';
}
</script>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>User</h2>
    <button onclick="showSection('dashboard')">Dashboard</button>
    <button onclick="showSection('services')">Services</button>
    <button onclick="showSection('apply')">Apply Service</button>
    <button onclick="showSection('applications')">My Applications</button>
    <a href="logout.php"><button class="logout">Logout</button></a>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">
    <h3>Welcome, <?= htmlspecialchars($user_name); ?></h3>
</div>

<?php if($success_msg) echo "<div class='success'>$success_msg</div>"; ?>
<?php if($error_msg) echo "<div class='error'>$error_msg</div>"; ?>

<!-- DASHBOARD -->
<div id="dashboard" class="section" style="display:block;">
    <div class="cards">
        <div class="card">
            <h3>Total Services</h3>
            <p><?= $total_services; ?></p>
        </div>
        <div class="card">
            <h3>My Applications</h3>
            <p><?= $total_apps; ?></p>
        </div>
    </div>
</div>

<!-- SERVICES -->
<div id="services" class="section">
<h3>Available Services</h3>
<table>
<tr><th>ID</th><th>Name</th><th>Description</th></tr>
<?php mysqli_data_seek($services,0); while($s=mysqli_fetch_assoc($services)): ?>
<tr>
<td><?= $s['id']; ?></td>
<td><?= $s['service_name']; ?></td>
<td><?= $s['description']; ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<!-- APPLY -->
<div id="apply" class="section">
<h3>Apply for Service</h3>
<form method="POST">
<select name="service_id" required>
<option value="">-- Select Service --</option>
<?php mysqli_data_seek($services,0); while($s=mysqli_fetch_assoc($services)): ?>
<option value="<?= $s['id']; ?>"><?= $s['service_name']; ?></option>
<?php endwhile; ?>
</select>
<input name="home_number" placeholder="Home Number" required>
<button class="submit" name="apply_service">Apply</button>
</form>
</div>

<!-- APPLICATIONS -->
<div id="applications" class="section">
<h3>My Applications</h3>
<table>
<tr>
<th>Service</th><th>Home No</th><th>Status</th>
<th>Remarks</th><th>Date</th><th>Document</th>
</tr>
<?php while($a=mysqli_fetch_assoc($applications)): ?>
<tr>
<td><?= $a['service_name']; ?></td>
<td><?= $a['home_number']; ?></td>
<td><?= $a['status']; ?></td>
<td><?= $a['remarks']; ?></td>
<td><?= $a['created_at']; ?></td>
<td>
<?php if($a['document_file']): ?>
<a href="uploads/documents/<?= $a['document_file']; ?>" target="_blank">Download</a>
<?php else: ?>N/A<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</body>
</html>
