<?php

session_start();

include '../config/koneksi.php';

if(
!isset($_SESSION['role'])
){

header(
"Location: ../login.php"
);

exit();

}

if(
$_SESSION['role']!="customer"
){

header(
"Location: ../login.php"
);

exit();

}

if(
!isset($_GET['id'])
){

header(
"Location: dashboard.php"
);

exit();

}

$id_destinasi =
intval($_GET['id']);

$query =
mysqli_query(

$koneksi,

"SELECT *
FROM destinasi
WHERE id='$id_destinasi'"

);

if(
mysqli_num_rows($query)==0
){

header(
"Location: dashboard.php"
);

exit();

}

$destinasi =
mysqli_fetch_assoc($query);

$success="";
$error="";

if(
isset($_POST['booking'])
){

$tanggal =
mysqli_real_escape_string(
$koneksi,
$_POST['tanggal']
);

$jumlah_orang =
intval(
$_POST['jumlah_orang']
);

$customer_id =
$_SESSION['id'];

if(
empty($tanggal)
||
$jumlah_orang<=0
){

$error =
"Lengkapi form booking.";

}
else{

mysqli_query(

$koneksi,

"INSERT INTO booking(

customer_id,
destinasi_id,
guide_id,
tanggal,
jumlah_orang,
status

)

VALUES(

'$customer_id',
'$id_destinasi',
NULL,
'$tanggal',
'$jumlah_orang',
'Menunggu Guide'

)"

);

$success =
"Booking berhasil dibuat!";

}

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Booking Destinasi</title>

<link
rel="stylesheet"
href="../assets/css/style.css">

<style>

body{

background:#eef2f7;

}

.navbar{

background:#1e293b;

padding:18px 40px;

display:flex;

justify-content:space-between;

align-items:center;

flex-wrap:wrap;

}

.logo{

color:white;

font-size:24px;

font-weight:bold;

}

.menu{

display:flex;

gap:18px;

flex-wrap:wrap;

}

.menu a{

color:white;

text-decoration:none;

}

.container{

width:92%;

max-width:1200px;

margin:40px auto;

}

.wrapper{

display:grid;

grid-template-columns:
1fr 1fr;

gap:35px;

}

.card-booking{

background:white;

border-radius:22px;

overflow:hidden;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

}

.card-booking img{

width:100%;

height:320px;

object-fit:cover;

}

.content{

padding:28px;

}

.content h2{

color:#1e293b;

margin-bottom:14px;

}

.lokasi{

color:#3b82f6;

font-weight:bold;

margin-bottom:12px;

}

.harga{

color:#22c55e;

font-size:24px;

font-weight:bold;

margin-bottom:18px;

}

.desc{

color:#64748b;

line-height:1.8;

}

.form-card{

background:white;

padding:35px;

border-radius:22px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

}

.form-card h2{

margin-bottom:25px;

color:#1e293b;

}

.form-group{

margin-bottom:18px;

}

.form-group label{

display:block;

margin-bottom:8px;

font-weight:bold;

color:#374151;

}

.form-group input{

width:100%;

padding:14px;

border:1px solid #d1d5db;

border-radius:12px;

outline:none;

}

.form-group input:focus{

border-color:#22c55e;

}

.total{

margin-top:18px;

padding:18px;

background:#f8fafc;

border-radius:12px;

font-size:18px;

font-weight:bold;

color:#1e293b;

}

@media(max-width:900px){

.wrapper{

grid-template-columns:1fr;

}

}

</style>

</head>

<body>

<div class="navbar">

<div class="logo">

BOOKING WISATA

</div>

<div class="menu">

<a href="dashboard.php">

Dashboard

</a>

<a href="riwayat.php">

Riwayat

</a>

<a href="../logout.php">

Logout

</a>

</div>

</div>

<div class="container">

<div class="wrapper">

<div class="card-booking">

<?php

$gambar =
!empty($destinasi['gambar'])

?

'../uploads/'.$destinasi['gambar']

:

'https://via.placeholder.com/600x400';

?>

<img src="<?= $gambar ?>">

<div class="content">

<h2>

<?= htmlspecialchars(
$destinasi['nama_destinasi']
) ?>

</h2>

<div class="lokasi">

📍
<?= htmlspecialchars(
$destinasi['lokasi']
) ?>

</div>

<div class="harga">

Rp
<?= number_format(
$destinasi['harga'],
0,
',',
'.'
) ?>

</div>

<div class="desc">

<?= nl2br(
htmlspecialchars(
$destinasi['deskripsi']
)
) ?>

</div>

</div>

</div>

<div class="form-card">

<h2>

Form Booking

</h2>

<?php
if($error!=""){
?>

<div class="alert-danger">

<?= $error ?>

</div>

<?php } ?>

<?php
if($success!=""){
?>

<div class="alert-success">

<?= $success ?>

</div>

<?php } ?>

<form method="POST">

<div class="form-group">

<label>

Nama Customer

</label>

<input
type="text"

value="<?= htmlspecialchars(
$_SESSION['nama']
) ?>"

readonly>

</div>

<div class="form-group">

<label>

Tanggal Wisata

</label>

<input
type="date"

name="tanggal"

required>

</div>

<div class="form-group">

<label>

Jumlah Orang

</label>

<input
type="number"

name="jumlah_orang"

min="1"

required>

</div>

<div class="total">

Status Awal:

Menunggu Guide

</div>

<br>

<button
type="submit"
name="booking"
class="btn btn-primary">

BOOKING SEKARANG

</button>

</form>

</div>

</div>

</div>

<script
src="../assets/js/script.js">

</script>

</body>
</html>