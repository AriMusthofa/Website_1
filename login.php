<?php
session_start();
include 'config/koneksi.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi,
    "SELECT * FROM users
    WHERE username='$username'
    AND password='$password'");

    $cek = mysqli_num_rows($query);

    if($cek > 0){

        $data = mysqli_fetch_assoc($query);

        $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        if($data['role']=="admin"){
            header("Location: admin/dashboard.php");
        }else{
            header("Location: user/home.php");
        }

    }else{
        $error = "Username atau Password Salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login Sembalun Guide</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{

height:100vh;
display:flex;
justify-content:center;
align-items:center;

background:
linear-gradient(
rgba(0,0,0,0.5),
rgba(0,0,0,0.5)
),

url('https://images.unsplash.com/photo-1506744038136-46273834b3fb')
center/cover;

}

.login-box{

width:400px;
background:white;
padding:35px;
border-radius:15px;
box-shadow:0 10px 30px rgba(0,0,0,0.3);

}

h2{

text-align:center;
margin-bottom:25px;
color:#2c3e50;

}

input{

width:100%;
padding:13px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:8px;

}

button{

width:100%;
padding:13px;
border:none;
border-radius:8px;
background:#27ae60;
color:white;
font-size:16px;
cursor:pointer;

}

button:hover{

background:#219150;

}

.error{

background:#e74c3c;
color:white;
padding:10px;
margin-bottom:15px;
border-radius:8px;
text-align:center;

}

.subtitle{

text-align:center;
margin-bottom:20px;
color:#666;

}

</style>

</head>
<body>

<div class="login-box">

<h2>SEMBALUN GUIDE</h2>

<p class="subtitle">
Login Admin / Konsumen
</p>

<?php
if(isset($error)){
echo "<div class='error'>$error</div>";
}
?>

<form method="POST">

<input
type="text"
name="username"
placeholder="Masukkan Username"
required>

<input
type="password"
name="password"
placeholder="Masukkan Password"
required>

<button
type="submit"
name="login">

LOGIN

</button>

</form>

</div>

</body>
</html>