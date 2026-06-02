<?php

require_once '../config/koneksi.php';
require_once '../config/security.php';

requireRole('guide');

$guide_id =
intval($_SESSION['id']);

$guide_name =
htmlspecialchars(
$_SESSION['nama'] ?? 'Guide'
);

/* =====================================================
AKSI TERIMA / TOLAK
===================================================== */

if(
isset($_POST['aksi'])
&&
isset($_POST['booking_id'])
){

$booking_id =
intval(
$_POST['booking_id']
);

$aksi =
$_POST['aksi'];

$cek =
mysqli_query(

$koneksi,

"SELECT id

FROM booking

WHERE id='$booking_id'

AND guide_id='$guide_id'

LIMIT 1"

);

if(
mysqli_num_rows($cek)>0
){

$new_status='';

if(
$aksi==='terima'
){

$new_status='Diterima Guide';

}
elseif(
$aksi==='tolak'
){

$new_status='Guide Menolak';

}

if($new_status!=''){

$ns =
mysqli_real_escape_string(
$koneksi,
$new_status
);

mysqli_query(

$koneksi,

"UPDATE booking

SET status='$ns'

WHERE id='$booking_id'

AND guide_id='$guide_id'"

);

header(
"Location: booking.php?success=1"
);

exit();

}

}

}

/* ============================================================
   LOAD semua booking milik guide ini
   ============================================================ */
$q_notif = mysqli_query(

$koneksi,

"SELECT

booking.*,

users.nama AS customer_nama,

destinasi.name AS destinasi_nama

FROM booking

LEFT JOIN users
ON booking.user_id = users.id

LEFT JOIN destinasi
ON booking.destinasi_id = destinasi.id

WHERE

booking.guide_id='$guide_id'

AND booking.status='Menunggu Guide'

ORDER BY booking.id DESC"

);

