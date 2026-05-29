<?php

session_start();
include '../config/koneksi.php';

if(
!isset($_SESSION['role'])
||
$_SESSION['role']!='guide'
){
header("Location: ../login.php");
exit();
}

$id =
$_SESSION['id'];

$success="";
$error="";

/* =====================
UPDATE PROFILE
===================== */

if(
isset($_POST['update_profile'])
){

$nama =
mysqli_real_escape_string(
$koneksi,
$_POST['nama']
);

$username =
mysqli_real_escape_string(
$koneksi,
$_POST['username']
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

$error =
"Username sudah digunakan.";

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

$success =
"Profile berhasil diperbarui.";

}

}

/* =====================
UPDATE PASSWORD
===================== */

if(
isset($_POST['update_password'])
){

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

$error =
"Password lama salah.";

}
elseif(
$password_baru
!=
$konfirmasi
){

$error =
"Konfirmasi password tidak cocok.";

}
else{

$hash =
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

$success =
"Password berhasil diubah.";

}

}

/* =====================
LOAD GUIDE
===================== */

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

<title>Profile Guide</title>

<link
rel="stylesheet"
href="../assets/css/style.css">

<style>

body{

background:#eef2f7;
font-family:Arial,sans-serif;

}

.topbar{

background:#0f172a;

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

background:#1e293b;

}

.container{

width:92%;

margin:35px auto;

}

.card{

background:white;

padding:35px;

border-radius:22px;

box-shadow:
0 10px 30px rgba(0,0,0,.08);

margin-bottom:30px;

}

.card h2{

margin-bottom:25px;

color:#1e293b;

}

input{

width:100%;

padding:14px;

margin-bottom:18px;

border:1px solid #d1d5db;

border-radius:12px;

outline:none;

}

button{

padding:14px 22px;

background:#2563eb;

color:white;

border:none;

border-radius:12px;

cursor:pointer;

font-weight:bold;

}

button:hover{

background:#1d4ed8;

}

.success{

background:#dcfce7;

color:#166534;

padding:14px;

border-radius:12px;

margin-bottom:20px;

}

.error{

background:#fee2e2;

color:#991b1b;

padding:14px;

border-radius:12px;

margin-bottom:20px;

}

</style>

</head>

<body>

<div class="topbar">

<div class="logo">

GUIDE PANEL

</div>

<div class="menu">

<a href="dashboard.php">Dashboard</a>

<a href="notifikasi.php">Notifikasi</a>

<a href="tugas.php">Tugas</a>

<a href="profile.php">Profile</a>

<a href="../logout.php">Logout</a>

</div>

</div>

<div class="container">

<?php if($success!=""){ ?>

<div class="success">

<?= $success ?>

</div>

<?php } ?>

<?php if($error!=""){ ?>

<div class="error">

<?= $error ?>

</div>

<?php } ?>

<div class="card">

<h2>

Edit Profile Guide

</h2>

<form method="POST">

<input
type="text"

name="nama"

value="<?= htmlspecialchars(
$data['nama']
) ?>"

required>

<input
type="text"

name="username"

value="<?= htmlspecialchars(
$data['username']
) ?>"

required>

<button
type="submit"

name="update_profile">

Update Profile

</button>

</form>

</div>

<div class="card">

<h2>

Ubah Password

</h2>

<form method="POST">

<input
type="password"

name="password_lama"

placeholder="Password Lama"

required>

<input
type="password"

name="password_baru"

placeholder="Password Baru"

required>

<input
type="password"

name="konfirmasi_password"

placeholder="Konfirmasi Password Baru"

required>

<button
type="submit"

name="update_password">

Update Password

</button>

</form>

</div>

</div>

</body>
</html>