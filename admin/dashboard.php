<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['role'])){
header("Location: ../login.php");
}

if($_SESSION['role']!="admin"){
header("Location: ../login.php");
}

/* TOTAL DATA */

$total_destinasi =
mysqli_num_rows(
mysqli_query(
$koneksi,
"SELECT * FROM destinasi"
)
);

$total_user =
mysqli_num_rows(
mysqli_query(
$koneksi,
"SELECT * FROM users"
)
);

$total_booking =
mysqli_num_rows(
mysqli_query(
$koneksi,
"SELECT * FROM booking"
)
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Dashboard Admin</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#eef2f7;
display:flex;
}

/* SIDEBAR */

.sidebar{

width:260px;

height:100vh;

background:#1e293b;

color:white;

padding:30px 20px;

position:fixed;

left:0;
top:0;

}

.logo{

font-size:24px;

font-weight:bold;

margin-bottom:40px;

text-align:center;

}

.menu a{

display:block;

padding:15px;

margin-bottom:12px;

background:#334155;

border-radius:12px;

text-decoration:none;

color:white;

transition:0.3s;

}

.menu a:hover{

background:#22c55e;

transform:translateX(6px);

}

/* CONTENT */

.content{

margin-left:260px;

width:100%;

padding:35px;

}

.header{

background:white;

padding:25px;

border-radius:18px;

box-shadow:
0 5px 20px rgba(0,0,0,0.08);

margin-bottom:30px;

}

.header h1{

color:#1e293b;

margin-bottom:10px;

}

.header p{

color:#64748b;

}

/* CARD */

.cards{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(240px,1fr));

gap:25px;

margin-bottom:35px;

}

.card{

background:white;

padding:30px;

border-radius:18px;

box-shadow:
0 5px 20px rgba(0,0,0,0.08);

transition:0.3s;

}

.card:hover{

transform:translateY(-5px);

}

.card h3{

color:#64748b;

margin-bottom:10px;

}

.card h2{

color:#1e293b;

font-size:34px;

}

/* QUICK MENU */

.quick{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(260px,1fr));

gap:25px;

}

.quick-card{

background:white;

padding:30px;

border-radius:18px;

box-shadow:
0 5px 20px rgba(0,0,0,0.08);

text-align:center;

transition:0.3s;

}

.quick-card:hover{

transform:translateY(-6px);

}

.quick-card h2{

color:#1e293b;

margin-bottom:15px;

}

.quick-card p{

color:#64748b;

margin-bottom:20px;

}

.btn{

display:inline-block;

background:#22c55e;

color:white;

padding:12px 22px;

border-radius:12px;

text-decoration:none;

}

/* MOBILE */

@media(max-width:900px){

body{
display:block;
}

.sidebar{

width:100%;

height:auto;

position:relative;

}

.content{

margin-left:0;

padding:20px;

}

}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">

ADMIN PANEL

</div>

<div class="menu">

<a href="dashboard.php">

🏠 Dashboard

</a>

<a href="destinasi.php">

📍 Kelola Destinasi

</a>

<a href="user.php">

👤 Kelola User

</a>

<a href="booking.php">

📑 Kelola Booking

</a>

<a href="../logout.php">

🚪 Logout

</a>

</div>

</div>

<div class="content">

<div class="header">

<h1>

Selamat Datang,
<?= $_SESSION['username'] ?>

</h1>

<p>

Dashboard Admin Sistem Booking Wisata

</p>

</div>

<div class="cards">

<div class="card">

<h3>Total Destinasi</h3>

<h2>

<?= $total_destinasi ?>

</h2>

</div>

<div class="card">

<h3>Total User</h3>

<h2>

<?= $total_user ?>

</h2>

</div>

<div class="card">

<h3>Total Booking</h3>

<h2>

<?= $total_booking ?>

</h2>

</div>

</div>

<div class="quick">

<div class="quick-card">

<h2>

📍 Destinasi

</h2>

<p>

Kelola data tempat wisata.

</p>

<a
href="destinasi.php"
class="btn">

Buka Menu

</a>

</div>

<div class="quick-card">

<h2>

👤 User

</h2>

<p>

Kelola akun admin & konsumen.

</p>

<a
href="user.php"
class="btn">

Buka Menu

</a>

</div>

<div class="quick-card">

<h2>

📑 Booking

</h2>

<p>

Kelola seluruh pemesanan.

</p>

<a
href="booking.php"
class="btn">

Buka Menu

</a>

</div>

</div>

</div>

</body>
</html>