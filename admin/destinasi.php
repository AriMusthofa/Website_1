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
"DELETE FROM destinasi WHERE id='$id'"
);

header(
"Location: destinasi.php?pesan=hapus"
);

exit();

}

/* EDIT */

if(isset($_GET['edit'])){

$edit=true;

$id=$_GET['edit'];

$data=mysqli_query(
$koneksi,
"SELECT * FROM destinasi WHERE id='$id'"
);

$rowEdit=mysqli_fetch_assoc($data);

}

/* TAMBAH / UPDATE */

if(isset($_POST['simpan'])){

$nama=$_POST['nama_destinasi'];
$lokasi=$_POST['lokasi'];
$harga=$_POST['harga'];
$deskripsi=$_POST['deskripsi'];

$gambar="";

if($_FILES['gambar']['name']!=""){

$gambar=time()."_".$_FILES['gambar']['name'];

$tmp=$_FILES['gambar']['tmp_name'];

move_uploaded_file(
$tmp,
"../uploads/".$gambar
);

}

if($_POST['id']==""){

mysqli_query(

$koneksi,

"INSERT INTO destinasi
(nama_destinasi,lokasi,harga,deskripsi,gambar)

VALUES

('$nama','$lokasi','$harga','$deskripsi','$gambar')"

);

header(
"Location: destinasi.php?pesan=tambah"
);

exit();

}else{

$id=$_POST['id'];

if($gambar==""){
$gambar=$rowEdit['gambar'];
}

mysqli_query(

$koneksi,

"UPDATE destinasi SET

nama_destinasi='$nama',
lokasi='$lokasi',
harga='$harga',
deskripsi='$deskripsi',
gambar='$gambar'

WHERE id='$id'"

);

header(
"Location: destinasi.php?pesan=update"
);

exit();

}

}

?>

<!DOCTYPE html>
<html>
<head>

<title>CRUD Destinasi Modern</title>

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

input,textarea{

width:100%;
padding:14px;

margin-bottom:15px;

border:1px solid #dcdcdc;
border-radius:10px;

outline:none;

}

input:focus,
textarea:focus{

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

img{

width:90px;
height:70px;

object-fit:cover;

border-radius:10px;

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

.empty{

padding:25px;
color:#777;
text-align:center;

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

<?= $edit ? 'Edit Destinasi' : 'Tambah Destinasi' ?>

</h2>

<form
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="id"

value="<?= $edit ? $rowEdit['id'] : '' ?>">

<input
type="text"
name="nama_destinasi"

placeholder="Nama Destinasi"

value="<?= $edit ? $rowEdit['nama_destinasi'] : '' ?>"

required>

<input
type="text"
name="lokasi"

placeholder="Lokasi"

value="<?= $edit ? $rowEdit['lokasi'] : '' ?>"

required>

<input
type="number"
name="harga"

placeholder="Harga"

value="<?= $edit ? $rowEdit['harga'] : '' ?>"

required>

<textarea
name="deskripsi"

placeholder="Deskripsi"><?= $edit ? $rowEdit['deskripsi'] : '' ?></textarea>

<input
type="file"
name="gambar">

<button
type="submit"
name="simpan">

<?= $edit ? 'UPDATE' : 'SIMPAN' ?>

</button>

</form>

</div>

<div class="card">

<h2>Data Destinasi</h2>

<form
method="GET"
class="search">

<input
type="text"

name="search"

placeholder="Cari destinasi..."

value="<?= $search ?>">

<button type="submit">

Cari

</button>

</form>

<table>

<tr>

<th>ID</th>
<th>Gambar</th>
<th>Nama</th>
<th>Lokasi</th>
<th>Harga</th>
<th>Aksi</th>

</tr>

<?php

$query=mysqli_query(

$koneksi,

"SELECT * FROM destinasi
WHERE nama_destinasi
LIKE '%$search%'"

);

if(mysqli_num_rows($query)>0){

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $row['id'] ?></td>

<td>

<?php
if($row['gambar']!=""){
?>

<img
src="../uploads/<?= $row['gambar'] ?>">

<?php } ?>

</td>

<td><?= $row['nama_destinasi'] ?></td>

<td><?= $row['lokasi'] ?></td>

<td>

Rp <?= number_format($row['harga']) ?>

</td>

<td>

<a
class="edit"

href="destinasi.php?edit=<?= $row['id'] ?>">

Edit

</a>

<a
class="hapus"

onclick="return confirm('Hapus data?')"

href="destinasi.php?hapus=<?= $row['id'] ?>">

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
colspan="6"
class="empty">

Belum ada data.

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>