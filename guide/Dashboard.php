<?php
session_start();
include '../config/koneksi.php';

// ── Auth: hanya guide yang boleh masuk ──────────────────────────────────────
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'guide') {
    header("Location: ../login.php");
    exit();
}

$guide_id   = intval($_SESSION['id']);
$guide_name = htmlspecialchars($_SESSION['nama'] ?? 'Guide');

// ── Statistik booking milik guide ini ───────────────────────────────────────
$q_total = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM booking
     WHERE guide_id = '$guide_id'"
);
$total = mysqli_fetch_assoc($q_total)['total'] ?? 0;

$q_tunggu = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM booking
     WHERE guide_id = '$guide_id'
     AND status = 'Menunggu Guide'"
);
$menunggu = mysqli_fetch_assoc($q_tunggu)['total'] ?? 0;

$q_terima = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM booking
     WHERE guide_id = '$guide_id'
     AND status = 'Diterima Guide'"
);
$diterima = mysqli_fetch_assoc($q_terima)['total'] ?? 0;

// ── 4 Booking terbaru ───────────────────────────────────────────────────────
$q_booking = mysqli_query(
    $koneksi,
    "SELECT
        booking.id,
        users.nama AS customer_nama,
        destinasi.name AS destinasi_nama,
        booking.status
     FROM booking
     LEFT JOIN users
        ON booking.user_id = users.id
     LEFT JOIN destinasi
        ON booking.destinasi_id = destinasi.id
     WHERE booking.guide_id = '$guide_id'
     ORDER BY booking.id DESC
     LIMIT 4"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Guide – Explore Tour</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*,
*::before,
*::after{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

:root{
    --navy:#0d1b2e;
    --blue:#2563eb;
    --blue-light:#eff6ff;
    --green:#16a34a;
    --green-bg:#f0fdf4;
    --green-text:#15803d;
    --orange:#ea580c;
    --orange-bg:#fff7ed;
    --orange-text:#c2410c;
    --red:#dc2626;
    --yellow-bg:#fef3c7;
    --yellow-text:#92400e;
    --bg:#f1f5f9;
    --card:#ffffff;
    --text:#1e293b;
    --muted:#64748b;
    --border:#e2e8f0;
    --sidebar-w:230px;
    --radius:14px;
}

/* BODY */
html,
body{
    width:100%;
    overflow-x:hidden;
}

body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:var(--bg);
    color:var(--text);
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */
.sidebar{
    width:var(--sidebar-w);
    height:100vh;
    background:var(--navy);
    display:flex;
    flex-direction:column;
    position:fixed;
    top:0;
    left:0;
    z-index:100;
    overflow-y:auto;
    transition:transform .3s;
}

.sidebar-brand{
    display:flex;
    align-items:center;
    gap:12px;
    padding:28px 20px 24px;
    border-bottom:1px solid rgba(255,255,255,.08);
}

.brand-icon{
    width:44px;
    height:44px;
    background:rgba(255,255,255,.1);
    border-radius:50%;
    display:grid;
    place-items:center;
    flex-shrink:0;
}

.brand-icon svg{
    width:24px;
    height:24px;
}

.brand-name{
    font-size:14px;
    font-weight:800;
    color:#fff;
    text-transform:uppercase;
    line-height:1.1;
}

.brand-sub{
    font-size:11px;
    color:rgba(255,255,255,.45);
    font-weight:500;
    margin-top:2px;
}

.sidebar-nav{
    flex:1;
    padding:20px 12px;
    display:flex;
    flex-direction:column;
    gap:4px;
}

.nav-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:11px 14px;
    border-radius:10px;
    color:rgba(255,255,255,.55);
    font-size:14px;
    font-weight:500;
    text-decoration:none;
    transition:.2s;
}

.nav-item i{
    width:20px;
    text-align:center;
}

.nav-item:hover{
    background:rgba(255,255,255,.07);
    color:#fff;
}

.nav-item.active{
    background:var(--blue);
    color:#fff;
    box-shadow:0 4px 14px rgba(37,99,235,.4);
}

.sidebar-footer{
    padding:16px 12px 24px;
    border-top:1px solid rgba(255,255,255,.08);
}

.logout-btn{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 14px;
    border-radius:10px;
    color:#f87171;
    text-decoration:none;
    font-weight:600;
}

.logout-btn:hover{
    background:rgba(248,113,113,.1);
}

/* MAIN */
.main{
    margin-left:var(--sidebar-w);
    width:calc(100vw - var(--sidebar-w));
    max-width:calc(100vw - var(--sidebar-w));
    padding:30px 24px;
    overflow-x:hidden;
}

.page-title{
    font-size:26px;
    font-weight:800;
}

.page-welcome{
    font-size:14px;
    color:var(--muted);
    margin-top:4px;
}

.page-welcome b{
    color:var(--text);
}

/* STAT */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
    margin:20px 0;
    width:100%;
}

