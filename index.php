<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/koneksi.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Rinjani Guide</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

html,
body{
    width:100%;
    min-height:100vh;
}

body{
    background:#000;
}

.hero{
    position:relative;
    width:100%;
    min-height:100vh;

    background:
        linear-gradient(
            rgba(0,0,0,.45),
            rgba(0,0,0,.45)
        ),
        url('upload/rinjani2.jpg');

    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;

    display:flex;
    justify-content:center;
    align-items:center;
    text-align:center;

    padding:20px;
}

.content{
    width:100%;
    max-width:900px;

    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;

    color:#fff;

    z-index:2;
}

.logo{
    width:320px;
    max-width:90%;
    margin-bottom:25px;
}

.badge{
    display:inline-block;

    background:rgba(46,164,79,.95);

    color:#fff;

    padding:12px 25px;

    border-radius:50px;

    font-size:16px;

    margin-bottom:30px;
}

.title-1{
    font-size:70px;
    font-weight:700;
    line-height:1.1;
    margin-bottom:8px;
}

.title-2{
    font-size:55px;
    font-weight:600;
    line-height:1.2;
    margin-bottom:25px;
}

.desc{
    max-width:750px;

    font-size:22px;
    line-height:1.8;

    color:#f8fafc;

    margin-bottom:40px;
}

.btn-login{
    display:inline-flex;

    justify-content:center;
    align-items:center;

    min-width:220px;

    text-decoration:none;

    background:#2ea44f;

    color:#fff;

    padding:18px 55px;

    border-radius:12px;

    font-size:20px;

    font-weight:600;

    transition:.3s ease;
}

.btn-login:hover{
    background:#238636;
    transform:translateY(-3px);
}

@media(max-width:768px){

    .logo{
        width:220px;
    }

    .badge{
        font-size:14px;
        padding:10px 20px;
    }

    .title-1{
        font-size:42px;
    }

    .title-2{
        font-size:32px;
    }

    .desc{
        font-size:16px;
        line-height:1.7;
    }

    .btn-login{
        min-width:180px;
        padding:14px 35px;
        font-size:18px;
    }
}

</style>
</head>
<body>

<section class="hero">

    <div class="content">

        <img
            src="upload/logo.png"
            alt="Rinjani Guide"
            class="logo">

        <div class="badge">
            Jelajahi Keindahan Alam Sembalun
        </div>

        <h1 class="title-1">
            Pendakian Aman,
        </h1>

        <h2 class="title-2">
            Pengalaman Tak Terlupakan
        </h2>

        <p class="desc">
            Bersama guide lokal berpengalaman,
            nikmati petualangan terbaik di setiap
            puncak Gunung Rinjani.
        </p>

        <a href="login.php" class="btn-login">
            Login
        </a>

    </div>

</section>

</body>
</html>