<div class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-mountain" style="color:white"></i>
        </div>

        <div>
            <div class="brand-name">EXPLORE TOUR</div>
            <div class="brand-sub">Guide Panel</div>
        </div>
    </div>

    <div class="sidebar-nav">

        <a href="dashboard.php"
           class="nav-item <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
            <i class="fas fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="booking.php"
           class="nav-item <?= basename($_SERVER['PHP_SELF'])=='booking.php'?'active':'' ?>">
            <i class="fas fa-calendar-check"></i>
            <span>Kelola Booking</span>
        </a>

        <a href="jadwal.php"
           class="nav-item <?= basename($_SERVER['PHP_SELF'])=='jadwal.php'?'active':'' ?>">
            <i class="fas fa-calendar-days"></i>
            <span>Jadwal</span>
        </a>

    </div>

    <div class="sidebar-footer">
        <a href="../logout.php" class="logout-btn">
            <i class="fas fa-right-from-bracket"></i>
            Logout
        </a>
    </div>

</div>