<?php

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('admin');

/* =========================
STATISTICS
========================= */

$q_destinasi =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total
FROM destinasi"

);

$total_destinasi =
mysqli_fetch_assoc(
$q_destinasi
)['total'];



$q_booking =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total
FROM booking"

);

$total_booking =
mysqli_fetch_assoc(
$q_booking
)['total'];



$q_guide =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total
FROM users
WHERE role='guide'"

);

$total_guide =
mysqli_fetch_assoc(
$q_guide
)['total'];



$q_customer =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total
FROM users
WHERE role='customer'"

);

$total_customer =
mysqli_fetch_assoc(
$q_customer
)['total'];

/* =========================
BOOKING TERBARU
========================= */

$q_booking_latest =
mysqli_query(

$koneksi,

"SELECT

booking.id,

booking.nama_customer,

booking.status,

destinasi.name AS nama_destinasi

FROM booking

LEFT JOIN destinasi

ON

booking.destinasi_id

=

destinasi.id

ORDER BY booking.id DESC

LIMIT 5"

);

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1.0">

<title>

Dashboard Admin

</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<style>

*{
box-sizing:border-box;
}

html,body{
max-width:100%;
overflow-x:hidden;
}

.layout{
display:flex;
min-height:100vh;
width:100%;
max-width:100%;
}

.main-content{
flex:1;
min-width:0;
max-width:100%;
padding:30px;
overflow-x:hidden;
}

.page-title{
font-size:26px;
font-weight:700;
color:#0f172a;
margin-bottom:24px;
}

/* CARDS */

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(190px,1fr));
gap:18px;
margin-bottom:28px;
width:100%;
}

.card{
background:#fff;
border-radius:16px;
padding:22px 24px;
box-shadow:0 8px 24px rgba(15,23,42,.06);
border:1px solid #e2e8f0;
min-width:0;
}

.card-title{
font-size:13px;
font-weight:600;
color:#64748b;
margin-bottom:8px;
}

.card-value{
font-size:28px;
font-weight:800;
color:#0f172a;
}

/* LAYOUT GRID */

.layout-grid{
display:grid;
grid-template-columns:2fr 1fr;
gap:22px;
align-items:start;
width:100%;
}

.content-card,
.right-panel{
background:#fff;
border-radius:16px;
padding:24px;
box-shadow:0 8px 24px rgba(15,23,42,.06);
border:1px solid #e2e8f0;
min-width:0;
}

.content-card h2,
.right-panel h3{
font-size:18px;
font-weight:700;
color:#0f172a;
margin-bottom:18px;
}

/* TABLE */

.table-wrap{
width:100%;
overflow-x:auto;
}

table{
width:100%;
border-collapse:collapse;
table-layout:fixed;
}

th,td{
padding:12px 10px;
text-align:left;
font-size:14px;
word-wrap:break-word;
overflow-wrap:break-word;
border-bottom:1px solid #eef1f5;
}

th{
color:#64748b;
font-size:12.5px;
text-transform:uppercase;
letter-spacing:.4px;
}

/* RIGHT PANEL LIST */

.right-panel ul{
list-style:none;
padding:0;
margin:0;
}

.right-panel li{
display:flex;
align-items:center;
gap:10px;
padding:10px 0;
font-size:14px;
color:#334155;
border-bottom:1px solid #f1f5f9;
word-break:break-word;
}

.right-panel li:last-child{
border-bottom:none;
}

/* RESPONSIVE */

@media(max-width:1000px){

.layout-grid{
grid-template-columns:1fr;
}

}

@media(max-width:600px){

.main-content{
padding:18px;
}

.cards{
grid-template-columns:repeat(auto-fit,minmax(140px,1fr));
gap:12px;
}

.card{
padding:16px 18px;
}

.card-value{
font-size:22px;
}

.content-card,
.right-panel{
padding:18px;
}

}

</style>

</head>

<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<div class="main-content">

<h1 class="page-title">Dashboard Admin</h1>

<!-- CARDS -->

<div class="cards">

<div class="card">
<div class="card-title">Total Destinasi</div>
<div class="card-value"><?= $total_destinasi ?></div>
</div>

<div class="card">
<div class="card-title">Total Booking</div>
<div class="card-value"><?= $total_booking ?></div>
</div>

<div class="card">
<div class="card-title">Total Guide</div>
<div class="card-value"><?= $total_guide ?></div>
</div>

<div class="card">
<div class="card-title">Total Customer</div>
<div class="card-value"><?= $total_customer ?></div>
</div>

</div>

<div class="layout-grid">

<!-- LEFT CONTENT -->

<div>

<div class="content-card">

<h2>Booking Terbaru</h2>

<div class="table-wrap">

<table>

<thead>

<tr>
<th>ID</th>
<th>Customer</th>
<th>Destinasi</th>
<th>Status</th>
</tr>

</thead>

<tbody>

<?php

if(
mysqli_num_rows($q_booking_latest) > 0
){

while($row = mysqli_fetch_assoc($q_booking_latest)){

?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= e($row['nama_customer']) ?></td>
<td><?= e($row['nama_destinasi'] ?? '-') ?></td>
<td><?= statusBadge($row['status']) ?></td>
</tr>

<?php

}

} else {

?>

<tr>
<td colspan="4" style="text-align:center;padding:24px;">Belum ada booking.</td>
</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

<!-- RIGHT PANEL -->

<div>

<div class="right-panel">

<h3>Aktivitas Cepat</h3>

<ul>

<li>📅 Booking baru masuk</li>

<li>🏔 Tambah destinasi baru</li>

<li>👨‍💼 Kelola guide</li>

<li>👥 Lihat customer</li>

</ul>

</div>

<div class="right-panel" style="margin-top:22px;">

<h3>Admin Info</h3>

<ul>

<li>👤 <?= e($_SESSION['nama'] ?? '-') ?></li>

<li>🛡 Role: Admin</li>

<li>🕒 <?= date('d M Y') ?></li>

</ul>

</div>

</div>

</div>

</div><!-- /.main-content -->

</div><!-- /.layout -->

</body>

</html>