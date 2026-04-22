<form method ="POST">
    Masukkan Angka : <input type="number" name="angka"><br>
    <input type="submit" value="kirim">
</form>

<?php
if (isset($_POST['angka'])){
    $data = $_POST['angka'];

    for($i=1; $i<=$data; $i++){
        echo "angka $i <br>";
    }

    // pindahkan ke dalam IF agar tidak error
    if ($data % 2 == 0){
        echo "Nilai Genap<br>";
    }
    else {
        echo "Nilai Ganjil<br>";
    }
}
?>

<br>//Looping While & Do While

<?php
echo "<br> Perulangan While<br>";

if (isset($_POST['angka'])) {
    $data = $_POST['angka'];
    $i = 1;

    while ($i <= $data) {
        echo "Angka $i <br>";
        $i++;
    }

    //Looping DO WHILE
    echo "<br> Perulangan Do While<br>";
    $i = 1;
    do {
        echo "Angka $i <br>";
        $i++;
    } while ($i <= $data);
}
?>