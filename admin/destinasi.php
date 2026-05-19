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
"DELETE FROM destinasi WHERE id='$id'");

header("Location: destinasi.php");

}

/* EDIT */

if(isset($_GET['edit'])){

$edit=true;

$id=$_GET['edit'];

$data=mysqli_query($koneksi,
"SELECT * FROM destinasi WHERE id='$id'");

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

mysqli_query($koneksi,

"INSERT INTO destinasi
(nama_destinasi,lokasi,harga,deskripsi,gambar)

VALUES
('$nama','$lokasi','$harga','$deskripsi','$gambar')"

);

}else{

$id=$_POST['id'];

if($gambar==""){

$gambar=$rowEdit['gambar'];

}

mysqli_query($koneksi,

"UPDATE destinasi SET

nama_destinasi='$nama',
lokasi='$lokasi',
harga='$harga',
deskripsi='$deskripsi',
gambar='$gambar'

WHERE id='$id'"

);

}

header("Location: destinasi.php");

}

?>

<!DOCTYPE html>
<html>
<head>

<title>CRUD Destinasi</title>

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

input,textarea{

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

img{

width:100px;
height:70px;

object-fit:cover;

border-radius:8px;

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

<?= $edit ? 'Edit Destinasi' : 'Tambah Destinasi'; ?>

</h2>

<br>

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

placeholder="Deskripsi">

<?= $edit ? $rowEdit['deskripsi'] : '' ?>

</textarea>

<label>Upload Gambar</label>

<input
type="file"
name="gambar">

<button
type="submit"
name="simpan">

<?= $edit ? 'UPDATE' : 'SIMPAN'; ?>

</button>

</form>

</div>

<div class="card">

<h2>Data Destinasi</h2>

<br>

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
"SELECT * FROM destinasi"
);

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $row['id']; ?></td>

<td>

<?php
if($row['gambar']!=""){
?>

<img
src="../uploads/<?= $row['gambar']; ?>">

<?php
}
?>

</td>

<td><?= $row['nama_destinasi']; ?></td>

<td><?= $row['lokasi']; ?></td>

<td>

Rp <?= number_format($row['harga']); ?>

</td>

<td>

<a
class="edit"

href="destinasi.php?edit=<?= $row['id']; ?>">

Edit

</a>

<a
class="hapus"

onclick="return confirm('Hapus data?')"

href="destinasi.php?hapus=<?= $row['id']; ?>">

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