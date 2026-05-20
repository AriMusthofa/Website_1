<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_projek";

$koneksi = mysqli_connect(
$dbhost,
$dbuser,
$dbpass,
$dbname
);

if(!$koneksi){

die(
"Koneksi Database Gagal : "
. mysqli_connect_error()
);

}

?>