<?php

session_start();

if(!isset($_SESSION['role'])){

header("Location: ../login.php");

}

if($_SESSION['role']!="user"){

header("Location: ../login.php");

}

?>

<!DOCTYPE html>
<html>
<head>

<title>SEMBALUN GUIDE</title>

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
padding:18px 40px;

display:flex;
justify-content:space-between;
align-items:center;

color:white;

}

.menu a{

text-decoration:none;
color:white;
margin-left:20px;

}

.hero{

height:85vh;

background:
linear-gradient(
rgba(0,0,0,0.5),
rgba(0,0,0,0.5)
),

url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b')
center/cover;

display:flex;
justify-content:center;
align-items:center;
text-align:center;

color:white;

}

.hero-content h1{

font-size:50px;
margin-bottom:20px;

}

.hero-content p{

font-size:20px;
margin-bottom:25px;

}

.btn{

background:#27ae60;
padding:14px 25px;
border-radius:10px;
color:white;
text-decoration:none;

}

.section{

width:90%;
margin:50px auto;

}

.card-container{

display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;

}

.card{

background:white;
padding:20px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);

}

.footer{

background:#2c3e50;
padding:20px;
margin-top:50px;
text-align:center;
color:white;

}

</style>

</head>
<body>

<div class="navbar">

<h2>SEMBALUN GUIDE</h2>

<div class="menu">

<a href="#">Beranda</a>
<a href="#">Paket</a>
<a href="#">Booking</a>
<a href="#">Profil</a>

<a href="../logout.php">

Logout

</a>

</div>

</div>

<div class="hero">

<div class="hero-content">

<h1>
Explore Sembalun Adventure
</h1>

<p>
Selamat Datang,
<?= $_SESSION['username']; ?>
</p>

<a href="#" class="btn">

Booking Sekarang

</a>

</div>

</div>

<div class="section">

<h2>
Destinasi Pendakian
</h2>

<br>

<div class="card-container">

<div class="card">

<h3>Gunung Rinjani</h3>

<p>
Petualangan pendakian terbaik Lombok.
</p>

</div>

<div class="card">

<h3>Bukit Pergasingan</h3>

<p>
View sunrise dan camping terbaik.
</p>

</div>

<div class="card">

<h3>Bukit Anak Dara</h3>

<p>
Jalur pendakian populer Sembalun.
</p>

</div>

</div>

</div>

<div class="footer">

Copyright © 2026
SEMBALUN GUIDE

</div>

</body>
</html>