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
'user/beranda.php'
);

break;

}

}

$error = '';

if(
$_SERVER['REQUEST_METHOD']
=== 'POST'
){

verifyCsrf();

$username =
e(
$_POST['username']
?? ''
);

$password =
$_POST['password']
?? '';

if(

empty(
$username
)

||

empty(
$password
)

){

$error =
'Semua field wajib diisi.';

}
else{

$stmt =
mysqli_prepare(

$koneksi,

"SELECT
id,
nama,
username,
password,
role

FROM users

WHERE username=?

LIMIT 1"

);

mysqli_stmt_bind_param(

$stmt,

"s",

$username

);

mysqli_stmt_execute(
$stmt
);

$result =
mysqli_stmt_get_result(
$stmt
);

if(
mysqli_num_rows(
$result
)>0
){

$user =
mysqli_fetch_assoc(
$result
);

if(

password_verify(

$password,

$user['password']

)

){

session_regenerate_id(
true
);

$_SESSION['id']
=
$user['id'];

$_SESSION['nama']
=
$user['nama'];

$_SESSION['username']
=
$user['username'];

$_SESSION['role']
=
$user['role'];

$_SESSION['LAST_ACTIVITY']
=
time();

switch(
$user['role']
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

$dest = $_SESSION['redirect_after_login']
    ?? 'user/beranda.php';

unset(
    $_SESSION['redirect_after_login']
);

redirect($dest);

break;

default:

$error =
'Role tidak valid.';

}

}
else{

$error =
'Password salah.';

}

}
else{

$error =
'Username tidak ditemukan.';

}

mysqli_stmt_close(
$stmt
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

<title>Login</title>

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

width:420px;

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

color:white;

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

LOGIN SYSTEM

</h1>

<form method="POST">

<input
type="hidden"
name="csrf_token"
value="<?= csrf() ?>">

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
type="submit">

LOGIN

</button>

</form>

<div class="link">

Belum punya akun?

<a href="register.php">

Daftar

</a>

</div>

</div>

<?php if($error!=''){ ?>

<script>

Swal.fire({

icon:'error',

title:'Login Gagal',

text:'<?= e($error) ?>',

confirmButtonColor:'#ef4444'

});

</script>

<?php } ?>

<?php if(isset($_SESSION['register_success'])){ ?>

<script>

Swal.fire({

icon:'success',

title:'Registrasi Berhasil',

text:'<?= e($_SESSION['register_success']) ?>',

confirmButtonColor:'#22c55e'

});

</script>

<?php
unset($_SESSION['register_success']);
} ?>

</body>
</html>