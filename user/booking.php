<?php
// =====================================================================
//  Rinjani Guide — Booking Pendakian (Step 1: Pesanan)
//  Koneksi: koneksi.php (mysqli) | DB: Projek_CRUD | Tabel: destinasi
// =====================================================================

// ── Security: session + helper functions (csrf, redirect, dll)
require_once '../config/security.php';

// ── Guard: wajib login sebagai customer
if (!isset($_SESSION['id'])) {
    // Simpan URL tujuan agar setelah login langsung diarahkan ke sini
    $_SESSION['redirect_after_login'] = 'user/booking.php' .
        (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
    redirect('../login.php');
    exit;
}

if ($_SESSION['role'] !== 'customer') {
    // Admin/guide tidak boleh akses halaman booking customer
    switch ($_SESSION['role']) {
        case 'admin': redirect('../admin/dashboard.php'); break;
        case 'guide': redirect('../guide/Dashboard.php'); break;
        default:      redirect('../login.php');
    }
    exit;
}

$page_title = "Booking Pendakian — Rinjani Guide";

// ── Koneksi via koneksi.php
require_once '../config/koneksi.php';
// $koneksi (mysqli) sudah tersedia setelah require

// ── Ambil semua destinasi dari DB
$destinasi_list = [];
$db_error       = null;

$sql    = "SELECT id, name, altitude, difficulty, diff_key,
                  duration, dur_key, price, price_num, image, popular
           FROM   destinasi
           ORDER  BY popular DESC, name ASC";
$result = mysqli_query($koneksi, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $destinasi_list[] = $row;
    }
    mysqli_free_result($result);
} else {
    $db_error = "Gagal memuat destinasi: " . mysqli_error($koneksi);
}

// ── Auto-fill dari paket-pendakian.php (?dest=ID)
$prefill_id = 0;
if (isset($_GET['dest']) && (int)$_GET['dest'] > 0) {
    $prefill_id = (int)$_GET['dest'];
    $_SESSION['prefill_dest_id'] = $prefill_id;
} elseif (isset($_SESSION['prefill_dest_id'])) {
    $prefill_id = (int)$_SESSION['prefill_dest_id'];
}

// ── Handle POST
$errors   = [];
$formdata = $_SESSION['booking_form'] ?? [];

