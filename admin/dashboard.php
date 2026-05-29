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

</head>

<body>

<?php

include 'sidebar.php';

?>

<div class="main-content">

    <h1>Dashboard Admin</h1>

    <!-- cards -->
    <div class="cards">

        <div class="card">
            <h3>Total Destinasi</h3>
            <h2><?= $total_destinasi ?></h2>
        </div>

        <div class="card">
            <h3>Total Booking</h3>
            <h2><?= $total_booking ?></h2>
        </div>

        <div class="card">
            <h3>Total Guide</h3>
            <h2><?= $total_guide ?></h2>
        </div>

        <div class="card">
            <h3>Total Customer</h3>
            <h2><?= $total_customer ?></h2>
        </div>

    </div>

</div>

<h1 class="page-title">

Dashboard Admin

</h1>

<!-- CARDS -->

<div class="cards">

<div class="card">

<div class="card-title">

Total Destinasi

</div>

<div class="card-value">

<?=

$total_destinasi

?>

</div>

</div>



<div class="card">

<div class="card-title">

Total Booking

</div>

<div class="card-value">

<?=

$total_booking

?>

</div>

</div>



<div class="card">

<div class="card-title">

Total Guide

</div>

<div class="card-value">

<?=

$total_guide

?>

</div>

</div>



<div class="card">

<div class="card-title">

Total Customer

</div>

<div class="card-value">

<?=

$total_customer

?>

</div>

</div>

</div>





<div class="layout-grid">

<!-- LEFT CONTENT -->

<div>

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

<th>Destinasi</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$q_booking_latest =
mysqli_query(

$koneksi,

"SELECT

booking.id,

booking.nama_customer,

booking.status,

destinasi.nama_destinasi

FROM booking

LEFT JOIN destinasi

ON

booking.destinasi_id

=

destinasi.id

ORDER BY booking.id DESC

LIMIT 5"

);

if(

mysqli_num_rows(
$q_booking_latest
)
>

0

){

while(

$row=
mysqli_fetch_assoc(
$q_booking_latest
)

){

?>

<tr>

<td>

<?=

$row['id']

?>

</td>

<td>

<?=

e(
$row['nama_customer']
)

?>

</td>

<td>

<?=

e(
$row['nama_destinasi']
)

?>

</td>

<td>

<?=

statusBadge(
$row['status']
)

?>

</td>

</tr>

<?php

}

}
else{

?>

<tr>

<td
colspan="4">

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

</div>





<!-- RIGHT PANEL -->

<div>

<div class="right-panel">

<h3>

Aktivitas Cepat

</h3>

<ul>

<li>

📅

Booking baru masuk

</li>

<li>

🏔

Tambah destinasi baru

</li>

<li>

👨‍💼

Kelola guide

</li>

<li>

👥

Lihat customer

</li>

</ul>

</div>



<div class="right-panel"
style="margin-top:25px;">

<h3>

Admin Info

</h3>

<ul>

<li>

👤

<?=

e(
$_SESSION['nama']
)

?>

</li>

<li>

🛡

Role:

Admin

</li>

<li>

🕒

<?=

date(
'd M Y'
)

?>

</li>

</ul>

</div>

</div>

</div>

</div>

</body>

</html>