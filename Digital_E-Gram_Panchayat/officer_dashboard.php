<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['officer_id'])) {
    header("Location: officer_login.php");
    exit();
}

/* CREATE SERVICE */
if (isset($_POST['create_service'])) {
    $name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    mysqli_query($conn, "INSERT INTO services (service_name, description) VALUES ('$name','$desc')");
}

/* DELETE SERVICE */
if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM services WHERE id=".(int)$_GET['delete']);
}

/* UPDATE APPLICATION */
if (isset($_POST['update_status'])) {
    $id = $_POST['application_id'];
    $status = $_POST['status'];
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    mysqli_query($conn, "UPDATE applications SET status='$status', remarks='$remarks' WHERE id=$id");
}

$total_services = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM services"))['total'];
$total_apps     = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM applications"));

$services = mysqli_query($conn,"SELECT * FROM services");
$applications = mysqli_query($conn,"
    SELECT a.*, u.name user_name, s.service_name, d.document_file
    FROM applications a
    JOIN users u ON u.id=a.user_id
    JOIN services s ON s.id=a.service_id
    LEFT JOIN application_documents d ON d.application_id=a.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Officer Dashboard</title>

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
th{background:#2563eb;color:white;padding:10px;}
td{padding:10px;border-bottom:1px solid #e5e7eb;}

input,select,textarea,button{
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
</style>

<script>
function showSection(id){
    document.querySelectorAll('.section').forEach(s => s.style.display='none');
    document.getElementById(id).style.display='block';
}
</script>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Officer</h2>
    <button onclick="showSection('dashboard')">Dashboard</button>
    <button onclick="showSection('create_service')">Create Service</button>
    <button onclick="showSection('services')">Total Services</button>
    <button onclick="showSection('applications')">Applications</button>
    <a href="logout.php"><button class="logout">Logout</button></a>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">
    <h3>Welcome, <?= $_SESSION['officer_name']; ?></h3>
</div>

<!-- DASHBOARD (DEFAULT) -->
<div id="dashboard" class="section" style="display:block;">
    <div class="cards">
        <div class="card">
            <h3>Total Services</h3>
            <p><?= $total_services; ?></p>
        </div>
        <div class="card">
            <h3>Total Applications</h3>
            <p><?= $total_apps; ?></p>
        </div>
    </div>
</div>

<!-- CREATE SERVICE -->
<div id="create_service" class="section">
<h3>Create New Service</h3>
<form method="POST">
    <input name="service_name" placeholder="Service Name" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button class="submit" name="create_service">Create</button>
</form>
</div>

<!-- SERVICES -->
<div id="services" class="section">
<h3>All Services</h3>
<table>
<tr><th>ID</th><th>Name</th><th>Description</th><th>Action</th></tr>
<?php while($s=mysqli_fetch_assoc($services)): ?>
<tr>
<td><?= $s['id']; ?></td>
<td><?= $s['service_name']; ?></td>
<td><?= $s['description']; ?></td>
<td><a href="?delete=<?= $s['id']; ?>" style="color:red;">Delete</a></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<!-- APPLICATIONS -->
<div id="applications" class="section">
<h3>Applications</h3>
<table>
<tr>
<th>User</th><th>Service</th><th>Status</th><th>Document</th><th>Update</th>
</tr>
<?php while($a=mysqli_fetch_assoc($applications)): ?>
<tr>    
<td><?= $a['user_name']; ?></td>
<td><?= $a['service_name']; ?></td>
<td><?= $a['status']; ?></td>
<td>
<?php if($a['document_file']): ?>
<a href="uploads/documents/<?= $a['document_file']; ?>" target="_blank">View</a>
<?php else: ?>N/A<?php endif; ?>
</td>
<td>
<form method="POST">
<input type="hidden" name="application_id" value="<?= $a['id']; ?>">
<select name="status">
<option>Pending</option>
<option>Approved</option>
<option>Rejected</option>
</select>
<input name="remarks" placeholder="Remarks">
<button class="submit" name="update_status">Update</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</body>
</html>
