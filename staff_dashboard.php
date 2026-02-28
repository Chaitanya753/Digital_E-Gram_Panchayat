<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.php");
    exit();
}

$success_msg = "";
$error_msg = "";

/* UPDATE APPLICATION STATUS */
if (isset($_POST['update_status'])) {
    $id = intval($_POST['application_id']);
    $status = $_POST['status'];
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    mysqli_query($conn, "UPDATE applications SET status='$status', remarks='$remarks' WHERE id=$id");
}

/* UPLOAD DOCUMENT */
if (isset($_POST['upload_document'])) {
    $app_id = intval($_POST['application_id']);
    $staff_id = $_SESSION['staff_id'];

    $dir = __DIR__ . "/uploads/documents/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    if (!empty($_FILES['document']['name'])) {
        $file = time()."_".$_FILES['document']['name'];
        if (move_uploaded_file($_FILES['document']['tmp_name'], $dir.$file)) {
            mysqli_query($conn,"
                INSERT INTO application_documents (application_id, document_file, created_by)
                VALUES ($app_id,'$file',$staff_id)
            ");
            $success_msg = "Document uploaded successfully!";
        }
    }
}

$total_services = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM services"))['total'];
$total_apps = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM applications"));

$services = mysqli_query($conn,"SELECT * FROM services");
$applications = mysqli_query($conn,"
    SELECT a.*, u.name user_name, s.service_name
    FROM applications a
    JOIN users u ON u.id=a.user_id
    JOIN services s ON s.id=a.service_id
    ORDER BY a.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Staff Dashboard</title>

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
.disabled{color:gray;}
.success{color:green;margin-bottom:10px;}
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
    <h2>Staff</h2>
    <button onclick="showSection('dashboard')">Dashboard</button>
    <button onclick="showSection('services')">Services</button>
    <button onclick="showSection('applications')">Applications</button>
    <a href="logout.php"><button class="logout">Logout</button></a>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">
    <h3>Welcome, <?= $_SESSION['staff_name']; ?></h3>
</div>

<?php if($success_msg) echo "<div class='success'>$success_msg</div>"; ?>

<!-- DASHBOARD -->
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

<!-- SERVICES -->
<div id="services" class="section">
<h3>All Services</h3>
<table>
<tr><th>ID</th><th>Name</th><th>Description</th></tr>
<?php while($s=mysqli_fetch_assoc($services)): ?>
<tr>
<td><?= $s['id']; ?></td>
<td><?= $s['service_name']; ?></td>
<td><?= $s['description']; ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<!-- APPLICATIONS -->
<div id="applications" class="section">
<h3>Applications</h3>
<table>
<tr>
<th>User</th><th>Service</th><th>Status</th><th>Update</th><th>Upload</th>
</tr>
<?php while($a=mysqli_fetch_assoc($applications)): ?>
<tr>
<td><?= $a['user_name']; ?></td>
<td><?= $a['service_name']; ?></td>
<td><?= $a['status']; ?></td>

<td>
<form method="POST">
<input type="hidden" name="application_id" value="<?= $a['id']; ?>">
<select name="status">
<option <?= $a['status']=='Pending'?'selected':''; ?>>Pending</option>
<option <?= $a['status']=='Approved'?'selected':''; ?>>Approved</option>
<option <?= $a['status']=='Rejected'?'selected':''; ?>>Rejected</option>
</select>
<input name="remarks" placeholder="Remarks" value="<?= $a['remarks']; ?>">
<button class="submit" name="update_status">Update</button>
</form>
</td>

<td>
<?php if($a['status']=='Approved'): ?>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="application_id" value="<?= $a['id']; ?>">
<input type="file" name="document" required>
<button class="submit" name="upload_document">Upload</button>
</form>
<?php else: ?>
<span class="disabled">Not Approved</span>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</body>
</html>
