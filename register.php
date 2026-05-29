<?php

require_once 'config/koneksi.php';
require_once 'config/security.php';

if(
isset($_SESSION['id'])
){

switch(
$_SESSION['role']
){

case 'admin':

redirect(
'admin/dashboard.php'
);

break;

case 'guide':

redirect(
'guide/dashboard.php'
);

break;

case 'customer':

redirect(
'user/dashboard.php'
);

break;

}

}

$error = '';
$success = '';

if(
$_SERVER['REQUEST_METHOD']
=== 'POST'
){

verifyCsrf();

$nama =
e(
$_POST['nama']
?? ''
);

$username =
e(
$_POST['username']
?? ''
);

$password =
$_POST['password']
?? '';

$konfirmasi =
$_POST['konfirmasi']
?? '';

if(

empty($nama)
||
empty($username)
||
empty($password)
||
empty($konfirmasi)

){

$error =
'Semua field wajib diisi.';

}
elseif(

!validatePassword(
$password
)

){

$error =
'Password minimal 8 karakter.';

}
elseif(

$password
!=
$konfirmasi

){

$error =
'Konfirmasi password tidak cocok.';

}
else{

$cek =
mysqli_prepare(

$koneksi,

"SELECT id
FROM users
WHERE username=?
LIMIT 1"

);

mysqli_stmt_bind_param(

$cek,

"s",

$username

);

mysqli_stmt_execute(
$cek
);

$result =
mysqli_stmt_get_result(
$cek
);

if(
mysqli_num_rows(
$result
)>0
){

$error =
'Username sudah digunakan.';

}
else{

$hash =
password_hash(

$password,

PASSWORD_DEFAULT

);

$role =
'customer';

$insert =
mysqli_prepare(

$koneksi,

"INSERT INTO users(

nama,
username,
password,
role

)

VALUES(

?,
?,
?,
?

)"

);

mysqli_stmt_bind_param(

$insert,

"ssss",

$nama,
$username,
$hash,
$role

);

if(
mysqli_stmt_execute(
$insert
)
){

$success =
'Registrasi berhasil. Silakan login.';

}
else{

$error =
'Registrasi gagal.';

}

mysqli_stmt_close(
$insert
);

}

mysqli_stmt_close(
$cek
);

}

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Register</title>

<link
rel="stylesheet"
href="assets/css/style.css">

<style>

body{

font-family:Arial,sans-serif;

background:#eef2f7;

display:flex;

justify-content:center;

align-items:center;

height:100vh;

margin:0;

}

.card{

width:450px;

background:#fff;

padding:40px;

border-radius:24px;

box-shadow:
0 15px 40px rgba(0,0,0,.12);

}

h1{

text-align:center;

color:#1e293b;

margin-bottom:25px;

}

input{

width:100%;

padding:14px;

margin-bottom:18px;

border:1px solid #d1d5db;

border-radius:12px;

outline:none;

box-sizing:border-box;

}

button{

width:100%;

padding:14px;

border:none;

border-radius:12px;

background:#2563eb;

color:#fff;

font-size:16px;

font-weight:bold;

cursor:pointer;

}

button:hover{

background:#1d4ed8;

}

.error{

background:#fee2e2;

color:#991b1b;

padding:14px;

border-radius:12px;

margin-bottom:18px;

text-align:center;

}

.success{

background:#dcfce7;

color:#166534;

padding:14px;

border-radius:12px;

margin-bottom:18px;

text-align:center;

}

.link{

margin-top:20px;

text-align:center;

}

.link a{

text-decoration:none;

color:#2563eb;

font-weight:bold;

}

</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="card">

<h1>

REGISTER SYSTEM

</h1>

<?php if($error!=''){ ?>

<?= e($error) ?>

</div>

<?php } ?>

<?php if($success!=''){ ?>

<?= e($success) ?>

</div>

<?php } ?>

<form method="POST">

<input
type="hidden"

name="csrf_token"

value="<?= csrf() ?>">

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

<input
type="password"

name="konfirmasi"

placeholder="Konfirmasi Password"

required>

<button
type="submit">

REGISTER

</button>

</form>

<div class="link">

Sudah punya akun?

<a href="login.php">

Login

</a>

</div>

</div>

<?php if($success!=''){ ?>

<script>

Swal.fire({

icon:'success',

title:'Registrasi Berhasil',

text:'<?= e($success) ?>',

confirmButtonColor:'#22c55e'

});

</script>

<?php } ?>

<?php if($error!=''){ ?>

<script>

Swal.fire({

icon:'error',

title:'Registrasi Gagal',

text:'<?= e($error) ?>',

confirmButtonColor:'#ef4444'

});

</script>

<?php } ?>

</body>
</html>