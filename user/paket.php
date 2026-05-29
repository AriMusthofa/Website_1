<?php
// =====================================================================
//  Rinjani Guide — Halaman Paket Pendakian
// =====================================================================

$page_title = "Paket Pendakian - Rinjani Guide";

require_once '../config/koneksi.php';

/* ======================
AMBIL DATA DATABASE
====================== */

$pakets = [];

$query = mysqli_query($koneksi, "SELECT * FROM destinasi");

while ($row = mysqli_fetch_assoc($query)) {

    $pakets[] = [

        "id"         => $row['id'],
        "name"       => $row['name'],
        "altitude"   => $row['altitude'],
        "difficulty" => $row['difficulty'],
        "diff_key"   => $row['diff_key'],
        "duration"   => $row['duration'],
        "dur_key"    => $row['dur_key'],
        "price"      => $row['price'],
        "price_num"  => $row['price_num'],
        "image"      => $row['image'],
        "popular"    => $row['popular']

    ];
}

/* ======================
FILTER TAB
====================== */

$filters = [

    ["key" => "semua", "label" => "Semua"],
    ["key" => "1-hari", "label" => "1 Hari"],
    ["key" => "2-3-hari", "label" => "2-3 Hari"],
    ["key" => "mudah", "label" => "Mudah"],
    ["key" => "menengah", "label" => "Menengah"],
    ["key" => "sulit", "label" => "Sulit"],

];

/* ======================
ACTIVE FILTER
====================== */

$active_filter =
    isset($_GET['filter'])
    && in_array($_GET['filter'], array_column($filters, 'key'))

    ? $_GET['filter']
    : 'semua';

/* ======================
FILTER DATA
====================== */

