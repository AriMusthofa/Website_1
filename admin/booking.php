<?php

session_start();
include '../config/koneksi.php';

if(
!isset($_SESSION['role'])
||
$_SESSION['role']!='admin'
){
header("Location: ../login.php");
exit();
}

/* =========================
ASSIGN GUIDE
========================= */

if(isset($_POST['assign'])){

$booking_id = intval($_POST['booking_id'] ?? 0);
$guide_id   = intval($_POST['guide_id']);

$cek_status = mysqli_query(
$koneksi,
"SELECT status FROM booking WHERE id='$booking_id' LIMIT 1"
);
$row_status = mysqli_fetch_assoc($cek_status);
if($row_status && $row_status['status'] == 'Diterima Guide'){
header("Location: booking.php");
exit();
}

$cekguide = mysqli_query(

$koneksi,

"SELECT nama
FROM users
WHERE id='$guide_id'
AND role='guide'"

);

$guide = mysqli_fetch_assoc($cekguide);

if($guide){

mysqli_query(

$koneksi,

"UPDATE booking
SET

guide_id='$guide_id',
status='Menunggu Guide'
WHERE id='$booking_id'"

);

$pesan =
"Booking baru ditugaskan kepada Anda. ID Booking: ".$booking_id;

mysqli_query(

$koneksi,

"INSERT INTO notifikasi(

user_id,
pesan,
status_baca

)

VALUES(

'$guide_id',

'$pesan',

'Belum Dibaca'

)"

);

}

header("Location: booking.php");
exit();

}

/* =========================
LOAD GUIDE
========================= */

$data_guide =
mysqli_query(

$koneksi,

"SELECT *
FROM users
WHERE role='guide'
ORDER BY nama ASC"

);

/* =========================
LOAD BUSY GUIDES PER DATE

Aturan ketersediaan guide:
- 'Diterima Guide'   => DIKUNCI per tanggal (sedang bertugas)
- 'Guide Ditugaskan' => DIKUNCI per tanggal (menunggu konfirmasi)
- 'Guide Menolak'    => DIKUNCI per booking (menolak booking ini)
- 'Menunggu Guide'   => BEBAS, admin boleh re-assign ke guide lain
========================= */

$busy_guides_by_date = [];

$busy_query = mysqli_query(

$koneksi,

"SELECT
guide_id,
tanggal,
id    AS booking_id,
status
FROM booking
WHERE
guide_id IS NOT NULL
AND guide_id != 0
AND status IN (
'Diterima Guide',
'Guide Ditugaskan',
'Guide Menolak'
)"

);

while($busy_row = mysqli_fetch_assoc($busy_query)){

$tgl   = $busy_row['tanggal'];
$gid   = $busy_row['guide_id'];
$bid   = $busy_row['booking_id'];
$bstat = $busy_row['status'];

if(!isset($busy_guides_by_date[$tgl])){
$busy_guides_by_date[$tgl] = [];
}

$busy_guides_by_date[$tgl][$gid] = [
'booking_id' => $bid,
'status'     => $bstat,
];

}

/* =========================
BOOKING SELESAI
Booking yang tanggalnya < hari ini
dan status 'Diterima Guide'
========================= */

$filter_periode = $_GET['periode'] ?? 'semua';
$today          = date('Y-m-d');

// Query booking selesai (tanggal sudah lewat & diterima guide)
$q_selesai = mysqli_query(

$koneksi,

"SELECT
booking.*,
users.nama        AS customer_nama,
booking.nama_customer AS booking_customer_nama,
destinasi.name    AS destinasi_nama,
guide.nama        AS guide_nama

FROM booking

LEFT JOIN users
ON booking.user_id = users.id

LEFT JOIN destinasi
ON booking.destinasi_id = destinasi.id

LEFT JOIN users AS guide
ON booking.guide_id = guide.id

WHERE
booking.status = 'Diterima Guide'
AND booking.tanggal < '$today'

ORDER BY booking.tanggal DESC"

);

