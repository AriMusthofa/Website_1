<?php

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('admin');

$success = '';
$error   = '';

/*
==================================
AUTO CREATE FOLDER UPLOAD
==================================
*/

$upload_dir = "../upload/";

if (!file_exists($upload_dir)) {
    mkdir($upload_dir,0777,true);
}

/*
==================================
FUNCTION VALIDATE IMAGE
==================================
*/

if(!function_exists('validateImage')){

function validateImage($file){

    $allowed = ['jpg','jpeg','png','webp'];

    $ext = strtolower(
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        )
    );

    if(!in_array($ext,$allowed)){
        return 'Format gambar harus JPG, PNG, atau WEBP.';
    }

    if($file['size'] > 5000000){
        return 'Ukuran gambar maksimal 5MB.';
    }

    return true;
}

}

if(!function_exists('randomFileName')){

function randomFileName($file){

    $ext = strtolower(
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        )
    );

    return uniqid()."_destinasi.".$ext;
}

}

/*
==================================
DELETE
==================================
*/

if(isset($_GET['hapus'])){

verifyCsrf();

$id = intval($_GET['hapus']);

$cek = mysqli_query(
    $koneksi,
    "SELECT image
    FROM destinasi
    WHERE id='$id'"
);

$datahapus = mysqli_fetch_assoc($cek);

if(
    !empty($datahapus['image'])
    &&
    file_exists(
        $upload_dir.$datahapus['image']
    )
){

unlink(
    $upload_dir.$datahapus['image']
);

}

mysqli_query(
    $koneksi,
    "DELETE FROM destinasi
    WHERE id='$id'"
);

header("Location: destinasi.php");
exit();

}

/*
==================================
EDIT MODE
==================================
*/

$edit = false;

$id_edit='';

$name='';

$altitude='';

$difficulty='';

$diff_key='';

$duration='';

$dur_key='';

$price='';

$price_num='';

$image='';

$popular=0;

if(isset($_GET['edit'])){

$edit=true;

$id_edit = intval($_GET['edit']);

$q_edit = mysqli_query(
    $koneksi,
    "SELECT *
    FROM destinasi
    WHERE id='$id_edit'"
);

$data_edit = mysqli_fetch_assoc($q_edit);

if($data_edit){

$name       = $data_edit['name'];
$altitude   = $data_edit['altitude'];
$difficulty = $data_edit['difficulty'];
$diff_key   = $data_edit['diff_key'];
$duration   = $data_edit['duration'];
$dur_key    = $data_edit['dur_key'];
$price      = $data_edit['price'];
$price_num  = $data_edit['price_num'];
$image      = $data_edit['image'];
$popular    = $data_edit['popular'];

}

}

/*
==================================
INSERT
==================================
*/

if(isset($_POST['tambah'])){

verifyCsrf();

$name       = e($_POST['name']);
$altitude   = e($_POST['altitude']);
$difficulty = e($_POST['difficulty']);
$diff_key   = e($_POST['diff_key']);
$duration   = e($_POST['duration']);
$dur_key    = e($_POST['dur_key']);
$price      = e($_POST['price']);
$price_num  = intval($_POST['price_num']);

$popular =
isset($_POST['popular'])
?
1
:
0;

$image='';

if(
isset($_FILES['image'])
&&
$_FILES['image']['error']==0
){

$validasi =
validateImage(
$_FILES['image']
);

if($validasi!==true){

$error=$validasi;

}else{

$image=
randomFileName(
$_FILES['image']
);

move_uploaded_file(
$_FILES['image']['tmp_name'],
$upload_dir.$image
);

}

}

if(empty($error)){

$stmt = mysqli_prepare(

$koneksi,

"INSERT INTO destinasi(

name,
altitude,
difficulty,
diff_key,
duration,
dur_key,
price,
price_num,
image,
popular

)

VALUES(

?,
?,
?,
?,
?,
?,
?,
?,
?,
?

)"

);

mysqli_stmt_bind_param(

$stmt,

"sssssssisi",

$name,
$altitude,
$difficulty,
$diff_key,
$duration,
$dur_key,
$price,
$price_num,
$image,
$popular

);

if(
mysqli_stmt_execute($stmt)
){

$success='Destinasi berhasil ditambahkan.';

}else{

$error='Gagal menambahkan destinasi.';

}

mysqli_stmt_close($stmt);

}

}

/* ==========================
UPDATE
========================== */

