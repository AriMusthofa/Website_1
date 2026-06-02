<?php
// ============================================================
//  Rinjani Guide — sukses.php  (folder: user/)
//  Halaman konfirmasi setelah pembayaran berhasil diupload
// ============================================================
session_start();
require_once '../config/koneksi.php';

// ── Ambil kode dari URL ───────────────────────────────────────
$kode = trim($_GET['kode'] ?? '');

if (empty($kode)) {
    header('Location: booking.php');
    exit;
}

// ── Ambil data booking dari DB ────────────────────────────────
$kode_esc = mysqli_real_escape_string($koneksi, $kode);
$result   = mysqli_query($koneksi,
    "SELECT b.*, d.name AS dest_name, d.duration, d.difficulty
     FROM   booking b
     LEFT   JOIN destinasi d ON d.id = b.destinasi_id
     WHERE  b.id = '$kode_esc'
     LIMIT  1");

$booking = $result ? mysqli_fetch_assoc($result) : null;

// Jika kode tidak ditemukan, redirect
if (!$booking) {
    header('Location: booking.php');
    exit;
}

// ── Helper ────────────────────────────────────────────────────
function rupiah($n) {
    return 'Rp ' . number_format((int)$n, 0, ',', '.');
}
function tglIndo($t) {
    $b = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
          '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
          '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
    if (!$t) return '—';
    [$y,$m,$d] = explode('-', $t);
    return (int)$d.' '.($b[$m]??$m).' '.$y;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil — Rinjani Guide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }

        :root {
            --gd:#1a3a2a; --gm:#2d6a4f; --g:#3a8c5c;
            --gl:#52b788; --gp:#d8f3dc;
            --wh:#ffffff; --cr:#f5f6f2;
            --td:#161d12; --tm:#3d4838; --tl:#8a9180;
            --bd:#e4e8df;
            --sh0:0 1px 4px rgba(0,0,0,.05);
            --sh1:0 3px 14px rgba(0,0,0,.08);
            --sh2:0 8px 32px rgba(0,0,0,.11);
            --r1:10px; --r2:16px;
            --nh:72px;
            --tr:.25s cubic-bezier(.25,.8,.25,1);
        }

        html { scroll-behavior:smooth; }
        body { font-family:'DM Sans',sans-serif; background:var(--cr); color:var(--td); overflow-x:hidden; min-height:100vh; }
        ::-webkit-scrollbar{width:6px}
        ::-webkit-scrollbar-track{background:var(--cr)}
        ::-webkit-scrollbar-thumb{background:var(--gl);border-radius:3px}

        /* ─── NAVBAR ──────────────────────────────────────── */
        .navbar{position:fixed;top:0;left:0;right:0;z-index:1000;height:var(--nh);display:flex;align-items:center;justify-content:space-between;padding:0 5%;background:var(--wh);border-bottom:1px solid var(--bd);box-shadow:var(--sh0);transition:box-shadow var(--tr)}
        .navbar.scrolled{box-shadow:var(--sh1)}
        .nav-logo a{display:flex;align-items:center;text-decoration:none}
        .nav-logo img{height:44px;width:auto;object-fit:contain;display:block}
        .nav-logo .lph{height:44px;width:150px;border:1.5px dashed var(--bd);border-radius:6px;background:var(--cr);display:flex;align-items:center;justify-content:center;color:var(--tl);font-size:11px;letter-spacing:.5px}
        .nav-menu{display:flex;align-items:center;gap:32px;list-style:none}
        .nav-menu a{color:var(--tm);text-decoration:none;font-size:14px;font-weight:500;position:relative;transition:color var(--tr)}
        .nav-menu a:hover{color:var(--g)}
        .btn-nav{display:inline-flex;align-items:center;gap:7px;background:var(--g);color:var(--wh)!important;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;transition:background var(--tr),transform var(--tr);box-shadow:0 4px 16px rgba(58,140,92,.3)}
        .btn-nav:hover{background:var(--gd)!important;transform:translateY(-2px)}
        .hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:4px}
        .hamburger span{display:block;width:24px;height:2px;background:var(--td);border-radius:2px;transition:transform .3s,opacity .3s}
        .hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
        .hamburger.open span:nth-child(2){opacity:0}
        .hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
        .mmenu{display:none;position:fixed;top:var(--nh);left:0;right:0;background:var(--wh);border-bottom:1px solid var(--bd);padding:20px 5% 28px;flex-direction:column;z-index:999;box-shadow:var(--sh2)}
        .mmenu.open{display:flex}
        .mmenu a{color:var(--tm);text-decoration:none;padding:13px 0;font-size:15px;font-weight:500;border-bottom:1px solid var(--bd);transition:color .2s}
        .mmenu a:last-child{border-bottom:none;margin-top:10px}
        .mmenu a:hover{color:var(--g)}
        .mmenu .bmb{display:block;background:var(--g);color:var(--wh)!important;padding:12px 22px;border-radius:8px;text-align:center;font-weight:600}

        /* ─── PAGE ────────────────────────────────────────── */
        .page{padding-top:var(--nh);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:flex-start}

        /* ─── SUCCESS HERO ────────────────────────────────── */
        .success-hero{
            width:100%;background:linear-gradient(135deg,#edf3ec 0%,#dce9db 50%,rgba(210,230,210,.5) 100%);
            padding:56px 5% 52px;text-align:center;position:relative;overflow:hidden;
        }
        .success-hero::after{
            content:'';position:absolute;inset:0;
            background:radial-gradient(ellipse at 70% 50%,rgba(82,183,136,.08) 0%,transparent 70%);
            pointer-events:none;
        }

        /* Lingkaran animasi centang */
        .check-ring{
            width:96px;height:96px;margin:0 auto 22px;position:relative;
        }
        .check-ring svg{width:96px;height:96px}
        .ring-circle{
            fill:none;stroke:var(--g);stroke-width:3;
            stroke-dasharray:283;stroke-dashoffset:283;
            transform-origin:50% 50%;transform:rotate(-90deg);
            animation:ring-in .7s .1s cubic-bezier(.4,0,.2,1) forwards;
        }
        @keyframes ring-in{to{stroke-dashoffset:0}}
        .check-mark{
            fill:none;stroke:var(--g);stroke-width:4;stroke-linecap:round;stroke-linejoin:round;
            stroke-dasharray:40;stroke-dashoffset:40;
            animation:check-in .4s .8s ease forwards;
        }
        @keyframes check-in{to{stroke-dashoffset:0}}
        .check-bg{
            fill:var(--gp);
            animation:pop-in .5s .05s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes pop-in{from{transform:scale(0);transform-origin:50% 50%}to{transform:scale(1)}}

        .sh-label{font-size:12px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--g);margin-bottom:10px;animation:fade-up .5s .9s both}
        .sh-title{font-family:'Playfair Display',serif;font-size:clamp(28px,5vw,42px);font-weight:800;color:var(--td);line-height:1.15;margin-bottom:10px;animation:fade-up .5s 1s both}
        .sh-sub{font-size:15px;color:var(--tl);max-width:520px;margin:0 auto 20px;line-height:1.65;animation:fade-up .5s 1.1s both}

        @keyframes fade-up{
            from{opacity:0;transform:translateY(16px)}
            to{opacity:1;transform:none}
        }

        /* Kode booking badge */
        .kode-badge{
            display:inline-flex;align-items:center;gap:10px;
            background:var(--wh);border:1.5px solid rgba(58,140,92,.25);
            border-radius:10px;padding:10px 20px;
            font-size:14px;color:var(--tm);font-weight:500;
            animation:fade-up .5s 1.2s both;
            box-shadow:var(--sh0);
        }
        .kode-badge strong{font-size:16px;font-weight:800;color:var(--gd);letter-spacing:.5px}
        .btn-kode-copy{
            background:none;border:none;cursor:pointer;color:var(--tl);padding:2px 4px;
            display:flex;align-items:center;transition:color var(--tr);
        }
        .btn-kode-copy:hover{color:var(--g)}

        /* Status badge */
        .status-badge{
            display:inline-flex;align-items:center;gap:7px;
            background:#fffbeb;border:1.5px solid #fde68a;
            border-radius:20px;padding:6px 16px;
            font-size:13px;font-weight:600;color:#92400e;
            margin-top:14px;animation:fade-up .5s 1.3s both;
        }
        .status-dot{width:8px;height:8px;border-radius:50%;background:#f59e0b;flex-shrink:0;animation:pulse 1.4s ease-in-out infinite}
        @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.85)}}

        /* ─── CONTENT AREA ────────────────────────────────── */
        .content{max-width:720px;width:100%;margin:0 auto;padding:32px 5% 72px}

        /* ─── CARD ────────────────────────────────────────── */
        .card{
            background:var(--wh);border:1.5px solid var(--bd);
            border-radius:var(--r2);padding:28px 32px;
            box-shadow:var(--sh0);margin-bottom:16px;
            animation:fade-up .5s 1.35s both;
        }
        .card-h{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--td);margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid var(--bd)}

        /* Detail rows */
        .det-grid{display:grid;grid-template-columns:1fr 1fr;gap:0}
        .det-item{padding:12px 0;border-bottom:1px solid var(--bd);display:flex;flex-direction:column;gap:4px}
        .det-item:nth-last-child(-n+2){border-bottom:none}
        .det-item.full{grid-column:1/-1}
        .det-lbl{font-size:12px;color:var(--tl);font-weight:500;text-transform:uppercase;letter-spacing:.5px}
        .det-val{font-size:15px;font-weight:700;color:var(--td)}
        .det-val.green{color:var(--g);font-family:'Playfair Display',serif;font-size:20px}
        .det-val.mono{font-family:monospace;letter-spacing:.5px}

        /* ─── LANGKAH SELANJUTNYA ─────────────────────────── */
        .steps-card{
            background:var(--wh);border:1.5px solid var(--bd);
            border-radius:var(--r2);padding:28px 32px;
            box-shadow:var(--sh0);margin-bottom:16px;
            animation:fade-up .5s 1.45s both;
        }
        .steps-card .card-h{margin-bottom:20px}
        .step-list{display:flex;flex-direction:column;gap:0}
        .step-item{display:flex;align-items:flex-start;gap:16px;padding:16px 0;border-bottom:1px solid var(--bd)}
        .step-item:last-child{border-bottom:none}
        .step-num{
            width:34px;height:34px;flex-shrink:0;
            background:var(--gp);border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            font-size:14px;font-weight:800;color:var(--g);
        }
        .step-text strong{display:block;font-size:14px;font-weight:700;color:var(--td);margin-bottom:3px}
        .step-text span{font-size:13px;color:var(--tl);line-height:1.6}

        /* ─── INFO BOX ────────────────────────────────────── */
        .info-box{
            display:flex;align-items:flex-start;gap:12px;
            background:var(--gp);border:1px solid rgba(58,140,92,.2);
            border-radius:var(--r1);padding:14px 18px;
            font-size:14px;color:var(--gm);line-height:1.6;
            animation:fade-up .5s 1.5s both;margin-bottom:16px;
        }
        .info-box svg{color:var(--g);flex-shrink:0;margin-top:1px}

        /* ─── ACTION BUTTONS ──────────────────────────────── */
        .actions{display:flex;gap:12px;flex-wrap:wrap;animation:fade-up .5s 1.55s both}
        .btn-primary{
            flex:1;min-width:160px;display:flex;align-items:center;justify-content:center;gap:9px;
            background:var(--gd);color:var(--wh);
            padding:14px 24px;border-radius:var(--r1);
            font-family:'DM Sans',sans-serif;font-size:15px;font-weight:700;
            text-decoration:none;transition:all var(--tr);
            box-shadow:0 6px 22px rgba(26,58,42,.28);
        }
        .btn-primary:hover{background:var(--gm);transform:translateY(-2px);box-shadow:0 10px 28px rgba(26,58,42,.22)}
        .btn-secondary{
            flex:1;min-width:160px;display:flex;align-items:center;justify-content:center;gap:9px;
            background:var(--wh);color:var(--tm);
            padding:14px 24px;border-radius:var(--r1);border:1.5px solid var(--bd);
            font-family:'DM Sans',sans-serif;font-size:15px;font-weight:600;
            text-decoration:none;transition:all var(--tr);
        }
        .btn-secondary:hover{border-color:var(--g);color:var(--g);background:var(--gp);transform:translateY(-2px)}

        /* ─── KONFETTI ────────────────────────────────────── */
        #konfetti{position:fixed;inset:0;pointer-events:none;z-index:9999;overflow:hidden}
        .kf{
            position:absolute;top:-12px;
            width:10px;height:10px;
            border-radius:2px;
            animation:kf-fall linear forwards;
        }
        @keyframes kf-fall{
            0%  {transform:translateY(0) rotate(0deg);opacity:1}
            80% {opacity:1}
            100%{transform:translateY(110vh) rotate(720deg);opacity:0}
        }

        /* ─── SCROLL TOP ──────────────────────────────────── */
        .stb{position:fixed;bottom:26px;right:26px;z-index:900;width:42px;height:42px;background:var(--g);color:var(--wh);border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(58,140,92,.4);opacity:0;transform:translateY(10px);pointer-events:none;transition:all var(--tr)}
        .stb.show{opacity:1;transform:none;pointer-events:all}
        .stb:hover{background:var(--gd);transform:translateY(-3px)}

        /* ─── RESPONSIVE ──────────────────────────────────── */
        @media(max-width:768px){
            .nav-menu{display:none}.hamburger{display:flex}
            .card{padding:20px 16px}
            .steps-card{padding:20px 16px}
            .det-grid{grid-template-columns:1fr}
            .det-item:nth-last-child(-n+2){border-bottom:1px solid var(--bd)}
            .det-item:last-child{border-bottom:none}
        }
        @media(max-width:480px){
            .actions{flex-direction:column}
            .btn-primary,.btn-secondary{min-width:100%}
        }
    </style>
