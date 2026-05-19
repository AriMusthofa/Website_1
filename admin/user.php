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
$search="";

/* SEARCH */

if(isset($_GET['search'])){
$search=$_GET['search'];
}

/* HAPUS */

if(isset($_GET['hapus'])){

$id=$_GET['hapus'];

mysqli_query(
$koneksi,
"DELETE FROM users WHERE id='$id'"
);

header(
"Location: user.php?pesan=hapus"
);

exit();

}

/* EDIT */

if(isset($_GET['edit'])){

$edit=true;

$id=$_GET['edit'];

$data=mysqli_query(
$koneksi,
"SELECT * FROM users WHERE id='$id'"
);

$rowEdit=mysqli_fetch_assoc($data);

}

/* TAMBAH / UPDATE */

if(isset($_POST['simpan'])){

$username=$_POST['username'];
$password=$_POST['password'];
$role=$_POST['role'];

if($_POST['id']==""){

mysqli_query(

$koneksi,

"INSERT INTO users
(username,password,role)

VALUES
('$username','$password','$role')"

);

header(
"Location: user.php?pesan=tambah"
);

exit();

}else{

$id=$_POST['id'];

mysqli_query(

$koneksi,

"UPDATE users SET

username='$username',
password='$password',
role='$role'

WHERE id='$id'"

);

header(
"Location: user.php?pesan=update"
);

exit();

}

}

?>

<!DOCTYPE html>
<html>
<head>

<title>CRUD User Modern</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#eef2f7;
}

.container{
width:92%;
margin:35px auto;
}

.card{

background:white;

padding:25px;

border-radius:15px;

margin-bottom:30px;

box-shadow:
0 5px 20px rgba(0,0,0,0.08);

}

h2{
color:#2c3e50;
margin-bottom:20px;
}

input,select{

width:100%;

padding:14px;

margin-bottom:15px;

border:1px solid #ddd;

border-radius:10px;

outline:none;

}

input:focus,
select:focus{

border-color:#27ae60;

}

button{

background:#27ae60;

color:white;

padding:12px 22px;

border:none;

border-radius:10px;

cursor:pointer;

}

.search{

display:flex;

gap:10px;

margin-bottom:25px;

}

.search input{
margin-bottom:0;
}

table{

width:100%;

border-collapse:collapse;

}

th{

background:#2c3e50;

color:white;

}

th,td{

padding:15px;

text-align:center;

border-bottom:1px solid #eee;

}

tr:hover{

background:#f8fafc;

}

.edit{

background:#f39c12;

color:white;

padding:10px 16px;

border-radius:8px;

text-decoration:none;

margin-right:5px;

}

.hapus{

background:#e74c3c;

color:white;

padding:10px 16px;

border-radius:8px;

text-decoration:none;

}

.back{

display:inline-block;

margin-bottom:20px;

background:#34495e;

color:white;

padding:10px 18px;

border-radius:10px;

text-decoration:none;

}

.badge-admin{

background:#27ae60;

color:white;

padding:8px 12px;

border-radius:20px;

font-size:13px;

}

.badge-user{

background:#3498db;

color:white;

padding:8px 12px;

border-radius:20px;

font-size:13px;

}

.empty{

padding:25px;

text-align:center;

color:#777;

}

/* ALERT */

.alert{

padding:16px;

border-radius:10px;

margin-bottom:20px;

color:white;

font-weight:bold;

animation:fadeout 3s forwards;

}

.success{
background:#27ae60;
}

.update{
background:#3498db;
}

.delete{
background:#e74c3c;
}

@keyframes fadeout{

0%{
opacity:1;
}

80%{
opacity:1;
}

100%{
opacity:0;
}

}

@media(max-width:900px){

table{
font-size:13px;
}

.container{
width:98%;
}

}

</style>

</head>

<body>

<div class="container">

<a
href="dashboard.php"
class="back">

← Dashboard

</a>

<div class="card">

<?php

if(isset($_GET['pesan'])){

if($_GET['pesan']=="tambah"){

echo "<div class='alert success'>
Data berhasil ditambahkan.
</div>";

}

elseif($_GET['pesan']=="update"){

echo "<div class='alert update'>
Data berhasil diupdate.
</div>";

}

elseif($_GET['pesan']=="hapus"){

echo "<div class='alert delete'>
Data berhasil dihapus.
</div>";

}

}

?>

<h2>

<?= $edit ? 'Edit User' : 'Tambah User' ?>

</h2>

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

<select
name="role"
required>

<option value="">

Pilih Role

</option>

<option
value="admin"

<?= ($edit && $rowEdit['role']=="admin")
? 'selected' : '' ?>>

Admin

</option>

<option
value="user"

<?= ($edit && $rowEdit['role']=="user")
? 'selected' : '' ?>>

User

</option>

</select>

<button
type="submit"
name="simpan">

<?= $edit ? 'UPDATE' : 'SIMPAN' ?>

</button>

</form>

</div>

<div class="card">

<h2>Data User</h2>

<form
method="GET"
class="search">

<input
type="text"

name="search"

placeholder="Cari username..."

value="<?= $search ?>">

<button type="submit">

Cari

</button>

</form>

<table>

<tr>

<th>ID</th>
<th>Username</th>
<th>Password</th>
<th>Role</th>
<th>Aksi</th>

</tr>

<?php

$query=mysqli_query(

$koneksi,

"SELECT * FROM users
WHERE username
LIKE '%$search%'"

);

if(mysqli_num_rows($query)>0){

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= $row['username'] ?></td>

<td><?= $row['password'] ?></td>

<td>

<?php

if($row['role']=="admin"){

echo "<span class='badge-admin'>
ADMIN
</span>";

}else{

echo "<span class='badge-user'>
USER
</span>";

}

?>

</td>

<td>

<a
class="edit"

href="user.php?edit=<?= $row['id'] ?>">

Edit

</a>

<a
class="hapus"

onclick="return confirm('Hapus user?')"

href="user.php?hapus=<?= $row['id'] ?>">

Hapus

</a>

</td>

</tr>

<?php
}

}else{
?>

<tr>

<td
colspan="5"
class="empty">

Belum ada data user.

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>