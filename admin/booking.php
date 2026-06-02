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
ASSIGN GUIDE
========================= */

if(isset($_POST['assign'])){

$booking_id = intval($_POST['booking_id'] ?? 0);
$guide_id   = intval($_POST['guide_id']);

$cekguide = mysqli_query(

$koneksi,

"SELECT nama
FROM users
WHERE id='$guide_id'
AND role='guide'"

);

$guide = mysqli_fetch_assoc($cekguide);

if($guide){

mysqli_query(

$koneksi,

"UPDATE booking
SET

guide_id='$guide_id',
status='Menunggu Guide'
WHERE id='$booking_id'"

);

$pesan =
"Booking baru ditugaskan kepada Anda. ID Booking: ".$booking_id;

mysqli_query(

$koneksi,

"INSERT INTO notifikasi(

user_id,
pesan,
status_baca

)

VALUES(

'$guide_id',

'$pesan',

'Belum Dibaca'

)"

);

}

header("Location: booking.php");
exit();

}

/* =========================
LOAD GUIDE
========================= */

$data_guide =
mysqli_query(

$koneksi,

"SELECT *
FROM users
WHERE role='guide'
ORDER BY nama ASC"

);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Kelola Booking</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<style>

select{
padding:12px;
border:1px solid #d1d5db;
border-radius:10px;
outline:none;
min-width:180px;
background:#fff;
}

.status{
padding:8px 14px;
border-radius:20px;
font-size:13px;
font-weight:700;
display:inline-block;
}

.status-blue{
background:#dbeafe;
color:#1d4ed8;
}

.status-green{
background:#dcfce7;
color:#166534;
}

.status-red{
background:#fee2e2;
color:#991b1b;
}

.status-yellow{
background:#fef3c7;
color:#92400e;
}

.table-wrapper{
overflow-x:auto;
}

</style>

</head>

<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="container">

<div class="form-card">

<h2>
Kelola Booking Customer
</h2>

<div class="table-wrapper">

<table class="dashboard-table">

<tr>

<th>ID</th>
<th>Customer</th>
<th>Destinasi</th>
<th>Tanggal</th>
<th>Jumlah</th>
<th>Guide</th>
<th>Status</th>
<th>Assign Guide</th>

</tr>

<?php

$query =
mysqli_query(

$koneksi,

"SELECT

booking.*,

users.nama AS customer_nama,

destinasi.name AS destinasi_nama,

guide.nama AS guide_nama

FROM booking

LEFT JOIN users
ON booking.user_id = users.id

LEFT JOIN destinasi
ON booking.destinasi_id = destinasi.id

LEFT JOIN users AS guide
ON booking.guide_id = guide.id

ORDER BY booking.id DESC"

);

if(
mysqli_num_rows($query)>0
){

while(
$row=
mysqli_fetch_assoc($query)
){

?>

<tr>

<td>
<?= $row['id'] ?>
</td>

<td>
<?= htmlspecialchars($row['customer_nama']) ?>
</td>

<td>
<?= htmlspecialchars($row['destinasi_nama']) ?>
</td>

<td>

<?=

!empty($row['tanggal'])

?

date(
'd-m-Y',
strtotime($row['tanggal'])
)

:

'-'

?>

</td>

<td>
<?= $row['jumlah_orang'] ?> Orang
</td>

<td>

<?=

!empty($row['guide_nama'])

?

htmlspecialchars($row['guide_nama'])

:

'-'

?>

</td>

<td>

<?php

$status =
$row['status'];

if(
$status=="Guide Ditugaskan"
){

echo
"<span class='status status-blue'>
Guide Ditugaskan
</span>";

}
elseif(
$status=="Diterima Guide"
){

echo
"<span class='status status-green'>
Diterima Guide
</span>";

}
elseif(
$status=="Guide Menolak"
){

echo
"<span class='status status-red'>
Guide Menolak
</span>";

}
else{

echo
"<span class='status status-yellow'>
".$status."
</span>";

}

?>

</td>

<td>

<form method="POST">

<input
type="hidden"
name="booking_id"
value="<?= $row['id'] ?>">

<select
name="guide_id"
required>

<option value="">
Pilih Guide
</option>

<?php

mysqli_data_seek(
$data_guide,
0
);

while(
$g=
mysqli_fetch_assoc($data_guide)
){

?>

<option

value="<?= $g['id'] ?>"

<?=

(
$row['guide_id']==$g['id']
)

?

'selected'

:

''

?>

>

<?= htmlspecialchars($g['nama']) ?>

</option>

<?php } ?>

</select>

<button
type="submit"
name="assign"
class="btn btn-primary">

Assign

</button>

</form>

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
padding:35px;
text-align:center;
">

Belum ada booking.

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</div>

</body>

</html>