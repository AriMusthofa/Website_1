<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['role'])){

header("Location: ../login.php");

}

if($_SESSION['role']!="user"){

header("Location: ../login.php");

}

?>

<!DOCTYPE html>
<html>
<head>

<title>SEMBALUN GUIDE</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#f4f6f9;
}

/* NAVBAR */

.navbar{

position:absolute;

top:0;
left:0;

width:100%;

padding:20px 60px;

display:flex;

justify-content:space-between;

align-items:center;

z-index:1000;

}

.logo{

font-size:28px;
font-weight:bold;

color:white;

}

.menu a{

color:white;

text-decoration:none;

margin-left:25px;

}

/* HERO */

.hero{

height:100vh;

background:
linear-gradient(
rgba(0,0,0,0.45),
rgba(0,0,0,0.45)
),

url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b')
center/cover;

display:flex;

justify-content:center;
align-items:center;

text-align:center;

color:white;

}

.hero h1{

font-size:65px;

margin-bottom:20px;

}

.hero p{

font-size:20px;

margin-bottom:30px;

}

.btn{

background:#27ae60;

padding:14px 28px;

border-radius:10px;

text-decoration:none;

color:white;

font-weight:bold;

}

/* SECTION */

.section{

width:90%;

margin:80px auto;

}

.title{

text-align:center;

margin-bottom:50px;

}

.title h2{

font-size:38px;

color:#2c3e50;

margin-bottom:10px;

}

/* CARD DESTINASI */

.grid{

display:grid;

grid-template-columns:
repeat(3,1fr);

gap:25px;

}

.card{

background:white;

border-radius:15px;

overflow:hidden;

box-shadow:
0 5px 20px rgba(0,0,0,0.1);

transition:0.3s;

}

.card:hover{

transform:translateY(-8px);

}

.card img{

width:100%;
height:220px;

object-fit:cover;

}

.card-body{

padding:20px;

}

.card-body h3{

margin-bottom:10px;

color:#2c3e50;

}

.card-body p{

color:#666;

margin-bottom:15px;

}

.price{

color:#27ae60;

font-weight:bold;

font-size:18px;

}

/* ABOUT */

.about{

display:grid;

grid-template-columns:
1fr 1fr;

gap:40px;

align-items:center;

}

.about img{

width:100%;

border-radius:15px;

}

/* FOOTER */

.footer{

background:#2c3e50;

padding:35px;

text-align:center;

color:white;

margin-top:80px;

}

</style>

</head>
<body>

<div class="navbar">

<div class="logo">

SEMBALUN GUIDE

</div>

<div class="menu">

<a href="#">Beranda</a>

<a href="booking.php">

Booking

</a>

<a href="../logout.php">

Logout

</a>

</div>

</div>

<div class="hero">

<div>

<h1>

Explore Nature Adventure

</h1>

<p>

Selamat Datang,
<?= $_SESSION['username']; ?>

</p>

<a
href="booking.php"
class="btn">

Booking Sekarang

</a>

</div>

</div>

<div class="section">

<div class="title">

<h2>

Destinasi Pendakian

</h2>

<p>

Jelajahi wisata alam terbaik.

</p>

</div>

<div class="grid">

<?php

$query=mysqli_query(
$koneksi,
"SELECT * FROM destinasi"
);

while($row=mysqli_fetch_assoc($query)){

?>

<div class="card">

<?php
if($row['gambar']!=""){
?>

<img
src="../uploads/<?= $row['gambar']; ?>">

<?php
}
?>

<div class="card-body">

<h3>

<?= $row['nama_destinasi']; ?>

</h3>

<p>

<?= $row['lokasi']; ?>

</p>

<p>

<?= $row['deskripsi']; ?>

</p>

<div class="price">

Rp <?= number_format($row['harga']); ?>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

<div class="section">

<div class="about">

<div>

<h2>

Tentang Kami

</h2>

<br>

<p>

SEMBALUN GUIDE adalah
layanan wisata dan
pendakian yang membantu
petualang menikmati
keindahan alam Sembalun.

</p>

<br>

<p>

Booking cepat,
destinasi lengkap,
dan pengalaman terbaik.

</p>

</div>

<div>

<img
src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee">

</div>

</div>

</div>

<div class="footer">

Copyright © 2026
SEMBALUN GUIDE

</div>

</body>
</html>