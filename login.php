<?php

session_start();
include 'config/koneksi.php';

$error = "";

if(isset($_POST['login'])){

$username =
mysqli_real_escape_string(
$koneksi,
$_POST['username']
);

$password = $_POST['password'];

$query =
mysqli_query(

$koneksi,

"SELECT * FROM users
WHERE username='$username'"

);

if(mysqli_num_rows($query)>0){

$data =
mysqli_fetch_assoc($query);

if(
password_verify(
$password,
$data['password']
)
){

$_SESSION['id']=$data['id'];

$_SESSION['nama']=$data['nama'];

$_SESSION['username']=$data['username'];

$_SESSION['role']=$data['role'];

if($data['role']=="admin"){

header(
"Location: admin/dashboard.php"
);

exit();

}

elseif($data['role']=="guide"){

header(
"Location: guide/dashboard.php"
);

exit();

}

elseif($data['role']=="customer"){

header(
"Location: user/dashboard.php"
);

exit();

}

}else{

$error =
"Password salah!";

}

}else{

$error =
"Username tidak ditemukan!";

}

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Login System</title>

<link rel="stylesheet"
href="assets/css/style.css">

</head>

<body class="login-body">

<div class="login-container">

<div class="login-card">

<h1>

LOGIN SISTEM

</h1>

<p>

Admin • Guide • Customer

</p>

<?php
if($error!=""){
?>

<div class="alert-danger">

<?= $error ?>

</div>

<?php } ?>

<form method="POST">

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
name="login">

LOGIN

</button>

</form>

<div class="register-link">

Customer belum punya akun?

<a href="register.php">

Daftar

</a>

</div>

</div>

</div>

</body>
</html>