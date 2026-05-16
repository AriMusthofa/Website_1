<?php
include 'koneksi.php';

// HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM user WHERE id='$id'");

    header("Location: index.php");
}

// EDIT DATA
$edit = false;

if (isset($_GET['edit'])) {
    $edit = true;

    $id = $_GET['edit'];

    $data = mysqli_query($koneksi, "SELECT * FROM user WHERE id='$id'");

    $rowEdit = mysqli_fetch_assoc($data);
}

// TAMBAH / UPDATE
if (isset($_POST['kirim'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    // TAMBAH
    if ($_POST['id'] == "") {

        mysqli_query($koneksi, "INSERT INTO user(username,password,nama,email)
        VALUES('$username','$password','$nama','$email')");

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
    }

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>CRUD DATA USER</h1>

    <!-- FORM -->
    <div class="card">

        <h2><?= $edit ? 'Edit Data' : 'Tambah Data' ?></h2>

        <form method="POST">

            <input type="hidden" name="id" value="<?= $edit ? $rowEdit['id'] : '' ?>">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username"
                value="<?= $edit ? $rowEdit['username'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                value="<?= $edit ? $rowEdit['password'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama"
                value="<?= $edit ? $rowEdit['nama'] : '' ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                value="<?= $edit ? $rowEdit['email'] : '' ?>" required>
            </div>

            <button type="submit" name="kirim" class="btn">
                <?= $edit ? 'Update Data' : 'Tambah Data' ?>
            </button>

        </form>
    </div>

    <!-- TABEL -->
    <div class="card">

        <h2>Data User</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>

            <?php
            $query = mysqli_query($koneksi, "SELECT * FROM user");

            while($row = mysqli_fetch_assoc($query)) {
            ?>

            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['username']; ?></td>
                <td><?= $row['password']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['email']; ?></td>
                <td>
                    <a class="edit"
                    href="index.php?edit=<?= $row['id']; ?>">
                    Edit
                    </a>

                    <a class="hapus"
                    href="index.php?hapus=<?= $row['id']; ?>"
                    onclick="return confirm('Yakin hapus data?')">
                    Hapus
                    </a>
                </td>
            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>