.stat-card{
    background:var(--card);
    border-radius:var(--radius);
    padding:20px 20px;
    display:flex;
    align-items:center;
    gap:16px;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
    min-width:0;
}

.stat-icon{
    width:48px;
    height:48px;
    border-radius:14px;
    display:grid;
    place-items:center;
    font-size:20px;
    flex-shrink:0;
}

.stat-icon.blue{
    background:var(--blue-light);
    color:var(--blue);
}

.stat-icon.orange{
    background:var(--orange-bg);
    color:var(--orange);
}

.stat-icon.green{
    background:var(--green-bg);
    color:var(--green);
}

.stat-label{
    font-size:13px;
    color:var(--muted);
}

.stat-value{
    font-size:30px;
    font-weight:800;
}

.stat-value.blue{
    color:var(--blue);
}

.stat-value.orange{
    color:var(--orange);
}

.stat-value.green{
    color:var(--green);
}

/* BOOKING TERBARU (full width, sejajar dengan stats-grid) */
.bottom-grid{
    width:100%;
}

.card{
    background:var(--card);
    border-radius:var(--radius);
    box-shadow:0 1px 4px rgba(0,0,0,.06);
    overflow:hidden;
    width:100%;
}

.card-header{
    display:flex;
    align-items:center;
    gap:10px;
    padding:18px 22px 14px;
    border-bottom:1px solid var(--border);
}

.card-header i{
    color:var(--blue);
    font-size:18px;
}

.card-header h2{
    font-size:16px;
    font-weight:700;
}

/* TABLE */
.table-responsive{
    width:100%;
    overflow:hidden;
}

table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
}

thead tr{
    background:var(--navy);
    color:#fff;
}

thead th{
    padding:12px 18px;
    font-size:13px;
    font-weight:600;
    text-align:left;
}

thead th:first-child{
    width:80px;
}

tbody tr{
    border-bottom:1px solid var(--border);
    transition:.15s;
}

tbody tr:last-child{
    border-bottom:none;
}

tbody tr:hover{
    background:#f8fafc;
}

tbody td{
    padding:13px 18px;
    font-size:14px;
    font-weight:500;
    word-wrap:break-word;
    overflow-wrap:break-word;
}

/* BADGE */
.badge{
    display:inline-flex;
    align-items:center;
    gap:5px;
    padding:4px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    white-space:nowrap;
}

.badge-green{
    background:var(--green-bg);
    color:var(--green-text);
    border:1px solid #bbf7d0;
}

.badge-orange{
    background:var(--orange-bg);
    color:var(--orange-text);
    border:1px solid #fed7aa;
}

.badge-blue{
    background:var(--blue-light);
    color:#1d4ed8;
    border:1px solid #bfdbfe;
}

.badge-red{
    background:#fee2e2;
    color:#991b1b;
    border:1px solid #fecaca;
}

.badge-yellow{
    background:var(--yellow-bg);
    color:var(--yellow-text);
    border:1px solid #fde68a;
}

/* FOOTER */
.card-footer{
    padding:13px 22px;
    border-top:1px solid var(--border);
    text-align:center;
}

.card-footer a{
    color:var(--blue);
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:6px;
}

.card-footer a:hover{
    gap:10px;
}

