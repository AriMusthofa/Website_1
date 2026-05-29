<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('admin');

$id =
$_SESSION['id'];

$success='';
$error='';

/* =========================
UPDATE PROFILE
========================= */

if(isset($_POST['update_profile'])){

$nama =
mysqli_real_escape_string(
$koneksi,
trim($_POST['nama'])
);

$username =
mysqli_real_escape_string(
$koneksi,
trim($_POST['username'])
);

$cek =
mysqli_query(

$koneksi,

"SELECT id
FROM users
WHERE username='$username'
AND id!='$id'"

);

if(
mysqli_num_rows($cek)>0
){

$error=
'Username sudah digunakan.';

}else{

mysqli_query(

$koneksi,

"UPDATE users
SET

nama='$nama',
username='$username'

WHERE id='$id'"

);

$_SESSION['nama']=$nama;

$success=
'Profile berhasil diperbarui.';

}

}

/* =========================
UPDATE PASSWORD
========================= */

if(isset($_POST['update_password'])){

$password_lama =
$_POST['password_lama'];

$password_baru =
$_POST['password_baru'];

$konfirmasi =
$_POST['konfirmasi_password'];

$getUser =
mysqli_query(

$koneksi,

"SELECT password
FROM users
WHERE id='$id'"

);

$user =
mysqli_fetch_assoc(
$getUser
);

if(

!password_verify(
$password_lama,
$user['password']
)

){

$error=
'Password lama salah.';

}
elseif(

$password_baru
!=
$konfirmasi

){

$error=
'Konfirmasi password tidak cocok.';

}
else{

$hash=
password_hash(
$password_baru,
PASSWORD_DEFAULT
);

mysqli_query(

$koneksi,

"UPDATE users
SET password='$hash'
WHERE id='$id'"

);

$success=
'Password berhasil diubah.';

}

}

/* =========================
LOAD DATA ADMIN
========================= */

$query =
mysqli_query(

$koneksi,

"SELECT *
FROM users
WHERE id='$id'"

);

$data =
mysqli_fetch_assoc(
$query
);

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Profile Admin</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<style>

/* =========================
LAYOUT
========================= */

.layout{

display:flex;

min-height:100vh;

background:#f1f5f9;

overflow:hidden;

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
CONTAINER
========================= */

.container{

width:100%;

max-width:1200px;

margin:auto;

}

/* =========================
CARD
========================= */

.card{

background:#fff;

padding:32px;

border-radius:24px;

box-shadow:
0 10px 35px rgba(0,0,0,.08);

margin-bottom:35px;

}

.card h2{

font-size:26px;

font-weight:700;

color:#0f172a;

margin-bottom:28px;

}

/* =========================
FORM
========================= */

.form-group{

margin-bottom:22px;

}

.form-group label{

display:block;

font-weight:700;

color:#334155;

margin-bottom:10px;

}

.form-group input{

width:100%;

padding:15px 18px;

border:1px solid #dbe2ea;

border-radius:14px;

outline:none;

font-size:15px;

transition:.2s ease;

}

.form-group input:focus{

border-color:#2563eb;

box-shadow:
0 0 0 4px rgba(37,99,235,.12);

}

/* =========================
BUTTON
========================= */

.btn{

display:inline-flex;

align-items:center;

justify-content:center;

padding:13px 22px;

border:none;

border-radius:12px;

font-size:14px;

font-weight:700;

cursor:pointer;

transition:.25s;

}

.btn-primary{

background:#2563eb;

color:white;

}

.btn-primary:hover{

background:#1d4ed8;

transform:translateY(-2px);

}

/* =========================
ALERT
========================= */

.success{

background:#dcfce7;

color:#166534;

padding:16px 18px;

border-radius:16px;

font-weight:600;

margin-bottom:25px;

border:1px solid #bbf7d0;

}

.error{

background:#fee2e2;

color:#991b1b;

padding:16px 18px;

border-radius:16px;

font-weight:600;

margin-bottom:25px;

border:1px solid #fecaca;

}

/* =========================
RESPONSIVE
========================= */

@media(max-width:991px){

.main-content{

margin-left:0;

width:100%;

padding:22px;

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

<div class="container">

<?php if($success!=''){ ?>

<div class="success">

<?= $success ?>

</div>

<?php } ?>

<?php if($error!=''){ ?>

<div class="error">

<?= $error ?>

</div>

<?php } ?>

<!-- =========================
PROFILE ADMIN
========================= -->

<div class="card">

<h2>

Edit Profile Admin

</h2>

<form method="POST">

<div class="form-group">

<label>

Nama Lengkap

</label>

<input
type="text"
name="nama"
required

value="<?= htmlspecialchars($data['nama']) ?>">

</div>

<div class="form-group">

<label>

Username

</label>

<input
type="text"
name="username"
required

value="<?= htmlspecialchars($data['username']) ?>">

</div>

<button
type="submit"
name="update_profile"
class="btn btn-primary">

UPDATE PROFILE

</button>

</form>

</div>

<!-- =========================
PASSWORD
========================= -->

<div class="card">

<h2>

Ubah Password

</h2>

<form method="POST">

<div class="form-group">

<label>

Password Lama

</label>

<input
type="password"
name="password_lama"
required>

</div>

<div class="form-group">

<label>

Password Baru

</label>

<input
type="password"
name="password_baru"
required>

</div>

<div class="form-group">

<label>

Konfirmasi Password Baru

</label>

<input
type="password"
name="konfirmasi_password"
required>

</div>

<button
type="submit"
name="update_password"
class="btn btn-primary">

UPDATE PASSWORD

</button>

</form>

</div>

</div>

</div>

</div>

</body>

</html>