// Hitung badge notif (booking yg masih 'Menunggu Guide')
$q_notif = mysqli_query($koneksi,
    "SELECT COUNT(*) AS total FROM booking
     WHERE guide_id = '$guide_id' AND status = 'Menunggu Guide'"
);
$notif_count = mysqli_fetch_assoc($q_notif)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking – Guide Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        :root {
            --navy:      #0d1b2e;
            --blue:      #2563eb;
            --blue-light:#eff6ff;
            --green:     #16a34a;
            --green-bg:  #f0fdf4;
            --green-text:#15803d;
            --orange:    #ea580c;
            --orange-bg: #fff7ed;
            --orange-text:#c2410c;
            --red:       #dc2626;
            --red-bg:    #fee2e2;
            --red-text:  #991b1b;
            --yellow-bg: #fef3c7;
            --yellow-text:#92400e;
            --bg:        #f1f5f9;
            --card:      #ffffff;
            --text:      #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
            --sidebar-w: 230px;
            --radius:    14px;
        }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--bg); color:var(--text); display:flex; min-height:100vh; }

        /* ── SIDEBAR ─────────────────────────────────────── */
        .sidebar { width:var(--sidebar-w); background:var(--navy); display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:100; transition:transform .3s; }
        .sidebar-brand { display:flex; align-items:center; gap:12px; padding:28px 20px 24px; border-bottom:1px solid rgba(255,255,255,.08); }
        .brand-icon { width:44px; height:44px; background:rgba(255,255,255,.1); border-radius:50%; display:grid; place-items:center; flex-shrink:0; }
        .brand-icon svg { width:24px; height:24px; }
        .brand-name { font-size:14px; font-weight:800; color:white; text-transform:uppercase; line-height:1.1; }
        .brand-sub { font-size:11px; color:rgba(255,255,255,.45); font-weight:500; margin-top:2px; }
        .sidebar-nav { flex:1; padding:20px 12px; display:flex; flex-direction:column; gap:4px; }
        .nav-item { display:flex; align-items:center; gap:12px; padding:11px 14px; border-radius:10px; color:rgba(255,255,255,.55); font-size:14px; font-weight:500; text-decoration:none; transition:all .2s; }
        .nav-item i { font-size:16px; width:20px; text-align:center; }
        .nav-item:hover { background:rgba(255,255,255,.07); color:white; }
        .nav-item.active { background:var(--blue); color:white; box-shadow:0 4px 14px rgba(37,99,235,.4); }
        .nav-item .badge-notif { background:var(--red); color:white; border-radius:999px; font-size:10px; font-weight:700; padding:1px 7px; margin-left:auto; line-height:1.5; }
        .sidebar-footer { padding:16px 12px 24px; border-top:1px solid rgba(255,255,255,.08); }
        .logout-btn { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; color:#f87171; font-size:14px; font-weight:600; text-decoration:none; transition:background .2s; }
        .logout-btn:hover { background:rgba(248,113,113,.1); }

        /* ── MAIN ────────────────────────────────────────── */
        .main { margin-left:var(--sidebar-w); flex:1; padding:36px 40px; min-width:0; }
        .page-title { font-size:28px; font-weight:800; }
        .page-sub { font-size:14px; color:var(--muted); margin-top:4px; }

        /* ── FILTER BAR ──────────────────────────────────── */
        .filter-bar { display:flex; align-items:center; gap:12px; margin:20px 0; flex-wrap:wrap; }
        .filter-bar select {
            padding:9px 14px; border:1px solid var(--border); border-radius:10px;
            font-family:inherit; font-size:13px; background:var(--card);
            color:var(--text); outline:none; cursor:pointer;
        }
        .filter-bar select:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,.12); }
        .search-wrap { position:relative; flex:1; max-width:280px; }
        .search-wrap i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:13px; }
        .search-wrap input { width:100%; padding:9px 12px 9px 34px; border:1px solid var(--border); border-radius:10px; font-family:inherit; font-size:13px; outline:none; }
        .search-wrap input:focus { border-color:var(--blue); }

        /* ── TABLE CARD ──────────────────────────────────── */
        .card { background:var(--card); border-radius:var(--radius); box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; animation:fadeUp .45s ease both; }
        .card-header { display:flex; align-items:center; gap:10px; padding:20px 22px 18px; border-bottom:1px solid var(--border); }
        .card-header i { color:var(--blue); font-size:18px; }
        .card-header h2 { font-size:17px; font-weight:700; }
        .card-header .total-pill { margin-left:auto; background:var(--blue-light); color:var(--blue); padding:3px 12px; border-radius:999px; font-size:12px; font-weight:700; }

        .table-wrapper { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:780px; }
        thead tr { background:var(--navy); color:white; }
        thead th { padding:12px 16px; font-size:12px; font-weight:700; text-align:left; letter-spacing:.3px; text-transform:uppercase; white-space:nowrap; }
        tbody tr { border-bottom:1px solid var(--border); transition:background .15s; }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover { background:#f8fafc; }
        tbody td { padding:14px 16px; font-size:14px; font-weight:500; vertical-align:middle; }

        /* badges */
        .badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; white-space:nowrap; }
        .badge-green  { background:var(--green-bg);   color:var(--green-text);   border:1px solid #bbf7d0; }
        .badge-orange { background:var(--orange-bg);  color:var(--orange-text);  border:1px solid #fed7aa; }
        .badge-blue   { background:var(--blue-light); color:#1d4ed8;             border:1px solid #bfdbfe; }
        .badge-red    { background:var(--red-bg);     color:var(--red-text);     border:1px solid #fecaca; }
        .badge-yellow { background:var(--yellow-bg);  color:var(--yellow-text);  border:1px solid #fde68a; }

        /* action buttons */
        .btn-group { display:flex; gap:7px; flex-wrap:wrap; }
        .btn { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border:none; border-radius:8px; font-family:inherit; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .15s; }
        .btn-terima { background:var(--green-bg); color:var(--green-text); border:1px solid #bbf7d0; }
        .btn-terima:hover { background:var(--green); color:white; border-color:var(--green); transform:translateY(-1px); }
        .btn-tolak  { background:var(--red-bg);   color:var(--red-text);   border:1px solid #fecaca; }
        .btn-tolak:hover  { background:var(--red);   color:white; border-color:var(--red);   transform:translateY(-1px); }
        .btn-disabled { background:#f1f5f9; color:var(--muted); border:1px solid var(--border); cursor:default; font-size:12px; }

        /* empty state */
        .empty-state { padding:48px 24px; text-align:center; color:var(--muted); }
        .empty-state i { font-size:40px; color:#cbd5e1; display:block; margin-bottom:12px; }

        /* toast */
        .toast { position:fixed; bottom:24px; right:24px; background:var(--navy); color:white; padding:12px 20px; border-radius:10px; font-size:14px; font-weight:600; box-shadow:0 8px 24px rgba(0,0,0,.2); z-index:999; display:none; animation:slideIn .3s ease; }
        @keyframes slideIn { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }

        /* modal confirm */
        .modal-bg { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:500; place-items:center; }
        .modal-bg.open { display:grid; }
        .modal { background:var(--card); border-radius:16px; padding:32px 28px; max-width:380px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,.2); }
        .modal-icon { font-size:42px; margin-bottom:12px; }
        .modal h3 { font-size:18px; font-weight:800; margin-bottom:6px; }
        .modal p { font-size:14px; color:var(--muted); margin-bottom:22px; }
        .modal-btns { display:flex; gap:10px; justify-content:center; }

        /* mobile */
        .menu-toggle { display:none; position:fixed; top:16px; left:16px; z-index:200; background:var(--navy); color:white; border:none; width:40px; height:40px; border-radius:10px; font-size:18px; cursor:pointer; }
        .overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99; }

        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

        @media(max-width:768px) {
            .sidebar{transform:translateX(-100%)} .sidebar.open{transform:translateX(0)}
            .overlay.open{display:block} .menu-toggle{display:grid;place-items:center}
            .main{margin-left:0;padding:20px 16px 32px;padding-top:64px}
        }
    </style>
</head>
<body>

<button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
<div class="overlay" id="overlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="white">
                <path d="M12 2L2 20h20L12 2zm0 4.5l6.5 11.5H5.5L12 6.5z"/>
            </svg>
        </div>
        <div class="brand-text">
            <div class="brand-name">Explore<br>Tour</div>
            <div class="brand-sub">Guide Panel</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item">
            <i class="fas fa-house"></i> Dashboard
        </a>
        <a href="booking.php" class="nav-item active">
            <i class="fas fa-calendar-check"></i> Kelola Booking
            <?php if ($notif_count > 0): ?>
                <span class="badge-notif"><?= $notif_count ?></span>
            <?php endif; ?>
        </a>
        <a href="jadwal.php" class="nav-item">
            <i class="fas fa-calendar-days"></i> Jadwal
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="../logout.php" class="logout-btn">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>

<!-- MAIN -->
<main class="main">
    <div style="margin-bottom:24px">
        <h1 class="page-title">Kelola Booking</h1>
        <p class="page-sub">Daftar booking yang ditugaskan kepada Anda, <b><?= $guide_name ?></b></p>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
        <div class="search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari customer / destinasi…">
        </div>
        <select id="statusFilter">
            <option value="">Semua Status</option>
            <option value="Menunggu Guide">Menunggu Guide</option>
            <option value="Diterima Guide">Diterima Guide</option>
            <option value="Guide Ditugaskan">Guide Ditugaskan</option>
            <option value="Guide Menolak">Guide Menolak</option>
        </select>
    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-clipboard-list"></i>
            <h2>Daftar Booking</h2>
            <span class="total-pill"><?= mysqli_num_rows($q_booking) ?> booking</span>
        </div>
        <div class="table-wrapper">
            <table id="bookingTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Destinasi</th>
                        <th>Tanggal</th>
                        <th>Orang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                mysqli_data_seek($q_booking, 0);
                if (mysqli_num_rows($q_booking) > 0):
                    while ($row = mysqli_fetch_assoc($q_booking)):
                        $s    = $row['status'];
                        $cls  = match($s) {
                            'Diterima Guide'   => 'badge-green',
                            'Menunggu Guide'   => 'badge-orange',
                            'Guide Ditugaskan' => 'badge-blue',
                            'Guide Menolak'    => 'badge-red',
                            default            => 'badge-yellow',
                        };
                        $can_act = ($s === 'Menunggu Guide' || $s === 'Guide Ditugaskan');
                        $tgl = !empty($row['tanggal'])
                            ? date('d M Y', strtotime($row['tanggal']))
                            : '-';
                ?>
                <tr data-search="<?= strtolower(
                    htmlspecialchars($row['customer_nama'] ?? '') . ' ' .
                    htmlspecialchars($row['destinasi_nama'] ?? '')
                ) ?>" data-status="<?= htmlspecialchars($s) ?>">
                    <td><b>#<?= $row['id'] ?></b></td>
                    <td><?= htmlspecialchars($row['customer_nama'] ?? '-') ?></td>
                    <td>
                        <?= htmlspecialchars($row['destinasi_nama'] ?? '-') ?>
                        <?php if (!empty($row['destinasi_lokasi'])): ?>
                            <br><small style="color:var(--muted);font-size:11px">
                                <i class="fas fa-location-dot"></i>
                                <?= htmlspecialchars($row['destinasi_lokasi']) ?>
                            </small>
                        <?php endif; ?>
                    </td>
                    <td style="white-space:nowrap"><?= $tgl ?></td>
                    <td><?= intval($row['jumlah_orang'] ?? 0) ?></td>
                    <td><span class="badge <?= $cls ?>"><?= htmlspecialchars($s) ?></span></td>
                    <td>
                        <?php if ($can_act): ?>
                        <div class="btn-group">
                            <button class="btn btn-terima"
                                onclick="confirmAksi('terima', <?= $row['id'] ?>, '<?= htmlspecialchars($row['customer_nama'] ?? '') ?>')">
                                <i class="fas fa-check"></i> Terima
                            </button>
                            <button class="btn btn-tolak"
                                onclick="confirmAksi('tolak', <?= $row['id'] ?>, '<?= htmlspecialchars($row['customer_nama'] ?? '') ?>')">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                        <?php else: ?>
                            <span class="btn btn-disabled"><i class="fas fa-lock"></i> <?= $s ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-calendar-xmark"></i>
                            Belum ada booking yang ditugaskan kepada Anda.
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- MODAL CONFIRM -->
<div class="modal-bg" id="modalBg">
    <div class="modal">
        <div class="modal-icon" id="modalIcon">✅</div>
        <h3 id="modalTitle">Konfirmasi Aksi</h3>
        <p id="modalDesc">Apakah Anda yakin?</p>
        <div class="modal-btns">
            <button class="btn" style="background:#f1f5f9;color:var(--text);border:1px solid var(--border)" onclick="closeModal()">
                <i class="fas fa-times"></i> Batal
            </button>
            <form method="POST" id="modalForm" style="display:inline">
                <input type="hidden" name="booking_id" id="modalBookingId">
                <input type="hidden" name="aksi"       id="modalAksi">
                <button type="submit" class="btn" id="modalConfirmBtn">
                    <i class="fas fa-check"></i> Ya, Lanjutkan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script>
/* ── Mobile sidebar ───────────────── */
const toggle  = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
toggle.addEventListener('click',  () => { sidebar.classList.add('open');    overlay.classList.add('open'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });

/* ── Search & Filter ─────────────── */
const searchInput  = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const rows         = document.querySelectorAll('#bookingTable tbody tr[data-search]');

function filterTable() {
    const q  = searchInput.value.toLowerCase();
    const sf = statusFilter.value.toLowerCase();
    rows.forEach(r => {
        const matchQ = r.dataset.search.includes(q);
        const matchS = sf === '' || r.dataset.status.toLowerCase() === sf;
        r.style.display = (matchQ && matchS) ? '' : 'none';
    });
}
searchInput.addEventListener('input', filterTable);
statusFilter.addEventListener('change', filterTable);

/* ── Modal Confirm ───────────────── */
function confirmAksi(aksi, id, nama) {
    const isTerima = aksi === 'terima';
    document.getElementById('modalIcon').textContent  = isTerima ? '✅' : '❌';
    document.getElementById('modalTitle').textContent = isTerima ? 'Terima Booking' : 'Tolak Booking';
    document.getElementById('modalDesc').textContent  =
        `Anda akan ${isTerima ? 'menerima' : 'menolak'} booking dari ${nama}. Lanjutkan?`;
    document.getElementById('modalBookingId').value   = id;
    document.getElementById('modalAksi').value        = aksi;
    const btn = document.getElementById('modalConfirmBtn');
    btn.style.background = isTerima ? 'var(--green)' : 'var(--red)';
    btn.style.color      = 'white';
    btn.style.border     = 'none';
    document.getElementById('modalBg').classList.add('open');
}
function closeModal() {
    document.getElementById('modalBg').classList.remove('open');
}
document.getElementById('modalBg').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeModal();
});

/* ── Toast on load (after redirect) ─ */
<?php if (isset($_GET['success'])): ?>
showToast('✅ Status booking berhasil diperbarui!');
<?php endif; ?>

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 3500);
}
</script>
</body>
</html>