if(isset($_POST['update'])){

    verifyCsrf();

    $id_update = intval($_POST['id']);

    $name        = e($_POST['name']);
    $altitude    = e($_POST['altitude']);
    $difficulty  = e($_POST['difficulty']);
    $diff_key    = e($_POST['diff_key']);
    $duration    = e($_POST['duration']);
    $dur_key     = e($_POST['dur_key']);
    $price       = e($_POST['price']);
    $price_num   = intval($_POST['price_num']);
    $popular     = isset($_POST['popular']) ? 1 : 0;

    $gambar_old  = $_POST['gambar_lama'];
    $gambar_final = $gambar_old;

    if(isset($_FILES['image']) && $_FILES['image']['error']==0){

        $validasi = validateImage($_FILES['image']);

        if($validasi!==true){

            $error = $validasi;

        }else{

            $gambar_baru = randomFileName($_FILES['image']);

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "../upload/".$gambar_baru
            );

            if(
                !empty($gambar_old)
                &&
                file_exists("../upload/".$gambar_old)
            ){
                unlink("../upload/".$gambar_old);
            }

            $gambar_final = $gambar_baru;
        }
    }

    if(empty($error)){

        $stmt = mysqli_prepare(

            $koneksi,

            "UPDATE destinasi SET

            name=?,
            altitude=?,
            difficulty=?,
            diff_key=?,
            duration=?,
            dur_key=?,
            price=?,
            price_num=?,
            image=?,
            popular=?

            WHERE id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sssssssdsii",

            $name,
            $altitude,
            $difficulty,
            $diff_key,
            $duration,
            $dur_key,
            $price,
            $price_num,
            $gambar_final,
            $popular,
            $id_update

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        header("Location: destinasi.php");

        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Kelola Destinasi</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

</head>

<body>

<div class="layout">

<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="container">

<div class="card">

<h2>

<?= $edit ? 'EDIT DESTINASI' : 'TAMBAH DESTINASI' ?>

</h2>

<form method="POST" enctype="multipart/form-data">

<input
type="hidden"
name="csrf_token"
value="<?= csrf() ?>">

<?php if($edit){ ?>

<input
type="hidden"
name="id"
value="<?= $id_edit ?>">

<input
type="hidden"
name="gambar_lama"
value="<?= $gambar_lama ?>">

<?php } ?>

<div class="form-grid">

<div class="form-group">

<label>Nama Destinasi</label>

<input
type="text"
name="name"
required
value="<?= htmlspecialchars($name) ?>">

</div>

<div class="form-group">

<label>Altitude</label>

<input
type="text"
name="altitude"
placeholder="3.726 mdpl"
required
value="<?= htmlspecialchars($altitude) ?>">

</div>

<div class="form-group">

<label>Difficulty</label>

<select name="difficulty">

<option value="Mudah">Mudah</option>

<option value="Menengah">Menengah</option>

<option value="Sulit">Sulit</option>

</select>

</div>

<div class="form-group">

<label>Diff Key</label>

<input
type="text"
name="diff_key"
placeholder="mudah / menengah / sulit"
required
value="<?= htmlspecialchars($diff_key) ?>">

</div>

<div class="form-group">

<label>Duration</label>

<input
type="text"
name="duration"
placeholder="2 - 3 Hari"
required
value="<?= htmlspecialchars($duration) ?>">

</div>

<div class="form-group">

<label>Dur Key</label>

<input
type="text"
name="dur_key"
placeholder="2-3-hari"
required
value="<?= htmlspecialchars($dur_key) ?>">

</div>

<div class="form-group">

<label>Price</label>

<input
type="text"
name="price"
placeholder="Mulai Rp 800.000"
required
value="<?= htmlspecialchars($price) ?>">

</div>

<div class="form-group">

<label>Price Number</label>

<input
type="number"
name="price_num"
required
value="<?= htmlspecialchars($price_num) ?>">

</div>

<div class="form-group full-width">

<label>Upload Gambar</label>

<label class="upload-box">

<div class="upload-icon">📷</div>

<div class="upload-text">

Klik untuk upload gambar destinasi

</div>

<input
type="file"
name="image"
accept="image/*"
onchange="previewImage(event)">

</label>

<div class="preview-wrapper">

<img

id="preview"

src="<?=
(!empty($image_lama))
?
'../uploads/'.$image_lama
:
'https://placehold.co/600x400?text=Preview'
?>">

</div>

</div>

<div class="full-width checkbox-box">

<input
type="checkbox"
name="popular"
value="1"
<?= (!empty($popular)) ? 'checked' : '' ?>>

<label>Jadikan destinasi populer</label>

</div>

</div>

<button
class="btn-primary"
type="submit"
name="<?= $edit ? 'update' : 'tambah' ?>">

<?=

$edit

?

'UPDATE DESTINASI'

:

'TAMBAH DESTINASI'

?>

</button>

</form>

</div>

<div class="card" style="margin-top:40px;">

<h2>DATA DESTINASI</h2>

<table border="1" cellpadding="10">

<tr>

<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Altitude</th>
<th>Difficulty</th>
<th>Duration</th>
<th>Price</th>
<th>Popular</th>
<th>Aksi</th>

</tr>

<?php

$query = mysqli_query(

$koneksi,

"SELECT * FROM destinasi
ORDER BY id DESC"

);

while($row = mysqli_fetch_assoc($query)){

?>

<tr>

<td>

<?= $row['id'] ?>

</td>

<td>

<?php
if(!empty($row['image'])){
?>

<img
src="../uploads/<?= $row['image'] ?>"
style="
width:80px;
height:80px;
object-fit:cover;
border-radius:10px;
">
<style>

:root{
--primary:#2563eb;
--primary2:#1d4ed8;
--success:#16a34a;
--danger:#dc2626;
--bg:#f1f5f9;
--card:#ffffff;
--border:#e2e8f0;
--text:#0f172a;
--muted:#64748b;
}

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI,sans-serif;
}

body{
background:var(--bg);
}

/* MAIN */

.container{
max-width:1450px;
margin:auto;
}

/* CARD */

.card{

background:#fff;

border-radius:24px;

padding:35px;

box-shadow:
0 15px 40px rgba(15,23,42,.08);

border:1px solid rgba(226,232,240,.8);

margin-bottom:35px;

}

/* TITLE */

.card h2{

font-size:30px;

font-weight:700;

color:var(--text);

margin-bottom:30px;

}

/* FORM GRID */

.form-grid{

display:grid;

grid-template-columns:
repeat(2,1fr);

gap:22px;

}

/* FULL WIDTH */

.full-width{

grid-column:1 / -1;

}

/* GROUP */

.form-group{

display:flex;

flex-direction:column;

}

/* LABEL */

.form-group label{

font-size:14px;

font-weight:700;

color:#334155;

margin-bottom:10px;

}

/* INPUT */

.form-group input,
.form-group select,
.form-group textarea{

width:100%;

padding:16px 18px;

border-radius:16px;

border:1px solid var(--border);

background:#f8fafc;

font-size:15px;

transition:.25s ease;

outline:none;

}

/* FOCUS */

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus{

border-color:var(--primary);

background:white;

box-shadow:
0 0 0 4px rgba(37,99,235,.12);

}

/* TEXTAREA */

textarea{

resize:none;

min-height:140px;

}

/* FILE BOX */

.upload-box{

border:2px dashed #cbd5e1;

background:#f8fafc;

padding:28px;

border-radius:22px;

text-align:center;

cursor:pointer;

transition:.25s;

}

.upload-box:hover{

border-color:var(--primary);

background:#eff6ff;

}

.upload-box input{

display:none;

}

.upload-icon{

font-size:48px;

margin-bottom:10px;

}

.upload-text{

font-weight:600;

color:#475569;

}

/* PREVIEW */

.preview-wrapper{

margin-top:20px;

display:flex;

justify-content:center;

}

.preview-wrapper img{

width:200px;

height:150px;

border-radius:18px;

object-fit:cover;

border:1px solid #e2e8f0;

box-shadow:
0 10px 25px rgba(0,0,0,.08);

}

/* CHECKBOX */

.checkbox-box{

display:flex;

align-items:center;

gap:12px;

padding-top:18px;

}

.checkbox-box input{

width:20px;

height:20px;

cursor:pointer;

}

/* BUTTON */

.btn-primary{

margin-top:28px;

border:none;

background:
linear-gradient(
135deg,
#2563eb,
#1d4ed8
);

color:white;

padding:16px 28px;

border-radius:18px;

font-size:15px;

font-weight:700;

cursor:pointer;

transition:.25s ease;

box-shadow:
0 10px 25px rgba(37,99,235,.25);

}

.btn-primary:hover{

transform:translateY(-3px);

box-shadow:
0 18px 35px rgba(37,99,235,.35);

}

/* RESPONSIVE */

@media(max-width:900px){

.form-grid{

grid-template-columns:1fr;

}

.card{

padding:24px;

}

}

</style>
<?php
}else{
echo "-";
}
?>

</td>

<td>

<?= htmlspecialchars($row['name']) ?>

</td>

<td>

<?= htmlspecialchars($row['altitude']) ?>

</td>

<td>

<?= htmlspecialchars($row['difficulty']) ?>

</td>

<td>

<?= htmlspecialchars($row['duration']) ?>

</td>

<td>

<?= htmlspecialchars($row['price']) ?>

</td>

<td>

<?= $row['popular'] ? 'YA' : 'TIDAK' ?>

</td>

<td>

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

</td>

</tr>

<?php } ?>

</table>

</div>

</div>
</div>
</div>
<script>

function previewImage(event){

const file =
event.target.files[0];

if(file){

const reader =
new FileReader();

reader.onload =
function(e){

document
.getElementById('preview')
.src =
e.target.result;

};

reader.readAsDataURL(file);

}

}

</script>

</body>
</html>