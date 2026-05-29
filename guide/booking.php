<?php

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('guide');

$guide_id =
$_SESSION['id'];

/* =========================
ACTION TERIMA / TOLAK
========================= */

if(isset($_GET['aksi'])){

$id =
intval($_GET['id']);

$aksi =
$_GET['aksi'];

if(
$aksi=='terima'
){

$status =
'Diterima Guide';

}

elseif(
$aksi=='tolak'
){

$status =
'Guide Menolak';

}

$stmt =
mysqli_prepare(

$koneksi,

"UPDATE booking
SET status=?
WHERE id=?
AND guide_id=?"

);

mysqli_stmt_bind_param(

$stmt,

"sii",

$status,
$id,
$guide_id

);

mysqli_stmt_execute(
$stmt
);

mysqli_stmt_close(
$stmt
);

header(
"Location: booking.php"
);

exit();

}

/* =========================
STATISTIK
========================= */

$q_total =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total

FROM booking

WHERE guide_id='$guide_id'"

);

$total_booking =
mysqli_fetch_assoc(
$q_total
)['total'];

$q_pending =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total

FROM booking

WHERE guide_id='$guide_id'

AND status='Menunggu Guide'"

);

$total_pending =
mysqli_fetch_assoc(
$q_pending
)['total'];

$q_jadwal =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total

FROM booking

WHERE guide_id='$guide_id'

AND(

status='Diterima Guide'

OR

status='Guide Ditugaskan'

)"

);

$total_jadwal =
mysqli_fetch_assoc(
$q_jadwal
)['total'];

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Booking Guide

</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

margin-bottom:30px;

color:#0f172a;

}

.cards{

display:grid;

grid-template-columns:
repeat(auto-fit,minmax(230px,1fr));

gap:22px;

margin-bottom:35px;

}

.card{

background:#fff;

padding:28px;

border-radius:22px;

box-shadow:
0 10px 35px rgba(0,0,0,.08);

}

.card-title{

font-size:15px;

color:#64748b;

margin-bottom:12px;

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

margin-bottom:35px;

}

.table-wrap{

overflow-x:auto;

}

table{

width:100%;

border-collapse:collapse;

min-width:1100px;

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

.badge-blue{

background:#dbeafe;

color:#1d4ed8;

}

.badge-green{

background:#dcfce7;

color:#166534;

}

.badge-red{

background:#fee2e2;

color:#dc2626;

}

.badge-yellow{

background:#fef3c7;

color:#92400e;

}

.btn{

display:inline-flex;

align-items:center;

justify-content:center;

padding:10px 16px;

border:none;

border-radius:12px;

font-size:14px;

font-weight:700;

text-decoration:none;

cursor:pointer;

transition:.2s;

}

.btn-success{

background:#16a34a;

color:white;

}

.btn-success:hover{

background:#15803d;

}

.btn-danger{

background:#dc2626;

color:white;

}

.btn-danger:hover{

background:#b91c1c;

}

.action{

display:flex;

gap:10px;

flex-wrap:wrap;

}

</style>

</head>

<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<div class="main-content">

<h1 class="page-title">

📋 Booking Guide

</h1>

<div class="cards">

<div class="card">

<div class="card-title">

Total Booking

</div>

<div class="card-value">

<?= $total_booking ?>

</div>

</div>

<div class="card">

<div class="card-title">

Menunggu Konfirmasi

</div>

<div class="card-value">

<?= $total_pending ?>

</div>

</div>

<div class="card">

<div class="card-title">

Jadwal Aktif

</div>

<div class="card-value">

<?= $total_jadwal ?>

</div>

</div>

</div>

<div class="content-card">

<h2 style="margin-bottom:25px;">

🔔 Notifikasi Booking Masuk

</h2>

<div class="table-wrap">

<table>

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Destinasi</th>

<th>Tanggal</th>

<th>Status</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php

$q_notif =
mysqli_query(

$koneksi,

"SELECT

booking.*,

users.nama
AS customer_nama,

destinasi.nama_destinasi

FROM booking

LEFT JOIN users
ON booking.customer_id=
users.id

LEFT JOIN destinasi
ON booking.destinasi_id=
destinasi.id

WHERE

booking.guide_id='$guide_id'

AND

booking.status='Menunggu Guide'

ORDER BY booking.id DESC"

);

if(
mysqli_num_rows($q_notif)>0
){

while(
$row=
mysqli_fetch_assoc(
$q_notif
)
){

?>

<tr>

<td>

#<?= $row['id'] ?>

</td>

<td>

<?= e(
$row['customer_nama']
?? '-'
) ?>

</td>

<td>

<?= e(
$row['nama_destinasi']
?? '-'
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

<span class="badge badge-yellow">

<?= e(
$row['status']
) ?>

</span>

</td>

<td>

<div class="action">

<a
href="?aksi=terima&id=<?= $row['id'] ?>"
class="btn btn-success btn-confirm">

Terima

</a>

<a
href="?aksi=tolak&id=<?= $row['id'] ?>"
class="btn btn-danger btn-confirm">

Tolak

</a>

</div>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td
colspan="6"
style="
text-align:center;
padding:35px;
">

Tidak ada booking baru.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

<div class="content-card">

<h2 style="margin-bottom:25px;">

🗓 Jadwal Guide

</h2>

<div class="table-wrap">

<table>

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Destinasi</th>

<th>Tanggal</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$q_jadwal =
mysqli_query(

$koneksi,

"SELECT

booking.*,

users.nama
AS customer_nama,

destinasi.nama_destinasi

FROM booking

LEFT JOIN users
ON booking.customer_id=
users.id

LEFT JOIN destinasi
ON booking.destinasi_id=
destinasi.id

WHERE

booking.guide_id='$guide_id'

AND(

booking.status='Diterima Guide'

OR

booking.status='Guide Ditugaskan'

)

ORDER BY booking.tanggal ASC"

);

if(
mysqli_num_rows(
$q_jadwal
)>0
){

while(
$row=
mysqli_fetch_assoc(
$q_jadwal
)
){

?>

<tr>

<td>

#<?= $row['id'] ?>

</td>

<td>

<?= e(
$row['customer_nama']
?? '-'
) ?>

</td>

<td>

<?= e(
$row['nama_destinasi']
?? '-'
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

<?php

if(
$row['status']
==
'Diterima Guide'
){

?>

<span class="badge badge-green">

Diterima Guide

</span>

<?php

}else{

?>

<span class="badge badge-blue">

Guide Ditugaskan

</span>

<?php

}

?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td
colspan="5"
style="
text-align:center;
padding:35px;
">

Belum ada jadwal aktif.

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

</div>

<script>

document
.querySelectorAll(
'.btn-confirm'
)

.forEach(

(btn)=>{

btn.addEventListener(

'click',

function(e){

e.preventDefault();

const url =
this.href;

Swal.fire({

title:
'Yakin ingin memproses booking?',

icon:
'warning',

showCancelButton:true,

confirmButtonColor:
'#2563eb',

cancelButtonColor:
'#64748b',

confirmButtonText:
'Ya',

cancelButtonText:
'Batal'

})

.then(

(result)=>{

if(
result.isConfirmed
){

window.location =
url;

}

}

);

}

);

}

);

</script>

</body>

</html>