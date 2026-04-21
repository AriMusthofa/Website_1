<form method ="POST">
    Masukkan Angka : <input type = "number" name ="angka"><br>
    <input type="submit" value="kirim">
</form>

<?php
if (isset($_POST['angka'])){
    $data = $_POST['angka'];
    for($i=1; $i<=$data; $i++){
        echo "angka $i <br>";
    }
}
    if ($data % 2 == 0){
        echo "Nilai Genap";
    }
    else {echo "Nilai Ganjil";
    }
?>