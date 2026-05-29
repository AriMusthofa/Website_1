<?php

$current_page =
basename($_SERVER['PHP_SELF']);

/* BADGE COUNTS */

$q_booking =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total
FROM booking
WHERE status='Menunggu Guide'"

);

$booking_count =
mysqli_fetch_assoc(
$q_booking
)['total'];



$q_guide =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total
FROM users
WHERE role='guide'"

);

$guide_count =
mysqli_fetch_assoc(
$q_guide
)['total'];



$q_customer =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total
FROM users
WHERE role='customer'"

);

$customer_count =
mysqli_fetch_assoc(
$q_customer
)['total'];



$q_destinasi =
mysqli_query(

$koneksi,

"SELECT COUNT(*)
AS total
FROM destinasi"

);

$destinasi_count =
mysqli_fetch_assoc(
$q_destinasi
)['total'];

?>

<div class="sidebar">

<div class="sidebar-logo">

ADMIN PANEL

</div>

<ul class="sidebar-menu">

<li>

<a

href="dashboard.php"

class="<?=

$current_page
==
'dashboard.php'

?

'active'

:

''

?>"

>

🏠 Dashboard

</a>

</li>



<li>

<a

href="destinasi.php"

class="<?=

$current_page
==
'destinasi.php'

?

'active'

:

''

?>"

>

🏔 Destinasi

<span class="badge">

<?=

$destinasi_count

?>

</span>

</a>

</li>



<li>

<a

href="booking.php"

class="<?=

$current_page
==
'booking.php'

?

'active'

:

''

?>"

>

📅 Booking

<?php

if(
$booking_count
>
0
){

?>

<span class="badge">

<?=

$booking_count

?>

</span>

<?php

}

?>

</a>

</li>



<li>

<a

href="karyawan.php"

class="<?=

$current_page
==
'karyawan.php'

?

'active'

:

''

?>"

>

👨‍💼 Guide

<span class="badge">

<?=

$guide_count

?>

</span>

</a>

</li>



<li>

<a

href="customer.php"

class="<?=

$current_page
==
'customer.php'

?

'active'

:

''

?>"

>

👥 Customer

<span class="badge">

<?=

$customer_count

?>

</span>

</a>

</li>



<li>

<a

href="profil.php"

class="<?=

$current_page
==
'profil.php'

?

'active'

:

''

?>"

>

👤 Profil

</a>

</li>

</ul>



<div class="sidebar-footer">

<a
href="../logout.php"
class="logout-btn"
>

🚪 Logout

</a>

</div>

</div>