<?php
function sapa()
{
    echo "Hallo World";
}
sapa();


function tambah(int $a, int $b)
{
    return $a + $b;
}

function kali(int $a, int $b)
{
    return $a * $b;
}
?>


<form method ="POST">
    Masukkan Angka : <input type="number" name="a"><br>
    Masukkan Angka : <input type="number" name="b"><br>
    <input type="submit" value="kirim">
</form>

<?php
if (isset($_POST['a']) && ($_POST['b'])) {
    $angka1 = $_POST['a'];
    $angka2 = $_POST['b'];
    echo tambah($angka1, $angka2);
    echo"<br>";
    echo kali($angka1, $angka2);
}
?>

<?php
function login($username, $password)
{
    $userBenar = "admin";
    $passBenar = "1234";

    if ($username == $userBenar && $password == $passBenar) {
        return "Login berhasil";
    } else {
        return "Login gagal";
    }
}
?>

<form method="POST">
    Masukan Username:<input type="text" name="username">
    <br>
    Masukan Password :<input type="password" name="password">
    <br>
    <input type="submit" value="Login">
</form>

<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
    $a = $_POST['username'];
    $b = $_POST['password'];

    echo login($a, $b);
}
?>