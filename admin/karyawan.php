<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('admin');

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';

unset($_SESSION['success']);
unset($_SESSION['error']);

/* ======================
DELETE
====================== */

if(isset($_GET['hapus'])){

    verifyCsrf();

    $id=intval($_GET['hapus']);

    $stmt=mysqli_prepare(

        $koneksi,

        "DELETE FROM users
        WHERE id=?
        AND role='guide'"

    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);

    if(mysqli_stmt_affected_rows($stmt)>0){

        $_SESSION['success']=
        'Guide berhasil dihapus.';

    }else{

        $_SESSION['error']=
        'Guide tidak ditemukan.';
    }

    mysqli_stmt_close($stmt);

    header("Location:karyawan.php");
    exit();
}

/* ======================
EDIT MODE
====================== */

$edit=false;

$id_edit='';
$nama='';
$username='';

if(isset($_GET['edit'])){

    $edit=true;

    $id_edit=
    intval($_GET['edit']);

    $stmt=
    mysqli_prepare(

        $koneksi,

        "SELECT
        id,
        nama,
        username

        FROM users

        WHERE id=?
        AND role='guide'
        LIMIT 1"

    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id_edit
    );

    mysqli_stmt_execute($stmt);

    $result=
    mysqli_stmt_get_result($stmt);

    $data_edit=
    mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if(!$data_edit){

        header("Location:karyawan.php");
        exit();
    }

    $nama=
    $data_edit['nama'];

    $username=
    $data_edit['username'];
}

/* ======================
CREATE
====================== */

if(isset($_POST['tambah'])){

    verifyCsrf();

    $nama=
    trim($_POST['nama']);

    $username=
    trim($_POST['username']);

    $password_raw=
    trim($_POST['password']);

    if(
        empty($nama)
        ||
        empty($username)
        ||
        empty($password_raw)
    ){

        $_SESSION['error']=
        'Semua field wajib diisi.';

        header("Location:karyawan.php");
        exit();
    }

    if(!validatePassword($password_raw)){

        $_SESSION['error']=
        'Password minimal 8 karakter.';

        header("Location:karyawan.php");
        exit();
    }

    $cek=
    mysqli_prepare(

        $koneksi,

        "SELECT id
        FROM users
        WHERE username=?"

    );

    mysqli_stmt_bind_param(
        $cek,
        "s",
        $username
    );

    mysqli_stmt_execute($cek);

    $result=
    mysqli_stmt_get_result($cek);

    if(mysqli_num_rows($result)>0){

        $_SESSION['error']=
        'Username sudah digunakan.';

        mysqli_stmt_close($cek);

        header("Location:karyawan.php");
        exit();
    }

    mysqli_stmt_close($cek);

    $password_hash=
    password_hash(
        $password_raw,
        PASSWORD_DEFAULT
    );

    $stmt=
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
        'guide'

        )"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "sss",

        $nama,
        $username,
        $password_hash

    );

    mysqli_stmt_execute($stmt);

    $_SESSION['success']=
    'Guide berhasil ditambahkan.';

    mysqli_stmt_close($stmt);

    header("Location:karyawan.php");
    exit();
}

/* ======================
UPDATE
====================== */

