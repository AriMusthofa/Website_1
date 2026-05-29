<?php

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('guide');

$id_guide = $_SESSION['id'];

/* =========================
TOTAL BOOKING
========================= */

$q_total = mysqli_query(

$koneksi,

"SELECT COUNT(*) AS total
FROM booking
WHERE guide_id='$id_guide'"

);

$total_booking =
mysqli_fetch_assoc($q_total)['total'];

/* =========================
PENDING
========================= */

$q_pending = mysqli_query(

$koneksi,

"SELECT COUNT(*) AS total
FROM booking
WHERE guide_id='$id_guide'
AND status='Menunggu Guide'"

);

$total_pending =
mysqli_fetch_assoc($q_pending)['total'];

/* =========================
DITERIMA
========================= */

$q_diterima = mysqli_query(

$koneksi,

"SELECT COUNT(*) AS total
FROM booking
WHERE guide_id='$id_guide'
AND status='Diterima Guide'"

);

$total_diterima =
mysqli_fetch_assoc($q_diterima)['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Dashboard Guide

</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<link
rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

/* =========================
PAGE HEADER
========================= */

.page-subtitle{

color:#64748b;

margin-top:6px;

font-size:16px;

}

/* =========================
CARD ICON
========================= */

.stat-icon{

font-size:32px;

margin-bottom:18px;

color:#2563eb;

}

/* =========================
GRID CONTENT
========================= */

.dashboard-grid{

display:grid;

grid-template-columns:
2fr 1fr;

gap:28px;

}

/* =========================
RIGHT CARD
========================= */

.side-card{

background:#ffffff;

padding:28px;

border-radius:24px;

box-shadow:
0 10px 30px rgba(0,0,0,.07);

margin-bottom:24px;

}

.side-card h3{

margin-bottom:18px;

color:#0f172a;

}

/* =========================
ACTIVITY
========================= */

.activity-item{

padding:14px 0;

border-bottom:
1px solid #e5e7eb;

color:#475569;

}

.activity-item:last-child{

border-bottom:none;

}

/* =========================
QUICK MENU
========================= */

.quick-menu{

display:flex;

flex-direction:column;

gap:14px;

}

.quick-menu a{

display:flex;

align-items:center;

gap:12px;

padding:14px 18px;

border-radius:14px;

text-decoration:none;

background:#f8fafc;

color:#0f172a;

font-weight:600;

transition:.25s;

}

.quick-menu a:hover{

background:#2563eb;

color:white;

}

/* =========================
STATUS BADGE WRAPPER
========================= */

.status-wrap{

display:inline-block;

}

/* =========================
RESPONSIVE
========================= */

@media(max-width:991px){

.dashboard-grid{

grid-template-columns:1fr;

}

}

</style>

</head>

<body>

<div class="layout">

<!-- SIDEBAR -->

<div class="sidebar">

<div class="sidebar-logo">

GUIDE PANEL

</div>

<ul class="sidebar-menu">

<li>

<a
href="dashboard.php"
class="active">

<span>

<i class="fa-solid fa-chart-line"></i>

Dashboard

</span>

</a>

</li>

<li>

<a href="booking.php">

<span>

<i class="fa-solid fa-calendar-check"></i>

Booking

</span>

<span class="badge">

<?= $total_pending ?>

</span>

</a>

</li>

</ul>

<div class="sidebar-footer">

<a
href="../logout.php"
class="logout-btn">

Logout

</a>

</div>

</div>

<!-- MAIN CONTENT -->

<div class="main-content">

<h1 class="page-title">

Dashboard Guide

</h1>

<div class="page-subtitle">

Selamat datang,

<b><?= e($_SESSION['nama']) ?></b>

</div>

<!-- CARD STATS -->

<div class="cards">

<div class="card">

<div class="stat-icon">

<i class="fa-solid fa-book"></i>

</div>

<div class="card-title">

Total Booking

</div>

<div class="card-value">

<?= $total_booking ?>

</div>

</div>

<div class="card">

<div class="stat-icon">

<i class="fa-solid fa-clock"></i>

</div>

<div class="card-title">

Menunggu Konfirmasi

</div>

<div class="card-value">

<?= $total_pending ?>

</div>

</div>

<div class="card">

<div class="stat-icon">

<i class="fa-solid fa-circle-check"></i>

</div>

<div class="card-title">

Booking Diterima

</div>

<div class="card-value">

<?= $total_diterima ?>

</div>

</div>

</div>

<div class="dashboard-grid">

<!-- LEFT -->

<div class="content-card">

<h2>

Booking Terbaru

</h2>

<div class="table-wrap">

<table>

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$q_booking =
mysqli_query(

$koneksi,

"SELECT *
FROM booking
WHERE guide_id='$id_guide'
ORDER BY id DESC
LIMIT 5"

);

if(
mysqli_num_rows($q_booking)>0
){

while(
$row=
mysqli_fetch_assoc($q_booking)
){

?>

<tr>

<td>

#<?= $row['id'] ?>

</td>

<td>

<?= e(
$row['nama_customer']
?? '-'
) ?>

</td>

<td>

<div class="status-wrap">

<?= statusBadge(
$row['status']
) ?>

</div>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td
colspan="3"

style="
text-align:center;
padding:30px;
">

Belum ada booking.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

<!-- RIGHT PANEL -->

<div>

<div class="side-card">

<h3>

Aktivitas Guide

</h3>

<div class="activity-item">

📌 Booking baru masuk

</div>

<div class="activity-item">

📌 Cek booking pending

</div>

<div class="activity-item">

📌 Update status booking

</div>

</div>

<div class="side-card">

<h3>

Quick Menu

</h3>

<div class="quick-menu">

<a href="booking.php">

<i class="fa-solid fa-calendar-check"></i>

Kelola Booking

</a>

<a href="../logout.php">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>

td{

padding:16px;

border-bottom:
1px solid #e5e7eb;

}

tr:hover{

background:#f8fafc;

}

.activity .item{

padding:14px 0;

border-bottom:
1px solid #e5e7eb;

}

.quick-menu{

display:flex;

flex-direction:column;

gap:14px;

}

.quick-menu a{

background:#2563eb;

color:white;

text-decoration:none;

padding:14px;

border-radius:14px;

text-align:center;

font-weight:bold;

transition:.25s;

}

.quick-menu a:hover{

background:#1d4ed8;

}

@media(max-width:991px){

.content-grid{

grid-template-columns:1fr;

}

.main-content{

margin-left:0;

}

.sidebar{

position:relative;

width:100%;

height:auto;

}

.layout{

flex-direction:column;

}

}

</style>

</head>

<body>

<div class="layout">

<div class="sidebar">

<h2>

GUIDE PANEL

</h2>

<ul>

<li>

<a
href="dashboard.php"
class="active">

Dashboard

</a>

</li>

<li>

<a href="booking.php">

Booking

<span class="badge">

<?= $total_pending ?>

</span>

</a>

</li>

<li>

<a href="tugas.php">

Tugas Saya

</a>

</li>

<li>

<a href="../logout.php">

Logout

</a>

</li>

</ul>

</div>

<div class="main-content">

<div class="topbar">

<div class="page-title">

Dashboard Guide

</div>

<div class="admin-box">

<?= e($_SESSION['nama']) ?>

</div>

</div>

<div class="cards">

<div class="card">

<h3>

Total Booking

</h3>

<div class="number">

<?= $total_booking ?>

</div>

<div class="desc">

Seluruh booking Anda

</div>

</div>

<div class="card">

<h3>

Menunggu Konfirmasi

</h3>

<div class="number">

<?= $total_pending ?>

</div>

<div class="desc">

Booking perlu tindakan

</div>

</div>

<div class="card">

<h3>

Booking Diterima

</h3>

<div class="number">

<?= $total_accepted ?>

</div>

<div class="desc">

Guide accepted

</div>

</div>

</div>

<div class="content-grid">

<div class="table-card">

<h2>

Booking Terbaru

</h2>

<div style="overflow-x:auto;">

<table>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Status</th>

</tr>

<?php

$q_booking =
mysqli_query(

$koneksi,

"SELECT *

FROM booking

WHERE guide_id='$id_guide'

ORDER BY id DESC

LIMIT 5"

);

if(
mysqli_num_rows($q_booking)>0
){

while(
$row=
mysqli_fetch_assoc($q_booking)
){

?>

<tr>

<td>

#<?= $row['id'] ?>

</td>

<td>

<?= e(
$row['nama_customer']
?? '-'
) ?>

</td>

<td>

<?= e(
$row['status']
) ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="3">

Belum ada booking.

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

<div class="right-panel">

<div class="panel-card">

<h2>

Aktivitas Guide

</h2>

<div class="activity">

<div class="item">

📌 Booking baru masuk

</div>

<div class="item">

📌 Periksa booking pending

</div>

<div class="item">

📌 Update status guide

</div>

</div>

</div>

<br>

<div class="panel-card">

<h2>

Quick Menu

</h2>

<div class="quick-menu">

<a href="booking.php">

Kelola Booking

</a>

<a href="tugas.php">

Lihat Tugas

</a>

<a href="../logout.php">

Logout

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>