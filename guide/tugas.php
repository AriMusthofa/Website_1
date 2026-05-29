<?php

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('guide');

$guide_id =
$_SESSION['id'];

$query =
mysqli_query(

$koneksi,

"SELECT

booking.*,

destinasi.nama_destinasi,

destinasi.lokasi,

users.nama AS customer_nama

FROM booking

INNER JOIN destinasi
ON booking.destinasi_id=
destinasi.id

INNER JOIN users
ON booking.customer_id=
users.id

WHERE booking.guide_id='$guide_id'

ORDER BY booking.id DESC"

);

$total_tugas =
mysqli_num_rows($query);

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Tugas Guide

</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<style>

.layout{

display:flex;

min-height:100vh;

background:#f1f5f9;

}

.main-content{

flex:1;

padding:35px;

overflow:auto;

}

.page-title{

font-size:30px;

font-weight:700;

color:#0f172a;

margin-bottom:30px;

}

.cards{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(240px,1fr));

gap:22px;

margin-bottom:35px;

}

.card{

background:#fff;

padding:28px;

border-radius:22px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

}

.card-title{

font-size:15px;

color:#64748b;

margin-bottom:14px;

}

.card-value{

font-size:34px;

font-weight:700;

color:#2563eb;

}

.content-card{

background:white;

padding:30px;

border-radius:24px;

box-shadow:
0 10px 35px rgba(0,0,0,.08);

}

.table-wrap{

overflow-x:auto;

}

table{

width:100%;

border-collapse:collapse;

min-width:1150px;

background:white;

}

thead{

background:
linear-gradient(
135deg,
#0f172a,
#1e3a8a
);

color:white;

}

th{

padding:18px;

text-align:left;

font-size:14px;

font-weight:700;

}

td{

padding:18px;

border-bottom:
1px solid #e5e7eb;

vertical-align:middle;

}

tbody tr:hover{

background:#f8fafc;

}

.badge{

display:inline-block;

padding:8px 14px;

border-radius:999px;

font-size:12px;

font-weight:700;

}

.badge-green{

background:#dcfce7;

color:#166534;

}

.badge-blue{

background:#dbeafe;

color:#1d4ed8;

}

.badge-red{

background:#fee2e2;

color:#dc2626;

}

.badge-yellow{

background:#fef3c7;

color:#92400e;

}

.empty{

text-align:center;

padding:70px 20px;

}

.empty h2{

margin-bottom:14px;

color:#0f172a;

}

.empty p{

color:#64748b;

margin-bottom:25px;

}

.btn{

display:inline-flex;

align-items:center;

justify-content:center;

padding:12px 18px;

border:none;

border-radius:12px;

font-size:14px;

font-weight:700;

text-decoration:none;

cursor:pointer;

transition:.2s;

}

.btn-primary{

background:#2563eb;

color:white;

}

.btn-primary:hover{

background:#1d4ed8;

}

@media(max-width:991px){

.main-content{

padding:22px;

}

.cards{

grid-template-columns:1fr;

}

}

</style>

</head>

<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<div class="main-content">

<h1 class="page-title">

📋 Tugas Guide Saya

</h1>

<div class="cards">

<div class="card">

<div class="card-title">

Total Tugas Guide

</div>

<div class="card-value">

<?= $total_tugas ?>

</div>

</div>

</div>

<div class="content-card">

<h2 style="margin-bottom:25px;">

Riwayat Tugas Guide

</h2>

<?php

if(
mysqli_num_rows($query)>0
){

?>

<div class="table-wrap">

<table>

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Destinasi</th>

<th>Lokasi</th>

<th>Tanggal</th>

<th>Jumlah Orang</th>

<th>Status</th>

<th>Dibuat</th>

</tr>

</thead>

<tbody>

<?php

while(
$row=
mysqli_fetch_assoc($query)
){

?>

<tr>

<td>

#<?= $row['id'] ?>

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

$status =
$row['status'];

if(
$status=="Diterima Guide"
){

echo
"<span class='badge badge-green'>
Diterima Guide
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
$status=="Guide Menolak"
){

echo
"<span class='badge badge-red'>
Guide Menolak
</span>";

}

elseif(
$status=="Selesai"
){

echo
"<span class='badge badge-green'>
Selesai
</span>";

}

else{

echo
"<span class='badge badge-yellow'>"

.$status.

"</span>";

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

</tbody>

</table>

</div>

<?php

}else{

?>

<div class="empty">

<h2>

Belum Ada Tugas

</h2>

<p>

Anda belum memiliki
booking yang ditugaskan.

</p>

<a
href="dashboard.php"
class="btn btn-primary">

Kembali Dashboard

</a>

</div>

<?php

}

?>

</div>

</div>

</body>

</html>