</head>
<body>

<!-- KONFETTI CANVAS -->
<div id="konfetti"></div>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="nav-logo">
        <a href="beranda.php">
            <?php
            $lpaths = ['../assets/images/logo.png','../assets/logo.png','../images/logo.png'];
            $lf = '';
            foreach ($lpaths as $lp) { if (file_exists($lp)) { $lf = $lp; break; } }
            ?>
            <?php if ($lf): ?>
                <img src="<?= $lf ?>" alt="Rinjani Guide">
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

<!-- PAGE -->
<div class="page">

    <!-- SUCCESS HERO -->
    <div class="success-hero">

        <!-- Animasi centang -->
        <div class="check-ring">
            <svg viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg">
                <circle class="check-bg" cx="48" cy="48" r="44"/>
                <circle class="ring-circle" cx="48" cy="48" r="45"/>
                <polyline class="check-mark" points="28,50 42,64 68,34"/>
            </svg>
        </div>

        <div class="sh-label">Booking Berhasil</div>
        <h1 class="sh-title">Pembayaran Diterima!</h1>
        <p class="sh-sub">
            Bukti pembayaran Anda telah kami terima dan sedang dalam proses verifikasi.
            Tim kami akan menghubungi Anda dalam 1×24 jam.
        </p>

        <!-- Kode Booking -->
        <div class="kode-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8"  y1="2" x2="8"  y2="6"/>
                <line x1="3"  y1="10" x2="21" y2="10"/>
            </svg>
            Kode Booking:&nbsp;<strong id="kodeText"><?= htmlspecialchars($kode) ?></strong>
            <button class="btn-kode-copy" id="btnKodeCopy" title="Salin kode">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <rect x="9" y="9" width="13" height="13" rx="2"/>
                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                </svg>
            </button>
        </div>

        <!-- Status -->
        <div>
            <span class="status-badge">
                <span class="status-dot"></span>
                Menunggu Verifikasi Admin
            </span>
        </div>

    </div><!-- /.success-hero -->

    <!-- CONTENT -->
    <div class="content">

        <!-- Detail Pesanan -->
        <div class="card">
            <div class="card-h">Detail Pesanan</div>
            <div class="det-grid">
                <div class="det-item">
                    <span class="det-lbl">Destinasi</span>
                    <span class="det-val"><?= htmlspecialchars($booking['dest_name'] ?? '—') ?></span>
                </div>
                <div class="det-item">
                    <span class="det-lbl">Tanggal Pendakian</span>
                    <span class="det-val"><?= tglIndo($booking['tanggal_pendakian'] ?? '') ?></span>
                </div>
                <div class="det-item">
                    <span class="det-lbl">Jumlah Peserta</span>
                    <span class="det-val"><?= (int)($booking['jumlah_peserta'] ?? 1) ?> Orang</span>
                </div>
                <div class="det-item">
                    <span class="det-lbl">Metode Pembayaran</span>
                    <span class="det-val"><?= htmlspecialchars(strtoupper($booking['metode_bayar'] ?? '—')) ?></span>
                </div>
                <div class="det-item">
                    <span class="det-lbl">Nama Lengkap</span>
                    <span class="det-val"><?= htmlspecialchars($booking['nama_lengkap'] ?? '—') ?></span>
                </div>
                <div class="det-item">
                    <span class="det-lbl">No. WhatsApp</span>
                    <span class="det-val"><?= htmlspecialchars($booking['no_wa'] ?? '—') ?></span>
                </div>
                <div class="det-item full">
                    <span class="det-lbl">Total Pembayaran</span>
                    <span class="det-val green"><?= rupiah($booking['total_harga'] ?? 0) ?></span>
                </div>
            </div>
        </div>

        <!-- Langkah Selanjutnya -->
        <div class="steps-card">
            <div class="card-h">Apa Selanjutnya?</div>
            <div class="step-list">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-text">
                        <strong>Verifikasi Pembayaran</strong>
                        <span>Tim admin kami akan memverifikasi bukti pembayaran Anda dalam 1×24 jam kerja.</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-text">
                        <strong>Konfirmasi via WhatsApp</strong>
                        <span>Setelah terverifikasi, kami akan menghubungi Anda melalui nomor WhatsApp yang terdaftar.</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-text">
                        <strong>Persiapan Pendakian</strong>
                        <span>Kami akan mengirimkan panduan persiapan pendakian, packing list, dan briefing teknis.</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">4</div>
                    <div class="step-text">
                        <strong>Hari Pendakian</strong>
                        <span>Bertemu dengan guide kami di titik kumpul yang telah disepakati dan mulai petualangan Anda!</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info box -->
        <div class="info-box">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>Simpan kode booking <strong><?= htmlspecialchars($kode) ?></strong> sebagai referensi. Hubungi kami di WhatsApp jika ada pertanyaan terkait pesanan Anda.</span>
        </div>

        <!-- Tombol Aksi -->
        <div class="actions">
            <a href="beranda.php" class="btn-primary">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Kembali ke Beranda
            </a>
            <a href="paket.php" class="btn-secondary">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
                </svg>
                Lihat Paket Lain
            </a>
        </div>

    </div><!-- /.content -->
