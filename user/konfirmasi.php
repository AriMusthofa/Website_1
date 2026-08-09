<?php
// ============================================================
//  Rinjani Guide — konfirmasi.php  (folder: user/)
//  Step 2 dari 3: Konfirmasi Pesanan
// ============================================================
ob_start(); // pastikan header() selalu bisa jalan walau ada output tak sengaja
session_start();

// ── Guard: wajib punya data dari booking.php ─────────────────
if (empty($_SESSION['booking_form']) || empty($_SESSION['booking_dest'])) {
    if (ob_get_level() > 0) { ob_end_clean(); }
    header('Location: booking.php');
    echo '<script>window.location.href="booking.php";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=booking.php"></noscript>';
    exit;
}

// ── Koneksi
require_once '../config/koneksi.php';

// ── Ambil data session
$form  = $_SESSION['booking_form'];   // dest_id, tanggal, peserta, nama, wa, catatan
$dest  = $_SESSION['booking_dest'];   // row dari tabel destinasi
$total = (int)($_SESSION['booking_total'] ?? 0);

// ── Handle aksi 
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

// Kembali ke booking
if ($action === 'back') {
    if (ob_get_level() > 0) { ob_end_clean(); }
    header('Location: booking.php');
    echo '<script>window.location.href="booking.php";</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=booking.php"></noscript>';
    exit;
}

// Lanjut ke pembayaran
if ($action === 'confirm') {
    // Simpan booking ke DB
    $user_id   = (int)($_SESSION['id'] ?? 0);
    $dest_id   = (int)$form['dest_id'];
    $tanggal   = mysqli_real_escape_string($koneksi, $form['tanggal']);
    $peserta   = (int)$form['peserta'];
    $nama      = mysqli_real_escape_string($koneksi, $form['nama']);
    $wa        = mysqli_real_escape_string($koneksi, $form['wa']);
    $catatan   = mysqli_real_escape_string($koneksi, $form['catatan'] ?? '');
    $total_esc = $total;

    $sql = "INSERT INTO booking
                (user_id, destinasi_id, tanggal, jumlah_orang,
                 nama_customer, whatsapp, catatan, total_harga, status)
            VALUES
                ($user_id, $dest_id, '$tanggal', $peserta,
                 '$nama', '$wa', '$catatan', $total_esc, 'pending')";

    if (mysqli_query($koneksi, $sql)) {
        // Gunakan id auto-increment asli sebagai kode booking,
        // supaya cocok dengan kolom 'id' saat dicari di pembayaran.php & sukses.php
        $kode = (string) mysqli_insert_id($koneksi);

        $_SESSION['booking_kode']  = $kode;
        $_SESSION['booking_step']  = 3;

        if (ob_get_level() > 0) { ob_end_clean(); }
        header('Location: pembayaran.php');
        echo '<script>window.location.href="pembayaran.php";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=pembayaran.php"></noscript>';
        exit;
    } else {
        $db_error = "Gagal menyimpan pesanan: " . mysqli_error($koneksi);
    }
}

// ── Helpers 
function rupiah($n) {
    return 'Rp ' . number_format((int)$n, 0, ',', '.');
}