?>
<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Kelola Booking</title>

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<style>

select{
padding:12px;
border:1px solid #d1d5db;
border-radius:10px;
outline:none;
min-width:180px;
background:#fff;
}

.status{
padding:8px 14px;
border-radius:20px;
font-size:13px;
font-weight:700;
display:inline-block;
}

.status-blue{
background:#dbeafe;
color:#1d4ed8;
}

.status-green{
background:#dcfce7;
color:#166534;
}

.status-red{
background:#fee2e2;
color:#991b1b;
}

.status-yellow{
background:#fef3c7;
color:#92400e;
}

.table-wrapper{
overflow-x:auto;
}

/* ===== BOOKING SELESAI ===== */

.section-selesai{
margin-top:40px;
}

.section-selesai h2{
font-size:20px;
font-weight:700;
margin-bottom:16px;
color:#0d1b2e;
display:flex;
align-items:center;
gap:8px;
}

.filter-bar{
display:flex;
align-items:center;
gap:12px;
flex-wrap:wrap;
margin-bottom:16px;
}

.filter-bar label{
font-weight:600;
font-size:14px;
color:#374151;
}

.filter-bar select{
padding:8px 14px;
min-width:140px;
font-size:14px;
}

.btn-laporan{
display:inline-flex;
align-items:center;
gap:6px;
padding:9px 18px;
background:#2563eb;
color:#fff;
border:none;
border-radius:10px;
font-size:14px;
font-weight:600;
cursor:pointer;
text-decoration:none;
transition:.2s;
}

.btn-laporan:hover{
background:#1d4ed8;
}

.btn-print{
display:inline-flex;
align-items:center;
gap:6px;
padding:9px 18px;
background:#16a34a;
color:#fff;
border:none;
border-radius:10px;
font-size:14px;
font-weight:600;
cursor:pointer;
text-decoration:none;
transition:.2s;
}

.btn-print:hover{
background:#15803d;
}

.total-pendapatan{
margin-top:14px;
padding:14px 20px;
background:#f0fdf4;
border:1px solid #bbf7d0;
border-radius:10px;
font-size:15px;
font-weight:700;
color:#166534;
display:inline-block;
}

/* ===== PRINT STYLES ===== */

@media print{

body *{ visibility:hidden; }

#area-cetak,
#area-cetak *{ visibility:visible; }

#area-cetak{
position:absolute;
top:0;left:0;
width:100%;
}

.no-print{ display:none !important; }

table{
width:100%;
border-collapse:collapse;
font-size:13px;
}

th,td{
border:1px solid #ccc;
padding:8px 10px;
text-align:left;
}

thead{
background:#0d1b2e !important;
color:#fff !important;
-webkit-print-color-adjust:exact;
print-color-adjust:exact;
}

.print-header{
margin-bottom:16px;
}

.print-header h2{
font-size:18px;
font-weight:700;
}

.print-header p{
font-size:13px;
color:#555;
margin-top:4px;
}

