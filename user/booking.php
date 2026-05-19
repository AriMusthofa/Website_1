<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['role'])){

header("Location: ../login.php");

}

if($_SESSION['role']!="user"){

header("Location: ../login.php");

}

if(isset($_POST['booking'])){

$user=$_SESSION['id'];

$destinasi=$_POST['destinasi'];

$tanggal=$_POST['tanggal'];

$jumlah=$_POST['jumlah'];

mysqli_query($koneksi,

"INSERT INTO booking
(user_id,destinasi_id,tanggal,jumlah_orang)

VALUES
('$user','$destinasi','$tanggal','$jumlah')"

);

header("Location: booking.php");

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Booking</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
}

.container{
width:80%;
margin:40px auto;
}

.card{

background:white;
padding:25px;

border-radius:10px;

box-shadow:0 5px 15px rgba(0,0,0,0.1);

}

input,select{

width:100%;
padding:12px;

margin-bottom:15px;

}

button{

background:#27ae60;
color:white;

border:none;

padding:12px 20px;

border-radius:8px;

cursor:pointer;

}

</style>

</head>
<body>

<div class="container">

<div class="card">

<h2>Booking Pendakian</h2>

<br>

<form method="POST">

<select
name="destinasi"
required>

<option value="">

Pilih Destinasi

</option>

<?php

$data=mysqli_query(
$koneksi,
"SELECT * FROM destinasi"
);

while($d=mysqli_fetch_assoc($data)){

?>

<option
value="<?= $d['id']; ?>">

<?= $d['nama_destinasi']; ?>

</option>

<?php } ?>

</select>

<input
type="date"
name="tanggal"
required>

<input
type="number"
name="jumlah"
placeholder="Jumlah Orang"
required>

<button
type="submit"
name="booking">

BOOKING

</button>

</form>

</div>

</div>

</body>
</html>