function tglIndo($tgl) {
    $bulan = [
        '01'=>'Januari','02'=>'Februari','03'=>'Maret',
        '04'=>'April','05'=>'Mei','06'=>'Juni',
        '07'=>'Juli','08'=>'Agustus','09'=>'September',
        '10'=>'Oktober','11'=>'November','12'=>'Desember',
    ];
    if (!$tgl) return '—';
    [$y, $m, $d] = explode('-', $tgl);
    return (int)$d . ' ' . ($bulan[$m] ?? $m) . ' ' . $y;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan — Rinjani Guide</title>
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
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cr); color: var(--td);
            overflow-x: hidden; min-height: 100vh;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--cr); }
        ::-webkit-scrollbar-thumb { background: var(--gl); border-radius: 3px; }

        /* ─── NAVBAR  */
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

        /* ─── HERO  */
        .hero {
            margin-top: var(--nh); min-height: 180px;
            display: flex; align-items: center;
            padding: 44px 5% 36px; position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #edf3ec 0%, #dce9db 40%, rgba(210,230,210,.4) 100%);
        }
        .hero::after {
            content: ''; position: absolute; inset: 0; z-index: 1;
            background:
                linear-gradient(to right, rgba(245,246,242,.97) 42%, rgba(245,246,242,.04) 100%),
                url('../assets/images/booking-hero.jpg') right center / cover no-repeat;
        }
        .hero-inner { position: relative; z-index: 2; }
        .hero-label { font-size: 12px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: var(--g); margin-bottom: 10px; }
        .hero-title { font-family: 'Playfair Display', serif; font-size: clamp(26px, 5vw, 46px); font-weight: 800; color: var(--td); line-height: 1.1; margin-bottom: 6px; }
        .hero-sub { font-size: 15px; color: var(--tl); }

        /* ─── STEPPER  */
        .stepper-bar { background: var(--wh); border-bottom: 1px solid var(--bd); padding: 16px 5%; }
        .stepper { display: flex; align-items: center; max-width: 640px; }
        .si { display: flex; align-items: center; flex: 1; }
        .si:last-child { flex: 0; }
        .sdot {
            width: 40px; height: 40px; border-radius: 50%;
            border: 2px solid var(--bd); background: var(--wh);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: var(--tl);
            flex-shrink: 0; transition: all var(--tr);
        }
        .sdot.done   { background: var(--g);  border-color: var(--g);  color: var(--wh); }
        .sdot.active { background: var(--gd); border-color: var(--gd); color: var(--wh); box-shadow: 0 0 0 4px rgba(58,140,92,.14); }
        .slbl { margin-left: 9px; font-size: 14px; font-weight: 600; color: var(--tl); white-space: nowrap; }
        .slbl.done, .slbl.active { color: var(--td); }
        .sline { flex: 1; height: 2px; background: var(--bd); margin: 0 14px; border-radius: 1px; }
        .sline.done { background: var(--g); }

        /* ─── PAGE  */
        .page { max-width: 860px; margin: 0 auto; padding: 36px 5% 80px; }

        /* Alert error DB */
        .alert-err {
            display: flex; align-items: flex-start; gap: 10px;
            background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
            padding: 13px 16px; border-radius: var(--r1); font-size: 14px;
            margin-bottom: 20px; animation: aIn .3s ease;
        }
        @keyframes aIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        .alert-err svg { flex-shrink: 0; margin-top: 1px; }

        /* ─── RINGKASAN CARD  */
        .card {
            background: var(--wh); border: 1.5px solid var(--bd);
            border-radius: var(--r2); padding: 32px 36px;
            box-shadow: var(--sh0);
        }

        /* Card header */
        .card-header {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 16px;
            margin-bottom: 28px;
        }
        .card-header-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 700; color: var(--td); margin-bottom: 4px;
        }
        .card-header-text p { font-size: 14px; color: var(--tl); }

        .btn-edit {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--wh); color: var(--tm);
            border: 1.5px solid var(--bd); border-radius: var(--r1);
            padding: 9px 16px; font-family: 'DM Sans', sans-serif;
            font-size: 13px; font-weight: 600; cursor: pointer;
            text-decoration: none; white-space: nowrap; flex-shrink: 0;
            transition: all var(--tr);
        }
        .btn-edit:hover { border-color: var(--g); color: var(--g); background: var(--gp); }

        /* Info grid */
        .info-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1px; background: var(--bd);
            border: 1px solid var(--bd); border-radius: var(--r1);
            overflow: hidden; margin-bottom: 20px;
        }
        .info-grid.cols-2 { grid-template-columns: repeat(2, 1fr); }
        .info-grid.cols-1 { grid-template-columns: 1fr; }

        .info-cell {
            background: var(--wh); padding: 18px 20px;
            display: flex; align-items: flex-start; gap: 14px;
        }
        .info-icon {
            width: 38px; height: 38px; flex-shrink: 0;
            background: var(--cr); border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .info-icon svg { color: var(--tm); }
        .info-label { font-size: 12px; color: var(--tl); margin-bottom: 4px; }
        .info-val { font-size: 16px; font-weight: 700; color: var(--td); line-height: 1.3; }

        /* Catatan cell - spans full */
        .info-grid.cols-1 .info-cell { grid-column: 1; }

        /* Total row */
        .total-row {
            background: var(--gp); border: 1.5px solid rgba(58,140,92,.2);
            border-radius: var(--r1); padding: 16px 20px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .total-label { font-size: 14px; color: var(--gm); font-weight: 600; }
        .total-val {
            font-family: 'Playfair Display', serif;
            font-size: 24px; font-weight: 800; color: var(--g);
        }

        /* Notice box */
        .notice {
            display: flex; align-items: flex-start; gap: 12px;
            background: var(--cr); border: 1px solid var(--bd);
            border-radius: var(--r1); padding: 14px 16px; margin-bottom: 28px;
        }
        .notice svg { color: var(--gm); flex-shrink: 0; margin-top: 1px; }
        .notice-text strong { font-size: 14px; color: var(--td); display: block; margin-bottom: 2px; }
        .notice-text span { font-size: 13px; color: var(--tl); }

        /* ─── ACTIONS */
        .btn-confirm {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 16px 32px;
            background: var(--gd); color: var(--wh);
            border: none; border-radius: var(--r1);
            font-family: 'DM Sans', sans-serif; font-size: 16px; font-weight: 700;
            cursor: pointer; transition: all var(--tr);
            box-shadow: 0 6px 22px rgba(26,58,42,.3); margin-bottom: 14px;
        }
        .btn-confirm:hover { background: var(--gm); transform: translateY(-2px); box-shadow: 0 10px 28px rgba(26,58,42,.28); }
        .btn-confirm:active { transform: translateY(0); }
        .btn-confirm.ld { pointer-events: none; opacity: .75; }
        .btn-confirm.ld .bt { display: none; }
        .btn-confirm.ld::after { content: ''; width: 20px; height: 20px; border: 2.5px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: sp .7s linear infinite; }
        @keyframes sp { to { transform: rotate(360deg); } }

        .btn-back {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 13px 20px;
            background: transparent; color: var(--gm);
            border: none; border-radius: var(--r1);
            font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all var(--tr); text-decoration: none;
        }
        .btn-back:hover { background: var(--gp); color: var(--gd); }

        /* Features strip */
        .fstrip { display: grid; grid-template-columns: repeat(4,1fr); gap: 1px; background: var(--bd); border: 1px solid var(--bd); border-radius: var(--r2); overflow: hidden; margin-top: 24px; }
        .feat { background: var(--wh); padding: 18px 16px; display: flex; align-items: center; gap: 12px; transition: background var(--tr); }
        .feat:hover { background: var(--gp); }
        .feat-ic { width: 38px; height: 38px; flex-shrink: 0; background: var(--gp); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .feat-ic svg { color: var(--g); }
        .feat span { font-size: 13px; font-weight: 600; color: var(--tm); }

        /* Scroll top */
        .stb { position: fixed; bottom: 26px; right: 26px; z-index: 900; width: 42px; height: 42px; background: var(--g); color: var(--wh); border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 20px rgba(58,140,92,.4); opacity: 0; transform: translateY(10px); pointer-events: none; transition: all var(--tr); }
        .stb.show { opacity: 1; transform: none; pointer-events: all; }
        .stb:hover { background: var(--gd); transform: translateY(-3px); }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu { display: none; } .hamburger { display: flex; }
            .slbl { display: none; }
            .info-grid { grid-template-columns: 1fr; }
            .info-grid.cols-2 { grid-template-columns: 1fr; }
            .card { padding: 22px 16px; }
            .card-header { flex-direction: column; gap: 12px; }
            .fstrip { grid-template-columns: repeat(2,1fr); }
        }
        @media (max-width: 480px) { .fstrip { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="nav-logo">
        <a href="beranda.php">
            <?php
            $logo_paths = ['../upload/logohitam.png','../upload/logohitam.png','../logohitam.png'];
            $logo_found = '';
            foreach ($logo_paths as $lp) { if (file_exists($lp)) { $logo_found = $lp; break; } }
            ?>
            <?php if ($logo_found): ?>
                <img src="<?= $logo_found ?>" alt="Rinjani Guide">
            <?php else: ?>
                <div class="lph">LOGO HERE</div>
            <?php endif; ?>
        </a>
    </div>
    <ul class="nav-menu">
        <li><a href="beranda.php">Beranda</a></li>
        <li><a href="paket.php">Paket Pendakian</a></li>
        <li><a href="Tentang.php">Tentang Kami</a></li>
        <li><a href="kontak.php">Kontak</a></li>
        <li>
            <a href="booking.php" class="btn-nav">
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

<div class="mmenu" id="mm">
    <a href="beranda.php">Beranda</a>
    <a href="paket.php">Paket Pendakian</a>
    <a href="Tentang.php">Tentang Kami</a>
    <a href="kontak.php">Kontak</a>
    <a href="booking.php" class="bmb">Booking Sekarang</a>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-inner">
        <div class="hero-label">Booking Pendakian</div>
        <h1 class="hero-title">Konfirmasi Pesanan</h1>
        <p class="hero-sub">Periksa kembali detail pesanan Anda sebelum melanjutkan ke pembayaran.</p>
    </div>
</div>

<!-- STEPPER -->
<div class="stepper-bar">
    <div class="stepper">
        <!-- Step 1: Pesanan (done) -->
        <div class="si">
            <div class="sdot done">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <span class="slbl done">Pesanan ✓</span>
        </div>
        <div class="sline done"></div>
        <!-- Step 2: Konfirmasi (active) -->
        <div class="si">
            <div class="sdot active">2</div>
            <span class="slbl active">Konfirmasi</span>
        </div>
        <div class="sline"></div>
        <!-- Step 3: Pembayaran -->
        <div class="si">
            <div class="sdot">3</div>
            <span class="slbl">Pembayaran</span>
        </div>
    </div>
</div>

<!-- PAGE -->
<div class="page">

    <!-- DB Error -->
    <?php if (!empty($db_error)): ?>
    <div class="alert-err">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8"  x2="12"   y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span><?= htmlspecialchars($db_error) ?></span>
    </div>
    <?php endif; ?>

    <!-- RINGKASAN CARD -->
    <div class="card">

        <!-- Header -->
        <div class="card-header">
            <div class="card-header-text">
                <h2>Ringkasan Pesanan</h2>
                <p>Pastikan semua informasi sudah benar.</p>
            </div>
            <a href="booking.php" class="btn-edit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Ubah Pesanan
            </a>
        </div>

        <!-- Baris 1: Destinasi · Tanggal · Peserta -->
        <div class="info-grid">
            <div class="info-cell">
                <div class="info-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">Destinasi</div>
                    <div class="info-val"><?= htmlspecialchars($dest['name'] ?? '—') ?></div>
                </div>
            </div>
            <div class="info-cell">
                <div class="info-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8"  y1="2" x2="8"  y2="6"/>
                        <line x1="3"  y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">Tanggal Pendakian</div>
                    <div class="info-val"><?= tglIndo($form['tanggal'] ?? '') ?></div>
                </div>
            </div>
            <div class="info-cell">
                <div class="info-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">Jumlah Peserta</div>
                    <div class="info-val"><?= (int)($form['peserta'] ?? 1) ?> Orang</div>
                </div>
            </div>
        </div>

        <!-- Baris 2: Nama · WhatsApp -->
        <div class="info-grid cols-2">
            <div class="info-cell">
                <div class="info-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-val"><?= htmlspecialchars($form['nama'] ?? '—') ?></div>
                </div>
            </div>
            <div class="info-cell">
                <div class="info-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">No. WhatsApp</div>
                    <div class="info-val"><?= htmlspecialchars($form['wa'] ?? '—') ?></div>
                </div>
            </div>
        </div>

        <!-- Baris 3: Catatan -->
        <div class="info-grid cols-1">
            <div class="info-cell">
                <div class="info-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="info-label">Catatan</div>
                    <div class="info-val">
                        <?= !empty($form['catatan'])
                            ? htmlspecialchars($form['catatan'])
                            : '<span style="color:var(--tl);font-weight:400">Tidak ada catatan</span>'
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total -->
        <?php if ($total > 0): ?>
        <div class="total-row">
            <span class="total-label">
                <?= htmlspecialchars($dest['price'] ?? '') ?> × <?= (int)($form['peserta'] ?? 1) ?> orang
            </span>
            <span class="total-val"><?= rupiah($total) ?></span>
        </div>
        <?php elseif (!empty($dest['price'])): ?>
        <div class="total-row">
            <span class="total-label">Harga</span>
            <span class="total-val"><?= htmlspecialchars($dest['price']) ?> / orang</span>
        </div>
        <?php endif; ?>

        <!-- Notice -->
        <div class="notice">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <polyline points="9 12 11 14 15 10"/>
            </svg>
            <div class="notice-text">
                <strong>Pastikan Informasi Sudah Benar</strong>
                <span>Setelah melanjutkan ke pembayaran, data pesanan tidak dapat diubah.</span>
            </div>
        </div>

        <!-- Tombol Lanjut ke Pembayaran -->
        <form method="POST" action="konfirmasi.php" id="confirmForm">
            <input type="hidden" name="action" value="confirm">
            <button type="submit" class="btn-confirm" id="btnConfirm">
                <span class="bt">Lanjut ke Pembayaran</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>
        </form>

        <!-- Tombol Kembali -->
        <form method="POST" action="konfirmasi.php">
            <input type="hidden" name="action" value="back">
            <button type="submit" class="btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Kembali ke Pesanan
            </button>
        </form>

    </div><!-- /.card -->

    <!-- Features strip -->
    <div class="fstrip">
        <?php
        $feats = [
            ['<path d="M8 3l4 8 5-5 5 15H2L8 3z"/>',                                                          'Pendakian Aman &amp; Nyaman'],
            ['<circle cx="12" cy="8" r="4"/><path d="M6 20v-1a6 6 0 0112 0v1"/>',                             'Guide Berpengalaman'],
            ['<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>',  'Asuransi &amp; Keamanan'],
            ['<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>',                                        'Jaga Alam, Lestari Bersama'],
        ];
        foreach ($feats as [$ic, $lbl]):
        ?>
        <div class="feat">
            <div class="feat-ic">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <?= $ic ?>
                </svg>
            </div>
            <span><?= $lbl ?></span>
        </div>
        <?php endforeach; ?>
    </div>

</div><!-- /.page -->

<button class="stb" id="stb">
    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="18 15 12 9 6 15"/>
    </svg>
</button>

<script>
(function () {
    'use strict';

    /* Navbar scroll */
    const nb  = document.getElementById('navbar');
    const stb = document.getElementById('stb');
    window.addEventListener('scroll', () => {
        nb.classList.toggle('scrolled', scrollY > 30);
        stb.classList.toggle('show',    scrollY > 300);
    }, { passive: true });
    stb.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    /* Hamburger */
    const ham = document.getElementById('ham');
    const mm  = document.getElementById('mm');
    ham.addEventListener('click', () => { ham.classList.toggle('open'); mm.classList.toggle('open'); });
    mm.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        ham.classList.remove('open'); mm.classList.remove('open');
    }));

    /* Loading state pada tombol konfirmasi */
    const confirmForm = document.getElementById('confirmForm');
    const btnConfirm  = document.getElementById('btnConfirm');
    if (confirmForm) {
        confirmForm.addEventListener('submit', () => {
            btnConfirm.classList.add('ld');
        });
    }
})();
</script>
</body>
</html>