.total-cetak{
margin-top:14px;
font-size:14px;
font-weight:700;
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

<h2>
Kelola Booking Customer
</h2>

<div class="table-wrapper">

<table class="dashboard-table">

<tr>

<th>ID</th>
<th>Customer</th>
<th>Destinasi</th>
<th>Tanggal</th>
<th>Jumlah</th>
<th>Guide</th>
<th>Status</th>
<th>Assign Guide</th>

</tr>

<?php

$query =
mysqli_query(

$koneksi,

"SELECT

booking.*,

users.nama        AS customer_nama,
booking.nama_customer AS booking_customer_nama,

destinasi.name    AS destinasi_nama,

guide.nama        AS guide_nama

FROM booking

LEFT JOIN users
ON booking.user_id = users.id

LEFT JOIN destinasi
ON booking.destinasi_id = destinasi.id

LEFT JOIN users AS guide
ON booking.guide_id = guide.id

WHERE NOT (
booking.status = 'Diterima Guide'
AND booking.tanggal < '$today'
)

ORDER BY booking.id DESC"

);

if(
mysqli_num_rows($query)>0
){

while(
$row=
mysqli_fetch_assoc($query)
){

?>

<tr>

<td>
<?= $row['id'] ?>
</td>

<td>
<?php
$tampil_customer =
!empty($row['customer_nama'])
? $row['customer_nama']
: (!empty($row['booking_customer_nama'])
    ? $row['booking_customer_nama']
    : '-');
echo htmlspecialchars($tampil_customer);
?>
</td>

<td>
<?= htmlspecialchars($row['destinasi_nama']) ?>
</td>

<td>

<?=

!empty($row['tanggal'])

?

date(
'd-m-Y',
strtotime($row['tanggal'])
)

:

'-'

?>

</td>

<td>
<?= $row['jumlah_orang'] ?> Orang
</td>

<td>

<?=

!empty($row['guide_nama'])

?

htmlspecialchars($row['guide_nama'])

:

'-'

?>

</td>

<td>

<?php

$status =
$row['status'];

if(
$status=="Guide Ditugaskan"
){

echo
"<span class='status status-blue'>
Guide Ditugaskan
</span>";

}
elseif(
$status=="Diterima Guide"
){

echo
"<span class='status status-green'>
Diterima Guide
</span>";

}
elseif(
$status=="Guide Menolak"
){

echo
"<span class='status status-red'>
Guide Menolak
</span>";

}
else{

echo
"<span class='status status-yellow'>
".$status."
</span>";

}

?>

</td>

<td>

<?php if($row['status'] == 'Diterima Guide'): ?>

<span style="
display:inline-flex;
align-items:center;
gap:6px;
padding:8px 14px;
background:#dcfce7;
color:#166534;
border-radius:10px;
font-size:13px;
font-weight:600;
">
&#10003; Guide Terkunci
</span>

<?php else: ?>

<form method="POST">

<input
type="hidden"
name="booking_id"
value="<?= $row['id'] ?>">

<select
name="guide_id"
required>

<option value="">
Pilih Guide
</option>

<?php

mysqli_data_seek(
$data_guide,
0
);

$booking_tanggal = $row['tanggal'];

while(
$g=
mysqli_fetch_assoc($data_guide)
){

// === CEK KETERSEDIAAN GUIDE ===
//
// Kasus 1 - per TANGGAL (guide sibuk di hari yang sama di booking LAIN):
//   'Diterima Guide'   => tidak bisa dipilih
//   'Guide Ditugaskan' => tidak bisa dipilih
//
// Kasus 2 - per BOOKING INI SENDIRI:
//   'Guide Menolak'    => guide ini sudah menolak booking ini, tidak bisa dipilih lagi
//
// Kasus bebas:
//   'Menunggu Guide'   => admin boleh re-assign ke guide lain

$is_busy     = false;
$busy_status = '';

if(!empty($booking_tanggal)
&& isset($busy_guides_by_date[$booking_tanggal][$g['id']])
){
$entry      = $busy_guides_by_date[$booking_tanggal][$g['id']];
$entry_stat = $entry['status'];
$entry_bid  = $entry['booking_id'];

if($entry_stat == 'Guide Menolak'
&& $entry_bid == $row['id']
){
$is_busy     = true;
$busy_status = 'Guide Menolak';

} elseif(
$entry_stat != 'Guide Menolak'
&& $entry_bid != $row['id']
){
$is_busy     = true;
$busy_status = $entry_stat;
}
}

$is_selected = ($row['guide_id'] == $g['id']);

// Label keterangan sesuai penyebab
if($is_busy){
if($busy_status == 'Guide Menolak'){
$ket = ' (Menolak)';
} elseif($busy_status == 'Diterima Guide'){
$ket = ' (Sedang Bertugas)';
} else {
$ket = ' (Menunggu Konfirmasi)';
}
} else {
$ket = '';
}

$label = htmlspecialchars($g['nama']) . $ket;

echo "<option"
. " value=\"" . intval($g['id']) . "\""
. ($is_selected ? ' selected' : '')
. ($is_busy ? ' disabled style="color:#9ca3af;"' : '')
. ">"
. $label
. "</option>";

} ?>

</select>

<button
type="submit"
name="assign"
class="btn btn-primary">

Assign

</button>

</form>

<?php endif; ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td
colspan="8"
style="
padding:35px;
text-align:center;
">

Belum ada booking aktif.

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

<!-- ============================================
     TABEL 2: BOOKING SELESAI
============================================ -->

<div class="form-card section-selesai">

<h2>
&#10003; Booking Selesai
</h2>

<!-- Filter & Tombol Laporan -->
<div class="filter-bar no-print">

<label for="sel-periode">Lihat Laporan:</label>

<select
id="sel-periode"
onchange="filterSelesai(this.value)">

<option value="semua"  <?= ($filter_periode=='semua'  ? 'selected':'') ?>>Semua</option>
<option value="hari"   <?= ($filter_periode=='hari'   ? 'selected':'') ?>>Hari Ini</option>
<option value="minggu" <?= ($filter_periode=='minggu' ? 'selected':'') ?>>Minggu Ini</option>
<option value="bulan"  <?= ($filter_periode=='bulan'  ? 'selected':'') ?>>Bulan Ini</option>

</select>

<button
class="btn-print"
onclick="cetakLaporan()">
&#128438; Cetak / Unduh PDF
</button>

</div>

<!-- Area yang dicetak -->
<div id="area-cetak">

<div class="print-header" style="display:none;" id="print-header">
<h2>Laporan Booking Selesai</h2>
<p id="print-periode-label"></p>
<p>Dicetak: <?= date('d-m-Y H:i') ?></p>
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

$no          = 1;
$grand_total = 0;
$rows_selesai = [];

// Simpan semua baris ke array untuk filter JS
mysqli_data_seek($q_selesai, 0);
while($r = mysqli_fetch_assoc($q_selesai)){
$rows_selesai[] = $r;
}

if(count($rows_selesai) > 0){

foreach($rows_selesai as $r){

$nama_c =
!empty($r['customer_nama'])
? $r['customer_nama']
: (!empty($r['booking_customer_nama'])
    ? $r['booking_customer_nama']
    : '-');

$tgl_raw = $r['tanggal'];

$grand_total += floatval($r['total_harga'] ?? 0);

?>

<tr
class="row-selesai"
data-tanggal="<?= htmlspecialchars($tgl_raw) ?>">

<td><?= $no++ ?></td>

<td><?= htmlspecialchars($nama_c) ?></td>

<td><?= htmlspecialchars($r['destinasi_nama'] ?? '-') ?></td>

<td>
<?= !empty($tgl_raw) ? date('d-m-Y', strtotime($tgl_raw)) : '-' ?>
</td>

<td><?= intval($r['jumlah_orang']) ?> Orang</td>

<td><?= htmlspecialchars($r['guide_nama'] ?? '-') ?></td>

<td>
Rp <?= number_format(floatval($r['total_harga'] ?? 0), 0, ',', '.') ?>
</td>

</tr>

<?php } ?>

<tr id="row-kosong" style="display:none;">
<td colspan="7" style="padding:30px;text-align:center;color:#6b7280;">
Tidak ada data untuk periode ini.
</td>
</tr>

<?php }else{ ?>

<tr>
<td colspan="7" style="padding:35px;text-align:center;">
Belum ada booking selesai.
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

<div
class="total-pendapatan"
id="total-pendapatan">
Total Pendapatan:
<span id="total-nominal">
Rp <?= number_format($grand_total, 0, ',', '.') ?>
</span>
</div>

<div class="total-cetak" id="total-cetak" style="display:none;">
Total Pendapatan: Rp <span id="total-cetak-nominal"></span>
</div>

</div><!-- /#area-cetak -->

</div><!-- /.form-card.section-selesai -->

</div><!-- /.container -->

</div><!-- /.main-content -->

</div><!-- /.layout -->

<script>

// Data semua baris (tanggal dalam format Y-m-d)
var allRows = document.querySelectorAll('.row-selesai');
var grandTotal = <?= $grand_total ?>;

function getTodayStr(){
var d = new Date();
return d.toISOString().split('T')[0];
}

function getWeekRange(){
var d    = new Date();
var day  = d.getDay(); // 0=Sun
var diff = d.getDate() - day + (day==0 ? -6 : 1); // Senin
var mon  = new Date(d.setDate(diff));
var sun  = new Date(mon);
sun.setDate(mon.getDate() + 6);
return {
start: mon.toISOString().split('T')[0],
end  : sun.toISOString().split('T')[0]
};
}

function getMonthRange(){
var d = new Date();
var y = d.getFullYear();
var m = String(d.getMonth()+1).padStart(2,'0');
var last = new Date(y, d.getMonth()+1, 0).getDate();
return {
start: y+'-'+m+'-01',
end  : y+'-'+m+'-'+String(last).padStart(2,'0')
};
}

function filterSelesai(periode){

var today  = getTodayStr();
var wRange = getWeekRange();
var mRange = getMonthRange();

var totalFiltered = 0;
var visibleCount  = 0;

allRows.forEach(function(row){

var tgl = row.getAttribute('data-tanggal');
var show = false;

if(periode === 'semua'){
show = true;
} else if(periode === 'hari'){
show = (tgl === today);
} else if(periode === 'minggu'){
show = (tgl >= wRange.start && tgl <= wRange.end);
} else if(periode === 'bulan'){
show = (tgl >= mRange.start && tgl <= mRange.end);
}

row.style.display = show ? '' : 'none';

if(show){
// Ambil nominal dari kolom ke-7 (index 6)
var nominal = row.cells[6].textContent
.replace(/[^0-9]/g,'');
totalFiltered += parseInt(nominal||0);
visibleCount++;
}

});

// Tampilkan baris kosong jika tidak ada hasil
var rowKosong = document.getElementById('row-kosong');
if(rowKosong){
rowKosong.style.display = (visibleCount === 0) ? '' : 'none';
}

// Update total
document.getElementById('total-nominal').textContent =
'Rp ' + totalFiltered.toLocaleString('id-ID');

// Simpan ke query string tanpa reload
var url = new URL(window.location.href);
url.searchParams.set('periode', periode);
window.history.replaceState({}, '', url);

}

function cetakLaporan(){

var periode  = document.getElementById('sel-periode').value;
var labelMap = {
'semua' : 'Semua Periode',
'hari'  : 'Hari Ini (' + getTodayStr() + ')',
'minggu': 'Minggu Ini',
'bulan' : 'Bulan Ini'
};

// Isi header cetak
document.getElementById('print-header').style.display = 'block';
document.getElementById('print-periode-label').textContent =
'Periode: ' + labelMap[periode];

// Salin total ke elemen cetak
var totalText = document.getElementById('total-nominal').textContent;
document.getElementById('total-cetak-nominal').textContent =
totalText.replace('Rp ','');
document.getElementById('total-cetak').style.display = 'block';

window.print();

// Sembunyikan kembali setelah cetak
document.getElementById('print-header').style.display = 'none';
document.getElementById('total-cetak').style.display = 'none';

}

// Jalankan filter awal dari URL param
(function(){
var params = new URLSearchParams(window.location.search);
var p = params.get('periode') || 'semua';
document.getElementById('sel-periode').value = p;
filterSelesai(p);
})();

</script>

</body>

</html>