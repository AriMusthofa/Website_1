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

$query =
mysqli_query(
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

<title>Dashboard Customer</title>

<link
rel="stylesheet"
href="../assets/css/style.css">

<style>

body{

background:#eef2f7;

}

.topbar{

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

padding:10px 16px;

border-radius:10px;

transition:.3s;

}

.menu a:hover{

background:#334155;

}

.hero{

background:

linear-gradient(
rgba(15,23,42,0.75),
rgba(15,23,42,0.75)
),

url('../assets/img/hero.jpg');

background-size:cover;

background-position:center;

padding:90px 20px;

text-align:center;

color:white;

}

.hero h1{

font-size:42px;

margin-bottom:12px;

}

.hero p{

max-width:700px;

margin:auto;

line-height:1.7;

}

.container{

width:92%;

margin:auto;

}

.section-title{

text-align:center;

margin:55px 0 35px;

}

.section-title h2{

font-size:36px;

color:#1e293b;

margin-bottom:10px;

}

.grid{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(300px,1fr));

gap:28px;

margin-bottom:70px;

}

.dest-card{

background:white;

border-radius:22px;

overflow:hidden;

box-shadow:
0 10px 30px rgba(0,0,0,0.08);

transition:.35s;

}

.dest-card:hover{

transform:translateY(-8px);

}

.dest-card img{

width:100%;

height:230px;

object-fit:cover;

}

.card-body{

padding:24px;

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

color:#22c55e;

font-size:22px;

font-weight:bold;

margin-bottom:15px;

}

.desc{

color:#64748b;

line-height:1.7;

margin-bottom:22px;

}

.welcome{

background:white;

padding:30px;

margin-top:40px;

border-radius:22px;

box-shadow:
0 8px 25px rgba(0,0,0,0.08);

text-align:center;

}

.welcome h2{

color:#1e293b;

margin-bottom:10px;

}

.footer{

background:#1e293b;

color:white;

padding:30px;

text-align:center;

margin-top:40px;

}

@media(max-width:768px){

.hero h1{

font-size:34px;

}

.topbar{

justify-content:center;

gap:15px;

}

.menu{

justify-content:center;

}

}

</style>

</head>

<body>

<div class="topbar">

<div class="logo">

CUSTOMER PANEL

</div>

<div class="menu">

<a href="dashboard.php">

Dashboard

</a>

<a href="riwayat.php">

Riwayat Booking

</a>

<a href="../logout.php">

Logout

</a>

</div>

</div>

<section class="hero">

<h1>

Selamat Datang,
<?= htmlspecialchars(
$_SESSION['nama']
) ?>

</h1>

<p>

Temukan pengalaman wisata
terbaik bersama guide profesional.

</p>

</section>

<div class="container">

<div class="welcome">

<h2>

Halo
<?= htmlspecialchars(
$_SESSION['nama']
) ?>

👋

</h2>

<p>

Silakan pilih destinasi favorit
dan lakukan booking perjalanan Anda.

</p>

</div>

<div class="section-title">

<h2>

Destinasi Wisata

</h2>

<p>

Booking cepat, mudah,
dan langsung masuk sistem.

</p>

</div>

<div class="grid">

<?php

if(
mysqli_num_rows($query)>0
){

while(
$row=mysqli_fetch_assoc($query)
){

?>

<div class="dest-card">

<?php

$gambar =
!empty($row['gambar'])

?

'../uploads/'.$row['gambar']

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
130
) ?>

...

</div>

<a
href="booking.php?id=<?= $row['id'] ?>"
class="btn btn-primary">

BOOKING SEKARANG

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

Dashboard Customer —
Booking Wisata & Guide.

</p>

</footer>

<script
src="../assets/js/script.js">

</script>

</body>
</html>