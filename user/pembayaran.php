<?php
// ============================================================
//  Rinjani Guide — pembayaran.php  (folder: user/)
//  Step 3 dari 3: Pembayaran
// ============================================================
ob_start(); // pastikan header() selalu bisa jalan walau ada output tak sengaja
session_start();

// ── Guard: wajib dari konfirmasi.php ─────────────────────────
if (empty($_SESSION['booking_kode']) || empty($_SESSION['booking_dest'])) {
    header('Location: booking.php');
    exit;
}

require_once '../config/koneksi.php';

// ── Data session ──────────────────────────────────────────────
$form  = $_SESSION['booking_form']  ?? [];
$dest  = $_SESSION['booking_dest']  ?? [];
$total = (int)($_SESSION['booking_total'] ?? 0);
$kode  = $_SESSION['booking_kode']  ?? '';

// ── Batas waktu pembayaran (24 jam dari sekarang, simpan di session) ──
if (empty($_SESSION['payment_deadline'])) {
    $_SESSION['payment_deadline'] = time() + (24 * 60 * 60);
}
$deadline    = (int)$_SESSION['payment_deadline'];
$sisa_detik  = max(0, $deadline - time());

// ── Metode pembayaran ─────────────────────────────────────────
$metode_list = [
    'bca'   => [
        'label'  => 'Bank Central Asia (BCA)',
        'logo'   => 'BCA',
        'color'  => '#005baa',
        'rek'    => '1234 5678 9012',
        'atas'   => 'Rinjani Guide Indonesia',
    ],
    'bri'   => [
        'label'  => 'Bank Rakyat Indonesia (BRI)',
        'logo'   => 'BRI',
        'color'  => '#00529B',
        'rek'    => '0987 6543 2100',
        'atas'   => 'Rinjani Guide Indonesia',
    ],
    'mandiri' => [
        'label'  => 'Bank Mandiri',
        'logo'   => 'MDR',
        'color'  => '#003087',
        'rek'    => '1400 0011 2233',
        'atas'   => 'Rinjani Guide Indonesia',
    ],
    'gopay'  => [
        'label'  => 'GoPay',
        'logo'   => 'GP',
        'color'  => '#00AED6',
        'rek'    => '0812-3456-7890',
        'atas'   => 'Rinjani Guide',
    ],
    'dana'   => [
        'label'  => 'DANA',
        'logo'   => 'DANA',
        'color'  => '#108EE9',
        'rek'    => '0812-3456-7890',
        'atas'   => 'Rinjani Guide',
    ],
    'kas'    => [
        'label'  => 'Bayar Tunai (Kas)',
        'logo'   => 'KAS',
        'color'  => '#2d6a4f',
        'rek'    => 'Bayar langsung ke kantor',
        'atas'   => 'Rinjani Guide Indonesia',
    ],
];
$selected_metode = 'bca';

