<?php

include 'config/koneksi.php';

$error="";
$sukses="";

if(isset($_POST['register'])){

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

$password =
$_POST['password'];

$cek =
mysqli_query(

$koneksi,

"SELECT *
FROM users
WHERE username='$username'"

);

if(
mysqli_num_rows($cek)>0
){

$error =
"Username sudah digunakan!";

}else{

$hash =
password_hash(
$password,
PASSWORD_DEFAULT
);

mysqli_query(

$koneksi,

"INSERT INTO users
(
nama,
username,
password,
role
)

VALUES
(
'$nama',
'$username',
'$hash',
'customer'
)"

);

$sukses =
"Registrasi berhasil!";

}

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Register Customer</title>

<link rel="stylesheet"
href="assets/css/style.css">

</head>

<body class="login-body">

<div class="login-container">

<div class="login-card">

<h1>

REGISTER CUSTOMER

</h1>

<?php
if($error!=""){
?>

<div class="alert-danger">

<?= $error ?>

</div>

<?php } ?>

<?php
if($sukses!=""){
?>

<div class="alert-success">

<?= $sukses ?>

</div>

<?php } ?>

<form method="POST">

<input
type="text"

name="nama"

placeholder="Nama Lengkap"

required>

<input
type="text"

name="username"

placeholder="Username"

required>

<input
type="password"

name="password"

placeholder="Password"

required>

<button
type="submit"
name="register">

DAFTAR

</button>

</form>

<div class="register-link">

Sudah punya akun?

<a href="login.php">

Login

</a>

</div>

</div>

</div>

</body>
</html>