/* NOTIFICATION */
.notif-badge{
    background:var(--red);
    color:#fff;
    border-radius:999px;
    font-size:10px;
    font-weight:700;
    padding:1px 6px;
    margin-left:auto;
}

/* MOBILE BUTTON */
.menu-toggle{
    display:none;
    position:fixed;
    top:16px;
    left:16px;
    z-index:200;
    background:var(--navy);
    color:#fff;
    border:none;
    width:42px;
    height:42px;
    border-radius:10px;
    cursor:pointer;
    font-size:18px;
}

.overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
    z-index:99;
}

/* RESPONSIVE */
@media(max-width:900px){

    .stats-grid{
        grid-template-columns:1fr 1fr;
    }

}

@media(max-width:768px){

    .sidebar{
        transform:translateX(-100%);
    }

    .sidebar.open{
        transform:translateX(0);
    }

    .overlay.open{
        display:block;
    }

    .menu-toggle{
        display:grid;
        place-items:center;
    }

    .main{
        width:100%;
        max-width:100%;
        margin-left:0;
        padding:20px 15px 30px;
        padding-top:70px;
    }

    .stats-grid{
        grid-template-columns:1fr;
        gap:14px;
    }

    table{
        table-layout:auto;
    }

    .table-responsive{
        overflow-x:auto;
    }
}

</style>
</head>
<body>

<button class="menu-toggle" id="menuToggle">
    <i class="fas fa-bars"></i>
</button>

<div class="overlay" id="overlay"></div>

<?php include 'sidebar.php'; ?>

<main class="main">

<div class="page-header">
    <h1 class="page-title">Dashboard Guide</h1>
    <p class="page-welcome">
        Selamat datang, <b><?= $guide_name ?></b>
    </p>
</div>

<!-- STAT CARDS -->
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div>
            <div class="stat-label">Total Booking</div>
            <div class="stat-value blue">
                <?= $total ?>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="stat-label">Menunggu Konfirmasi</div>
            <div class="stat-value orange">
                <?= $menunggu ?>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-circle-check"></i>
        </div>
        <div>
            <div class="stat-label">Booking Diterima</div>
            <div class="stat-value green">
                <?= $diterima ?>
            </div>
        </div>
    </div>

</div>

<!-- CONTENT -->
<div class="bottom-grid">

    <!-- BOOKING TERBARU -->
    <div class="card">

        <div class="card-header">
            <i class="fas fa-clipboard-list"></i>
            <h2>Booking Terbaru</h2>
        </div>

        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Destinasi</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(mysqli_num_rows($q_booking) > 0): ?>

                    <?php $no = 1; while($row = mysqli_fetch_assoc($q_booking)): ?>

                    <tr>
                        <td><?= $no++; ?></td>

                        <td>
                            <?= htmlspecialchars($row['customer_nama'] ?? '-') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['destinasi_nama'] ?? '-') ?>
                        </td>

                        <td>

                            <?php

                            $s = $row['status'];

                            $cls = match($s){
                                'Diterima Guide'   => 'badge-green',
                                'Menunggu Guide'   => 'badge-orange',
                                'Guide Ditugaskan' => 'badge-blue',
                                'Guide Menolak'    => 'badge-red',
                                default            => 'badge-yellow'
                            };

                            ?>

                            <span class="badge <?= $cls ?>">
                                <?= htmlspecialchars($s) ?>
                            </span>

                        </td>
                    </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="4"
                            style="padding:30px;text-align:center;color:var(--muted)">
                            Belum ada booking.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>

        </div>

        <div class="card-footer">
            <a href="booking.php">
                <i class="fas fa-calendar-check"></i>
                Lihat semua booking
                <i class="fas fa-arrow-right" style="font-size:11px"></i>
            </a>
        </div>

    </div>

</div>

</main>

<script>

const toggle  = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

if(toggle && sidebar && overlay){

    toggle.addEventListener('click', () => {

        sidebar.classList.add('open');
        overlay.classList.add('open');

    });

    overlay.addEventListener('click', () => {

        sidebar.classList.remove('open');
        overlay.classList.remove('open');

    });

}

</script>

</body>
</html>