$filtered = array_filter($pakets, function ($p) use ($active_filter) {

    if ($active_filter === 'semua') {
        return true;
    }

    return
        $p['diff_key'] === $active_filter
        ||
        $p['dur_key'] === $active_filter;
});
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ===== RESET & VARIABLES ===== */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --green-dark: #1a3a2a;
            --green-mid: #2d6a4f;
            --green-main: #3a8c5c;
            --green-light: #52b788;
            --green-pale: #d8f3dc;
            --amber: #f4a261;
            --cream: #f4f5f2;
            --white: #ffffff;
            --text-dark: #1a2212;
            --text-mid: #4a5240;
            --text-light: #8a9180;
            --border: #e8ebe4;
            --shadow-xs: 0 1px 4px rgba(0, 0, 0, .05);
            --shadow-sm: 0 2px 12px rgba(0, 0, 0, .07);
            --shadow-md: 0 8px 32px rgba(0, 0, 0, .10);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, .14);
            --radius-sm: 10px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --nav-h: 72px;
            --transition: .28s cubic-bezier(.25, .8, .25, 1);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--cream);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--green-light);
            border-radius: 3px;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            height: var(--nav-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-xs);
            transition: box-shadow var(--transition);
        }

        .navbar.scrolled {
            box-shadow: var(--shadow-sm);
        }

        .navbar-logo img {
            height: 44px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .navbar-logo .logo-placeholder {
            height: 44px;
            width: 150px;
            border: 1.5px dashed var(--border);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 11px;
            letter-spacing: .5px;
            background: var(--cream);
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 36px;
            list-style: none;
        }

        .navbar-menu a {
            color: var(--text-mid);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: .2px;
            transition: color var(--transition);
            position: relative;
        }

        .navbar-menu a:hover,
        .navbar-menu a.active {
            color: var(--green-main);
        }

        .navbar-menu a.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--green-main);
            border-radius: 1px;
        }

        .btn-booking {
            background: var(--green-main);
            color: var(--white) !important;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: .3px;
            transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
            box-shadow: 0 4px 16px rgba(58, 140, 92, .35);
        }

        .btn-booking:hover {
            background: var(--green-dark) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26, 58, 42, .35);
        }

        .btn-booking::after {
            display: none !important;
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--text-dark);
            border-radius: 2px;
            transition: transform .3s, opacity .3s;
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: var(--nav-h);
            left: 0;
            right: 0;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 20px 5% 28px;
            flex-direction: column;
            gap: 0;
            z-index: 999;
            box-shadow: var(--shadow-md);
        }

        .mobile-menu.open {
            display: flex;
        }

        .mobile-menu a {
            color: var(--text-mid);
            text-decoration: none;
            padding: 13px 0;
            font-size: 15px;
            font-weight: 500;
            border-bottom: 1px solid var(--border);
            transition: color .2s;
        }

        .mobile-menu a:last-child {
            border-bottom: none;
            margin-top: 12px;
        }

        .mobile-menu a:hover {
            color: var(--green-main);
        }

        .mobile-menu .btn-booking-m {
            display: inline-block;
            background: var(--green-main);
            color: var(--white) !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            border: none;
        }

        /* ===== PAGE WRAPPER ===== */
        .page-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: calc(var(--nav-h) + 48px) 5% 80px;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            margin-bottom: 36px;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(30px, 5vw, 52px);
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.1;
            margin-bottom: 10px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-light);
        }

        .breadcrumb a {
            color: var(--text-light);
            text-decoration: none;
            transition: color .2s;
        }

        .breadcrumb a:hover {
            color: var(--green-main);
        }

        .breadcrumb svg {
            color: var(--text-light);
            flex-shrink: 0;
        }

        .breadcrumb span {
            color: var(--text-mid);
            font-weight: 500;
        }

        /* ===== TOOLBAR (filter + search + sort) ===== */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        /* Filter chips */
        .filter-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            list-style: none;
        }

        .filter-chip {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: 1.5px solid var(--border);
            background: var(--white);
            color: var(--text-mid);
            text-decoration: none;
            transition: all var(--transition);
            white-space: nowrap;
            user-select: none;
        }

        .filter-chip:hover {
            border-color: var(--green-main);
            color: var(--green-main);
            background: var(--green-pale);
        }

        .filter-chip.active {
            background: var(--green-main);
            border-color: var(--green-main);
            color: var(--white);
            box-shadow: 0 4px 14px rgba(58, 140, 92, .3);
        }

        /* Right toolbar: search + sort */
        .toolbar-right {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 50px;
            padding: 8px 16px;
            transition: border-color var(--transition), box-shadow var(--transition);
        }

        .search-box:focus-within {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(58, 140, 92, .1);
        }

        .search-box svg {
            color: var(--text-light);
            flex-shrink: 0;
        }

        .search-box input {
            border: none;
            outline: none;
            background: transparent;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            width: 180px;
        }

        .search-box input::placeholder {
            color: var(--text-light);
        }

        .sort-select {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 50px;
            padding: 8px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-mid);
            cursor: pointer;
            outline: none;
            transition: border-color var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a9180' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        .sort-select:focus {
            border-color: var(--green-main);
        }

        /* Result count */
        .result-count {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 22px;
        }

        .result-count strong {
            color: var(--green-main);
        }

        /* ===== PACKAGE GRID ===== */
        .pkg-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        /* ===== PACKAGE CARD ===== */
        .pkg-card {
            background: var(--white);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
            border: 1.5px solid var(--border);
            transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
            cursor: pointer;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .pkg-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: var(--green-light);
        }

        /* Popular badge */
        .popular-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
            background: var(--amber);
            color: var(--white);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            padding: 3px 10px;
            border-radius: 50px;
        }

        /* Image */
        .card-img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            display: block;
            transition: transform .5s ease;
        }

        .pkg-card:hover .card-img {
            transform: scale(1.06);
        }

        .card-img-wrap {
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
        }

        /* Fallback when no image */
        .img-fallback {
            width: 100%;
            aspect-ratio: 4/3;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a3a2a, #2d6a4f);
        }

        .img-fallback svg {
            opacity: .3;
        }

        /* Difficulty overlay tag */
        .diff-tag {
            position: absolute;
            bottom: 10px;
            left: 10px;
            z-index: 2;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .4px;
            padding: 3px 10px;
            border-radius: 6px;
        }

        .diff-tag.mudah {
            background: var(--green-main);
            color: #fff;
        }

        .diff-tag.menengah {
            background: var(--amber);
            color: #fff;
        }

        .diff-tag.sulit {
            background: #e63946;
            color: #fff;
        }

        /* Card body */
        .card-body {
            padding: 16px 16px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .card-name {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .card-alt {
            font-size: 13px;
            color: var(--text-light);
        }

        .card-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: 4px;
        }

        .card-meta-row {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: var(--text-mid);
        }

        .card-meta-row svg {
            color: var(--green-main);
            flex-shrink: 0;
        }

        .card-price {
            font-size: 15px;
            font-weight: 700;
            color: var(--green-main);
            margin-top: 6px;
        }

        /* Card footer with CTA */
        .card-footer {
            padding: 0 16px 16px;
            display: flex;
            gap: 8px;
        }

        .btn-detail {
            flex: 1;
            text-align: center;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1.5px solid var(--green-main);
            color: var(--green-main);
            background: transparent;
            transition: all var(--transition);
        }

        .btn-detail:hover {
            background: var(--green-pale);
        }

        .btn-book-now {
            flex: 1;
            text-align: center;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            color: var(--white);
            background: var(--green-main);
            transition: all var(--transition);
            box-shadow: 0 4px 12px rgba(58, 140, 92, .3);
        }

        .btn-book-now:hover {
            background: var(--green-dark);
            box-shadow: 0 6px 18px rgba(26, 58, 42, .3);
        }

        /* ===== "SEE ALL" CARD ===== */
        .see-all-card {
            background: var(--green-dark);
            border-radius: var(--radius-md);
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            padding: 32px 24px;
            min-height: 260px;
            cursor: pointer;
            text-decoration: none;
            transition: transform var(--transition), box-shadow var(--transition);
            box-shadow: var(--shadow-xs);
        }

        .see-all-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }

        .see-all-card .see-all-label {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--white);
            text-align: center;
            line-height: 1.3;
        }

        .see-all-card .see-all-sub {
            font-size: 13px;
            color: rgba(255, 255, 255, .55);
            text-align: center;
        }

        .see-all-arrow {
            width: 48px;
            height: 48px;
            background: var(--green-main);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background var(--transition), transform var(--transition);
        }

        .see-all-card:hover .see-all-arrow {
            background: var(--green-light);
            transform: translateX(4px);
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
            color: var(--text-light);
        }

        .empty-state svg {
            margin-bottom: 16px;
            opacity: .35;
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
        }

        .empty-state a {
            display: inline-block;
            margin-top: 20px;
            background: var(--green-main);
            color: #fff;
            padding: 11px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background var(--transition);
        }

        .empty-state a:hover {
            background: var(--green-dark);
        }

        /* ===== STATS STRIP ===== */
        .stats-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-bottom: 52px;
        }

        .stat-box {
            background: var(--white);
            padding: 24px 20px;
            text-align: center;
            transition: background var(--transition);
        }

        .stat-box:hover {
            background: var(--green-pale);
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--green-main);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-light);
        }

        /* ===== MODAL ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: rgba(0, 0, 0, .55);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }

        .modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .modal {
            background: var(--white);
            border-radius: var(--radius-lg);
            max-width: 560px;
            width: 100%;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transform: translateY(24px) scale(.97);
            transition: transform .3s;
        }

        .modal-overlay.open .modal {
            transform: none;
        }

        .modal-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }

        .modal-img-placeholder {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body {
            padding: 28px 28px 24px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .modal-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--cream);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-mid);
            flex-shrink: 0;
            transition: background var(--transition);
        }

        .modal-close:hover {
            background: var(--border);
        }

        .modal-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .modal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 5px 12px;
            font-size: 13px;
            color: var(--text-mid);
        }

        .modal-badge svg {
            color: var(--green-main);
        }

        .modal-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid var(--border);
            padding-top: 16px;
            margin-top: 16px;
        }

        .modal-price {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 800;
            color: var(--green-main);
        }

        .modal-price-note {
            font-size: 12px;
            color: var(--text-light);
            margin-top: 2px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .btn-modal-wa {
            background: #25d366;
            color: #fff;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background var(--transition);
        }

        .btn-modal-wa:hover {
            background: #1da851;
        }

        .btn-modal-close {
            padding: 11px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid var(--border);
            background: transparent;
            color: var(--text-mid);
            transition: border-color var(--transition);
        }

        .btn-modal-close:hover {
            border-color: var(--text-mid);
        }

        .modal-includes {
            margin-bottom: 16px;
        }

        .modal-includes h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .modal-includes ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .modal-includes li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-mid);
        }

        .modal-includes li::before {
            content: '';
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            background: var(--green-pale) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232d6a4f' stroke-width='3'%3E%3Cpath d='M20 6L9 17l-5-5'/%3E%3C/svg%3E") center/10px no-repeat;
            border-radius: 50%;
        }

        /* ===== SCROLL TOP ===== */
        .scroll-top {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 900;
            width: 44px;
            height: 44px;
            background: var(--green-main);
            color: #fff;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(58, 140, 92, .4);
            transition: all var(--transition);
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
        }

        .scroll-top.visible {
            opacity: 1;
            transform: none;
            pointer-events: all;
        }

        .scroll-top:hover {
            background: var(--green-dark);
            transform: translateY(-3px);
        }

        /* ===== REVEAL ===== */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .55s ease, transform .55s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        .reveal-d1 {
            transition-delay: .06s;
        }

        .reveal-d2 {
            transition-delay: .12s;
        }

        .reveal-d3 {
            transition-delay: .18s;
        }

        .reveal-d4 {
            transition-delay: .24s;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1100px) {
            .pkg-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .stats-strip {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .pkg-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .toolbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .toolbar-right {
                width: 100%;
            }

            .search-box {
                flex: 1;
            }

            .search-box input {
                width: 100%;
            }
        }

        @media (max-width: 520px) {
            .pkg-grid {
                grid-template-columns: 1fr;
            }

            .stats-strip {
                grid-template-columns: repeat(2, 1fr);
            }

            .modal-body {
                padding: 20px 18px 18px;
            }

            .modal-title {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar" id="navbar">
        <a class="navbar-logo" href="index.php">
            <?php if (file_exists('images/logo.png')): ?>
                <img src="images/logo.png" alt="Rinjani Guide Logo">
            <?php else: ?>
                <div class="logo-placeholder">LOGO HERE</div>
            <?php endif; ?>
        </a>

        <ul class="navbar-menu">
            <li><a href="beranda.php">Beranda</a></li>
            <li><a href="paket.php" class="active">Paket Pendakian</a></li>
            <li><a href="tentang.php">Tentang Kami</a></li>
            <li><a href="kontak.php">Kontak</a></li>
            <li><a href="booking.php" class="btn-booking">Booking Sekarang</a></li>
        </ul>

        <button class="hamburger" id="hamburger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="beranda.php">Beranda</a>
        <a href="paket.php">Paket Pendakian</a>
        <a href="tentang.php">Tentang Kami</a>
        <a href="kontak.php">Kontak</a>
        <a href="booking.php" class="btn-booking-m">Booking Sekarang</a>
    </div>

    <!-- ===== MAIN ===== -->
    <main class="page-wrap">

        <!-- Page Header -->
        <div class="page-header reveal">
            <h1 class="page-title">Paket Pendakian</h1>
            <nav class="breadcrumb">
                <a href="index.php">Beranda</a>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
                <span>Paket Pendakian</span>
            </nav>
        </div>

        <!-- Stats Strip -->
        <div class="stats-strip reveal">
            <div class="stat-box">
                <div class="stat-num"><?= count($pakets) ?>+</div>
                <div class="stat-label">Destinasi Tersedia</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">50+</div>
                <div class="stat-label">Pendaki Dipandu</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">4.9</div>
                <div class="stat-label">Rating Kepuasan</div>
            </div>
            <div class="stat-box">
                <div class="stat-num">1+</div>
                <div class="stat-label">Tahun Pengalaman</div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar reveal">
            <!-- Filter chips -->
            <ul class="filter-chips" id="filterChips">
                <?php foreach ($filters as $f): ?>
                    <li>
                        <a href="?filter=<?= urlencode($f['key']) ?>#pkg"
                            class="filter-chip <?= $active_filter === $f['key'] ? 'active' : '' ?>"
                            data-filter="<?= htmlspecialchars($f['key']) ?>">
                            <?= htmlspecialchars($f['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Search + sort -->
            <div class="toolbar-right">
                <div class="search-box">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari destinasi…" autocomplete="off">
                </div>
                <select class="sort-select" id="sortSelect">
                    <option value="default">Urutan Default</option>
                    <option value="price-asc">Harga: Terendah</option>
                    <option value="price-desc">Harga: Tertinggi</option>
                    <option value="name-asc">Nama: A–Z</option>
                    <option value="altitude-desc">Ketinggian Tertinggi</option>
                </select>
            </div>
        </div>

        <!-- Result count -->
        <p class="result-count" id="resultCount">
            Menampilkan <strong><?= count($filtered) ?></strong> dari <strong><?= count($pakets) ?></strong> destinasi
        </p>

        <!-- ===== PACKAGE GRID ===== -->
        <div class="pkg-grid" id="pkgGrid">
            <?php
            $i = 0;
            foreach ($filtered as $p):
                $delay = 'd' . (($i % 4) + 1);
                $i++;
            ?>
                <div class="pkg-card reveal reveal-<?= $delay ?>"
                    data-name="<?= htmlspecialchars(strtolower($p['name'])) ?>"
                    data-price="<?= $p['price_num'] ?>"
                    data-filter="<?= htmlspecialchars($p['diff_key']) ?> <?= htmlspecialchars($p['dur_key']) ?>"
                    data-altitude="<?= (int) filter_var($p['altitude'], FILTER_SANITIZE_NUMBER_INT) ?>"
                    onclick="openModal(<?= $p['id'] ?>)">

                    <?php if ($p['popular']): ?>
                        <div class="popular-badge">⭐ Terpopuler</div>
                    <?php endif; ?>

                    <div class="card-img-wrap">

                        <?php
                        $gambar = "../upload/" . $p['image'];
                        ?>

                        <?php if (!empty($p['image']) && file_exists($gambar)): ?>

                            <img class="card-img"
                                src="<?= htmlspecialchars($gambar) ?>"
                                alt="<?= htmlspecialchars($p['name']) ?>"
                                loading="lazy">

                        <?php else: ?>

                            <div class="img-fallback">
                                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5">
                                    <path d="M8 3l4 8 5-5 5 15H2L8 3z" />
                                </svg>
                            </div>

                        <?php endif; ?>

                        <span class="diff-tag <?= htmlspecialchars($p['diff_key']) ?>">
                            <?= htmlspecialchars($p['difficulty']) ?>
                        </span>

                    </div>

                    <div class="card-body">
                        <div class="card-name"><?= htmlspecialchars($p['name']) ?></div>
                        <div class="card-alt"><?= htmlspecialchars($p['altitude']) ?></div>
                        <div class="card-meta">
                            <div class="card-meta-row">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                <?= htmlspecialchars($p['duration']) ?>
                            </div>
                            <div class="card-meta-row">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="1" x2="12" y2="23" />
                                    <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                                </svg>
                                <span class="card-price"><?= htmlspecialchars($p['price']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" onclick="event.stopPropagation()">

                        <a href="../user/booking.php?destinasi=<?= urlencode($p['name']) ?>"
                            class="btn-book-now">
                            Booking Sekarang
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>

            <?php if (count($filtered) === 0): ?>
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                        <path d="M8 11h6M11 8v6" stroke-width="1.5" />
                    </svg>
                    <h3>Destinasi tidak ditemukan</h3>
                    <p>Coba filter lain atau reset pencarian</p>
                    <a href="paket-pendakian.php">Tampilkan Semua</a>
                </div>
            <?php endif; ?>

            <!-- "Lihat Semua" card selalu tampil saat ada hasil -->
            <?php if (count($filtered) > 0): ?>
                <a href="paket-pendakian.php" class="see-all-card">
                    <div class="see-all-label">Lihat Semua Destinasi</div>
                    <div class="see-all-sub">Lihat semua destinasi pendakian lainnya</div>
                    <div class="see-all-arrow">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </div>
                </a>
            <?php endif; ?>
        </div>

    </main>

    <!-- ===== MODAL DETAIL ===== -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
        <div class="modal" id="modalBox">
            <div id="modalImgWrap"></div>
            <div class="modal-body">
                <div class="modal-header">
                    <div class="modal-title" id="modalTitle">—</div>
                    <button class="modal-close" onclick="closeModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>
                <div class="modal-badges" id="modalBadges"></div>
                <div class="modal-includes" id="modalIncludes"></div>
                <div class="modal-price-row">
                    <div>
                        <div class="modal-price" id="modalPrice">—</div>
                        <div class="modal-price-note">per orang</div>
                    </div>
                    <div class="modal-actions">
                        <a href="#" id="modalWaBtn" target="_blank" class="btn-modal-wa">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" />
                            </svg>
                            Booking WA
                        </a>
                        <button class="btn-modal-close" onclick="closeModal()">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SCROLL TOP ===== -->
    <button class="scroll-top" id="scrollTop" aria-label="Kembali ke atas">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="18 15 12 9 6 15" />
        </svg>
    </button>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        (function() {
            'use strict';

            /* ---- PHP data ke JS ---- */
            const PAKETS = <?php
                            $js_pakets = [];
                            foreach ($pakets as $p) {
                                $includes = match ($p['difficulty']) {
                                    'Mudah'    => ['Guide lokal berpengalaman', 'Briefing pra-pendakian', 'Jalur terpandu', 'Air minum'],
                                    'Menengah' => ['Guide senior bersertifikat', 'Briefing & perlengkapan dasar', 'Jalur terpandu', 'P3K', 'Air minum & snack'],
                                    'Sulit'    => ['Guide senior bersertifikat', 'Peralatan camping lengkap', 'Makan 3x sehari', 'Asuransi perjalanan', 'Dokumentasi foto/video', 'Sertifikat pendakian'],
                                    default    => ['Guide lokal berpengalaman', 'Jalur terpandu'],
                                };
                                $js_pakets[] = [
                                    'id'       => $p['id'],
                                    'name'     => $p['name'],
                                    'altitude' => $p['altitude'],
                                    'diff'     => $p['difficulty'],
                                    'diffKey'  => $p['diff_key'],
                                    'duration' => $p['duration'],
                                    'price'    => $p['price'],
                                    'image'    => $p['image'],
                                    'includes' => $includes,
                                ];
                            }
                            echo json_encode($js_pakets, JSON_UNESCAPED_UNICODE);
                            ?>;

            /* ---- Navbar ---- */
            const navbar = document.getElementById('navbar');
            const scrollTopBtn = document.getElementById('scrollTop');

            window.addEventListener('scroll', () => {
                navbar.classList.toggle('scrolled', window.scrollY > 20);
                scrollTopBtn.classList.toggle('visible', window.scrollY > 400);
            }, {
                passive: true
            });

            scrollTopBtn.addEventListener('click', () => window.scrollTo({
                top: 0,
                behavior: 'smooth'
            }));

            /* ---- Hamburger ---- */
            const ham = document.getElementById('hamburger');
            const mMenu = document.getElementById('mobileMenu');
            ham.addEventListener('click', () => {
                ham.classList.toggle('open');
                mMenu.classList.toggle('open');
            });
            mMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
                ham.classList.remove('open');
                mMenu.classList.remove('open');
            }));

            /* ---- Intersection Observer (reveal) ---- */
            const observer = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('visible');
                        observer.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.1
            });
            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

            /* ---- Filter chips (client-side too, for instant feel) ---- */
            const cards = [...document.querySelectorAll('.pkg-grid .pkg-card')];
            const resultCount = document.getElementById('resultCount');
            const totalCount = <?= count($pakets) ?>;

            function updateCount(visible) {
                resultCount.innerHTML = `Menampilkan <strong>${visible}</strong> dari <strong>${totalCount}</strong> destinasi`;
            }

            /* ---- Search ---- */
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', applyFilters);

            /* ---- Sort ---- */
            const sortSelect = document.getElementById('sortSelect');
            sortSelect.addEventListener('change', applyFilters);

            /* Active filter from URL */
            let activeFilter = '<?= $active_filter ?>';

            document.querySelectorAll('.filter-chip').forEach(chip => {
                chip.addEventListener('click', e => {
                    e.preventDefault();
                    activeFilter = chip.dataset.filter;
                    document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                    applyFilters();
                });
            });

            function applyFilters() {
                const q = searchInput.value.toLowerCase().trim();
                const sort = sortSelect.value;
                let visible = 0;

                // Collect & sort
                const cardData = cards.map(card => ({
                    el: card,
                    name: card.dataset.name,
                    price: parseInt(card.dataset.price),
                    filters: card.dataset.filter,
                    altitude: parseInt(card.dataset.altitude),
                }));

                // Sort
                cardData.sort((a, b) => {
                    if (sort === 'price-asc') return a.price - b.price;
                    if (sort === 'price-desc') return b.price - a.price;
                    if (sort === 'name-asc') return a.name.localeCompare(b.name);
                    if (sort === 'altitude-desc') return b.altitude - a.altitude;
                    return 0;
                });

                // Re-order DOM
                const grid = document.getElementById('pkgGrid');
                const seeAll = grid.querySelector('.see-all-card');
                cardData.forEach(d => grid.insertBefore(d.el, seeAll));

                // Show/hide
                cardData.forEach(({
                    el,
                    name,
                    filters
                }) => {
                    const matchSearch = !q || name.includes(q);
                    const matchFilter = activeFilter === 'semua' || filters.includes(activeFilter);
                    const show = matchSearch && matchFilter;
                    el.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                updateCount(visible);

                // Empty state
                let empty = grid.querySelector('.empty-state');
                if (visible === 0) {
                    if (!empty) {
                        empty = document.createElement('div');
                        empty.className = 'empty-state';
                        empty.innerHTML = `
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <h3>Destinasi tidak ditemukan</h3>
                    <p>Coba kata kunci atau filter lain</p>
                    <a href="paket-pendakian.php">Tampilkan Semua</a>`;
                        grid.insertBefore(empty, seeAll);
                    }
                    if (seeAll) seeAll.style.display = 'none';
                } else {
                    if (empty) empty.remove();
                    if (seeAll) seeAll.style.display = '';
                }
            }

            /* ---- Modal ---- */
            const overlay = document.getElementById('modalOverlay');
            const modalBox = document.getElementById('modalBox');

            window.openModal = function(id) {
                const p = PAKETS.find(x => x.id === id);
                if (!p) return;

                // Image
                const wrap = document.getElementById('modalImgWrap');
                wrap.innerHTML = `<div class="modal-img-placeholder">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5">
                <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
            </svg></div>`;
                const img = new Image();
                img.onload = () => {
                    wrap.innerHTML = `<img class="modal-img" src="${p.image}" alt="${p.name}">`;
                };
                img.src = p.image;

                // Title
                document.getElementById('modalTitle').textContent = p.name;

                // Badges
                const diffColor = {
                    mudah: '#3a8c5c',
                    menengah: '#f4a261',
                    sulit: '#e63946'
                };
                document.getElementById('modalBadges').innerHTML = `
            <span class="modal-badge">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M8 3l4 8 5-5 5 15H2L8 3z"/></svg>
                ${p.altitude}
            </span>
            <span class="modal-badge">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                ${p.duration}
            </span>
            <span class="modal-badge" style="background:${diffColor[p.diffKey]||'#3a8c5c'};color:#fff;border-color:transparent">
                Tingkat ${p.diff}
            </span>`;

                // Includes
                document.getElementById('modalIncludes').innerHTML = `
            <h4>Yang Sudah Termasuk:</h4>
            <ul>${p.includes.map(i => `<li>${i}</li>`).join('')}</ul>`;

                // Price
                document.getElementById('modalPrice').textContent = p.price;

                // WA link
                document.getElementById('modalWaBtn').href =
                    `https://wa.me/6281234567890?text=Halo%2C+saya+ingin+booking+${encodeURIComponent(p.name)}`;

                // Open
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            };

            window.closeModal = function(e) {
                if (e && e.target !== overlay) return;
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            };

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    overlay.classList.remove('open');
                    document.body.style.overflow = '';
                }
            });

        })();
    </script>
</body>

</html>