if(isset($_POST['update'])){

    verifyCsrf();

    $id_update=
    intval($_POST['id']);

    $nama=
    trim($_POST['nama']);

    $username=
    trim($_POST['username']);

    $password_input=
    trim($_POST['password']);

    if(empty($nama)||empty($username)){

        $_SESSION['error']=
        'Nama & Username wajib diisi.';

        header("Location:karyawan.php");
        exit();
    }

    if(!empty($password_input)){

        $password_hash=
        password_hash(
            $password_input,
            PASSWORD_DEFAULT
        );

        $stmt=
        mysqli_prepare(

            $koneksi,

            "UPDATE users

            SET

            nama=?,
            username=?,
            password=?

            WHERE id=?
            AND role='guide'"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sssi",

            $nama,
            $username,
            $password_hash,
            $id_update

        );

    }else{

        $stmt=
        mysqli_prepare(

            $koneksi,

            "UPDATE users

            SET

            nama=?,
            username=?

            WHERE id=?
            AND role='guide'"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "ssi",

            $nama,
            $username,
            $id_update

        );
    }

    mysqli_stmt_execute($stmt);

    $_SESSION['success']=
    'Guide berhasil diupdate.';

    mysqli_stmt_close($stmt);

    header("Location:karyawan.php");
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

<title>Kelola Guide</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

*{
box-sizing:border-box;
}

html,body{
max-width:100%;
overflow-x:hidden;
}

.layout{

display:flex;

min-height:100vh;

width:100%;

max-width:100%;

background:#eef2f7;

}

.main-content{

flex:1;

min-width:0;

max-width:100%;

padding:35px;

overflow-x:hidden;

}

.container{

width:100%;

max-width:100%;

}

.card{

background:white;

padding:35px;

border-radius:24px;

box-shadow:
0 10px 35px rgba(0,0,0,.08);

margin-bottom:35px;

max-width:100%;

}

.card h2{

font-size:28px;

margin-bottom:30px;

color:#0f172a;

}

.form-group{

margin-bottom:22px;

}

.form-group label{

display:block;

font-weight:700;

margin-bottom:10px;

}

.form-group input{

width:100%;

padding:15px;

border:1px solid #dbe2ea;

border-radius:14px;

font-size:15px;

outline:none;

}

.form-group input:focus{

border-color:#2563eb;

box-shadow:
0 0 0 4px rgba(37,99,235,.15);

}

.button-group{

display:flex;

gap:12px;

flex-wrap:wrap;

}

.btn{

display:inline-flex;

align-items:center;

justify-content:center;

padding:12px 18px;

border:none;

border-radius:12px;

text-decoration:none;

font-weight:700;

cursor:pointer;

transition:.2s;

}

.btn-primary{

background:#2563eb;
color:white;

}

.btn-primary:hover{

background:#1d4ed8;

}

.btn-warning{

background:#f59e0b;
color:white;

}

.btn-warning:hover{

background:#d97706;

}

.btn-delete{

background:#ef4444;
color:white;

}

.btn-delete:hover{

background:#dc2626;

}

.table-wrapper{

width:100%;

overflow-x:auto;

margin-top:15px;

}

.tbl-guide{

width:100%;

max-width:100%;

border-collapse:collapse;

table-layout:fixed;

}

.tbl-guide th,
.tbl-guide td{

padding:14px 10px;

word-wrap:break-word;

overflow-wrap:break-word;

font-size:14px;

}

/* RESPONSIVE */

@media(max-width:900px){

.main-content{

padding:20px;

}

.card{

padding:22px;

}

.tbl-guide th,
.tbl-guide td{

font-size:12.5px;

padding:10px 6px;

}

.button-group .btn,
.tbl-guide .btn{

padding:9px 12px;

font-size:13px;

}

}

</style>

</head>

<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="container">

<div class="card">

<h2>

<?=

$edit

?

'Edit Guide'

:

'Tambah Guide'

?>

</h2>

<form method="POST">

<input
type="hidden"
name="csrf_token"
value="<?= csrf() ?>">

<?php if($edit){ ?>

<input
type="hidden"
name="id"
value="<?= $id_edit ?>">

<?php } ?>

<div class="form-group">

<label>Nama Guide</label>

<input
type="text"
name="nama"
required
value="<?= e($nama) ?>">

</div>

<div class="form-group">

<label>Username</label>

<input
type="text"
name="username"
required
value="<?= e($username) ?>">

</div>

<div class="form-group">

<label>

Password

<?=

$edit

?

'(Kosongkan jika tidak diubah)'

:

''

?>

</label>

<input
type="password"
name="password"

<?= !$edit ? 'required' : '' ?>

>

</div>

<div class="button-group">

<button
type="submit"
class="btn btn-primary"
name="<?= $edit ? 'update':'tambah' ?>">

<?= $edit ? 'UPDATE GUIDE':'TAMBAH GUIDE' ?>

</button>

<?php if($edit){ ?>

<a
href="karyawan.php"
class="btn btn-warning">

BATAL

</a>

<?php } ?>

</div>

</form>

</div>

<!-- ======================
DATA GUIDE
====================== -->

<div class="card">

<h2>Data Guide / Karyawan</h2>

<div class="table-wrapper">

<table class="tbl-guide">

<thead style="
background:linear-gradient(
135deg,
#1e3a8a,
#2563eb
);
color:white;
">

<tr>

<th style="width:80px;">Nomor</th>

<th>Nama</th>

<th>Username</th>

<th style="width:110px;">Role</th>

<th style="
text-align:center;
width:170px;
">

Aksi

</th>

</tr>

</thead>

<tbody>

<?php

$stmt=
mysqli_prepare(

$koneksi,

"SELECT *

FROM users

WHERE role='guide'

ORDER BY id DESC"

);

mysqli_stmt_execute($stmt);

$query=
mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($query)>0){

$no = 1;

while($row=mysqli_fetch_assoc($query)){

?>

<tr style="
border-bottom:1px solid #e5e7eb;
transition:.2s;
">

<td style="text-align:center;">

<?= $no++ ?>

</td>

<td>

<?= e($row['nama']) ?>

</td>

<td>

<?= e($row['username']) ?>

</td>

<td>

<span style="
display:inline-block;
background:#fee2e2;
color:#b91c1c;
padding:7px 14px;
border-radius:999px;
font-size:13px;
font-weight:700;
white-space:nowrap;
">

<?= ucfirst($row['role']) ?>

</span>

</td>

<td style="
text-align:center;
">

<div style="
display:flex;
justify-content:center;
gap:8px;
flex-wrap:wrap;
">

<a
href="?edit=<?= $row['id'] ?>"
class="btn btn-warning">

Edit

</a>

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
padding:35px;
text-align:center;
">

Belum ada data guide.

</td>

</tr>

<?php

}

mysqli_stmt_close($stmt);

?>

</tbody>

</table>

</div>

</div>

</div>
</div>

<?php if($success!=''){ ?>

<script>

Swal.fire({

icon:'success',

title:'Berhasil',

text:'<?= e($success) ?>',

timer:2000,

showConfirmButton:false

});

</script>

<?php } ?>

<?php if($error!=''){ ?>

<script>

Swal.fire({

icon:'error',

title:'Error',

text:'<?= e($error) ?>'

});

</script>

<?php } ?>

<script>

document
.querySelectorAll('.btn-delete')

.forEach(

(btn)=>{

btn.addEventListener(

'click',

function(e){

e.preventDefault();

const url=this.href;

Swal.fire({

title:'Yakin hapus guide?',

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

if(result.isConfirmed){

window.location=url;

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