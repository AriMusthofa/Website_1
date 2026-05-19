<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['role'])){

header("Location: ../login.php");

}

if($_SESSION['role']!="admin"){

header("Location: ../login.php");

}

$edit=false;

/* HAPUS */

if(isset($_GET['hapus'])){

$id=$_GET['hapus'];

mysqli_query($koneksi,
"DELETE FROM users WHERE id='$id'");

header("Location: user.php");

}

/* EDIT */

if(isset($_GET['edit'])){

$edit=true;

$id=$_GET['edit'];

$data=mysqli_query($koneksi,
"SELECT * FROM users WHERE id='$id'");

$rowEdit=mysqli_fetch_assoc($data);

}

/* TAMBAH / UPDATE */

if(isset($_POST['simpan'])){

$username=$_POST['username'];
$password=$_POST['password'];
$role=$_POST['role'];

if($_POST['id']==""){

mysqli_query($koneksi,

"INSERT INTO users
(username,password,role)

VALUES
('$username','$password','$role')"

);

}else{

$id=$_POST['id'];

mysqli_query($koneksi,

"UPDATE users SET

username='$username',
password='$password',
role='$role'

WHERE id='$id'"

);

}

header("Location: user.php");

}

?>

<!DOCTYPE html>
<html>
<head>

<title>CRUD User</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:#f4f6f9;
}

.container{
width:90%;
margin:30px auto;
}

.card{

background:white;
padding:25px;
border-radius:10px;

margin-bottom:30px;

box-shadow:0 5px 15px rgba(0,0,0,0.1);

}

input,select{

width:100%;
padding:12px;

margin-bottom:15px;

border:1px solid #ccc;
border-radius:8px;

}

button{

background:#27ae60;
color:white;

padding:12px 18px;

border:none;
border-radius:8px;

cursor:pointer;

}

table{

width:100%;
border-collapse:collapse;

}

table th,
table td{

padding:12px;

border-bottom:1px solid #ddd;

text-align:center;

}

th{

background:#2c3e50;
color:white;

}

.edit{

background:orange;
color:white;

padding:8px 15px;

border-radius:8px;

text-decoration:none;

}

.hapus{

background:red;
color:white;

padding:8px 15px;

border-radius:8px;

text-decoration:none;

}

.back{

display:inline-block;

margin-bottom:20px;

padding:10px 18px;

background:#34495e;
color:white;

border-radius:8px;

text-decoration:none;

}

</style>

</head>
<body>

<div class="container">

<a href="dashboard.php"
class="back">

← Dashboard

</a>

<div class="card">

<h2>

<?= $edit ? 'Edit User' : 'Tambah User'; ?>

</h2>

<br>

<form method="POST">

<input
type="hidden"
name="id"

value="<?= $edit ? $rowEdit['id'] : '' ?>">

<input
type="text"

name="username"

placeholder="Username"

value="<?= $edit ? $rowEdit['username'] : '' ?>"

required>

<input
type="text"

name="password"

placeholder="Password"

value="<?= $edit ? $rowEdit['password'] : '' ?>"

required>

<select name="role" required>

<option value="">

Pilih Role

</option>

<option value="admin"
<?= ($edit && $rowEdit['role']=="admin") ? 'selected' : ''; ?>>

Admin

</option>

<option value="user"
<?= ($edit && $rowEdit['role']=="user") ? 'selected' : ''; ?>>

User

</option>

</select>

<button
type="submit"
name="simpan">

<?= $edit ? 'UPDATE' : 'SIMPAN'; ?>

</button>

</form>

</div>

<div class="card">

<h2>Data User</h2>

<br>

<table>

<tr>

<th>ID</th>
<th>Username</th>
<th>Password</th>
<th>Role</th>
<th>Aksi</th>

</tr>

<?php

$query=mysqli_query($koneksi,
"SELECT * FROM users");

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= $row['username']; ?></td>

<td><?= $row['password']; ?></td>

<td><?= strtoupper($row['role']); ?></td>

<td>

<a
class="edit"

href="user.php?edit=<?= $row['id']; ?>">

Edit

</a>

<a
class="hapus"

onclick="return confirm('Hapus user?')"

href="user.php?hapus=<?= $row['id']; ?>">

Hapus

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>