<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'guide') {
    header("Location: ../login.php");
    exit;
}

$guide_id = intval($_SESSION['id'] ?? $_SESSION['user_id'] ?? 0);

$guide_name = htmlspecialchars($_SESSION['nama'] ?? 'Guide');

$today = date('Y-m-d');

/* =========================
   HELPER: FORMAT TANGGAL INDONESIA (tanpa tahun)
========================= */

if(!function_exists('tglSingkatId')){

function tglSingkatId($tgl){

    $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];

    $ts = strtotime($tgl);

    if(!$ts) return '-';

    $namaHari  = $hari[(int)date('w',$ts)];
    $tanggal   = date('d',$ts);
    $namaBulan = $bulan[(int)date('n',$ts)];

    return $namaHari.', '.$tanggal.' '.$namaBulan;
}

}

/* =========================
   STATISTIK JADWAL
========================= */

$q_total = mysqli_query($koneksi,"
    SELECT COUNT(*) total
    FROM booking
    WHERE guide_id='$guide_id'
");

$total_jadwal = mysqli_fetch_assoc($q_total)['total'] ?? 0;

$q_bulan = mysqli_query($koneksi,"
    SELECT COUNT(*) total
    FROM booking
    WHERE guide_id='$guide_id'
    AND MONTH(tanggal)=MONTH(CURDATE())
    AND YEAR(tanggal)=YEAR(CURDATE())
");

$jadwal_bulan = mysqli_fetch_assoc($q_bulan)['total'] ?? 0;

$q_minggu = mysqli_query($koneksi,"
    SELECT COUNT(*) total
    FROM booking
    WHERE guide_id='$guide_id'
    AND tanggal >= CURDATE()
    AND tanggal <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
");

$jadwal_minggu = mysqli_fetch_assoc($q_minggu)['total'] ?? 0;


/* =========================
   DATA JADWAL
========================= */

$q_jadwal = mysqli_query($koneksi,"
    SELECT
        booking.*,
        destinasi.name AS destinasi_nama
    FROM booking
    LEFT JOIN destinasi
        ON booking.destinasi_id = destinasi.id
    WHERE booking.guide_id='$guide_id'
    ORDER BY booking.tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Jadwal Guide</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

:root{
    --navy:#0d1b2e;
    --blue:#2563eb;
    --green:#16a34a;
    --orange:#ea580c;
    --bg:#f1f5f9;
    --card:#ffffff;
    --text:#1e293b;
    --muted:#64748b;
    --border:#e2e8f0;
    --sidebar-w:230px;
    --radius:14px;
}

body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:var(--bg);
    color:var(--text);
    overflow-x:hidden;
}

.sidebar{
    width:230px;
    height:100vh;
    background:#0d1b2e;
    position:fixed;
    top:0;
    left:0;
    display:flex;
    flex-direction:column;
    z-index:100;
}

.sidebar-brand{
    display:flex;
    align-items:center;
    gap:12px;
    padding:25px 20px;
    border-bottom:1px solid rgba(255,255,255,.08);
}

.brand-icon{
    width:42px;
    height:42px;
    border-radius:50%;
    background:rgba(255,255,255,.1);
    display:flex;
    align-items:center;
    justify-content:center;
}

.brand-name{
    color:white;
    font-size:14px;
    font-weight:800;
}

.brand-sub{
    color:#94a3b8;
    font-size:12px;
}

.sidebar-nav{
    flex:1;
    padding:15px 12px;
}

.nav-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 14px;
    border-radius:10px;
    color:#cbd5e1;
    text-decoration:none;
    margin-bottom:6px;
    transition:.2s;
}

.nav-item:hover{
    background:rgba(255,255,255,.08);
}

.nav-item.active{
    background:#2563eb;
    color:white;
}

.sidebar-footer{
    padding:15px;
    border-top:1px solid rgba(255,255,255,.08);
}

.logout-btn{
    display:flex;
    align-items:center;
    gap:10px;
    color:#f87171;
    text-decoration:none;
    padding:10px;
}

.main{
    margin-left:230px;
    width:calc(100% - 230px);
    min-height:100vh;
    padding:30px;
    background:#f1f5f9;
}

.page-title{
    font-size:30px;
    font-weight:800;
}

.page-subtitle{
    color:var(--muted);
    margin-top:6px;
}

.stats-grid{
    margin-top:25px;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}

.stat-card{
    background:white;
    border-radius:16px;
    padding:24px;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}

.stat-title{
    font-size:14px;
    color:var(--muted);
}

.stat-value{
    font-size:34px;
    font-weight:800;
    margin-top:8px;
}

.blue{color:var(--blue);}
.green{color:var(--green);}
.orange{color:var(--orange);}

/* =========================
   TABLE CARD
========================= */

.card{
    margin-top:25px;
    background:white;
    border-radius:16px;
    border:1px solid var(--border);
    box-shadow:0 2px 10px rgba(0,0,0,.05);
    overflow:hidden;
}

.card-header{
    padding:20px 24px;
    border-bottom:1px solid var(--border);
    display:flex;
    align-items:center;
    gap:10px;
}

.card-header i{
    color:var(--blue);
}

.card-header h2{
    font-size:18px;
    font-weight:700;
}

.table-responsive{
    width:100%;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:var(--navy);
    color:white;
}

thead th{
    padding:14px 18px;
    text-align:left;
    font-size:13px;
    font-weight:600;
}

tbody td{
    padding:15px 18px;
    border-bottom:1px solid var(--border);
    font-size:14px;
}

tbody tr:nth-child(even){
    background:#f8fafc;
}

tbody tr:hover{
    background:#eef2ff;
}

/* =========================
   BADGE STATUS
========================= */

.badge{
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
}

.badge-success{
    background:#dcfce7;
    color:#166534;
}

.badge-warning{
    background:#fef3c7;
    color:#92400e;
}

.badge-danger{
    background:#fee2e2;
    color:#991b1b;
}

.badge-primary{
    background:#dbeafe;
    color:#1d4ed8;
}

/* =========================
   EMPTY DATA
========================= */

.empty{
    padding:50px;
    text-align:center;
    color:var(--muted);
}

/* =========================
   MOBILE
========================= */

@media(max-width:991px){

    .stats-grid{
        grid-template-columns:1fr;
    }

    .main{
        margin-left:0;
        padding:20px;
    }

}

</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main">

    <h1 class="page-title">
        Jadwal Guide
    </h1>

    <p class="page-subtitle">
        Selamat datang, <?= $guide_name ?>
    </p>

    <!-- STAT CARD -->

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-title">
                Total Jadwal
            </div>

            <div class="stat-value blue">
                <?= $total_jadwal ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                Jadwal Bulan Ini
            </div>

            <div class="stat-value green">
                <?= $jadwal_bulan ?>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-title">
                Jadwal 7 Hari Ke Depan
            </div>

            <div class="stat-value orange">
                <?= $jadwal_minggu ?>
            </div>
        </div>

    </div>

    <!-- TABEL JADWAL -->

    <div class="card">

        <div class="card-header">
            <i class="fas fa-calendar-days"></i>
            <h2>Daftar Jadwal Guide</h2>
        </div>

        <div class="table-responsive">

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Whatsapp</th>
                        <th>Destinasi</th>
                        <th>Tanggal</th>
                        <th>Peserta</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(mysqli_num_rows($q_jadwal) > 0): ?>

                    <?php $no = 1; while($row = mysqli_fetch_assoc($q_jadwal)): ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['nama_customer']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['whatsapp']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['destinasi_nama'] ?? '-'); ?>
                        </td>

                        <td>
                            <?= tglSingkatId($row['tanggal']); ?>
                        </td>

                        <td>
                            <?= $row['jumlah_orang']; ?> Orang
                        </td>

                        <td>

                            <?php

                            $sudah_lewat = ($row['tanggal'] < $today);

                            if($sudah_lewat){
                                echo '<span class="badge badge-success">Selesai</span>';
                            } else {
                                echo '<span class="badge badge-warning">Menunggu</span>';
                            }

                            ?>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7">

                            <div class="empty">
                                <i class="fas fa-calendar-xmark"
                                   style="font-size:42px;margin-bottom:12px;display:block;">
                                </i>

                                Belum ada jadwal pendakian.
                            </div>

                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>