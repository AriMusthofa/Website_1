<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

/* ==========================
SESSION SETTINGS
========================== */

ini_set('session.use_only_cookies',1);
ini_set('session.use_strict_mode',1);
ini_set('session.cookie_httponly',1);
ini_set('session.cookie_samesite','Lax');

/* ==========================
START SESSION
========================== */

if(session_status()===PHP_SESSION_NONE){

    session_start();

}

/* ==========================
SESSION TIMEOUT
========================== */

$timeout = 1800;

if(isset($_SESSION['LAST_ACTIVITY'])){

    if(

        time()
        -
        $_SESSION['LAST_ACTIVITY']

        >

        $timeout

    ){

        session_unset();

        session_destroy();

        header(
        "Location: ../login.php?timeout=1"
        );

        exit();
    }
}

$_SESSION['LAST_ACTIVITY']=time();

/* ==========================
SESSION REGENERATE
========================== */

if(!isset($_SESSION['CREATED'])){

    $_SESSION['CREATED']=time();

}
elseif(

    time()
    -
    $_SESSION['CREATED']

    >

    1800

){

    session_regenerate_id(true);

    $_SESSION['CREATED']=time();

}

/* ==========================
HELPERS
========================== */

function e($data){

    return htmlspecialchars(
        trim($data),
        ENT_QUOTES,
        'UTF-8'
    );
}

function redirect($url){

    header("Location: ".$url);

    exit();
}

function requireLogin(){

    if(!isset($_SESSION['id'])){

        redirect('../login.php');
    }
}

function requireRole($role){

    requireLogin();

    if(

        !isset($_SESSION['role'])
        ||

        $_SESSION['role']!=$role

    ){

        session_destroy();

        redirect('../login.php');
    }
}

/* ---------- CSRF ---------- */

if(empty($_SESSION['csrf_token'])){

    $_SESSION['csrf_token']=
    bin2hex(random_bytes(32));

}

function csrf(){

    return $_SESSION['csrf_token'];
}

function verifyCsrf(){

    $token=

    $_POST['csrf_token']

    ??

    $_GET['csrf_token']

    ??

    '';

    if(

        !hash_equals(

            $_SESSION['csrf_token'],

            $token

        )

    ){

        die('CSRF validation failed.');
    }
}

/* ---------- PASSWORD ---------- */

function validatePassword($password){

    return strlen($password)>=8;
}

/* ---------- STATUS BADGE ---------- */

function statusBadge($status){

switch($status){

case 'Menunggu Guide':

return
"<span style='
background:#fef3c7;
color:#92400e;
padding:6px 12px;
border-radius:30px;
font-size:13px;
font-weight:bold;
'>
🟡 Menunggu Guide
</span>";

case 'Guide Ditugaskan':

return
"<span style='
background:#dbeafe;
color:#1e40af;
padding:6px 12px;
border-radius:30px;
font-size:13px;
font-weight:bold;
'>
🔵 Guide Ditugaskan
</span>";

case 'Diterima Guide':

return
"<span style='
background:#dcfce7;
color:#166534;
padding:6px 12px;
border-radius:30px;
font-size:13px;
font-weight:bold;
'>
🟢 Diterima Guide
</span>";

case 'Guide Menolak':

return
"<span style='
background:#fee2e2;
color:#991b1b;
padding:6px 12px;
border-radius:30px;
font-size:13px;
font-weight:bold;
'>
🔴 Guide Menolak
</span>";

default:

return e($status);

}

}

/* ==========================
VALIDATE IMAGE
========================== */

function validateImage($file){

$allowed=['jpg','jpeg','png','webp'];

$ext=
strtolower(

pathinfo(

$file['name'],
PATHINFO_EXTENSION

)

);

if(

!in_array(
$ext,
$allowed
)

){

return
'Format gambar harus JPG, JPEG, PNG, atau WEBP.';

}

if(

$file['size'] > 5*1024*1024

){

return
'Ukuran gambar maksimal 5MB.';

}

return true;

}


/* ==========================
RANDOM FILE NAME
========================== */

function randomFileName($file){

$ext=
strtolower(

pathinfo(

$file['name'],
PATHINFO_EXTENSION

)

);

return

uniqid().
'_destinasi.'.
$ext;

}