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

"SELECT booking.*, users.nama AS customer_nama,
        booking.nama_customer AS booking_customer_nama,
        destinasi.name AS nama_destinasi

FROM booking

LEFT JOIN users ON booking.user_id = users.id

LEFT JOIN destinasi ON booking.destinasi_id = destinasi.id

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
margin:0;
padding:0;
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
padding:22px 24px !important;
overflow-x:hidden;
}

.page-title{
font-size:24px;
font-weight:700;
color:#0f172a;
margin:0 0 16px 0 !important;
}

/* CARDS */

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(190px,1fr));
gap:16px;
margin:0 0 16px 0 !important;
width:100%;
}

.card{
background:#fff;
border-radius:16px;
padding:18px 22px !important;
box-shadow:0 8px 24px rgba(15,23,42,.06);
border:1px solid #e2e8f0;
min-width:0;
margin:0 !important;
}

.card-title{
font-size:13px;
font-weight:600;
color:#64748b;
margin-bottom:6px !important;
}

.card-value{
font-size:26px;
font-weight:800;
color:#0f172a;
}

/* CONTENT CARD */

.content-card{
background:#fff;
border-radius:16px;
padding:20px 22px !important;
box-shadow:0 8px 24px rgba(15,23,42,.06);
border:1px solid #e2e8f0;
width:100%;
min-width:0;
margin:0 !important;
}

.content-card h2{
font-size:17px;
font-weight:700;
color:#0f172a;
margin:0 0 12px 0 !important;
}

/* TABLE */

.table-wrap{
width:100%;
overflow:hidden;
}

table{
width:100%;
max-width:100%;
border-collapse:collapse;
table-layout:fixed;
}

thead tr{
background:#0f2557;
}

th{
color:#cbd5e1;
font-size:11.5px;
text-transform:uppercase;
letter-spacing:.4px;
text-align:left;
padding:11px 10px !important;
}

th:first-child{ border-radius:10px 0 0 10px; }
th:last-child{ border-radius:0 10px 10px 0; }

td{
padding:11px 10px !important;
text-align:left;
font-size:13.5px;
color:#1e293b;
word-wrap:break-word;
overflow-wrap:break-word;
border-bottom:1px solid #eef1f5;
}

tbody tr:last-child td{
border-bottom:none;
}

.badge-status{
display:inline-flex;
align-items:center;
gap:6px;
padding:6px 12px;
border-radius:20px;
font-size:12.5px;
font-weight:700;
background:#dcfce7;
color:#166534;
white-space:nowrap;
}

.status {
padding: 7px 13px;
border-radius: 20px;
font-size: 12.5px;
font-weight: 700;
display: inline-block;
white-space:nowrap;
}
.status-blue   { background: #dbeafe; color: #1d4ed8; }
.status-green  { background: #dcfce7; color: #166534; }
.status-red    { background: #fee2e2; color: #991b1b; }
.status-yellow { background: #fef3c7; color: #92400e; }

.catatan-cell{
max-width:180px;
white-space:normal;
color:#475569;
font-size:13px;
}

/* RESPONSIVE */

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

.content-card{
padding:18px;
}

th,td{
font-size:11.5px;
padding:8px 6px;
}

.status{
padding:5px 8px;
font-size:11px;
}

.catatan-cell{
max-width:110px;
font-size:11.5px;
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

<!-- PEMESANAN TERBARU -->

<div class="content-card">

<h2>Pemesanan Terbaru</h2>

<div class="table-wrap">

<table>

<thead>

<tr>
<th style="width:42px;text-align:center;">No</th>
<th style="width:15%;">Customer</th>
<th style="width:15%;">Destinasi</th>
<th style="width:11%;">Tanggal</th>
<th style="width:9%;">Jumlah</th>
<th>Catatan</th>
<th style="width:150px;">Status</th>
</tr>

</thead>

<tbody>

<?php

if(
mysqli_num_rows($q_booking_latest) > 0
){

$no = 1;

while($row = mysqli_fetch_assoc($q_booking_latest)){

$tampil_customer = !empty($row['customer_nama'])
    ? $row['customer_nama']
    : (!empty($row['booking_customer_nama']) ? $row['booking_customer_nama'] : '-');

?>

<tr>
<td style="text-align:center;"><?= $no++ ?></td>
<td><?= e($tampil_customer) ?></td>
<td><?= e($row['nama_destinasi'] ?? '-') ?></td>
<td><?= !empty($row['tanggal']) ? date('d-m-Y', strtotime($row['tanggal'])) : '-' ?></td>
<td><?= (int)($row['jumlah_orang'] ?? 0) ?> Orang</td>
<td class="catatan-cell"><?= !empty($row['catatan']) ? e($row['catatan']) : '-' ?></td>
<td><?php
    $status = $row['status'];
    if($status == "Guide Ditugaskan")     echo "<span class='status status-blue'>Guide Ditugaskan</span>";
    elseif($status == "Diterima Guide")   echo "<span class='status status-green'>Diterima Guide</span>";
    elseif($status == "Guide Menolak")    echo "<span class='status status-red'>Guide Menolak</span>";
    else                                  echo "<span class='status status-yellow'>".e($status)."</span>";
?></td>
</tr>

<?php

}

} else {

?>

<tr>
<td colspan="7" style="text-align:center;padding:24px;">Belum ada booking.</td>
</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div><!-- /.main-content -->

</div><!-- /.layout -->

</body>

</html>