// ── Handle upload bukti ───────────────────────────────────────
$upload_ok    = false;
$upload_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $selected_metode = $_POST['metode'] ?? 'bca';

    // ==========================
    // METODE KAS → TANPA UPLOAD
    // ==========================
    if ($selected_metode === 'kas') {

        $kode_esc = mysqli_real_escape_string($koneksi, $kode);
        $mt_esc   = mysqli_real_escape_string($koneksi, $selected_metode);

        mysqli_query($koneksi,"
            UPDATE booking
            SET status='paid',
                metode_bayar='$mt_esc'
            WHERE id='$kode_esc'
        ");

        unset(
            $_SESSION['booking_form'],
            $_SESSION['booking_dest'],
            $_SESSION['booking_total'],
            $_SESSION['booking_kode'],
            $_SESSION['payment_deadline'],
            $_SESSION['booking_step']
        );

        if (ob_get_level() > 0) { ob_end_clean(); }
        header('Location: sukses.php?kode=' . urlencode($kode));
        echo '<script>window.location.href="sukses.php?kode=' . urlencode($kode) . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=sukses.php?kode=' . urlencode($kode) . '"></noscript>';
        exit;
    }

    // ==========================
    // SELAIN KAS → WAJIB UPLOAD
    // ==========================

    if (!isset($_FILES['bukti'])) {
        $upload_error='Silakan upload bukti pembayaran.';
    } else {

        $file = $_FILES['bukti'];

        $allow=['image/jpeg','image/jpg','image/png','application/pdf'];
        $maxsz=5*1024*1024;

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $upload_error='File gagal diupload.';
        }
        elseif (!in_array($file['type'],$allow)) {
            $upload_error='Format file harus PNG, JPG, atau PDF.';
        }
        elseif ($file['size']>$maxsz) {
            $upload_error='Ukuran file maksimal 5MB.';
        }
        else {

            $ext=pathinfo($file['name'],PATHINFO_EXTENSION);

            $filename='bukti_'.$kode.'_'.time().'.'.strtolower($ext);

            $dest_dir='../upload/bukti/';

            if(!is_dir($dest_dir)){
                mkdir($dest_dir,0755,true);
            }

            if(move_uploaded_file($file['tmp_name'],$dest_dir.$filename)){

                $kode_esc=mysqli_real_escape_string($koneksi,$kode);
                $file_esc=mysqli_real_escape_string($koneksi,$filename);
                $mt_esc=mysqli_real_escape_string($koneksi,$selected_metode);

                mysqli_query($koneksi,"
                    UPDATE booking
                    SET status='paid',
                        bukti_pembayaran='$file_esc',
                        metode_bayar='$mt_esc'
                    WHERE id='$kode_esc'
                ");

                unset(
                    $_SESSION['booking_form'],
                    $_SESSION['booking_dest'],
                    $_SESSION['booking_total'],
                    $_SESSION['booking_kode'],
                    $_SESSION['payment_deadline'],
                    $_SESSION['booking_step']
                );

                if (ob_get_level() > 0) { ob_end_clean(); }
                header('Location: sukses.php?kode='.urlencode($kode));
                echo '<script>window.location.href="sukses.php?kode=' . urlencode($kode) . '";</script>';
                echo '<noscript><meta http-equiv="refresh" content="0;url=sukses.php?kode=' . urlencode($kode) . '"></noscript>';
                exit;
            }
            else{
                $upload_error='Gagal menyimpan file.';
            }
        }
    }
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
    <title>Pembayaran — Rinjani Guide</title>
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
        .btn-nav::after{display:none!important}
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

        /* ─── HERO ────────────────────────────────────────── */
        .hero{margin-top:var(--nh);min-height:180px;display:flex;align-items:center;padding:44px 5% 36px;position:relative;overflow:hidden}
        .hero::before{content:'';position:absolute;inset:0;z-index:0;background:linear-gradient(135deg,#edf3ec 0%,#dce9db 40%,rgba(210,230,210,.4) 100%)}
        .hero::after{content:'';position:absolute;inset:0;z-index:1;background:linear-gradient(to right,rgba(245,246,242,.97) 42%,rgba(245,246,242,.04) 100%),url('../assets/images/booking-hero.jpg') right center/cover no-repeat}
        .hero-inner{position:relative;z-index:2}
        .hero-label{font-size:12px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--g);margin-bottom:10px}
        .hero-title{font-family:'Playfair Display',serif;font-size:clamp(26px,5vw,46px);font-weight:800;color:var(--td);line-height:1.1;margin-bottom:6px}
        .hero-sub{font-size:15px;color:var(--tl)}

        /* ─── STEPPER ─────────────────────────────────────── */
        .stepper-bar{background:var(--wh);border-bottom:1px solid var(--bd);padding:16px 5%}
        .stepper{display:flex;align-items:center;max-width:640px}
        .si{display:flex;align-items:center;flex:1}
        .si:last-child{flex:0}
        .sdot{width:40px;height:40px;border-radius:50%;border:2px solid var(--bd);background:var(--wh);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:var(--tl);flex-shrink:0;transition:all var(--tr)}
        .sdot.done{background:var(--g);border-color:var(--g);color:var(--wh)}
        .sdot.active{background:var(--gd);border-color:var(--gd);color:var(--wh);box-shadow:0 0 0 4px rgba(58,140,92,.14)}
        .slbl{margin-left:9px;font-size:14px;font-weight:600;color:var(--tl);white-space:nowrap}
        .slbl.done,.slbl.active{color:var(--td)}
        .sline{flex:1;height:2px;background:var(--bd);margin:0 14px;border-radius:1px}
        .sline.done{background:var(--g)}

        /* ─── PAGE LAYOUT ─────────────────────────────────── */
        .page{max-width:1100px;margin:0 auto;padding:36px 5% 80px}
        .two-col{display:grid;grid-template-columns:1fr 420px;gap:24px;align-items:start}

        /* ─── CARD BASE ───────────────────────────────────── */
        .card{background:var(--wh);border:1.5px solid var(--bd);border-radius:var(--r2);padding:28px 32px;box-shadow:var(--sh0)}
        .card-h{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--td);margin-bottom:4px}
        .card-sub{font-size:14px;color:var(--tl);margin-bottom:22px}

        /* ─── METODE DROPDOWN ─────────────────────────────── */
        .metode-select-wrap{position:relative;margin-bottom:20px}
        .metode-select{
            width:100%;padding:14px 44px 14px 56px;
            border:1.5px solid var(--bd);border-radius:var(--r1);
            font-family:'DM Sans',sans-serif;font-size:15px;color:var(--td);
            background:var(--wh);appearance:none;cursor:pointer;outline:none;
            transition:border-color var(--tr),box-shadow var(--tr);
        }
        .metode-select:focus{border-color:var(--g);box-shadow:0 0 0 3px rgba(58,140,92,.1)}
        .metode-logo{
            position:absolute;left:14px;top:50%;transform:translateY(-50%);
            width:32px;height:24px;border-radius:5px;
            display:flex;align-items:center;justify-content:center;
            font-size:10px;font-weight:800;color:#fff;
            transition:background var(--tr);pointer-events:none;
        }
        .metode-arrow{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--tl);pointer-events:none}

        /* ─── REKENING BOX ────────────────────────────────── */
        .rek-box{border:1.5px solid var(--bd);border-radius:var(--r1);padding:20px 20px 16px;margin-bottom:14px}
        .rek-label{font-size:12px;color:var(--tl);margin-bottom:4px}
        .rek-num{font-size:22px;font-weight:800;color:var(--td);letter-spacing:.5px;margin-bottom:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
        .btn-copy{
            display:inline-flex;align-items:center;gap:6px;
            background:var(--wh);border:1.5px solid var(--bd);
            border-radius:8px;padding:7px 14px;
            font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;
            color:var(--tm);cursor:pointer;transition:all var(--tr);
        }
        .btn-copy:hover{border-color:var(--g);color:var(--g);background:var(--gp)}
        .btn-copy.copied{border-color:var(--g);color:var(--g);background:var(--gp)}
        .rek-atas-label{font-size:12px;color:var(--tl);margin-bottom:3px}
        .rek-atas{font-size:15px;font-weight:600;color:var(--tm);margin-bottom:14px}
        .rek-total-label{font-size:13px;color:var(--tl);margin-bottom:3px}
        .rek-total{font-family:'Playfair Display',serif;font-size:24px;font-weight:800;color:var(--g)}

        /* ─── NOTICE ──────────────────────────────────────── */
        .notice{
            display:flex;align-items:center;gap:10px;
            background:var(--gp);border:1px solid rgba(58,140,92,.2);
            border-radius:var(--r1);padding:13px 16px;
            font-size:14px;color:var(--gm);font-weight:500;margin-bottom:20px;
        }
        .notice svg{color:var(--g);flex-shrink:0}

        /* ─── COUNTDOWN ───────────────────────────────────── */
        .countdown-wrap{margin-bottom:20px}
        .cd-label{font-size:13px;color:var(--tl);margin-bottom:8px;font-weight:500}
        .cd-row{display:flex;align-items:flex-start;gap:16px}
        .cd-timer{display:flex;align-items:center;gap:6px}
        .cd-block{text-align:center}
        .cd-num{
            font-family:'Playfair Display',serif;
            font-size:32px;font-weight:800;color:var(--td);
            display:block;line-height:1;
        }
        .cd-unit{font-size:11px;color:var(--tl);margin-top:3px}
        .cd-sep{font-size:28px;font-weight:700;color:var(--bd);margin-top:2px}
        .cd-warn{
            flex:1;background:#fef9ec;border:1px solid #f7d45e;
            border-radius:var(--r1);padding:12px 14px;font-size:13px;color:#7a5900;
            display:flex;align-items:flex-start;gap:9px;
        }
        .cd-warn svg{flex-shrink:0;margin-top:1px}

        /* ─── UPLOAD AREA ─────────────────────────────────── */
        .upload-section{margin-bottom:8px}
        .upload-label-text{font-size:14px;font-weight:600;color:var(--td);margin-bottom:4px}
        .upload-sub{font-size:13px;color:var(--tl);margin-bottom:12px}

        .upload-area{
            border:2px dashed var(--bd);border-radius:var(--r1);
            padding:28px 20px;text-align:center;
            cursor:pointer;transition:border-color var(--tr),background var(--tr);
            position:relative;
        }
        .upload-area:hover,.upload-area.drag{border-color:var(--g);background:var(--gp)}
        .upload-area input[type=file]{
            position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;
        }
        .upload-icon{width:44px;height:44px;background:var(--gp);border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px}
        .upload-icon svg{color:var(--g)}
        .upload-text{font-size:15px;font-weight:600;color:var(--td);margin-bottom:4px}
        .upload-hint{font-size:13px;color:var(--tl)}

        /* Preview file terpilih */
        .file-preview{
            display:none;align-items:center;gap:12px;
            background:var(--gp);border:1.5px solid rgba(58,140,92,.22);
            border-radius:var(--r1);padding:12px 16px;margin-top:10px;
        }
        .file-preview.show{display:flex}
        .file-preview svg{color:var(--g);flex-shrink:0}
        .fp-name{font-size:14px;font-weight:600;color:var(--td);flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .fp-size{font-size:12px;color:var(--tl)}
        .fp-remove{background:none;border:none;color:var(--tl);cursor:pointer;padding:4px;transition:color var(--tr)}
        .fp-remove:hover{color:#e63946}

        /* Error upload */
        .upload-err{display:none;align-items:center;gap:8px;background:#fef2f2;border:1px solid #fecaca;border-radius:var(--r1);padding:11px 14px;margin-top:10px;font-size:13px;color:#991b1b}
        .upload-err.show{display:flex}

        /* Submit button */
        .btn-bayar{
            display:flex;align-items:center;justify-content:center;gap:10px;
            width:100%;padding:16px 32px;
            background:var(--gd);color:var(--wh);
            border:none;border-radius:var(--r1);
            font-family:'DM Sans',sans-serif;font-size:16px;font-weight:700;
            cursor:pointer;margin-top:20px;
            transition:all var(--tr);box-shadow:0 6px 22px rgba(26,58,42,.3);
        }
        .btn-bayar:hover{background:var(--gm);transform:translateY(-2px);box-shadow:0 10px 28px rgba(26,58,42,.28)}
        .btn-bayar:active{transform:translateY(0)}
        .btn-bayar:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none}
        .btn-bayar.ld{pointer-events:none;opacity:.75}
        .btn-bayar.ld .bt{display:none}
        .btn-bayar.ld::after{content:'';width:20px;height:20px;border:2.5px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:sp .7s linear infinite}
        @keyframes sp{to{transform:rotate(360deg)}}

        /* ─── RINGKASAN KANAN ─────────────────────────────── */
        .summary-card{background:var(--wh);border:1.5px solid var(--bd);border-radius:var(--r2);padding:28px 28px;box-shadow:var(--sh0)}
        .summary-title{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--td);margin-bottom:4px}
        .summary-sub{font-size:13px;color:var(--tl);margin-bottom:20px}

        .info-row{
            display:flex;align-items:flex-start;gap:13px;
            padding:14px 0;border-bottom:1px solid var(--bd);
        }
        .info-row:last-of-type{border-bottom:none}
        .info-icon-sm{width:34px;height:34px;flex-shrink:0;background:var(--cr);border-radius:8px;display:flex;align-items:center;justify-content:center}
        .info-icon-sm svg{color:var(--tm)}
        .info-lbl{font-size:12px;color:var(--tl);margin-bottom:3px}
        .info-val{font-size:15px;font-weight:700;color:var(--td)}

        /* Dua info dalam satu row */
        .info-dual{display:grid;grid-template-columns:1fr 1fr;gap:0;border-bottom:1px solid var(--bd)}
        .info-dual .info-row{border-bottom:none;padding:14px 0}

        /* Subtotal / Total */
        .price-rows{margin-top:6px;padding-top:6px}
        .price-line{display:flex;justify-content:space-between;align-items:center;font-size:14px;color:var(--tm);padding:6px 0}
        .price-line.total{padding-top:12px;margin-top:6px;border-top:1px solid var(--bd)}
        .price-line.total .pl-label{font-weight:700;font-size:15px;color:var(--td)}
        .price-line.total .pl-val{font-family:'Playfair Display',serif;font-size:22px;font-weight:800;color:var(--g)}

        /* Aman notice */
        .safe-box{
            display:flex;align-items:flex-start;gap:11px;
            background:var(--gp);border:1px solid rgba(58,140,92,.2);
            border-radius:var(--r1);padding:14px 16px;margin-top:18px;
        }
        .safe-box svg{color:var(--g);flex-shrink:0;margin-top:1px}
        .safe-box strong{font-size:14px;font-weight:700;color:var(--g);display:block;margin-bottom:2px}
        .safe-box span{font-size:13px;color:var(--gm)}

        /* ─── FEATURES ────────────────────────────────────── */
        .fstrip{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--bd);border:1px solid var(--bd);border-radius:var(--r2);overflow:hidden;margin-top:24px}
        .feat{background:var(--wh);padding:18px 16px;display:flex;align-items:center;gap:12px;transition:background var(--tr)}
        .feat:hover{background:var(--gp)}
        .feat-ic{width:38px;height:38px;flex-shrink:0;background:var(--gp);border-radius:10px;display:flex;align-items:center;justify-content:center}
        .feat-ic svg{color:var(--g)}
        .feat span{font-size:13px;font-weight:600;color:var(--tm)}

        /* ─── SCROLL TOP ──────────────────────────────────── */
        .stb{position:fixed;bottom:26px;right:26px;z-index:900;width:42px;height:42px;background:var(--g);color:var(--wh);border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(58,140,92,.4);opacity:0;transform:translateY(10px);pointer-events:none;transition:all var(--tr)}
        .stb.show{opacity:1;transform:none;pointer-events:all}
        .stb:hover{background:var(--gd);transform:translateY(-3px)}

        /* ─── RESPONSIVE ──────────────────────────────────── */
        @media(max-width:960px){.two-col{grid-template-columns:1fr}}
        @media(max-width:768px){
            .nav-menu{display:none}.hamburger{display:flex}
            .slbl{display:none}
            .card{padding:22px 16px}
            .summary-card{padding:22px 16px}
            .fstrip{grid-template-columns:repeat(2,1fr)}
        }
        @media(max-width:480px){
            .cd-num{font-size:26px}
            .fstrip{grid-template-columns:1fr}
            .info-dual{grid-template-columns:1fr}
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="nav-logo">
        <a href="beranda.php">
            <?php
            $lpaths = ['../upload/logohitam.png','../upload/logohitam.png','../upload/logohitam.png'];
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

<!-- HERO -->
<div class="hero">
    <div class="hero-inner">
        <div class="hero-label">Booking Pendakian</div>
        <h1 class="hero-title">Pembayaran</h1>
        <p class="hero-sub">Selesaikan pembayaran untuk mengamankan booking pendakian Anda.</p>
    </div>
</div>

<!-- STEPPER -->
<div class="stepper-bar">
    <div class="stepper">
        <div class="si">
            <div class="sdot done">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span class="slbl done">Pesanan ✓</span>
        </div>
        <div class="sline done"></div>
        <div class="si">
            <div class="sdot done">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span class="slbl done">Konfirmasi ✓</span>
        </div>
        <div class="sline done"></div>
        <div class="si">
            <div class="sdot active">3</div>
            <span class="slbl active">Pembayaran</span>
        </div>
    </div>
</div>

<!-- PAGE -->
<div class="page">
    <div class="two-col">

        <!-- ═══ KOLOM KIRI: PEMBAYARAN ═══ -->
        <div>
            <div class="card">
                <div class="card-h">Metode Pembayaran</div>
                <div class="card-sub">Lakukan pembayaran ke salah satu rekening berikut.</div>

                <form method="POST" action="pembayaran.php" enctype="multipart/form-data" id="payForm">

                    <!-- Dropdown Metode -->
                    <div class="metode-select-wrap">
                        <div class="metode-logo" id="metodeLogo" style="background:#005baa">BCA</div>
                        <select class="metode-select" name="metode" id="metodeSelect">
                            <?php foreach ($metode_list as $key => $m): ?>
                            <option value="<?= $key ?>"
                                    data-rek="<?= htmlspecialchars($m['rek']) ?>"
                                    data-atas="<?= htmlspecialchars($m['atas']) ?>"
                                    data-logo="<?= htmlspecialchars($m['logo']) ?>"
                                    data-color="<?= htmlspecialchars($m['color']) ?>"
                                    <?= $key === $selected_metode ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['label']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="metode-arrow">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Box Rekening -->
                    <div class="rek-box">
                        <div class="rek-label">Nomor Rekening</div>
                        <div class="rek-num">
                            <span id="rekNum"><?= htmlspecialchars($metode_list[$selected_metode]['rek']) ?></span>
                            <button type="button" class="btn-copy" id="btnCopy">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <rect x="9" y="9" width="13" height="13" rx="2"/>
                                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                                </svg>
                                <span id="copyTxt">Salin</span>
                            </button>
                        </div>
                        <div class="rek-atas-label">Atas Nama</div>
                        <div class="rek-atas" id="rekAtas"><?= htmlspecialchars($metode_list[$selected_metode]['atas']) ?></div>
                        <div class="rek-total-label">Total Pembayaran</div>
                        <div class="rek-total"><?= $total > 0 ? rupiah($total) : htmlspecialchars($dest['price'] ?? '—') ?></div>
                    </div>

                    <!-- Notice -->
                    <div class="notice">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Segera lakukan pembayaran sebelum waktu habis.
                    </div>

                    <!-- Countdown -->
                    <div class="countdown-wrap">
                        <div class="cd-label">Batas Waktu Pembayaran</div>
                        <div class="cd-row">
                            <div class="cd-timer">
                                <div class="cd-block">
                                    <span class="cd-num" id="cdJam">--</span>
                                    <div class="cd-unit">Jam</div>
                                </div>
                                <span class="cd-sep">:</span>
                                <div class="cd-block">
                                    <span class="cd-num" id="cdMenit">--</span>
                                    <div class="cd-unit">Menit</div>
                                </div>
                                <span class="cd-sep">:</span>
                                <div class="cd-block">
                                    <span class="cd-num" id="cdDetik">--</span>
                                    <div class="cd-unit">Detik</div>
                                </div>
                            </div>
                            <div class="cd-warn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                    <line x1="12" y1="9" x2="12" y2="13"/>
                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                                Pesanan Anda akan otomatis dibatalkan jika melewati batas waktu pembayaran.
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti -->
                    <div class="upload-section">
                        <div class="upload-label-text">Bukti Pembayaran</div>
                        <div class="upload-sub">Upload bukti pembayaran setelah Anda transfer.</div>

                        <div class="upload-area" id="uploadArea">
                            <input type="file" name="bukti" id="buktiInput"
                                   accept=".png,.jpg,.jpeg,.pdf">
                            <div class="upload-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="16 16 12 12 8 16"/>
                                    <line x1="12" y1="12" x2="12" y2="21"/>
                                    <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/>
                                </svg>
                            </div>
                            <div class="upload-text">Upload Bukti Pembayaran</div>
                            <div class="upload-hint">PNG, JPG atau PDF. Maks 5MB</div>
                        </div>

                        <!-- Preview file -->
                        <div class="file-preview" id="filePreview">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <span class="fp-name" id="fpName">—</span>
                            <span class="fp-size" id="fpSize">—</span>
                            <button type="button" class="fp-remove" id="fpRemove" title="Hapus">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6"  y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Error upload dari PHP -->
                        <?php if (!empty($upload_error)): ?>
                        <div class="upload-err show">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <?= htmlspecialchars($upload_error) ?>
                        </div>
                        <?php endif; ?>

                        <!-- Error JS -->
                        <div class="upload-err" id="jsErr">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span id="jsErrTxt"></span>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn-bayar" id="btnBayar" disabled>
                        <span class="bt">Konfirmasi Pembayaran</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </button>

                </form>
            </div>
        </div><!-- /kolom kiri -->

        <!-- ═══ KOLOM KANAN: RINGKASAN ═══ -->
        <div>
            <div class="summary-card">
                <div class="summary-title">Ringkasan Pesanan</div>
                <div class="summary-sub">Detail pesanan Anda.</div>

                <!-- Destinasi -->
                <div class="info-row">
                    <div class="info-icon-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-lbl">Destinasi</div>
                        <div class="info-val"><?= htmlspecialchars($dest['name'] ?? '—') ?></div>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="info-row">
                    <div class="info-icon-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8"  y1="2" x2="8"  y2="6"/>
                            <line x1="3"  y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-lbl">Tanggal Pendakian</div>
                        <div class="info-val"><?= tglIndo($form['tanggal'] ?? '') ?></div>
                    </div>
                </div>

                <!-- Peserta -->
                <div class="info-row">
                    <div class="info-icon-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                            <path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-lbl">Jumlah Peserta</div>
                        <div class="info-val"><?= (int)($form['peserta'] ?? 1) ?> Orang</div>
                    </div>
                </div>

                <!-- Nama & WA -->
                <div class="info-dual">
                    <div class="info-row">
                        <div class="info-icon-sm">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="info-lbl">Nama Lengkap</div>
                            <div class="info-val" style="font-size:14px"><?= htmlspecialchars($form['nama'] ?? '—') ?></div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon-sm">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="info-lbl">No. WhatsApp</div>
                            <div class="info-val" style="font-size:14px"><?= htmlspecialchars($form['wa'] ?? '—') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="info-row">
                    <div class="info-icon-sm">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="info-lbl">Catatan</div>
                        <div class="info-val" style="font-size:14px">
                            <?= !empty($form['catatan'])
                                ? htmlspecialchars($form['catatan'])
                                : '<span style="color:var(--tl);font-weight:400">Tidak ada catatan</span>'
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Harga -->
                <div class="price-rows">
                    <div class="price-line">
                        <span class="pl-label">Subtotal</span>
                        <span class="pl-val"><?= $total > 0 ? rupiah($total) : htmlspecialchars($dest['price'] ?? '—') ?></span>
                    </div>
                    <div class="price-line total">
                        <span class="pl-label">Total Pembayaran</span>
                        <span class="pl-val"><?= $total > 0 ? rupiah($total) : htmlspecialchars($dest['price'] ?? '—') ?></span>
                    </div>
                </div>

                <!-- Aman -->
                <div class="safe-box">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                    <div>
                        <strong>Transaksi Aman &amp; Terlindungi</strong>
                        <span>Data dan pembayaran Anda aman bersama Rinjani Guide.</span>
                    </div>
                </div>
            </div>
        </div><!-- /kolom kanan -->

    </div><!-- /.two-col -->

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
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?= $ic ?></svg>
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

<!-- DATA PHP → JS -->
<script>
const METODE = <?php
    $js_m = [];
    foreach ($metode_list as $k => $m) {
        $js_m[$k] = ['rek' => $m['rek'], 'atas' => $m['atas'], 'logo' => $m['logo'], 'color' => $m['color']];
    }
    echo json_encode($js_m, JSON_UNESCAPED_UNICODE);
?>;
const DEADLINE_TS = <?= $deadline ?>;
</script>

<script>
(function () {
    'use strict';

    /* Navbar */
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

    /* ── Dropdown metode ── */
    const selMetode = document.getElementById('metodeSelect');
    const uploadSection=document.querySelector('.upload-section');
    const btnBayar=document.getElementById('btnBayar');
    const logo      = document.getElementById('metodeLogo');
    const rekNum    = document.getElementById('rekNum');
    const rekAtas   = document.getElementById('rekAtas');

    function updateMetode() {

    const key = selMetode.value;
    const m = METODE[key];

    if(!m) return;

    logo.textContent = m.logo;
    logo.style.background = m.color;
    rekNum.textContent = m.rek;
    rekAtas.textContent = m.atas;

    document.getElementById('copyTxt').textContent='Salin';
    document.getElementById('btnCopy').classList.remove('copied');

    if(key === 'kas'){

        uploadSection.style.display='none';

        btnBayar.disabled=false;

    }else{

        uploadSection.style.display='block';
    }
}

    selMetode.addEventListener('change', updateMetode);
    updateMetode();

    /* ── Salin nomor rekening ── */
    document.getElementById('btnCopy').addEventListener('click', () => {
        const num = document.getElementById('rekNum').textContent.trim();
        navigator.clipboard.writeText(num).then(() => {
            const btn = document.getElementById('btnCopy');
            const txt = document.getElementById('copyTxt');
            txt.textContent = 'Tersalin!';
            btn.classList.add('copied');
            setTimeout(() => { txt.textContent = 'Salin'; btn.classList.remove('copied'); }, 2500);
        }).catch(() => {
            // Fallback
            const ta = document.createElement('textarea');
            ta.value = num; document.body.appendChild(ta);
            ta.select(); document.execCommand('copy');
            document.body.removeChild(ta);
        });
    });

    /* ── Countdown ── */
    const cdJam   = document.getElementById('cdJam');
    const cdMenit = document.getElementById('cdMenit');
    const cdDetik = document.getElementById('cdDetik');

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
        const now  = Math.floor(Date.now() / 1000);
        const sisa = Math.max(0, DEADLINE_TS - now);
        const jam  = Math.floor(sisa / 3600);
        const min  = Math.floor((sisa % 3600) / 60);
        const det  = sisa % 60;
        cdJam.textContent   = pad(jam);
        cdMenit.textContent = pad(min);
        cdDetik.textContent = pad(det);

        if (sisa === 0) {
            clearInterval(timer);
            // Tampilkan pesan waktu habis
            alert('Batas waktu pembayaran telah habis. Pesanan dibatalkan.');
            window.location.href = 'booking.php';
        }
    }
    tick();
    const timer = setInterval(tick, 1000);

    /* ── Upload file ── */
    const uploadArea  = document.getElementById('uploadArea');
    const buktiInput  = document.getElementById('buktiInput');
    const filePreview = document.getElementById('filePreview');
    const fpName      = document.getElementById('fpName');
    const fpSize      = document.getElementById('fpSize');
    const fpRemove    = document.getElementById('fpRemove');
    const jsErr       = document.getElementById('jsErr');
    const jsErrTxt    = document.getElementById('jsErrTxt');

    function formatBytes(b) {
        if (b < 1024)        return b + ' B';
        if (b < 1024*1024)   return (b/1024).toFixed(1) + ' KB';
        return (b/(1024*1024)).toFixed(1) + ' MB';
    }

    function showErr(msg) {
        jsErrTxt.textContent = msg;
        jsErr.classList.add('show');
    }
    function hideErr() { jsErr.classList.remove('show'); }

    function handleFile(file) {
        hideErr();
        if (!file) return;

        const allowed = ['image/jpeg','image/jpg','image/png','application/pdf'];
        if (!allowed.includes(file.type)) {
            showErr('Format file harus PNG, JPG, atau PDF.');
            buktiInput.value = '';
            filePreview.classList.remove('show');
            btnBayar.disabled = true;
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            showErr('Ukuran file maksimal 5 MB.');
            buktiInput.value = '';
            filePreview.classList.remove('show');
            btnBayar.disabled = true;
            return;
        }

        fpName.textContent = file.name;
        fpSize.textContent = formatBytes(file.size);
        filePreview.classList.add('show');
        btnBayar.disabled = false;
    }

    buktiInput.addEventListener('change', () => handleFile(buktiInput.files[0]));

    // Drag & drop
    uploadArea.addEventListener('dragover',  e => { e.preventDefault(); uploadArea.classList.add('drag'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('drag');
        const file = e.dataTransfer.files[0];
        if (file) {
            // Transfer ke input
            const dt = new DataTransfer();
            dt.items.add(file);
            buktiInput.files = dt.files;
            handleFile(file);
        }
    });

    // Hapus file
    fpRemove.addEventListener('click', () => {
        buktiInput.value = '';
        filePreview.classList.remove('show');
        btnBayar.disabled = true;
        hideErr();
    });

    /* ── Loading state saat submit ── */
    document.getElementById('payForm').addEventListener('submit', e => {

    if(
        selMetode.value !== 'kas' &&
        !buktiInput.files.length
    ){
        e.preventDefault();
        showErr('Silakan upload bukti pembayaran.');
        return;
    }

    btnBayar.classList.add('ld');
    clearInterval(timer);
});

})();
</script>
</body>
</html>