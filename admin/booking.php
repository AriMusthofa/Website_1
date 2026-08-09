<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

/* ========================= VERIFIKASI PEMBAYARAN ========================= */
if(isset($_POST['verifikasi_bayar'])){

    $booking_id = intval($_POST['booking_id'] ?? 0);
    $keputusan  = $_POST['keputusan'] ?? '';

    if($booking_id > 0 && in_array($keputusan, ['setuju','tolak'])){

        $nilai = ($keputusan === 'setuju') ? 'setuju' : 'tolak';

        try{
            $stmt = mysqli_prepare($koneksi, "UPDATE booking SET verifikasi_bayar=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "si", $nilai, $booking_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } catch(mysqli_sql_exception $e){
            // Kolom verifikasi_bayar belum ada di database.
            // Jalankan: ALTER TABLE booking ADD COLUMN verifikasi_bayar VARCHAR(20) NOT NULL DEFAULT 'menunggu';
            $_SESSION['error'] = 'Gagal menyimpan verifikasi: kolom verifikasi_bayar belum ada di database.';
        }
    }

    header("Location: booking.php");
    exit();
}

/* ========================= ASSIGN GUIDE ========================= */
if(isset($_POST['assign'])){
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $guide_id   = intval($_POST['guide_id']);

    // Ambil status & tanggal booking yang akan di-assign
    $cek_status = mysqli_query($koneksi, "SELECT status, tanggal FROM booking WHERE id='$booking_id' LIMIT 1");
    $row_status = mysqli_fetch_assoc($cek_status);

    // Booking ini sudah diterima guide -> terkunci, tidak boleh diubah lagi
    if($row_status && $row_status['status'] == 'Diterima Guide'){
        header("Location: booking.php");
        exit();
    }

    $booking_tanggal_assign = $row_status['tanggal'] ?? '';

    // ── Validasi server-side: pastikan guide yang dipilih benar-benar
    //    tersedia di tanggal ini, agar tidak bisa di-bypass lewat request
    //    POST langsung (di luar tampilan <select> yang sudah men-disable
    //    opsi guide yang sibuk).
    $guide_tersedia = true;

    if(!empty($booking_tanggal_assign) && $guide_id > 0){
        $cek_busy = mysqli_query($koneksi,
            "SELECT id, status FROM booking
             WHERE guide_id = '$guide_id'
             AND tanggal = '".mysqli_real_escape_string($koneksi, $booking_tanggal_assign)."'
             AND status IN ('Diterima Guide','Guide Ditugaskan','Guide Menolak')"
        );

        while($busy_row = mysqli_fetch_assoc($cek_busy)){
            if($busy_row['status'] == 'Guide Menolak' && (int)$busy_row['id'] == $booking_id){
                // Guide sudah menolak booking INI -> tidak boleh dipilih ulang
                $guide_tersedia = false;
                break;
            }
            if($busy_row['status'] != 'Guide Menolak' && (int)$busy_row['id'] != $booking_id){
                // Guide sedang Diterima/Ditugaskan di booking LAIN, tanggal sama
                $guide_tersedia = false;
                break;
            }
        }
    }

    if(!$guide_tersedia){
        // Guide tidak tersedia -> batalkan assign, jangan ubah apapun
        header("Location: booking.php");
        exit();
    }

    $cekguide = mysqli_query($koneksi, "SELECT nama FROM users WHERE id='$guide_id' AND role='guide'");
    $guide    = mysqli_fetch_assoc($cekguide);

    if($guide){
        mysqli_query($koneksi, "UPDATE booking SET guide_id='$guide_id', status='Menunggu Guide' WHERE id='$booking_id'");
        $pesan = "Booking baru ditugaskan kepada Anda. ID Booking: ".$booking_id;
        mysqli_query($koneksi, "INSERT INTO notifikasi(user_id, pesan, status_baca) VALUES('$guide_id','$pesan','Belum Dibaca')");
    }

    header("Location: booking.php");
    exit();
}

/* ========================= LOAD GUIDE ========================= */
$data_guide = mysqli_query($koneksi, "SELECT * FROM users WHERE role='guide' ORDER BY nama ASC");

/* =========================================================
   LOAD BUSY GUIDES PER TANGGAL

   Aturan ketersediaan guide (per TANGGAL booking, tanpa peduli destinasi):

   - 'Diterima Guide'   => guide SUDAH MENERIMA booking lain di tanggal ini
                           => TERKUNCI (tidak bisa dipilih) untuk booking LAIN
                              di tanggal yang sama
   - 'Guide Ditugaskan' => guide sedang DITUGASKAN/menunggu respon untuk
                           booking lain di tanggal ini
                           => TERKUNCI (tidak bisa dipilih) untuk booking LAIN
                              di tanggal yang sama
   - 'Guide Menolak'    => guide sudah MENOLAK booking INI (booking yang
                           sedang dilihat admin sekarang)
                           => TERKUNCI khusus untuk booking itu sendiri,
                              tidak bisa dipilih ulang untuk booking yang sama
   - 'Menunggu Guide'   => status bebas/netral; SENGAJA tidak dimasukkan ke
                           query di bawah ini, sehingga guide dengan status
                           ini TETAP BISA dipilih/di-reassign oleh admin
   ========================================================= */
$busy_guides_by_date = [];
$busy_query = mysqli_query($koneksi,
    "SELECT guide_id, tanggal, id AS booking_id, status
     FROM booking
     WHERE guide_id IS NOT NULL AND guide_id != 0
     AND status IN ('Diterima Guide','Guide Ditugaskan','Guide Menolak')"
);
while($busy_row = mysqli_fetch_assoc($busy_query)){
    $tgl   = $busy_row['tanggal'];
    $gid   = $busy_row['guide_id'];
    $bid   = $busy_row['booking_id'];
    $bstat = $busy_row['status'];
    if(!isset($busy_guides_by_date[$tgl])) $busy_guides_by_date[$tgl] = [];
    $busy_guides_by_date[$tgl][$gid] = ['booking_id' => $bid, 'status' => $bstat];
}

/* ========================= LABEL METODE PEMBAYARAN ========================= */
$metode_label = [
    'bca'     => 'BCA',
    'bri'     => 'BRI',
    'mandiri' => 'Mandiri',
    'gopay'   => 'GoPay',
    'dana'    => 'DANA',
    'kas'     => 'Tunai (Kas)',
];

// Folder penyimpanan bukti pembayaran (relatif dari folder admin/)
$bukti_dir = "../upload/bukti/";

/* ========================= BOOKING SELESAI ========================= */
$filter_periode = $_GET['periode'] ?? 'semua';
$today          = date('Y-m-d');

$q_selesai = mysqli_query($koneksi,
    "SELECT booking.*, users.nama AS customer_nama,
            booking.nama_customer AS booking_customer_nama,
            destinasi.name AS destinasi_nama, guide.nama AS guide_nama
     FROM booking
     LEFT JOIN users      ON booking.user_id      = users.id
     LEFT JOIN destinasi  ON booking.destinasi_id = destinasi.id
     LEFT JOIN users AS guide ON booking.guide_id = guide.id
     WHERE booking.status = 'Diterima Guide' AND booking.tanggal < '$today'
     ORDER BY booking.tanggal DESC"
);

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Booking</title>
<link rel="stylesheet" href="../assets/css/dashboard.css">
<style>

*{ box-sizing:border-box; }

html,body{ max-width:100%; overflow-x:hidden; }

select {
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    outline: none;
    min-width: 180px;
    background: #fff;
}

.status {
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    display: inline-block;
}
.status-blue   { background: #dbeafe; color: #1d4ed8; }
.status-green  { background: #dcfce7; color: #166534; }
.status-red    { background: #fee2e2; color: #991b1b; }
.status-yellow { background: #fef3c7; color: #92400e; }

.table-wrapper { overflow-x: auto; }

.catatan-cell{
    max-width:220px;
    white-space:normal;
    word-break:break-word;
    font-size:13px;
    color:#475569;
}

.btn-lihat-bukti{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:10px 16px;
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:13px;
    font-weight:700;
    cursor:pointer;
    transition:.2s;
}
.btn-lihat-bukti:hover{ background:#1d4ed8; }

/* ===== MODAL BUKTI PEMBAYARAN ===== */
.modal-overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.55);
    z-index:1000;
    align-items:center;
    justify-content:center;
    padding:20px;
}

.modal-box{
    background:#fff;
    border-radius:20px;
    padding:28px;
    max-width:460px;
    width:100%;
    max-height:90vh;
    overflow-y:auto;
    position:relative;
    box-shadow:0 20px 50px rgba(0,0,0,.25);
}

.modal-box h3{
    font-size:19px;
    font-weight:700;
    color:#0f172a;
    margin-bottom:16px;
}

.modal-close{
    position:absolute;
    top:14px;
    right:16px;
    background:none;
    border:none;
    font-size:22px;
    line-height:1;
    color:#64748b;
    cursor:pointer;
}
.modal-close:hover{ color:#0f172a; }

#modalMetode{
    font-size:14px;
    font-weight:700;
    color:#1d4ed8;
    background:#eef2ff;
    padding:10px 14px;
    border-radius:10px;
    margin-bottom:16px;
}

#modalGambarWrap{
    margin-bottom:16px;
}

#modalGambarWrap img{
    width:100%;
    max-height:340px;
    object-fit:contain;
    border-radius:12px;
    border:1px solid #e2e8f0;
    background:#f8fafc;
}

#modalNoBukti{
    display:none;
    background:#f0fdf4;
    color:#166534;
    padding:14px;
    border-radius:10px;
    font-size:14px;
    margin-bottom:16px;
}

.modal-actions{
    display:flex;
    gap:12px;
    margin-top:6px;
}

.btn-setuju,
.btn-tolak{
    flex:1;
    padding:13px 16px;
    border:none;
    border-radius:12px;
    font-size:14px;
    font-weight:700;
    cursor:pointer;
    transition:.2s;
    color:#fff;
}

.btn-setuju{ background:#16a34a; }
.btn-setuju:hover{ background:#15803d; }

.btn-tolak{ background:#ef4444; }
.btn-tolak:hover{ background:#dc2626; }

/* ===== BOOKING SELESAI ===== */
.section-selesai { margin-top: 40px; }

.section-selesai h2 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 16px;
    color: #0d1b2e;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}
.filter-bar label { font-weight: 600; font-size: 14px; color: #374151; }
.filter-bar select { padding: 8px 14px; min-width: 140px; font-size: 14px; }
.filter-bar input[type="date"] {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    background: #fff;
}

.btn-print {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    background: #16a34a;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}
.btn-print:hover { background: #15803d; }

.total-pendapatan {
    margin-top: 14px;
    padding: 14px 20px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    color: #166534;
    display: inline-block;
}

/* ===== PRINT STYLES ===== */
@media print {

    body * { visibility: hidden; }

    #area-cetak,
    #area-cetak * { visibility: visible; }

    #area-cetak {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        padding: 30px;
        box-sizing: border-box;
    }

    /* Sembunyikan semua judul / card di luar area cetak */
    .no-print { display: none !important; }
    .form-card > h2 { display: none !important; }

    /* Header cetak: logo + judul tengah */
    .print-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .print-header img {
        height: 65px;
        display: block;
        margin: 0 auto 8px auto;
    }
    .print-header h2 {
        font-size: 17px;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #000;
        /* override semua gaya card agar h2 ini tetap tampil */
        display: block !important;
        visibility: visible !important;
    }

    /* Periode kiri, tanggal cetak kanan — tepat di atas tabel */
    .print-meta {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        margin-bottom: 6px;
        color: #000;
    }

    /* Tabel hitam-putih kotak bersih — reset semua radius & overflow */
    .table-wrapper {
        overflow: visible !important;
        border-radius: 0 !important;
    }

    table {
        width: 100%;
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        font-size: 12px;
        border-radius: 0 !important;
        overflow: visible !important;
    }

    th, td {
        border: 1px solid #000 !important;
        padding: 7px 10px;
        text-align: left;
        color: #000 !important;
        background: #fff !important;
        border-radius: 0 !important;
    }

    thead tr th {
        font-weight: 700;
    }

    /* Total pendapatan — satu elemen, tampil saat cetak */
    .total-pendapatan {
        margin-top: 14px;
        font-size: 14px;
        font-weight: 700;
        color: #000 !important;
        background: none !important;
        border: none !important;
        padding: 0 !important;
        border-radius: 0 !important;
        display: block !important;
    }
}

</style>
</head>

<body>
<div class="layout">

<?php include 'sidebar.php'; ?>

<div class="main-content">
<div class="container">

<!-- ============================================
     TABEL 1: KELOLA BOOKING CUSTOMER
============================================ -->
<div class="form-card">
<h2>Kelola Booking Customer</h2>
<div class="table-wrapper">
<table class="dashboard-table">
<tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Destinasi</th>
    <th>Tanggal</th>
    <th>Jumlah</th>
    <th>Catatan</th>
    <th>Guide</th>
    <th>Status</th>
    <th>Assign Guide</th>
</tr>

<?php
$query = mysqli_query($koneksi,
    "SELECT booking.*, users.nama AS customer_nama,
            booking.nama_customer AS booking_customer_nama,
            destinasi.name AS destinasi_nama, guide.nama AS guide_nama
     FROM booking
     LEFT JOIN users      ON booking.user_id      = users.id
     LEFT JOIN destinasi  ON booking.destinasi_id = destinasi.id
     LEFT JOIN users AS guide ON booking.guide_id = guide.id
     WHERE NOT (booking.status = 'Diterima Guide' AND booking.tanggal < '$today')
     ORDER BY booking.id DESC"
);

if(mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_assoc($query)){
        $tampil_customer = !empty($row['customer_nama'])
            ? $row['customer_nama']
            : (!empty($row['booking_customer_nama']) ? $row['booking_customer_nama'] : '-');
?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($tampil_customer) ?></td>
    <td><?= htmlspecialchars($row['destinasi_nama']) ?></td>
    <td><?= !empty($row['tanggal']) ? date('d-m-Y', strtotime($row['tanggal'])) : '-' ?></td>
    <td><?= $row['jumlah_orang'] ?> Orang</td>
    <td class="catatan-cell"><?= !empty($row['catatan']) ? htmlspecialchars($row['catatan']) : '-' ?></td>
    <td><?= !empty($row['guide_nama']) ? htmlspecialchars($row['guide_nama']) : '-' ?></td>
    <td><?php
        $status = $row['status'];
        if($status == "Guide Ditugaskan")     echo "<span class='status status-blue'>Guide Ditugaskan</span>";
        elseif($status == "Diterima Guide")   echo "<span class='status status-green'>Diterima Guide</span>";
        elseif($status == "Guide Menolak")    echo "<span class='status status-red'>Guide Menolak</span>";
        else                                  echo "<span class='status status-yellow'>".$status."</span>";
    ?></td>
    <td><?php

        $sudah_terkunci = ($row['status'] == 'Diterima Guide');
        $verif          = $row['verifikasi_bayar'] ?? 'menunggu';

        if($sudah_terkunci){
        ?>
        <span style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;
            background:#dcfce7;color:#166534;border-radius:10px;font-size:13px;font-weight:600;">
            &#10003; Guide Terkunci
        </span>
        <?php
        } elseif($verif !== 'setuju'){
            // Default ('menunggu') maupun setelah 'tolak' -> tampil tombol Lihat Bukti Pembayaran lagi

            $mt         = $row['metode_bayar'] ?? '';
            $mt_label   = $metode_label[$mt] ?? strtoupper($mt);
            $bukti_file = $row['bukti_pembayaran'] ?? '';
            $bukti_url  = (!empty($bukti_file) && file_exists($bukti_dir.$bukti_file))
                          ? $bukti_dir.htmlspecialchars($bukti_file, ENT_QUOTES)
                          : '';
        ?>
        <button type="button" class="btn-lihat-bukti"
            onclick="bukaModalBukti(
                <?= (int)$row['id'] ?>,
                '<?= $bukti_url ?>',
                '<?= htmlspecialchars($mt_label, ENT_QUOTES) ?>'
            )">
            &#128065; Lihat Bukti Pembayaran
        </button>
        <?php
        } else { // verif === 'setuju' -> form assign guide seperti biasa
        ?>
        <form method="POST">
        <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
        <select name="guide_id" required>
            <option value="">Pilih Guide</option>
            <?php
            mysqli_data_seek($data_guide, 0);
            $booking_tanggal = $row['tanggal'];
            while($g = mysqli_fetch_assoc($data_guide)){

                $is_busy     = false;
                $busy_status = '';

                // Cek apakah guide ini punya jadwal di TANGGAL yang sama
                // (tidak peduli destinasi booking lain)
                if(!empty($booking_tanggal) && isset($busy_guides_by_date[$booking_tanggal][$g['id']])){

                    $entry      = $busy_guides_by_date[$booking_tanggal][$g['id']];
                    $entry_stat = $entry['status'];
                    $entry_bid  = $entry['booking_id'];

                    if($entry_stat == 'Guide Menolak' && $entry_bid == $row['id']){
                        // Guide menolak BOOKING INI SENDIRI -> terkunci khusus booking ini
                        $is_busy     = true;
                        $busy_status = 'Guide Menolak';

                    } elseif($entry_stat != 'Guide Menolak' && $entry_bid != $row['id']){
                        // Guide sedang "Diterima Guide" / "Guide Ditugaskan"
                        // pada BOOKING LAIN di tanggal yang sama -> terkunci
                        $is_busy     = true;
                        $busy_status = $entry_stat;
                    }
                    // Catatan: status 'Menunggu Guide' tidak pernah masuk ke
                    // $busy_guides_by_date (lihat query di atas), jadi guide
                    // dengan status itu SELALU tetap bisa dipilih/di-reassign.
                }

                $is_selected = ($row['guide_id'] == $g['id']);

                if($is_busy){
                    if($busy_status == 'Guide Menolak')       $ket = ' (Menolak)';
                    elseif($busy_status == 'Diterima Guide')  $ket = ' (Sedang Bertugas)';
                    else                                      $ket = ' (Menunggu Konfirmasi)';
                } else { $ket = ''; }

                echo "<option value=\"".intval($g['id'])."\""
                   . ($is_selected ? ' selected' : '')
                   . ($is_busy ? ' disabled style="color:#9ca3af;"' : '')
                   . ">" . htmlspecialchars($g['nama']) . $ket . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="assign" class="btn btn-primary">Assign</button>
        </form>
        <?php
        } ?>
    </td>
</tr>
<?php
    }
} else { ?>
<tr>
    <td colspan="9" style="padding:35px;text-align:center;">Belum ada booking aktif.</td>
</tr>
<?php } ?>

</table>
</div>
</div><!-- /.form-card -->


<!-- ============================================
     TABEL 2: BOOKING SELESAI
============================================ -->
<div class="form-card section-selesai">
<h2>&#10003; Booking Selesai</h2>

<!-- Filter Bar -->
<div class="filter-bar no-print">

    <label>Lihat Laporan:</label>

    <select id="sel-periode" onchange="handlePeriodeChange(this.value)">
        <option value="semua"  <?= ($filter_periode=='semua'  ? 'selected':'') ?>>Semua</option>
        <option value="hari"   <?= ($filter_periode=='hari'   ? 'selected':'') ?>>Hari Ini</option>
        <option value="minggu" <?= ($filter_periode=='minggu' ? 'selected':'') ?>>Minggu Ini</option>
        <option value="bulan"  <?= ($filter_periode=='bulan'  ? 'selected':'') ?>>Bulan Ini</option>
        <option value="custom" <?= ($filter_periode=='custom' ? 'selected':'') ?>>Pilih Periode...</option>
    </select>

    <!-- Range tanggal custom -->
    <span id="wrap-custom" style="display:none;align-items:center;gap:8px;flex-wrap:wrap;">
        <label>Dari:</label>
        <input type="date" id="tgl-dari"   onchange="filterSelesai()">
        <label>Hingga:</label>
        <input type="date" id="tgl-hingga" onchange="filterSelesai()">
    </span>

    <button class="btn-print" onclick="cetakLaporan()">&#128438; Cetak / Unduh PDF</button>

</div>

<!-- Area Cetak -->
<div id="area-cetak">

    <!-- Header cetak: logo tengah + judul (hanya tampil saat print) -->
    <div class="print-header" id="print-header" style="display:none;">
        <img src="../upload/logohitam.png" alt="Rinjani Guide">
        <h2>Laporan Pemesanan</h2>
    </div>

    <!-- Meta: periode kiri — tanggal cetak kanan (hanya tampil saat print) -->
    <div class="print-meta" id="print-meta" style="display:none;">
        <span id="print-periode-label" style="text-align:left;"></span>
        <span style="text-align:right;">Tanggal Cetak: <?= date('d-m-Y H:i') ?></span>
    </div>

    <div class="table-wrapper">
    <table class="dashboard-table" id="tabel-selesai">
        <thead>
        <tr>
            <th>No</th>
            <th>Customer</th>
            <th>Destinasi</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Guide</th>
            <th>Total Harga</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $no = 1; $grand_total = 0; $rows_selesai = [];
        mysqli_data_seek($q_selesai, 0);
        while($r = mysqli_fetch_assoc($q_selesai)) $rows_selesai[] = $r;

        if(count($rows_selesai) > 0){
            foreach($rows_selesai as $r){
                $nama_c  = !empty($r['customer_nama'])
                    ? $r['customer_nama']
                    : (!empty($r['booking_customer_nama']) ? $r['booking_customer_nama'] : '-');
                $tgl_raw = $r['tanggal'];
                $grand_total += floatval($r['total_harga'] ?? 0);
        ?>
        <tr class="row-selesai" data-tanggal="<?= htmlspecialchars($tgl_raw) ?>">
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($nama_c) ?></td>
            <td><?= htmlspecialchars($r['destinasi_nama'] ?? '-') ?></td>
            <td><?= !empty($tgl_raw) ? date('d-m-Y', strtotime($tgl_raw)) : '-' ?></td>
            <td><?= intval($r['jumlah_orang']) ?> Orang</td>
            <td><?= htmlspecialchars($r['guide_nama'] ?? '-') ?></td>
            <td>Rp <?= number_format(floatval($r['total_harga'] ?? 0), 0, ',', '.') ?></td>
        </tr>
        <?php
            }
        } else { ?>
        <tr>
            <td colspan="7" style="padding:35px;text-align:center;">Belum ada booking selesai.</td>
        </tr>
        <?php } ?>

        <tr id="row-kosong" style="display:none;">
            <td colspan="7" style="padding:30px;text-align:center;color:#6b7280;">
                Tidak ada data untuk periode ini.
            </td>
        </tr>

        </tbody>
    </table>
    </div>

    <!-- Total Pendapatan: SATU elemen — dipakai layar & cetak sekaligus -->
    <div class="total-pendapatan" id="total-pendapatan">
        Total Pendapatan: <span id="total-nominal">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
    </div>

</div><!-- /#area-cetak -->
</div><!-- /.form-card.section-selesai -->

</div><!-- /.container -->
</div><!-- /.main-content -->
</div><!-- /.layout -->

<!-- ============================================
     MODAL: LIHAT BUKTI PEMBAYARAN
============================================ -->
<div class="modal-overlay" id="modalBukti">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="tutupModalBukti()">&times;</button>
        <h3>Verifikasi Pembayaran</h3>

        <div id="modalMetode"></div>

        <div id="modalGambarWrap">
            <img id="modalGambar" src="" alt="Bukti Pembayaran">
        </div>

        <div id="modalNoBukti">
            Pembayaran tunai (kas) — tidak ada bukti upload. Silakan konfirmasi setelah menerima pembayaran secara langsung.
        </div>

        <form method="POST" id="formVerifikasi">
            <input type="hidden" name="verifikasi_bayar" value="1">
            <input type="hidden" name="booking_id" id="modalBookingId" value="">
            <input type="hidden" name="keputusan" id="modalKeputusan" value="">
            <div class="modal-actions">
                <button type="submit" class="btn-setuju" onclick="document.getElementById('modalKeputusan').value='setuju'">
                    &#10003; Setuju
                </button>
                <button type="submit" class="btn-tolak" onclick="document.getElementById('modalKeputusan').value='tolak'">
                    &#10005; Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>

var allRows    = document.querySelectorAll('.row-selesai');
var grandTotal = <?= $grand_total ?>;

/* ── Modal Bukti Pembayaran ── */
function bukaModalBukti(id, buktiUrl, metodeLabel){
    document.getElementById('modalBookingId').value = id;
    document.getElementById('modalKeputusan').value = '';
    document.getElementById('modalMetode').textContent = 'Metode Pembayaran: ' + metodeLabel;

    var imgWrap = document.getElementById('modalGambarWrap');
    var img     = document.getElementById('modalGambar');
    var noBukti = document.getElementById('modalNoBukti');

    if(buktiUrl){
        img.src = buktiUrl;
        imgWrap.style.display = 'block';
        noBukti.style.display = 'none';
    } else {
        imgWrap.style.display = 'none';
        noBukti.style.display = 'block';
    }

    document.getElementById('modalBukti').style.display = 'flex';
}

function tutupModalBukti(){
    document.getElementById('modalBukti').style.display = 'none';
}

document.getElementById('modalBukti').addEventListener('click', function(e){
    if(e.target === this) tutupModalBukti();
});

/* ── Helpers tanggal ── */
function getTodayStr(){
    return new Date().toISOString().split('T')[0];
}

function getWeekRange(){
    var d   = new Date();
    var day = d.getDay();
    var mon = new Date(d);
    mon.setDate(d.getDate() - day + (day === 0 ? -6 : 1));
    var sun = new Date(mon);
    sun.setDate(mon.getDate() + 6);
    return {
        start: mon.toISOString().split('T')[0],
        end  : sun.toISOString().split('T')[0]
    };
}

function getMonthRange(){
    var d    = new Date();
    var y    = d.getFullYear();
    var m    = d.getMonth();
    var ms   = String(m + 1).padStart(2, '0');
    var last = new Date(y, m + 1, 0).getDate();
    return {
        start: y + '-' + ms + '-01',
        end  : y + '-' + ms + '-' + String(last).padStart(2, '0')
    };
}

function fmt(str){
    if(!str) return '-';
    var p = str.split('-');
    return p[2] + '-' + p[1] + '-' + p[0];
}

/* ── Tampil/sembunyikan input custom ── */
function handlePeriodeChange(val){
    var wc = document.getElementById('wrap-custom');
    wc.style.display = (val === 'custom') ? 'inline-flex' : 'none';
    if(val !== 'custom') filterSelesai();
}

/* ── Filter baris tabel ── */
function filterSelesai(){
    var periode   = document.getElementById('sel-periode').value;
    var tglDari   = document.getElementById('tgl-dari').value;
    var tglHingga = document.getElementById('tgl-hingga').value;
    var today     = getTodayStr();
    var wRange    = getWeekRange();
    var mRange    = getMonthRange();

    var totalFiltered = 0, visibleCount = 0;

    allRows.forEach(function(row){
        var tgl  = row.getAttribute('data-tanggal');
        var show = false;

        if     (periode === 'semua')  show = true;
        else if(periode === 'hari')   show = (tgl === today);
        else if(periode === 'minggu') show = (tgl >= wRange.start && tgl <= wRange.end);
        else if(periode === 'bulan')  show = (tgl >= mRange.start && tgl <= mRange.end);
        else if(periode === 'custom'){
            if     (tglDari && tglHingga) show = (tgl >= tglDari && tgl <= tglHingga);
            else if(tglDari)              show = (tgl >= tglDari);
            else if(tglHingga)            show = (tgl <= tglHingga);
            else                          show = true;
        }

        row.style.display = show ? '' : 'none';

        if(show){
            var nominal = row.cells[6].textContent.replace(/[^0-9]/g, '');
            totalFiltered += parseInt(nominal || 0);
            visibleCount++;
        }
    });

    var rk = document.getElementById('row-kosong');
    if(rk) rk.style.display = (visibleCount === 0) ? '' : 'none';

    /* Update total — satu elemen saja */
    document.getElementById('total-nominal').textContent =
        'Rp ' + totalFiltered.toLocaleString('id-ID');

    /* Simpan ke URL tanpa reload */
    var url = new URL(window.location.href);
    url.searchParams.set('periode', periode);
    window.history.replaceState({}, '', url);
}

/* ── Cetak laporan ── */
function cetakLaporan(){
    var periode   = document.getElementById('sel-periode').value;
    var tglDari   = document.getElementById('tgl-dari').value;
    var tglHingga = document.getElementById('tgl-hingga').value;
    var label     = '';

    if     (periode === 'semua')  label = 'Semua Periode';
    else if(periode === 'hari')   label = 'Hari Ini (' + fmt(getTodayStr()) + ')';
    else if(periode === 'minggu'){ var w = getWeekRange(); label = 'Minggu Ini (' + fmt(w.start) + ' s/d ' + fmt(w.end) + ')'; }
    else if(periode === 'bulan') { var m = getMonthRange(); label = 'Bulan Ini (' + fmt(m.start) + ' s/d ' + fmt(m.end) + ')'; }
    else if(periode === 'custom') label = (tglDari ? fmt(tglDari) : '...') + ' s/d ' + (tglHingga ? fmt(tglHingga) : '...');

    /* Tampilkan elemen print-only */
    document.getElementById('print-header').style.display = 'block';
    document.getElementById('print-meta').style.display   = 'flex';
    document.getElementById('print-periode-label').textContent = 'Periode: ' + label;

    window.print();

    /* Sembunyikan kembali setelah cetak */
    document.getElementById('print-header').style.display = 'none';
    document.getElementById('print-meta').style.display   = 'none';
}

/* ── Init: jalankan filter dari URL param ── */
(function(){
    var params = new URLSearchParams(window.location.search);
    var p      = params.get('periode') || 'semua';
    document.getElementById('sel-periode').value = p;
    if(p === 'custom') document.getElementById('wrap-custom').style.display = 'inline-flex';
    filterSelesai();
})();

</script>

</body>
</html>