<?php

session_start();

if(!isset($_SESSION['role'])){

header("Location: ../login.php");

}

if($_SESSION['role']!="admin"){

header("Location: ../login.php");

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Dashboard Admin</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#f4f6f9;
}

.navbar{

background:#2c3e50;
padding:18px;
display:flex;
justify-content:space-between;
align-items:center;
color:white;

}

.container{

width:90%;
margin:30px auto;

}

.card{

background:white;
padding:25px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);

}

.menu{

display:grid;
grid-template-columns:repeat(2,1fr);
gap:20px;
margin-top:20px;

}

.box{

background:#27ae60;
color:white;
padding:35px;
text-align:center;
border-radius:10px;
font-size:18px;

}

a{

text-decoration:none;

}

.logout{

background:#e74c3c;
padding:10px 18px;
border-radius:8px;
color:white;

}

</style>

</head>
<body>

<div class="navbar">

<h2>Dashboard Admin</h2>

<a
href="../logout.php"
class="logout">

Logout

</a>

</div>

<div class="container">

<div class="card">

<h2>
Selamat Datang,
<?= $_SESSION['username']; ?>
</h2>

<p>
Role :
<?= $_SESSION['role']; ?>
</p>

<div class="menu">

<a href="destinasi.php">

<div class="box">

Kelola Destinasi

</div>

</a>

<a href="#">

<div class="box">

Kelola Booking

</div>

</a>

<a href="user.php">

<div class="box">

Kelola User

</div>

</a>

<a href="#">

<div class="box">

Laporan

</div>

</a>

</div>

</div>

</div>

</body>
</html>