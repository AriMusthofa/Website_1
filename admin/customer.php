<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('admin');

$success='';
$error='';

/* =========================
DELETE CUSTOMER
========================= */

if(isset($_GET['hapus'])){

verifyCsrf();

$id =
intval($_GET['hapus']);

$stmt =
mysqli_prepare(

$koneksi,

"DELETE FROM users
WHERE id=?
AND role='customer'"

);

mysqli_stmt_bind_param(
$stmt,
"i",
$id
);

if(
mysqli_stmt_execute($stmt)
){

if(
mysqli_stmt_affected_rows($stmt)>0
){

$success=
'Customer berhasil dihapus.';

}else{

$error=
'Customer tidak ditemukan.';
}

}else{

$error=
'Gagal menghapus customer.';
}

mysqli_stmt_close($stmt);

header(
"Location: customer.php"
);

exit();

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Kelola Customer</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

/* =========================
LAYOUT
========================= */

.layout{

display:flex;

min-height:100vh;

overflow:hidden;

background:#f1f5f9;

}

.main-content{

flex:1;

margin-left:260px;

width:calc(100% - 260px);

padding:28px;

box-sizing:border-box;

overflow-x:hidden;

}

/* =========================
CARD
========================= */

.card{

background:white;

border-radius:24px;

padding:32px;

box-shadow:
0 10px 35px rgba(0,0,0,.08);

width:100%;

max-width:1200px;

margin:auto;

}

/* =========================
TITLE
========================= */

.card h2{

font-size:28px;

font-weight:700;

color:#0f172a;

margin-bottom:28px;

}

/* =========================
TABLE
========================= */

.table-wrapper{

width:100%;

overflow:hidden;

border-radius:18px;

}

table{

width:100%;

border-collapse:collapse;

table-layout:fixed;

}

thead{

background:
linear-gradient(
135deg,
#1e3a8a,
#2563eb
);

color:white;

}

th{

padding:18px;

text-align:left;

font-size:15px;

font-weight:700;

}

td{

padding:18px;

border-bottom:
1px solid #e5e7eb;

vertical-align:middle;

word-break:break-word;

}

tbody tr:hover{

background:#f8fafc;

}

/* =========================
BADGE
========================= */

.badge{

display:inline-block;

padding:8px 14px;

border-radius:999px;

font-size:13px;

font-weight:700;

}

.badge-green{

background:#dcfce7;

color:#166534;

}

/* =========================
BUTTON
========================= */

.btn{

display:inline-flex;

align-items:center;

justify-content:center;

padding:11px 18px;

border:none;

border-radius:12px;

font-size:14px;

font-weight:700;

text-decoration:none;

cursor:pointer;

transition:.25s ease;

}

.btn-delete{

background:#ef4444;

color:white;

box-shadow:
0 4px 12px rgba(239,68,68,.22);

}

.btn-delete:hover{

background:#dc2626;

transform:translateY(-2px);

}

/* =========================
AKSI
========================= */

.aksi-col{

display:flex;

justify-content:center;

align-items:center;

gap:12px;

white-space:nowrap;

}

/* =========================
RESPONSIVE
========================= */

@media(max-width:991px){

.main-content{

margin-left:0;

width:100%;

padding:20px;

}

.card{

padding:24px;

}

}

</style>

</head>

<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="card">

<h2>

Data Customer

</h2>

<div class="table-wrapper">

<table>

<thead>

<tr>

<th style="width:80px;">Nomor</th>

<th>Nama</th>

<th>Username</th>

<th style="width:140px;">Role</th>

<th style="width:180px; text-align:center;">

Aksi

</th>

</tr>

</thead>

<tbody>

<?php

$query =
mysqli_query(

$koneksi,

"SELECT *
FROM users
WHERE role='customer'
ORDER BY id DESC"

);

if(
mysqli_num_rows($query)>0
){

$no = 1;

while(
$row=
mysqli_fetch_assoc($query)
){

?>

<tr>

<td>

<?= $no++ ?>

</td>

<td>

<?= htmlspecialchars(
$row['nama']
) ?>

</td>

<td>

<?= htmlspecialchars(
$row['username']
) ?>

</td>

<td>

<span class="badge badge-green">

<?= ucfirst(
$row['role']
) ?>

</span>

</td>

<td>

<div class="aksi-col">

<a
href="?hapus=<?= $row['id'] ?>&csrf_token=<?= csrf() ?>"
class="btn btn-delete">

Hapus

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
colspan="5"

style="
text-align:center;
padding:35px;
">

Belum ada data customer.

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<script>

document
.querySelectorAll(
'.btn-delete'
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

title:'Yakin hapus customer?',

text:'Data tidak bisa dikembalikan.',

icon:'warning',

showCancelButton:true,

confirmButtonColor:'#dc2626',

cancelButtonColor:'#64748b',

confirmButtonText:'Ya, Hapus',

cancelButtonText:'Batal'

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