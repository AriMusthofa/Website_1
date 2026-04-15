<?php

$nama = "Ari";
$umur = 20;
$tinggi = 175.9;
$hobi = ["main Game", "futsal", "Membaca"];

echo "Nama saya $nama, Umur saya $umur, Tinggi saya $tinggi, Hobi saya $hobi[0]";


echo "<br><br>====================================================<br><br>";
//operator dan kondisi (if else)

$angka1 = 10;
$angka2 = 20;
$hasil = $angka1 + $angka2;
$hasil2= $angka1 - $angka2;
$hasil3= $angka1 * $angka2;
$hasil4= $angka1 / $angka2;

echo "hasil dari $angka1 ditambah $angka2 adalah $hasil<br>";
echo "hasil dari $angka1 dikurang $angka2 adalah $hasil2<br>";
echo "hasil dari $angka1 dikali $angka2 adalah $hasil3<br>";
echo "hasil dari $angka1 dibagi $angka2 adalah $hasil4<br>";

echo "<br><br>====================================================<br><br>";
//pengkondisian

$nilaii = 90;

if($nilaii >= 90){
    echo "Nilai anda A";
}
else if ($nilaii >= 80){
    echo "Nilai anda B";
}
else if ($nilaii >= 70){
    echo "Nilai anda C";
}
else {
    echo "Nilai anda D";
}

echo "<br><br>====================================================<br><br>";

$nilaiii = 99;

if ($nilaiii % 2 == 0){
    echo "Nilai Genap";
}
else {echo "Nilai Ganjil";
}
?>