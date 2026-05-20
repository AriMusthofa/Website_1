<?php
include 'config/koneksi.php';

$query = mysqli_query(
$koneksi,
"SELECT * FROM destinasi ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Projek CRUD Wisata</title>

<link
rel="stylesheet"
href="assets/css/style.css">

<style>

.hero{

background:
linear-gradient(
rgba(15,23,42,0.75),
rgba(15,23,42,0.75)
),

url('assets/img/hero.jpg');

background-size:cover;
background-position:center;

color:white;

text-align:center;

padding:140px 20px;

}

.hero h1{

font-size:52px;

margin-bottom:18px;

}

.hero p{

font-size:18px;

margin-bottom:35px;

max-width:750px;

margin-left:auto;
margin-right:auto;

line-height:1.7;

}

.hero-btn{

display:flex;

justify-content:center;

gap:15px;

flex-wrap:wrap;

}

.container{

width:92%;

margin:auto;

}

.section-title{

text-align:center;

margin:60px 0 40px;

}

.section-title h2{

font-size:38px;

color:#1e293b;

margin-bottom:10px;

}

.section-title p{

color:#64748b;

}

.destinasi-grid{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(300px,1fr));

gap:30px;

margin-bottom:70px;

}

.card-destinasi{

background:white;

border-radius:22px;

overflow:hidden;

box-shadow:
0 10px 30px rgba(0,0,0,0.08);

transition:.35s;

}

.card-destinasi:hover{

transform:translateY(-10px);

}

.card-destinasi img{

width:100%;

height:240px;

object-fit:cover;

}

.card-body{

padding:25px;

}

.card-body h3{

color:#1e293b;

margin-bottom:12px;

font-size:24px;

}

.lokasi{

color:#3b82f6;

font-weight:bold;

margin-bottom:10px;

}

.harga{

font-size:22px;

font-weight:bold;

color:#22c55e;

margin-bottom:15px;

}

.desc{

color:#64748b;

line-height:1.7;

margin-bottom:22px;

}

.footer{

background:#1e293b;

color:white;

text-align:center;

padding:35px;

margin-top:50px;

}

@media(max-width:768px){

.hero h1{

font-size:36px;

}

.hero{

padding:100px 20px;

}

}

</style>

</head>

<body>

<nav class="navbar">

<div class="logo">

TRAVEL GUIDE

</div>

<ul>

<li>

<a href="#beranda">

Beranda

</a>

</li>

<li>

<a href="#destinasi">

Destinasi

</a>

</li>

<li>

<a href="login.php">

Login

</a>

</li>

<li>

<a href="register.php">

Register

</a>

</li>

</ul>

</nav>

<section
class="hero"
id="beranda">

<h1>

Temukan Wisata Terbaik Anda

</h1>

<p>

Booking destinasi wisata favorit,
dapatkan pengalaman perjalanan
lebih mudah bersama guide profesional.

</p>

<div class="hero-btn">

<a
href="login.php"
class="btn btn-primary">

LOGIN

</a>

<a
href="register.php"
class="btn btn-warning">

REGISTER

</a>

</div>

</section>

<div
class="container"
id="destinasi">

<div class="section-title">

<h2>

Destinasi Wisata

</h2>

<p>

Jelajahi pilihan wisata terbaik.

</p>

</div>

<div class="destinasi-grid">

<?php

if(
mysqli_num_rows($query)>0
){

while(
$row=mysqli_fetch_assoc($query)
){

?>

<div class="card-destinasi">

<?php

$gambar =
!empty($row['gambar'])

?

'uploads/'.$row['gambar']

:

'https://via.placeholder.com/600x400';

?>

<img
src="<?= $gambar ?>">

<div class="card-body">

<h3>

<?= htmlspecialchars(
$row['nama_destinasi']
) ?>

</h3>

<div class="lokasi">

📍
<?= htmlspecialchars(
$row['lokasi']
) ?>

</div>

<div class="harga">

Rp
<?= number_format(
$row['harga'],
0,
',',
'.'
) ?>

</div>

<div class="desc">

<?= substr(
htmlspecialchars(
$row['deskripsi']
),
0,
120
) ?>

...

</div>

<a
href="login.php"
class="btn btn-primary">

Booking Sekarang

</a>

</div>

</div>

<?php

}

}else{

?>

<div class="card">

<h3>

Belum ada destinasi tersedia.

</h3>

</div>

<?php

}

?>

</div>

</div>

<footer class="footer">

<h3>

TRAVEL GUIDE SYSTEM

</h3>

<p>

© 2026 Projek CRUD Wisata.

</p>

</footer>

<script
src="assets/js/script.js">

</script>

</body>
</html>