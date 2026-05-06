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

<table border="1" cellpading="10" cellspacing="0">
    <tr>
        <th>Id</th>
        <th>Username</th>
        <th>Password</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>

<?php
$query = "SELECT * FROM user";
$result = mysqli_query($koneksi, $query);
while ($row = mysqli_fetch_assoc ($result)) {
    echo "<tr>";
    echo "<td>" . $row['Id'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['password'] . "</td>";
    echo "<td>" . $row['nama'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td><a href='materi4.php?id=" . $row['Id'] . "'Edit</a> | <a href='materi4.php?id?=" .$row['id'] . "'>Hapus</a> </td>";
    echo "</tr>";
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM user WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        echo "Data berhasil dihapus";
    } else {
        echo "Data gagal dihapus";
    }
}

$edit = false;
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $data = mysqli_query($koneksi, "SELECT * FROM user WHERE id='$id'");
    $rowEdit = mysqli_fetch_assoc($data);
}

// PROSES TAMBAH / UPDATE
if (isset($_POST['kirim'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    if ($_POST['id'] == "") {
        // TAMBAH
        mysqli_query($koneksi, "INSERT INTO user (username, password, nama, email)
        VALUES ('$username','$password','$nama','$email')");
        echo "Data berhasil ditambah";
    } else {
        // UPDATE
        $id = $_POST['id'];
        mysqli_query($koneksi, "UPDATE user SET 
            username='$username',
            password='$password',
            nama='$nama',
            email='$email'
            WHERE id='$id'
        ");
        echo "Data berhasil diupdate";
    }

    header("Location: materi4.php");
}
?>

<!-- FORM -->
<form method="POST">
    <input type="hidden" name="id" value="<?= $edit ? $rowEdit['id'] : '' ?>">

    Username:
    <input type="text" name="username" value="<?= $edit ? $rowEdit['username'] : '' ?>"><br>

    Password:
    <input type="password" name="password" value="<?= $edit ? $rowEdit['password'] : '' ?>"><br>

    Nama:
    <input type="text" name="nama" value="<?= $edit ? $rowEdit['nama'] : '' ?>"><br>

    Email:
    <input type="text" name="email" value="<?= $edit ? $rowEdit['email'] : '' ?>"><br>

    <input type="submit" value="<?= $edit ? 'Update' : 'Kirim Data' ?>" name="kirim"><br>
</form>

<br>

<!-- TABEL -->
<table border="1" cellpadding="10" cellspacing="0">
<tr>
    <th>Id</th>
    <th>Username</th>
    <th>Password</th>
    <th>Nama</th>
    <th>Email</th>
    <th>Aksi</th>
</tr>

<?php
$query = "SELECT * FROM user";
$result = mysqli_query($koneksi, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['username']."</td>";
    echo "<td>".$row['password']."</td>";
    echo "<td>".$row['nama']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>
        <a href='materi4.php?edit=".$row['id']."'>Edit</a> |
        <a href='materi4.php?hapus=".$row['id']."' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
    </td>";
    echo "</tr>";
}
?>
</table>