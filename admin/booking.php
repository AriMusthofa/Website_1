<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['role'])){
header("Location: ../login.php");
}

if($_SESSION['role']!="admin"){
header("Location: ../login.php");
}

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
"DELETE FROM booking WHERE id='$id'"
);

header(
"Location: booking.php?pesan=hapus"
);

exit();

}

/* UPDATE STATUS */

if(isset($_POST['update'])){

$id=$_POST['id'];
$status=$_POST['status'];

mysqli_query(

$koneksi,

"UPDATE booking
SET status='$status'
WHERE id='$id'"

);

header(
"Location: booking.php?pesan=update"
);

exit();

}

?>

<!DOCTYPE html>
<html>
<head>

<title>CRUD Booking Modern</title>

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

box-shadow:
0 5px 20px rgba(0,0,0,0.08);

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

h2{
color:#2c3e50;
margin-bottom:20px;
}

.search{

display:flex;
gap:10px;

margin-bottom:25px;

}

.search input{

flex:1;

padding:14px;

border:1px solid #ddd;

border-radius:10px;

}

button{

background:#27ae60;

color:white;

border:none;

padding:12px 18px;

border-radius:10px;

cursor:pointer;

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

select{

padding:10px;

border-radius:8px;

border:1px solid #ddd;

}

.hapus{

background:#e74c3c;

color:white;

padding:10px 15px;

border-radius:8px;

text-decoration:none;

}

.badge-pending{

background:#f39c12;

color:white;

padding:8px 12px;

border-radius:20px;

font-size:13px;

}

.badge-terima{

background:#27ae60;

color:white;

padding:8px 12px;

border-radius:20px;

font-size:13px;

}

.badge-tolak{

background:#e74c3c;

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
font-size:12px;
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

if($_GET['pesan']=="update"){

echo "<div class='alert update'>
Status booking berhasil diupdate.
</div>";

}

elseif($_GET['pesan']=="hapus"){

echo "<div class='alert delete'>
Booking berhasil dihapus.
</div>";

}

}

?>

<h2>Data Booking</h2>

<form
method="GET"
class="search">

<input
type="text"

name="search"

placeholder="Cari user / destinasi..."

value="<?= $search ?>">

<button type="submit">

Cari

</button>

</form>

<table>

<tr>

<th>ID</th>
<th>User</th>
<th>Destinasi</th>
<th>Tanggal</th>
<th>Jumlah</th>
<th>Status</th>
<th>Update</th>
<th>Aksi</th>

</tr>

<?php

$query=mysqli_query(

$koneksi,

"SELECT booking.*,
users.username,
destinasi.nama_destinasi

FROM booking

JOIN users
ON booking.user_id=users.id

JOIN destinasi
ON booking.destinasi_id=destinasi.id

WHERE

users.username
LIKE '%$search%'

OR

destinasi.nama_destinasi
LIKE '%$search%'"

);

if(mysqli_num_rows($query)>0){

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= $row['username'] ?></td>

<td><?= $row['nama_destinasi'] ?></td>

<td><?= $row['tanggal'] ?></td>

<td><?= $row['jumlah_orang'] ?></td>

<td>

<?php

if($row['status']=="Pending"){

echo "<span class='badge-pending'>
Pending
</span>";

}

elseif($row['status']=="Diterima"){

echo "<span class='badge-terima'>
Diterima
</span>";

}

else{

echo "<span class='badge-tolak'>
Ditolak
</span>";

}

?>

</td>

<td>

<form method="POST">

<input
type="hidden"
name="id"

value="<?= $row['id'] ?>">

<select name="status">

<option
value="Pending"

<?= ($row['status']=="Pending")
? 'selected' : '' ?>>

Pending

</option>

<option
value="Diterima"

<?= ($row['status']=="Diterima")
? 'selected' : '' ?>>

Diterima

</option>

<option
value="Ditolak"

<?= ($row['status']=="Ditolak")
? 'selected' : '' ?>>

Ditolak

</option>

</select>

<button
type="submit"
name="update">

Update

</button>

</form>

</td>

<td>

<a
class="hapus"

onclick="return confirm('Hapus booking?')"

href="booking.php?hapus=<?= $row['id'] ?>">

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
colspan="8"
class="empty">

Belum ada data booking.

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>