// Jika dari GET (klik booking di paket), terapkan dest_id
if (isset($_GET['dest']) && (int)$_GET['dest'] > 0) {
    $formdata['dest_id'] = (int)$_GET['dest'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dest_id = (int)($_POST['destinasi_id'] ?? 0);
    $tanggal = trim($_POST['tanggal']       ?? '');
    $peserta = max(1, min(50, (int)($_POST['peserta'] ?? 1)));
    $nama    = trim(strip_tags($_POST['nama']     ?? ''));
    $wa      = trim(strip_tags($_POST['whatsapp'] ?? ''));
    $catatan = trim(strip_tags($_POST['catatan']  ?? ''));

    $formdata = compact('dest_id','tanggal','peserta','nama','wa','catatan');

    // Validasi
    if (!$dest_id)
        $errors[] = 'Silakan pilih destinasi pendakian.';
    if (!$tanggal)
        $errors[] = 'Tanggal pendakian wajib diisi.';
    elseif (strtotime($tanggal) < strtotime('tomorrow'))
        $errors[] = 'Tanggal pendakian minimal besok.';
    if (!$nama)
        $errors[] = 'Nama lengkap wajib diisi.';
    if (!$wa || !preg_match('/^[0-9+\-\s]{8,16}$/', $wa))
        $errors[] = 'No. WhatsApp tidak valid (8–16 digit).';

    // Verifikasi destinasi ke DB
    $dest_data = null;
    if (empty($errors)) {
        $did = (int)$dest_id;
        $q   = mysqli_query($koneksi, "SELECT * FROM destinasi WHERE id = $did LIMIT 1");
        if ($q && mysqli_num_rows($q) > 0) {
            $dest_data = mysqli_fetch_assoc($q);
            mysqli_free_result($q);
        } else {
            $errors[] = 'Destinasi tidak ditemukan.';
        }
    }

    if (empty($errors) && $dest_data) {
        $_SESSION['booking_form']  = $formdata;
        $_SESSION['booking_dest']  = $dest_data;
        $_SESSION['booking_total'] = (int)($dest_data['price_num'] ?? 0) * $peserta;
        $_SESSION['booking_step']  = 2;
        unset($_SESSION['prefill_dest_id']);
        header('Location: konfirmasi.php');
        exit;
    }
    $_SESSION['booking_form'] = $formdata;
}

// ── Tentukan destinasi aktif
$active_dest_id = (int)($formdata['dest_id'] ?? $prefill_id ?? 0);
$selected_dest  = null;
foreach ($destinasi_list as $d) {
    if ((int)$d['id'] === $active_dest_id) { $selected_dest = $d; break; }
}

// ── Helper
function rupiah($n) {
    if (is_numeric($n) && (int)$n > 0)
        return 'Rp ' . number_format((int)$n, 0, ',', '.');
    return htmlspecialchars((string)$n);
}
function hargaNum($d) { return (int)($d['price_num'] ?? 0); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }

        :root {
            --gd: #1a3a2a; --gm: #2d6a4f; --g: #3a8c5c;
            --gl: #52b788;  --gp: #d8f3dc;
            --wh: #ffffff;  --cr: #f5f6f2;
            --td: #161d12;  --tm: #3d4838; --tl: #8a9180;
            --bd: #e4e8df;
            --sh0: 0 1px 4px rgba(0,0,0,.05);
            --sh1: 0 3px 14px rgba(0,0,0,.08);
            --sh2: 0 8px 32px rgba(0,0,0,.11);
            --r1: 10px; --r2: 16px;
            --nh: 72px;
            --tr: .25s cubic-bezier(.25,.8,.25,1);
        }

        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; background: var(--cr); color: var(--td); overflow-x: hidden; min-height: 100vh; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--cr); }
        ::-webkit-scrollbar-thumb { background: var(--gl); border-radius: 3px; }

        /* ━━ NAVBAR*/
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            height: var(--nh); display: flex; align-items: center;
            justify-content: space-between; padding: 0 5%;
            background: var(--wh); border-bottom: 1px solid var(--bd);
            box-shadow: var(--sh0); transition: box-shadow var(--tr);
        }
        .navbar.scrolled { box-shadow: var(--sh1); }

        .nav-logo a { display: flex; align-items: center; text-decoration: none; }
        .nav-logo img { height: 44px; width: auto; object-fit: contain; display: block; }
        .nav-logo .lph {
            height: 44px; width: 150px; border: 1.5px dashed var(--bd);
            border-radius: 6px; background: var(--cr);
            display: flex; align-items: center; justify-content: center;
            color: var(--tl); font-size: 11px; letter-spacing: .5px;
        }

        .nav-menu { display: flex; align-items: center; gap: 32px; list-style: none; }
        .nav-menu a {
            color: var(--tm); text-decoration: none; font-size: 14px;
            font-weight: 500; position: relative; transition: color var(--tr);
        }
        .nav-menu a:hover, .nav-menu a.active { color: var(--g); }
        .nav-menu a.active::after {
            content: ''; position: absolute; bottom: -4px; left: 0; right: 0;
            height: 2px; background: var(--g); border-radius: 1px;
        }
        .btn-nav {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--g); color: var(--wh) !important;
            padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;
            transition: background var(--tr), transform var(--tr);
            box-shadow: 0 4px 16px rgba(58,140,92,.3);
        }
        .btn-nav:hover { background: var(--gd) !important; transform: translateY(-2px); }
        .btn-nav::after { display: none !important; }

        .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; background: none; border: none; padding: 4px; }
        .hamburger span { display: block; width: 24px; height: 2px; background: var(--td); border-radius: 2px; transition: transform .3s, opacity .3s; }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mmenu {
            display: none; position: fixed; top: var(--nh); left: 0; right: 0;
            background: var(--wh); border-bottom: 1px solid var(--bd);
            padding: 20px 5% 28px; flex-direction: column;
            z-index: 999; box-shadow: var(--sh2);
        }
        .mmenu.open { display: flex; }
        .mmenu a { color: var(--tm); text-decoration: none; padding: 13px 0; font-size: 15px; font-weight: 500; border-bottom: 1px solid var(--bd); transition: color .2s; }
        .mmenu a:last-child { border-bottom: none; margin-top: 10px; }
        .mmenu a:hover { color: var(--g); }
        .mmenu .bmb { display: block; background: var(--g); color: var(--wh) !important; padding: 12px 22px; border-radius: 8px; text-align: center; font-weight: 600; }

        /* ━━ HERO BANNER */
        .hero {
            margin-top: var(--nh); min-height: 220px;
            display: flex; align-items: center;
            padding: 52px 5% 44px; position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #edf3ec 0%, #dce9db 40%, rgba(210,230,210,.5) 100%);
        }
        .hero::after {
            content: ''; position: absolute; inset: 0; z-index: 1;
            background:
                linear-gradient(to right, rgba(245,246,242,.97) 42%, rgba(245,246,242,.05) 100%),
                url('../upload/rinjani3.jpg') right center / cover no-repeat;
        }
        .hero-inner { position: relative; z-index: 2; max-width: 540px; }
        .hero-label { font-size: 12px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: var(--g); margin-bottom: 12px; }
        .hero-title { font-family: 'Playfair Display', serif; font-size: clamp(30px,5vw,52px); font-weight: 800; color: var(--td); line-height: 1.1; margin-bottom: 12px; }
        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--tl); flex-wrap: wrap; }
        .breadcrumb a { color: var(--tl); text-decoration: none; transition: color .2s; }
        .breadcrumb a:hover, .breadcrumb .cur { color: var(--g); font-weight: 600; }

        /* ━━ STEPPER */
        .stepper-bar { background: var(--wh); border-bottom: 1px solid var(--bd); padding: 18px 5%; }
        .stepper { display: flex; align-items: center; max-width: 700px; }
        .si { display: flex; align-items: center; flex: 1; }
        .si:last-child { flex: 0; }
        .sdot {
            width: 42px; height: 42px; border-radius: 50%;
            border: 2px solid var(--bd); background: var(--wh);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; color: var(--tl);
            flex-shrink: 0; transition: all var(--tr);
        }
        .sdot.active { background: var(--gd); border-color: var(--gd); color: var(--wh); box-shadow: 0 0 0 5px rgba(58,140,92,.14); }
        .sdot.done   { background: var(--g);  border-color: var(--g);  color: var(--wh); }
        .slbl { margin-left: 10px; font-size: 14px; font-weight: 600; color: var(--tl); white-space: nowrap; }
        .slbl.active { color: var(--td); }
        .sline { flex: 1; height: 2px; background: var(--bd); margin: 0 16px; border-radius: 1px; }
        .sline.done { background: var(--g); }

        /* ━━ PAGE */
        .page { max-width: 1100px; margin: 0 auto; padding: 40px 5% 80px; }

        /* Alerts */
        .alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 13px 16px; border-radius: var(--r1); font-size: 14px;
            margin-bottom: 18px; animation: aIn .3s ease;
        }
        @keyframes aIn { from { opacity:0; transform:translateY(-5px); } to { opacity:1; transform:translateY(0); } }
        .alert-warn { background: #fef9ec; border: 1px solid #f7d45e; color: #7a5900; }
        .alert-err  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert svg  { flex-shrink: 0; margin-top: 1px; }
        .alert ul   { list-style: disc; padding-left: 16px; display: flex; flex-direction: column; gap: 3px; }

        /* Prefill banner */
        .pfill {
            display: flex; align-items: center; gap: 16px;
            background: var(--gp); border: 1.5px solid rgba(58,140,92,.22);
            border-radius: var(--r2); padding: 16px 20px; margin-bottom: 22px;
            animation: aIn .4s ease;
        }
        .pfill-img { width: 72px; height: 56px; border-radius: var(--r1); object-fit: cover; flex-shrink: 0; box-shadow: var(--sh1); }
        .pfill-ph  { width: 72px; height: 56px; border-radius: var(--r1); background: linear-gradient(135deg, var(--gd), var(--gm)); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .pfill-tag  { font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: var(--gm); margin-bottom: 3px; }
        .pfill-name { font-family: 'Playfair Display', serif; font-size: 17px; font-weight: 700; color: var(--td); margin-bottom: 5px; line-height: 1.2; }
        .pfill-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .pfill-meta span { display: flex; align-items: center; gap: 5px; font-size: 13px; color: var(--gm); font-weight: 500; }
        .pfill-meta svg  { color: var(--gm); flex-shrink: 0; }
        .dbadge            { display: inline-block; padding: 2px 9px; border-radius: 5px; font-size: 11px; font-weight: 700; }
        .dbadge-Mudah      { background: var(--g);  color: #fff; }
        .dbadge-Menengah   { background: #f4a261;   color: #fff; }
        .dbadge-Sulit      { background: #e63946;   color: #fff; }
        .pfill-chg { margin-left: auto; flex-shrink: 0; display: flex; align-items: center; gap: 5px; font-size: 13px; color: var(--gm); font-weight: 600; text-decoration: none; white-space: nowrap; transition: color .2s; }
        .pfill-chg:hover { color: var(--gd); }

        /* ━━ MAIN CARD */
        .card { background: var(--wh); border: 1.5px solid var(--bd); border-radius: var(--r2); padding: 38px 42px; box-shadow: var(--sh0); }
        .card-h   { font-size: 22px; font-weight: 700; color: var(--td); margin-bottom: 4px; }
        .card-sub { font-size: 14px; color: var(--tl); margin-bottom: 32px; }

        /* Form layout */
        .frow { display: grid; gap: 20px; margin-bottom: 20px; }
        .c3 { grid-template-columns: 1fr 1fr 200px; }
        .c2 { grid-template-columns: 1fr 1fr; }
        .c1 { grid-template-columns: 1fr; }
        .fg  { display: flex; flex-direction: column; gap: 7px; }
        .lbl { font-size: 13px; font-weight: 600; color: var(--tm); letter-spacing: .2px; }
        .req { color: #e63946; margin-left: 2px; }
        .opt { font-weight: 400; color: var(--tl); }

        /* Inputs */
        .fi, .fta, .fsel {
            font-family: 'DM Sans', sans-serif; font-size: 15px; color: var(--td);
            background: var(--wh); border: 1.5px solid var(--bd);
            border-radius: var(--r1); padding: 13px 16px;
            outline: none; width: 100%;
            transition: border-color var(--tr), box-shadow var(--tr), background var(--tr);
        }
        .fi:focus, .fta:focus, .fsel:focus { border-color: var(--g); box-shadow: 0 0 0 3px rgba(58,140,92,.1); }
        .fi.err, .fta.err, .fsel.err { border-color: #e63946; }
        .fi::placeholder, .fta::placeholder { color: #b8c0b4; }
        .fi.pre, .fsel.pre { border-color: var(--gl); background: rgba(216,243,220,.15); }

        /* Select */
        .fsel {
            appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%238a9180' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center; padding-right: 42px;
        }

        /* Price chip */
        .pchip { display: none; align-items: center; gap: 7px; background: var(--gp); border-radius: 8px; padding: 9px 14px; font-size: 13px; color: var(--gm); font-weight: 500; margin-top: -12px; }
        .pchip.show { display: flex; }

        /* Date */
        .dw { position: relative; }
        .dw .fi { padding-right: 44px; }
        .dic { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--tl); pointer-events: none; }

        /* Counter */
        .ctr { display: flex; align-items: center; border: 1.5px solid var(--bd); border-radius: var(--r1); overflow: hidden; background: var(--wh); height: 48px; }
        .cb  { width: 48px; height: 100%; background: none; border: none; cursor: pointer; font-size: 22px; color: var(--tm); display: flex; align-items: center; justify-content: center; transition: background var(--tr), color var(--tr); flex-shrink: 0; }
        .cb:hover:not(:disabled) { background: var(--gp); color: var(--g); }
        .cb:disabled { opacity: .32; cursor: not-allowed; }
        .cv { flex: 1; text-align: center; font-size: 17px; font-weight: 700; color: var(--td); border-left: 1px solid var(--bd); border-right: 1px solid var(--bd); }

        /* Textarea */
        .fta { resize: vertical; min-height: 110px; line-height: 1.65; }

        /* Total box */
        .tbox { background: var(--gp); border: 1.5px solid rgba(58,140,92,.2); border-radius: var(--r1); padding: 16px 18px; margin-bottom: 20px; display: none; }
        .tbox.show { display: block; }
        .tr2 { display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: var(--tm); }
        .tr2 + .tr2 { margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(58,140,92,.15); }
        .tbig { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 800; color: var(--g); }

        /* Submit */
        .bsub {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 16px 32px;
            background: var(--gd); color: var(--wh);
            border: none; border-radius: var(--r1);
            font-family: 'DM Sans', sans-serif; font-size: 16px; font-weight: 700;
            cursor: pointer; margin-top: 8px;
            transition: all var(--tr); box-shadow: 0 6px 22px rgba(26,58,42,.3);
        }
        .bsub:hover  { background: var(--gm); transform: translateY(-2px); box-shadow: 0 10px 28px rgba(26,58,42,.28); }
        .bsub:active { transform: translateY(0); }
        .bsub.ld { pointer-events: none; opacity: .75; }
        .bsub.ld .bt { display: none; }
        .bsub.ld::after { content: ''; width: 20px; height: 20px; border: 2.5px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: sp .7s linear infinite; }
        @keyframes sp { to { transform: rotate(360deg); } }

        /* Features strip */
        .fstrip { display: grid; grid-template-columns: repeat(4,1fr); gap: 1px; background: var(--bd); border: 1px solid var(--bd); border-radius: var(--r2); overflow: hidden; margin-top: 28px; }
        .feat   { background: var(--wh); padding: 20px 18px; display: flex; align-items: center; gap: 13px; transition: background var(--tr); }
        .feat:hover { background: var(--gp); }
        .feat-ic { width: 40px; height: 40px; flex-shrink: 0; background: var(--gp); border-radius: 11px; display: flex; align-items: center; justify-content: center; }
        .feat-ic svg { color: var(--g); }
        .feat span { font-size: 13px; font-weight: 600; color: var(--tm); }

        /* Scroll top */
        .stb { position: fixed; bottom: 28px; right: 28px; z-index: 900; width: 44px; height: 44px; background: var(--g); color: var(--wh); border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 20px rgba(58,140,92,.4); opacity: 0; transform: translateY(10px); pointer-events: none; transition: all var(--tr); }
        .stb.show { opacity: 1; transform: none; pointer-events: all; }
        .stb:hover { background: var(--gd); transform: translateY(-3px); }

        /* ━━ RESPONSIVE */
        @media (max-width: 900px)  { .c3 { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 768px)  {
            .nav-menu { display: none; } .hamburger { display: flex; }
            .slbl { display: none; }
            .c3, .c2 { grid-template-columns: 1fr; }
            .card { padding: 26px 18px; }
            .fstrip { grid-template-columns: repeat(2,1fr); }
            .pfill-chg { display: none; }
        }
        @media (max-width: 480px) {
            .hero { padding: 40px 5% 36px; }
            .fstrip { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ━━ NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="nav-logo">
        <a href="../upload/logohitam.png">
            <?php if (file_exists('../upload/logohitam.png')): ?>
                <img src="../upload/logohitam.png" alt="Rinjani Guide">
            <?php else: ?>
                <div class="lph">LOGO HERE</div>
            <?php endif; ?>
        </a>
    </div>

    <ul class="nav-menu">
        <li><a href="beranda.php">Beranda</a></li>
        <li><a href="paket.php">Paket Pendakian</a></li>
        <li><a href="tentang.php">Tentang Kami</a></li>
        <li><a href="kontak.php">Kontak</a></li>
        <li>
            <a href="booking.php" class="btn-nav active">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8"  y1="2" x2="8"  y2="6"/>
                    <line x1="3"  y1="10" x2="21" y2="10"/>
                </svg>
                Booking Sekarang
            </a>
        </li>
    </ul>

    <button class="hamburger" id="ham"><span></span><span></span><span></span></button>
</nav>

<!-- Mobile menu -->
<div class="mmenu" id="mm">
    <a href="beranda.php">Beranda</a>
    <a href="paket.php">Paket Pendakian</a>
    <a href="tentang.php">Tentang Kami</a>
    <a href="kontak.php">Kontak</a>
    <a href="booking.php" class="bmb">Booking Sekarang</a>
</div>

<!-- ━━ HERO BANNER -->
<div class="hero">
    <div class="hero-inner">
        <div class="hero-label">Booking Pendakian</div>
        <h1 class="hero-title">Pesan Pendakian Anda</h1>
        <nav class="breadcrumb">
            <a href="beranda.php">Beranda</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <?php if ($selected_dest): ?>
                <a href="paket.php">Paket Pendakian</a>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                <span class="cur"><?= htmlspecialchars($selected_dest['name']) ?></span>
            <?php else: ?>
                <span class="cur">Booking Pendakian</span>
            <?php endif; ?>
        </nav>
    </div>
</div>

<!-- ━━ STEPPER-->
<div class="stepper-bar">
    <div class="stepper">
        <?php foreach (['Pesanan','Konfirmasi','Pembayaran'] as $i => $lbl):
            $n = $i + 1; $ac = $n === 1 ? 'active' : '';
        ?>
        <div class="si">
            <div class="sdot <?= $ac ?>"><?= $n ?></div>
            <span class="slbl <?= $ac ?>"><?= $lbl ?></span>
        </div>
        <?php if ($n < 3): ?><div class="sline"></div><?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- ━━ KONTEN HALAMAN -->
<div class="page">

    <!-- Pesan error DB -->
    <?php if ($db_error): ?>
    <div class="alert alert-warn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <div>
            <strong>Database:</strong> <?= htmlspecialchars($db_error) ?><br>
            <small>Pastikan tabel <code>destinasi</code> sudah dibuat di database <code>Projek_CRUD</code>.</small>
        </div>
    </div>
    <?php endif; ?>

    <!-- Error validasi -->
    <?php if (!empty($errors)): ?>
    <div class="alert alert-err">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <ul>
            <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Banner paket terpilih (muncul jika dari paket-pendakian.php) -->
    <?php if ($selected_dest): ?>
    <div class="pfill">
        <?php if (!empty($selected_dest['image']) && file_exists($selected_dest['image'])): ?>
            <img class="pfill-img"
                 src="<?= htmlspecialchars($selected_dest['image']) ?>"
                 alt="<?= htmlspecialchars($selected_dest['name']) ?>">
        <?php else: ?>
            <div class="pfill-ph">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5">
                    <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
                </svg>
            </div>
        <?php endif; ?>

        <div>
            <div class="pfill-tag">Paket terpilih dari Paket Pendakian</div>
            <div class="pfill-name"><?= htmlspecialchars($selected_dest['name']) ?></div>
            <div class="pfill-meta">
                <?php if (!empty($selected_dest['altitude'])): ?>
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M8 3l4 8 5-5 5 15H2L8 3z"/></svg>
                    <?= htmlspecialchars($selected_dest['altitude']) ?>
                </span>
                <?php endif; ?>
                <?php if (!empty($selected_dest['duration'])): ?>
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?= htmlspecialchars($selected_dest['duration']) ?>
                </span>
                <?php endif; ?>
                <?php if (!empty($selected_dest['price'])): ?>
                <span><strong><?= htmlspecialchars($selected_dest['price']) ?></strong> / orang</span>
                <?php endif; ?>
                <?php if (!empty($selected_dest['difficulty'])): ?>
                <span class="dbadge dbadge-<?= htmlspecialchars($selected_dest['difficulty']) ?>">
                    <?= htmlspecialchars($selected_dest['difficulty']) ?>
                </span>
                <?php endif; ?>
            </div>
        </div>

        <a href="paket.php" class="pfill-chg">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Ganti Paket
        </a>
    </div>
    <?php endif; ?>

    <!-- ━━ FORM CARD -->
    <div class="card">
        <div class="card-h">Informasi Pesanan</div>
        <div class="card-sub">Lengkapi detail pesanan pendakian Anda</div>

        <form method="POST" id="oForm" novalidate>

            <!-- Row 1: Destinasi · Tanggal · Jumlah Peserta -->
            <div class="frow c3">

                <!-- Destinasi (dari DB) -->
                <div class="fg">
                    <label class="lbl" for="destinasi_id">
                        Destinasi <span class="req">*</span>
                    </label>
                    <select class="fsel <?= $active_dest_id ? 'pre' : '' ?>"
                            id="destinasi_id" name="destinasi_id" required>
                        <option value="">Pilih gunung / destinasi</option>
                        <?php foreach ($destinasi_list as $d): ?>
                        <option
                            value="<?= (int)$d['id'] ?>"
                            data-price="<?= (int)($d['price_num'] ?? 0) ?>"
                            data-pricestr="<?= htmlspecialchars($d['price'] ?? '') ?>"
                            data-diff="<?= htmlspecialchars($d['difficulty'] ?? '') ?>"
                            data-dur="<?= htmlspecialchars($d['duration'] ?? '') ?>"
                            <?= (int)$d['id'] === $active_dest_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['name']) ?><?= $d['popular'] ? ' ⭐' : '' ?>
                        </option>
                        <?php endforeach; ?>
                        <?php if (empty($destinasi_list) && !$db_error): ?>
                        <option disabled>Belum ada destinasi tersedia</option>
                        <?php endif; ?>
                    </select>
                    <!-- Chip harga muncul otomatis saat pilih destinasi -->
                    <div class="pchip <?= $selected_dest ? 'show' : '' ?>" id="pchip">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                        </svg>
                        <span id="pchipTxt"><?php
                            if ($selected_dest) {
                                echo htmlspecialchars($selected_dest['price'] ?? '');
                                echo ' / orang';
                                if (!empty($selected_dest['difficulty'])) echo '  •  ' . htmlspecialchars($selected_dest['difficulty']);
                                if (!empty($selected_dest['duration']))   echo '  •  ' . htmlspecialchars($selected_dest['duration']);
                            }
                        ?></span>
                    </div>
                </div>

                <!-- Tanggal Pendakian -->
                <div class="fg">
                    <label class="lbl" for="tanggal">
                        Tanggal Pendakian <span class="req">*</span>
                    </label>
                    <div class="dw">
                        <input class="fi" type="date" id="tanggal" name="tanggal"
                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                               value="<?= htmlspecialchars($formdata['tanggal'] ?? '') ?>"
                               placeholder="Pilih tanggal pendakian" required>
                        <span class="dic">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8"  y1="2" x2="8"  y2="6"/>
                                <line x1="3"  y1="10" x2="21" y2="10"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Jumlah Peserta -->
                <div class="fg">
                    <label class="lbl">Jumlah Peserta <span class="req">*</span></label>
                    <input type="hidden" name="peserta" id="pVal" value="<?= (int)($formdata['peserta'] ?? 1) ?>">
                    <div class="ctr">
                        <button type="button" class="cb" id="bMinus"
                                <?= ($formdata['peserta'] ?? 1) <= 1  ? 'disabled' : '' ?>>−</button>
                        <span class="cv" id="pDisp"><?= (int)($formdata['peserta'] ?? 1) ?></span>
                        <button type="button" class="cb" id="bPlus"
                                <?= ($formdata['peserta'] ?? 1) >= 50 ? 'disabled' : '' ?>>+</button>
                    </div>
                </div>
            </div>

            <!-- Total estimasi — muncul setelah destinasi dipilih -->
            <div class="tbox <?= $selected_dest ? 'show' : '' ?>" id="tbox">
                <div class="tr2">
                    <span id="tdesc"><?php
                        if ($selected_dest) {
                            $pn = hargaNum($selected_dest);
                            $pc = (int)($formdata['peserta'] ?? 1);
                            echo $pn > 0 ? rupiah($pn).' × '.$pc.' orang' : (htmlspecialchars($selected_dest['price'] ?? '')).' × '.$pc.' orang';
                        }
                    ?></span>
                </div>
                <div class="tr2">
                    <span style="font-weight:700;color:var(--td)">Estimasi Total</span>
                    <span class="tbig" id="tval"><?php
                        if ($selected_dest) {
                            $pn = hargaNum($selected_dest);
                            $pc = (int)($formdata['peserta'] ?? 1);
                            echo $pn > 0 ? rupiah($pn * $pc) : '—';
                        }
                    ?></span>
                </div>
            </div>

            <!-- Row 2: Nama Lengkap · No. WhatsApp -->
            <div class="frow c2">
                <div class="fg">
                    <label class="lbl" for="nama">Nama Lengkap <span class="req">*</span></label>
                    <input class="fi" type="text" id="nama" name="nama"
                           placeholder="Masukkan nama lengkap"
                           value="<?= htmlspecialchars($formdata['nama'] ?? '') ?>"
                           autocomplete="name" required>
                </div>
                <div class="fg">
                    <label class="lbl" for="whatsapp">No. WhatsApp <span class="req">*</span></label>
                    <input class="fi" type="tel" id="whatsapp" name="whatsapp"
                           placeholder="08xxxxxxxxxx"
                           value="<?= htmlspecialchars($formdata['wa'] ?? '') ?>"
                           autocomplete="tel" required>
                </div>
            </div>

            <!-- Row 3: Catatan -->
            <div class="frow c1">
                <div class="fg">
                    <label class="lbl" for="catatan">
                        Catatan <span class="opt">(opsional)</span>
                    </label>
                    <textarea class="fta" id="catatan" name="catatan"
                              placeholder="Tulis catatan jika ada..."
                              maxlength="500"><?= htmlspecialchars($formdata['catatan'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="bsub" id="bSub">
                <span class="bt">Lanjut ke Konfirmasi</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>

        </form>
    </div><!-- /.card -->

    <!-- Features strip -->
    <div class="fstrip">
        <?php
        $feats = [
            ['<path d="M8 3l4 8 5-5 5 15H2L8 3z"/>',                                                         'Pendakian Aman &amp; Nyaman'],
            ['<circle cx="12" cy="8" r="4"/><path d="M6 20v-1a6 6 0 0112 0v1"/>',                            'Guide Berpengalaman'],
            ['<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>', 'Asuransi &amp; Keamanan'],
            ['<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>',                                       'Jaga Alam, Lestari Bersama'],
        ];
        foreach ($feats as [$ic, $lbl]):
        ?>
        <div class="feat">
            <div class="feat-ic">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <?= $ic ?>
                </svg>
            </div>
            <span><?= $lbl ?></span>
        </div>
        <?php endforeach; ?>
    </div>

</div><!-- /.page -->

<button class="stb" id="stb">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="18 15 12 9 6 15"/>
    </svg>
</button>

<!-- ━━ DATA PHP → JS  -->
<script>
// Data destinasi dikirim ke JS untuk kalkulasi harga real-time
const DESTS = <?php
    $js = [];
    foreach ($destinasi_list as $d) {
        $js[] = [
            'id'       => (int)$d['id'],
            'name'     => $d['name'],
            'price'    => (int)($d['price_num'] ?? 0),   // angka → kalkulasi
            'priceStr' => $d['price'] ?? '',               // teks → tampilan
            'diff'     => $d['difficulty'] ?? '',
            'diffKey'  => $d['diff_key']   ?? '',
            'dur'      => $d['duration']   ?? '',
            'durKey'   => $d['dur_key']    ?? '',
        ];
    }
    echo json_encode($js, JSON_UNESCAPED_UNICODE);
?>;
const PFILL_ID = <?= $active_dest_id ?: 'null' ?>;
</script>

<!-- ━━ JAVASCRIPT -->
<script>
(function () {
    'use strict';

    /* ── Navbar scroll ── */
    const nb  = document.getElementById('navbar');
    const stb = document.getElementById('stb');
    window.addEventListener('scroll', () => {
        nb.classList.toggle('scrolled', scrollY > 30);
        stb.classList.toggle('show',    scrollY > 350);
    }, { passive: true });
    stb.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    /* ── Hamburger ── */
    const ham = document.getElementById('ham');
    const mm  = document.getElementById('mm');
    ham.addEventListener('click', () => { ham.classList.toggle('open'); mm.classList.toggle('open'); });
    mm.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        ham.classList.remove('open'); mm.classList.remove('open');
    }));

    /* ── Rupiah formatter ── */
    const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');

    /* ── DOM refs ── */
    const sel    = document.getElementById('destinasi_id');
    const chip   = document.getElementById('pchip');
    const chipT  = document.getElementById('pchipTxt');
    const tbox   = document.getElementById('tbox');
    const tdesc  = document.getElementById('tdesc');
    const tval   = document.getElementById('tval');
    const pVal   = document.getElementById('pVal');
    const pDisp  = document.getElementById('pDisp');
    const bMinus = document.getElementById('bMinus');
    const bPlus  = document.getElementById('bPlus');

    function getDest() {
        const id = parseInt(sel.value) || 0;
        return DESTS.find(d => d.id === id) || null;
    }

    /* Update chip harga & total estimasi */
    function updateUI() {
        const d = getDest();
        const p = parseInt(pVal.value) || 1;

        if (d) {
            const display = d.priceStr || rp(d.price);

            // Chip info di bawah select
            chipT.textContent = display + ' / orang'
                + (d.diff ? '  •  ' + d.diff : '')
                + (d.dur  ? '  •  ' + d.dur  : '');
            chip.classList.add('show');

            // Total box
            if (d.price > 0) {
                tdesc.textContent = rp(d.price) + ' × ' + p + ' orang';
                tval.textContent  = rp(d.price * p);
            } else {
                tdesc.textContent = display + ' × ' + p + ' orang';
                tval.textContent  = '—';
            }
            tbox.classList.add('show');
        } else {
            chip.classList.remove('show');
            tbox.classList.remove('show');
        }
    }

    /* Init: jika prefill dari paket-pendakian */
    if (PFILL_ID) { sel.classList.add('pre'); updateUI(); }

    /* Saat user ganti destinasi */
    sel.addEventListener('change', () => {
        sel.classList.toggle('pre', !!sel.value);
        sel.classList.remove('err');
        updateUI();
    });

    /* ── Counter peserta ── */
    function syncCtr() {
        const v = parseInt(pVal.value) || 1;
        pDisp.textContent = v;
        bMinus.disabled   = v <= 1;
        bPlus.disabled    = v >= 50;
        updateUI();
    }
    bMinus.addEventListener('click', () => { const v = parseInt(pVal.value); if (v > 1)  { pVal.value = v - 1; syncCtr(); } });
    bPlus .addEventListener('click', () => { const v = parseInt(pVal.value); if (v < 50) { pVal.value = v + 1; syncCtr(); } });
    syncCtr();

    /* ── Validasi form ── */
    const form = document.getElementById('oForm');
    const bSub = document.getElementById('bSub');

    form.addEventListener('submit', e => {
        let ok = true;
        const flds = [
            { el: sel,                                   fn: v => v !== '' },
            { el: document.getElementById('tanggal'),    fn: v => v !== '' },
            { el: document.getElementById('nama'),       fn: v => v.trim() !== '' },
            { el: document.getElementById('whatsapp'),   fn: v => /^[0-9+\-\s]{8,16}$/.test(v.trim()) },
        ];
        flds.forEach(({ el, fn }) => {
            el.classList.remove('err');
            if (!fn(el.value)) { el.classList.add('err'); ok = false; }
        });
        if (!ok) { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); return; }
        bSub.classList.add('ld');
    });

    form.querySelectorAll('.fi, .fsel').forEach(el =>
        el.addEventListener('input', () => el.classList.remove('err'))
    );

    /* ── Set tanggal min = besok ── */
    const tgl = document.getElementById('tanggal');
    if (tgl && !tgl.value) {
        const t = new Date(); t.setDate(t.getDate() + 1);
        tgl.min = t.toISOString().split('T')[0];
    }
})();
</script>
</body>
</html>