</div><!-- /.page -->

<button class="stb" id="stb">
    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="18 15 12 9 6 15"/>
    </svg>
</button>

<script>
(function () {
    'use strict';

    /* ── Navbar scroll ── */
    const nb  = document.getElementById('navbar');
    const stb = document.getElementById('stb');
    window.addEventListener('scroll', () => {
        nb.classList.toggle('scrolled', scrollY > 30);
        stb.classList.toggle('show',    scrollY > 300);
    }, { passive: true });
    stb.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    /* ── Hamburger ── */
    const ham = document.getElementById('ham');
    const mm  = document.getElementById('mm');
    ham.addEventListener('click', () => { ham.classList.toggle('open'); mm.classList.toggle('open'); });
    mm.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        ham.classList.remove('open'); mm.classList.remove('open');
    }));

    /* ── Salin kode booking ── */
    document.getElementById('btnKodeCopy').addEventListener('click', () => {
        const kode = document.getElementById('kodeText').textContent.trim();
        const btn  = document.getElementById('btnKodeCopy');
        navigator.clipboard.writeText(kode).then(() => {
            btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--g)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`;
            setTimeout(() => {
                btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>`;
            }, 2000);
        });
    });

    /* ── Konfetti ── */
    const canvas  = document.getElementById('konfetti');
    const colors  = ['#52b788','#3a8c5c','#2d6a4f','#d8f3dc','#b7e4c7','#f5c842','#ff6b6b','#74b9ff'];
    const shapes  = ['square','circle','rect'];
    const TOTAL   = 90;

    function spawnKonfetti() {
        for (let i = 0; i < TOTAL; i++) {
            const el    = document.createElement('div');
            const color = colors[Math.floor(Math.random() * colors.length)];
            const shape = shapes[Math.floor(Math.random() * shapes.length)];
            const size  = 6 + Math.random() * 8;
            const left  = Math.random() * 100;
            const delay = Math.random() * 1.6;
            const dur   = 2.4 + Math.random() * 2;

            el.className = 'kf';
            el.style.cssText = `
                left:${left}vw;
                width:${shape === 'rect' ? size * 2 : size}px;
                height:${size}px;
                background:${color};
                border-radius:${shape === 'circle' ? '50%' : shape === 'rect' ? '2px' : '2px'};
                animation-duration:${dur}s;
                animation-delay:${delay}s;
            `;
            canvas.appendChild(el);
        }
        // Bersihkan setelah animasi selesai
        setTimeout(() => { canvas.innerHTML = ''; }, 5000);
    }

    // Tunda sedikit agar animasi centang tampil dulu
    setTimeout(spawnKonfetti, 900);

})();
</script>
</body>
</html>