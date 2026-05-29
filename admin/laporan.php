<?php

session_start();
include '../config/koneksi.php';

if(
!isset($_SESSION['role'])
||
$_SESSION['role']!='admin'
){
header("Location: ../login.php");
exit();
}

/* =========================
FILTER
========================= */

$where = "1=1";

if(
isset($_GET['status'])
&&
$_GET['status']!=''
){

$status =
mysqli_real_escape_string(
$koneksi,
$_GET['status']
);

$where .=
" AND booking.status='$status'";

}

if(
isset($_GET['tanggal_awal'])
&&
isset($_GET['tanggal_akhir'])
&&
$_GET['tanggal_awal']!=''
&&
$_GET['tanggal_akhir']!=''
){

$tanggal_awal =
$_GET['tanggal_awal'];

$tanggal_akhir =
$_GET['tanggal_akhir'];

$where .=
" AND booking.tanggal
BETWEEN
'$tanggal_awal'
AND
'$tanggal_akhir'";

}

/* =========================
STATISTIK
========================= */

$total_booking =
mysqli_fetch_assoc(

mysqli_query(

$koneksi,

"SELECT COUNT(*) AS total
FROM booking"

)

)['total'];

$total_customer =
mysqli_fetch_assoc(

mysqli_query(

$koneksi,

"SELECT COUNT(*) AS total
FROM users
WHERE role='customer'"

)

)['total'];

$total_destinasi =
mysqli_fetch_assoc(

mysqli_query(

$koneksi,

"SELECT COUNT(*) AS total
FROM destinasi"

)

)['total'];

$total_pendapatan =
mysqli_fetch_assoc(

mysqli_query(

$koneksi,

"SELECT
SUM(
booking.jumlah_orang
*
destinasi.harga
) AS total

FROM booking

INNER JOIN destinasi
ON booking.destinasi_id=
destinasi.id"

)

)['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Laporan Booking</title>

<link
rel="stylesheet"
href="../assets/css/style.css">

<style>

body{

background:#eef2f7;
font-family:Arial,sans-serif;

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

gap:15px;

flex-wrap:wrap;

}

.menu a{

color:white;

text-decoration:none;

padding:10px 16px;

border-radius:10px;

}

.menu a:hover{

background:#334155;

}

.container{

width:92%;

margin:35px auto;

}

.stats{

display:grid;

grid-template-columns:
repeat(
auto-fit,
minmax(220px,1fr)
);

gap:20px;

margin-bottom:30px;

}

.box{

background:white;

padding:28px;

border-radius:22px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

}

.box h3{

margin:0;

color:#64748b;

font-size:16px;

}

.box h1{

margin-top:14px;

color:#1e293b;

}

.card{

background:white;

padding:35px;

border-radius:22px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

}

.card h2{

margin-bottom:25px;

color:#1e293b;

}

.filter{

display:flex;

gap:15px;

flex-wrap:wrap;

margin-bottom:25px;

}

.filter input,
.filter select{

padding:12px;

border:1px solid #d1d5db;

border-radius:12px;

outline:none;

}

.filter button{

padding:12px 18px;

border:none;

border-radius:12px;

cursor:pointer;

background:#2563eb;

color:white;

font-weight:bold;

}

.table-wrapper{

overflow-x:auto;

}

table{

width:100%;

border-collapse:collapse;

min-width:1600px;

}

th{

background:#1e293b;

color:white;

padding:14px;

text-align:left;

}

td{

padding:14px;

background:white;

border-bottom:
1px solid #e5e7eb;

}

</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="topbar">

<div class="logo">

ADMIN PANEL

</div>

<div class="menu">

<a href="dashboard.php">Dashboard</a>

<a href="destinasi.php">Destinasi</a>

<a href="karyawan.php">Guide</a>

<a href="customer.php">Customer</a>

<a href="booking.php">Booking</a>

<a href="laporan.php">Laporan</a>

<a href="../logout.php">Logout</a>

</div>

</div>

<div class="container">

<div class="stats">

<div class="box">

<h3>Total Booking</h3>

<h1><?= $total_booking ?></h1>

</div>

<div class="box">

<h3>Total Customer</h3>

<h1><?= $total_customer ?></h1>

</div>

<div class="box">

<h3>Total Destinasi</h3>

<h1><?= $total_destinasi ?></h1>

</div>

<div class="box">

<h3>Total Pendapatan</h3>

<h1>

Rp <?= number_format(
$total_pendapatan ?? 0,
0,
',',
'.'
) ?>

</h1>

</div>

</div>

<div class="card">

<h2>

Laporan Booking

</h2>

<form
method="GET"
class="filter">

<input
type="date"
name="tanggal_awal">

<input
type="date"
name="tanggal_akhir">

<select
name="status">

<option value="">

Semua Status

</option>

<option>

Guide Ditugaskan

</option>

<option>

Diterima Guide

</option>

<option>

Guide Menolak

</option>

<option>

Menunggu Guide

</option>

</select>

<button
type="submit">

Filter

</button>

<button
type="button"
onclick="window.print()">

Print

</button>

</form>

<div class="table-wrapper">

<table>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Destinasi</th>

<th>Guide</th>

<th>Tanggal</th>

<th>Jumlah</th>

<th>Status</th>

<th>Total Harga</th>

</tr>

<?php

$query =
mysqli_query(

$koneksi,

"SELECT

booking.*,

customer.nama AS customer_nama,

guide.nama AS guide_nama,

destinasi.nama_destinasi,

destinasi.harga

FROM booking

INNER JOIN users AS customer
ON booking.customer_id=
customer.id

INNER JOIN destinasi
ON booking.destinasi_id=
destinasi.id

LEFT JOIN users AS guide
ON booking.guide_id=
guide.id

WHERE $where

ORDER BY booking.id DESC"

);

if(
mysqli_num_rows($query)>0
){

while(
$row=
mysqli_fetch_assoc($query)
){

$total_harga =
$row['jumlah_orang']
*
$row['harga'];

?>

<tr>

<td>

<?= $row['id'] ?>

</td>

<td>

<?= htmlspecialchars(
$row['customer_nama']
) ?>

</td>

<td>

<?= htmlspecialchars(
$row['nama_destinasi']
) ?>

</td>

<td>

<?= !empty(
$row['guide_nama']
)

? htmlspecialchars(
$row['guide_nama']
)

: '-'; ?>

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

<?= htmlspecialchars(
$row['status']
) ?>

</td>

<td>

Rp <?= number_format(
$total_harga,
0,
',',
'.'
) ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td
colspan="8"

style="
padding:30px;
text-align:center;
">

Tidak ada data laporan.

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

</div>

</body>
</html>