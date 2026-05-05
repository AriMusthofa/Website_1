<form method="POST">
    Username:<input type="text" name="username"><br>
    Password :<input type="password" name="password"><br>
    Nama :<input type="text" name="nama"><br>
    Email :<input type="text" name="email"><br>
    <input type="submit" value="Kirim Data" name = "kirim"><br>
</form>

<?php
include 'koneksi.php';
if (isset($_POST['kirim'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    $query = "INSERT INTO user (username, password, nama, email) VALUES ('$username', '$password', '$nama', '$email')";

    if (mysqli_query($koneksi, $query)) {
        echo "Data berhasil ditambah";
    } else {
        echo "Data gagal ditambahkan";
    }
}
?>