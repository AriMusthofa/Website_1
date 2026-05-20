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

$customer_id =
$_SESSION['id'];

$query =
mysqli_query(

$koneksi,

"SELECT

booking.*,

destinasi.nama_destinasi,

destinasi.lokasi,

users.nama AS guide_nama

FROM booking

INNER JOIN destinasi
ON booking.destinasi_id =
destinasi.id

LEFT JOIN users
ON booking.guide_id =
users.id

WHERE booking.customer_id='$customer_id'

ORDER BY booking.id DESC"

);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Riwayat Booking</title>

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

.container{

width:94%;

max-width:1400px;

margin:45px auto;

}

.header{

background:white;

padding:30px;

border-radius:22px;

box-shadow:
0 8px 25px rgba(0,0,0,.08);

margin-bottom:30px;

}

.header h1{

color:#1e293b;

margin-bottom:10px;

}

.header p{

color:#64748b;

}

.table-card{

background:white;

padding:25px;

border-radius:22px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

overflow-x:auto;

}

table{

width:100%;

min-width:1100px;

}

.empty{

text-align:center;

padding:50px;

color:#64748b;

}

.footer{

margin-top:45px;

background:#1e293b;

padding:30px;

color:white;

text-align:center;

}

@media(max-width:768px){

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

<div class="container">

<div class="header">

<h1>

Riwayat Booking Anda

</h1>

<p>

Pantau seluruh booking,
status guide,
dan perkembangan order wisata Anda.

</p>

</div>

<div class="table-card">

<?php

if(
mysqli_num_rows($query)>0
){

?>

<table>

<tr>

<th>ID</th>

<th>Destinasi</th>

<th>Lokasi</th>

<th>Tanggal</th>

<th>Jumlah Orang</th>

<th>Guide</th>

<th>Status</th>

<th>Dibuat</th>

</tr>

<?php

while(
$row=mysqli_fetch_assoc($query)
){

?>

<tr>

<td>

<?= $row['id'] ?>

</td>

<td>

<?= htmlspecialchars(
$row['nama_destinasi']
) ?>

</td>

<td>

<?= htmlspecialchars(
$row['lokasi']
) ?>

</td>

<td>

<?= date(
'd-m-Y',
strtotime(
$row['tanggal']
)
) ?>

</td>

<td>

<?= $row['jumlah_orang'] ?>

Orang

</td>

<td>

<?php

if(
!empty($row['guide_nama'])
){

echo htmlspecialchars(
$row['guide_nama']
);

}else{

echo "-";

}

?>

</td>

<td>

<?php

$status =
$row['status'];

if(
$status=="Menunggu Guide"
){

echo
"<span class='badge badge-yellow'>
Menunggu Guide
</span>";

}

elseif(
$status=="Guide Ditugaskan"
){

echo
"<span class='badge badge-blue'>
Guide Ditugaskan
</span>";

}

elseif(
$status=="Diterima Guide"
){

echo
"<span class='badge badge-green'>
Diterima Guide
</span>";

}

elseif(
$status=="Guide Menolak"
){

echo
"<span class='badge badge-red'>
Guide Menolak
</span>";

}

else{

echo
"<span class='badge badge-green'>
Selesai
</span>";

}

?>

</td>

<td>

<?= date(
'd-m-Y H:i',
strtotime(
$row['created_at']
)
) ?>

</td>

</tr>

<?php

}

?>

</table>

<?php

}else{

?>

<div class="empty">

<h2>

Belum Ada Booking

</h2>

<br>

<p>

Anda belum melakukan booking wisata.

</p>

<br>

<a
href="dashboard.php"
class="btn btn-primary">

Booking Sekarang

</a>

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

Riwayat Booking Customer.

</p>

</footer>

<script
src="../assets/js/script.js">

</script>

</body>
</html>