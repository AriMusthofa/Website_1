<?php
//Function sederhana
function namaFungsi() {
    echo "Halo World <br>";
}

//Function dengan parameter
function sapa($nama) {
    echo "Halo, $nama <br>";
}

//Function dengan return
function tambah($a, $b) {
    return $a + $b;
}

//Function cek genap / ganjil
function cekGenapGanjil($angka) {
    if ($angka % 2 == 0) {
        return "Genap";
    } else {
        return "Ganjil";
    }
}

// PEMANGGILAN FUNCTION

namaFungsi(); // panggil fungsi sederhana

sapa("Rohmat"); // panggil dengan parameter

$hasil = tambah(5, 3);
echo "Hasil penjumlahan: $hasil <br>";

$nilai = 10;
echo "Angka $nilai adalah " . cekGenapGanjil($nilai);
?>