<?php
// Rinjani Guide - Halaman Utama
$page_title = "Rinjani Guide - Pendakian Aman, Pengalaman Tak Terlupakan";
$destinations = [
    [
        "name" => "Gunung Rinjani",
        "altitude" => "3.726 mdpl",
        "difficulty" => "Sulit",
        "difficulty_class" => "sulit",
        "duration" => "2 - 3 Hari",
        "price" => "Mulai Rp 300.000",
        "image" => "6a193065f14f2_destinasi",
    ],
    [
        "name" => "Bukit Pergasingan",
        "altitude" => "1.805 mdpl",
        "difficulty" => "Mudah",
        "difficulty_class" => "mudah",
        "duration" => "2 - 4 Jam",
        "price" => "Mulai Rp 100.000",
        "image" => "../upload/6a1bab4cbe9b2_destinasi",
    ],
    [
        "name" => "Bukit Sempana",
        "altitude" => "2.329 mdpl",
        "difficulty" => "Menengah",
        "difficulty_class" => "menengah",
        "duration" => "4 - 7 Jam",
        "price" => "Mulai Rp 100.000",
        "image" => "../upload/6a192d57899da_destinasi",
    ],
    [
        "name" => "Bukit Anak Dara",
        "altitude" => "1.923 mdpl",
        "difficulty" => "Mudah",
        "difficulty_class" => "mudah",
        "duration" => "4 - 6 Jam",
        "price" => "Mulai Rp 100.000",
        "image" => "../upload/6a1bab3bc6ee4_destinasi",
    ],
];

$features = [
    ["icon" => "guide", "title" => "Guide Lokal", "desc" => "Berpengalaman"],
    ["icon" => "route", "title" => "Jalur Terbaik", "desc" => "& Aman"],
    ["icon" => "equipment", "title" => "Peralatan Lengkap", "desc" => "& Standar"],
    ["icon" => "price", "title" => "Harga Terbaik", "desc" => "& Transparan"],
];

$why_us = [
    ["icon" => "guide-exp", "title" => "Guide Berpengalaman", "desc" => "Tim guide lokal yang ramah dan profesional"],
    ["icon" => "safety", "title" => "Keamanan Terjamin", "desc" => "Mengutamakan keselamatan pendaki di setiap perjalanan"],
    ["icon" => "transparent", "title" => "Harga Transparan", "desc" => "Tidak ada biaya tersembunyi, harga sesuai layanan"],
    ["icon" => "service", "title" => "Pelayanan Terbaik", "desc" => "Siap membantu sebelum, selama, dan setelah pendakian"],
];
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
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-dark:   #1a3a2a;
            --green-mid:    #2d6a4f;
            --green-main:   #3a8c5c;
            --green-light:  #52b788;
            --green-pale:   #d8f3dc;
            --amber:        #f4a261;
            --amber-dark:   #e76f51;
            --cream:        #f8f5f0;
            --white:        #ffffff;
            --text-dark:    #1a1a2e;
            --text-mid:     #3d3d5c;
            --text-light:   #7a7a9a;
            --card-bg:      #ffffff;
            --shadow-sm:    0 2px 12px rgba(0,0,0,.07);
            --shadow-md:    0 6px 30px rgba(0,0,0,.12);
            --shadow-lg:    0 16px 60px rgba(0,0,0,.18);
            --radius-sm:    10px;
            --radius-md:    18px;
            --radius-lg:    28px;
            --transition:   .3s cubic-bezier(.25,.8,.25,1);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text-dark);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--cream); }
        ::-webkit-scrollbar-thumb { background: var(--green-light); border-radius: 3px; }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%;
            height: 72px;
            background: rgba(20, 40, 30, 0.55);
            backdrop-filter: blur(18px) saturate(1.8);
            -webkit-backdrop-filter: blur(18px) saturate(1.8);
            border-bottom: 1px solid rgba(255,255,255,.08);
            transition: background var(--transition), box-shadow var(--transition);
        }
        .navbar.scrolled {
            background: rgba(20, 40, 30, 0.97);
            box-shadow: var(--shadow-md);
        }

        .navbar-logo img {
            height: 44px;
            width: auto;
            object-fit: contain;
            display: block;
        }
        .navbar-logo .logo-placeholder {
            height: 44px; width: 140px;
            background: rgba(255,255,255,.08);
            border: 1.5px dashed rgba(255,255,255,.3);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.5); font-size: 11px; letter-spacing: .5px;
        }

        .navbar-menu {
            display: flex; align-items: center; gap: 36px; list-style: none;
        }
        .navbar-menu a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            letter-spacing: .3px;
            transition: color var(--transition);
            position: relative;
        }
        .navbar-menu a::after {
            content: '';
            position: absolute; bottom: -4px; left: 0; right: 0;
            height: 2px; background: var(--green-light);
            transform: scaleX(0); transform-origin: left;
            transition: transform var(--transition);
        }
        .navbar-menu a:hover { color: #fff; }
        .navbar-menu a:hover::after { transform: scaleX(1); }

        .btn-booking {
            background: var(--green-main);
            color: #fff !important;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: .3px;
            transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
            box-shadow: 0 4px 18px rgba(58,140,92,.4);
        }
        .btn-booking:hover {
            background: var(--green-light) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(82,183,136,.45);
        }
        .btn-booking::after { display: none !important; }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column; gap: 5px; cursor: pointer;
            background: none; border: none; padding: 4px;
        }
        .hamburger span {
            display: block; width: 24px; height: 2px;
            background: #fff; border-radius: 2px;
            transition: transform .3s, opacity .3s;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* Mobile menu */
        .mobile-menu {
            display: none; position: fixed; top: 72px; left: 0; right: 0;
            background: rgba(20,40,30,.98); backdrop-filter: blur(20px);
            padding: 24px 5% 32px; flex-direction: column; gap: 0;
            z-index: 999; border-bottom: 1px solid rgba(255,255,255,.07);
            transform: translateY(-10px); opacity: 0;
            transition: transform .3s, opacity .3s;
        }
        .mobile-menu.open { display: flex; transform: translateY(0); opacity: 1; }
        .mobile-menu a {
            color: rgba(255,255,255,.85); text-decoration: none;
            padding: 14px 0; font-size: 15px; font-weight: 500;
            border-bottom: 1px solid rgba(255,255,255,.07);
            transition: color .2s;
        }
        .mobile-menu a:last-child { border-bottom: none; margin-top: 16px; }
        .mobile-menu a:hover { color: var(--green-light); }
        .mobile-menu .btn-booking-mobile {
            display: inline-block; margin-top: 8px;
            background: var(--green-main); color: #fff !important;
            padding: 12px 24px; border-radius: 8px; text-align: center;
            font-weight: 600; border: none;
        }

        /* ===== HERO ===== */
        .hero {
            position: relative; height: 100vh; min-height: 600px;
            display: flex; align-items: flex-end;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background:
                linear-gradient(to bottom, rgba(10,25,18,.35) 0%, rgba(10,25,18,.15) 40%, rgba(10,25,18,.72) 100%),
                url('../upload/rinjani4.jpg') center center / cover no-repeat;
            transform: scale(1.04);
            transition: transform 8s ease-out;
        }
        .hero-bg.loaded { transform: scale(1); }

        /* Particle overlay */
        .hero-particles {
            position: absolute; inset: 0; pointer-events: none;
            background-image:
                radial-gradient(circle at 20% 60%, rgba(82,183,136,.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 30%, rgba(244,162,97,.06) 0%, transparent 45%);
        }

        .hero-content {
            position: relative; z-index: 2;
            width: 100%; padding: 0 5% 80px;
            animation: heroFadeUp .9s ease-out both;
        }

        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(58,140,92,.75);
            backdrop-filter: blur(8px);
            color: #fff; font-size: 13px; font-weight: 500;
            padding: 7px 16px; border-radius: 50px;
            margin-bottom: 20px; border: 1px solid rgba(82,183,136,.4);
            animation: heroFadeUp .9s .15s ease-out both;
        }
        .hero-badge svg { flex-shrink: 0; }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 6vw, 72px);
            font-weight: 800; color: #fff;
            line-height: 1.12; letter-spacing: -1px;
            margin-bottom: 18px;
            text-shadow: 0 4px 30px rgba(0,0,0,.4);
            animation: heroFadeUp .9s .25s ease-out both;
        }
        .hero-title span { color: var(--green-light); }

        .hero-sub {
            color: rgba(255,255,255,.8);
            font-size: clamp(14px, 1.8vw, 18px);
            max-width: 480px; margin-bottom: 36px;
            animation: heroFadeUp .9s .35s ease-out both;
        }

        .hero-actions {
            display: flex; gap: 14px; flex-wrap: wrap;
            animation: heroFadeUp .9s .45s ease-out both;
        }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--green-main); color: #fff;
            padding: 14px 28px; border-radius: 10px;
            font-size: 15px; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer;
            transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
            box-shadow: 0 6px 24px rgba(58,140,92,.5);
        }
        .btn-primary:hover {
            background: var(--green-light);
            transform: translateY(-3px);
            box-shadow: 0 12px 36px rgba(82,183,136,.55);
        }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(10px);
            color: #fff; padding: 14px 28px;
            border-radius: 10px; font-size: 15px; font-weight: 600;
            text-decoration: none; border: 1.5px solid rgba(255,255,255,.3);
            cursor: pointer;
            transition: background var(--transition), border-color var(--transition), transform var(--transition);
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,.2);
            border-color: rgba(255,255,255,.6);
            transform: translateY(-3px);
        }

        /* ===== STATS BAR ===== */
        .stats-bar {
            position: relative; z-index: 10;
            margin: -48px 5% 0;
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            display: grid; grid-template-columns: repeat(4, 1fr);
            overflow: hidden;
            animation: fadeInUp .7s .6s ease-out both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .stat-item {
            display: flex; align-items: center; gap: 16px;
            padding: 26px 28px;
            border-right: 1px solid rgba(0,0,0,.06);
            transition: background var(--transition);
        }
        .stat-item:last-child { border-right: none; }
        .stat-item:hover { background: var(--green-pale); }

        .stat-icon {
            width: 48px; height: 48px; flex-shrink: 0;
            background: var(--green-pale);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-icon svg { color: var(--green-main); }

        .stat-text .stat-title {
            font-weight: 700; font-size: 15px; color: var(--text-dark); line-height: 1.2;
        }
        .stat-text .stat-desc { font-size: 13px; color: var(--text-light); }

        /* ===== SECTION BASE ===== */
        section { padding: 90px 5%; }

        .section-header { text-align: center; margin-bottom: 52px; }
        .section-label {
            display: inline-block;
            background: var(--green-pale); color: var(--green-mid);
            font-size: 12px; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; padding: 5px 14px;
            border-radius: 50px; margin-bottom: 12px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 700; color: var(--text-dark);
            line-height: 1.2; margin-bottom: 10px;
        }
        .section-sub {
            color: var(--text-light); font-size: 16px; max-width: 480px; margin: 0 auto;
        }

        /* ===== DESTINATIONS ===== */
        .destinations { background: var(--cream); }

        .dest-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .dest-card {
            background: var(--card-bg);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: transform var(--transition), box-shadow var(--transition);
            cursor: pointer;
        }
        .dest-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .dest-img-wrap {
            position: relative; height: 200px; overflow: hidden;
        }
        .dest-img-wrap img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .5s ease;
        }
        .dest-card:hover .dest-img-wrap img { transform: scale(1.08); }

        /* Fallback gradient per destination */
        .dest-img-wrap .img-fallback {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            display: flex; align-items: center; justify-content: center;
        }
        .dest-img-wrap .img-fallback svg { opacity: .35; }

        .dest-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(10,25,18,.85) 0%, transparent 55%);
            display: flex; flex-direction: column; justify-content: flex-end;
            padding: 18px 16px 14px;
        }
        .dest-name {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 700; color: #fff;
            line-height: 1.2; margin-bottom: 4px;
        }
        .dest-alt { color: rgba(255,255,255,.75); font-size: 13px; margin-bottom: 8px; }

        .badge {
            display: inline-block; padding: 3px 12px;
            border-radius: 6px; font-size: 12px; font-weight: 700;
            letter-spacing: .3px;
        }
        .badge.sulit   { background: #e63946; color: #fff; }
        .badge.mudah   { background: var(--green-main); color: #fff; }
        .badge.menengah { background: var(--amber); color: #fff; }

        .dest-meta {
            padding: 16px 16px 20px;
            display: flex; flex-direction: column; gap: 8px;
        }
        .dest-meta-row {
            display: flex; align-items: center; gap: 8px;
            color: var(--text-mid); font-size: 14px;
        }
        .dest-meta-row svg { color: var(--green-main); flex-shrink: 0; }

        .dest-actions {
            text-align: center; margin-top: 52px;
        }
        .btn-outline {
            display: inline-flex; align-items: center; gap: 10px;
            background: var(--green-main); color: #fff;
            padding: 14px 32px; border-radius: 10px;
            font-size: 15px; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer;
            transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
            box-shadow: 0 6px 24px rgba(58,140,92,.35);
        }
        .btn-outline:hover {
            background: var(--green-dark);
            transform: translateY(-3px);
            box-shadow: 0 12px 36px rgba(26,58,42,.4);
        }

        /* ===== WHY US ===== */
        .why-us {
            background: linear-gradient(160deg, var(--green-dark) 0%, #0d2118 100%);
            position: relative; overflow: hidden;
        }
        .why-us::before {
            content: '';
            position: absolute; top: -120px; right: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(82,183,136,.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .why-us .section-title { color: #fff; }
        .why-us .section-sub { color: rgba(255,255,255,.55); }
        .why-us .section-label { background: rgba(82,183,136,.15); color: var(--green-light); }

        .why-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 28px; position: relative; z-index: 1;
        }

        .why-card {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius-md);
            padding: 32px 24px;
            text-align: center;
            transition: background var(--transition), transform var(--transition), border-color var(--transition);
        }
        .why-card:hover {
            background: rgba(82,183,136,.1);
            border-color: rgba(82,183,136,.3);
            transform: translateY(-6px);
        }

        .why-icon {
            width: 68px; height: 68px;
            background: rgba(82,183,136,.12);
            border-radius: 18px; margin: 0 auto 20px;
            display: flex; align-items: center; justify-content: center;
            transition: background var(--transition);
        }
        .why-card:hover .why-icon { background: rgba(82,183,136,.22); }

        .why-title {
            font-weight: 700; font-size: 16px; color: #fff;
            margin-bottom: 8px;
        }
        .why-desc { font-size: 14px; color: rgba(255,255,255,.55); line-height: 1.6; }

        /* ===== PACKAGES TEASER ===== */
        .packages-teaser {
            background: var(--cream);
        }

        .pkg-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
        }

        .pkg-card {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 32px 28px;
            box-shadow: var(--shadow-sm);
            position: relative; overflow: hidden;
            transition: transform var(--transition), box-shadow var(--transition);
            border: 1.5px solid transparent;
        }
        .pkg-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: var(--green-light);
        }
        .pkg-card.featured {
            background: linear-gradient(155deg, var(--green-dark), var(--green-mid));
            border-color: var(--green-light);
        }
        .pkg-badge-featured {
            position: absolute; top: 20px; right: 20px;
            background: var(--amber);
            color: #fff; font-size: 11px; font-weight: 700;
            padding: 4px 12px; border-radius: 50px;
            letter-spacing: .5px;
        }
        .pkg-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700;
            color: var(--text-dark); margin-bottom: 6px;
        }
        .pkg-card.featured .pkg-name { color: #fff; }
        .pkg-desc { font-size: 14px; color: var(--text-light); margin-bottom: 24px; }
        .pkg-card.featured .pkg-desc { color: rgba(255,255,255,.65); }

        .pkg-price {
            font-family: 'Playfair Display', serif;
            font-size: 32px; font-weight: 800;
            color: var(--green-main); margin-bottom: 4px;
        }
        .pkg-card.featured .pkg-price { color: var(--green-light); }
        .pkg-price-note { font-size: 13px; color: var(--text-light); margin-bottom: 24px; }
        .pkg-card.featured .pkg-price-note { color: rgba(255,255,255,.5); }

        .pkg-features { list-style: none; margin-bottom: 28px; display: flex; flex-direction: column; gap: 10px; }
        .pkg-features li { display: flex; align-items: center; gap: 10px; font-size: 14px; color: var(--text-mid); }
        .pkg-card.featured .pkg-features li { color: rgba(255,255,255,.8); }
        .pkg-features li::before {
            content: '';
            width: 18px; height: 18px; flex-shrink: 0;
            background: var(--green-pale) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232d6a4f' stroke-width='3'%3E%3Cpath d='M20 6L9 17l-5-5'/%3E%3C/svg%3E") center/12px no-repeat;
            border-radius: 50%;
        }
        .pkg-card.featured .pkg-features li::before {
            background-color: rgba(82,183,136,.2);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2352b788' stroke-width='3'%3E%3Cpath d='M20 6L9 17l-5-5'/%3E%3C/svg%3E");
        }

        .btn-pkg {
            display: block; width: 100%; text-align: center;
            padding: 13px 20px; border-radius: 10px;
            font-size: 15px; font-weight: 600; cursor: pointer;
            text-decoration: none; border: none;
            transition: all var(--transition);
        }
        .btn-pkg-outline {
            border: 2px solid var(--green-main); color: var(--green-main);
            background: transparent;
        }
        .btn-pkg-outline:hover { background: var(--green-main); color: #fff; }
        .btn-pkg-solid {
            background: var(--green-light); color: #fff;
            box-shadow: 0 6px 24px rgba(82,183,136,.4);
        }
        .btn-pkg-solid:hover { background: #fff; color: var(--green-dark); }

        /* ===== TESTIMONIALS ===== */
        .testimonials { background: var(--white); }

        .testi-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
        }

        .testi-card {
            background: var(--cream);
            border-radius: var(--radius-md);
            padding: 28px 24px;
            position: relative;
            transition: transform var(--transition), box-shadow var(--transition);
        }
        .testi-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }

        .testi-quote {
            font-size: 48px; color: var(--green-light);
            font-family: 'Playfair Display', serif;
            line-height: .8; margin-bottom: 12px;
        }
        .testi-text { font-size: 15px; color: var(--text-mid); line-height: 1.7; margin-bottom: 20px; }

        .testi-author { display: flex; align-items: center; gap: 12px; }
        .testi-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--green-pale);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px; color: var(--green-mid);
            flex-shrink: 0;
        }
        .testi-name { font-weight: 600; font-size: 14px; color: var(--text-dark); }
        .testi-role { font-size: 12px; color: var(--text-light); }

        .testi-stars { color: var(--amber); font-size: 13px; margin-bottom: 2px; }

        /* ===== CTA STRIP ===== */
        .cta-strip {
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            padding: 72px 5%;
            text-align: center; position: relative; overflow: hidden;
        }
        .cta-strip::before {
            content: '';
            position: absolute; bottom: -80px; left: -80px;
            width: 320px; height: 320px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
        }
        .cta-strip::after {
            content: '';
            position: absolute; top: -60px; right: -60px;
            width: 240px; height: 240px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }
        .cta-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800; color: #fff; margin-bottom: 12px;
        }
        .cta-sub { color: rgba(255,255,255,.7); font-size: 16px; margin-bottom: 36px; }
        .cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }
        .btn-cta-white {
            background: #fff; color: var(--green-dark);
            padding: 14px 32px; border-radius: 10px;
            font-size: 15px; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer;
            transition: all var(--transition);
            box-shadow: 0 6px 24px rgba(0,0,0,.2);
        }
        .btn-cta-white:hover { background: var(--green-pale); transform: translateY(-3px); }
        .btn-cta-border {
            background: transparent; color: #fff;
            padding: 14px 32px; border-radius: 10px;
            font-size: 15px; font-weight: 600;
            text-decoration: none; border: 2px solid rgba(255,255,255,.5);
            transition: all var(--transition); cursor: pointer;
        }
        .btn-cta-border:hover { border-color: #fff; background: rgba(255,255,255,.1); transform: translateY(-3px); }

        /* ===== FOOTER ===== */
        footer {
            background: var(--green-dark);
            color: rgba(255,255,255,.7);
            padding: 64px 5% 36px;
        }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px; margin-bottom: 48px;
        }
        .footer-brand .logo-wrap { margin-bottom: 16px; }
        .footer-brand .logo-wrap img { height: 40px; width: auto; }
        .footer-brand .logo-placeholder {
            height: 40px; width: 120px;
            background: rgba(255,255,255,.06);
            border: 1.5px dashed rgba(255,255,255,.2);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.35); font-size: 11px;
        }
        .footer-brand p { font-size: 14px; line-height: 1.7; max-width: 280px; margin-bottom: 20px; }
        .footer-social { display: flex; gap: 10px; }
        .social-btn {
            width: 38px; height: 38px;
            background: rgba(255,255,255,.07);
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.6); text-decoration: none;
            transition: background var(--transition), color var(--transition);
        }
        .social-btn:hover { background: var(--green-main); color: #fff; }

        .footer-heading {
            font-weight: 700; font-size: 14px; color: #fff;
            letter-spacing: .5px; margin-bottom: 18px;
        }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-links a {
            color: rgba(255,255,255,.55); text-decoration: none; font-size: 14px;
            transition: color .2s;
        }
        .footer-links a:hover { color: var(--green-light); }

        .footer-contact { display: flex; flex-direction: column; gap: 12px; }
        .footer-contact-item { display: flex; align-items: flex-start; gap: 10px; font-size: 14px; }
        .footer-contact-item svg { color: var(--green-light); flex-shrink: 0; margin-top: 2px; }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.07);
            padding-top: 28px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }
        .footer-bottom p { font-size: 13px; }
        .footer-bottom-links { display: flex; gap: 20px; }
        .footer-bottom-links a {
            color: rgba(255,255,255,.4); font-size: 13px;
            text-decoration: none; transition: color .2s;
        }
        .footer-bottom-links a:hover { color: var(--green-light); }

        /* ===== SCROLL TOP ===== */
        .scroll-top {
            position: fixed; bottom: 28px; right: 28px; z-index: 900;
            width: 46px; height: 46px;
            background: var(--green-main); color: #fff;
            border-radius: 12px; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 24px rgba(58,140,92,.45);
            transition: all var(--transition);
            opacity: 0; transform: translateY(12px) scale(.9);
            pointer-events: none;
        }
        .scroll-top.visible { opacity: 1; transform: none; pointer-events: all; }
        .scroll-top:hover { background: var(--green-dark); transform: translateY(-3px); }

        /* ===== REVEAL ANIMATIONS ===== */
        .reveal {
            opacity: 0; transform: translateY(32px);
            transition: opacity .65s ease, transform .65s ease;
        }
        .reveal.visible { opacity: 1; transform: none; }
        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .4s; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1100px) {
            .dest-grid { grid-template-columns: repeat(2, 1fr); }
            .why-grid  { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 36px; }
        }

        @media (max-width: 820px) {
            .stats-bar { grid-template-columns: repeat(2, 1fr); margin: -36px 4% 0; }
            .stat-item:nth-child(2) { border-right: none; }
            .stat-item:nth-child(3) { border-right: 1px solid rgba(0,0,0,.06); }
            .stat-item:nth-child(3),
            .stat-item:nth-child(4) { border-top: 1px solid rgba(0,0,0,.06); }

            .pkg-grid { grid-template-columns: 1fr; max-width: 480px; margin: 0 auto; }
            .testi-grid { grid-template-columns: 1fr; max-width: 520px; margin: 0 auto; }
            .footer-grid { grid-template-columns: 1fr; gap: 32px; }
        }

        @media (max-width: 768px) {
            .navbar-menu { display: none; }
            .hamburger { display: flex; }
            section { padding: 64px 5%; }
        }

        @media (max-width: 600px) {
            .dest-grid { grid-template-columns: 1fr; }
            .why-grid  { grid-template-columns: 1fr; }
            .stats-bar { grid-template-columns: 1fr; }
            .stat-item { border-right: none !important; border-top: 1px solid rgba(0,0,0,.06); }
            .stat-item:first-child { border-top: none; }
            .hero-content { padding-bottom: 60px; }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" id="navbar">
    <a class="navbar-logo" href="#">
        <!-- Ganti src dengan path logo Anda -->
        <?php if (file_exists('../upload/logo.png')): ?>
            <img src="../upload/logo.png" alt="Rinjani Guide Logo">
        <?php else: ?>
            <div class="logo-placeholder">LOGO HERE</div>
        <?php endif; ?>
    </a>

    <ul class="navbar-menu">
        <li><a href="beranda.php">Beranda</a></li>
        <li><a href="paket.php">Paket Pendakian</a></li>
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
    <a href="booking.php" class="btn-booking-mobile">Booking Sekarang</a>
</div>

<!-- ===== HERO ===== -->
<section class="hero" id="home">
    <div class="hero-bg" id="heroBg"></div>
    <div class="hero-particles"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Jelajahi Keindahan Alam Sembalun
        </div>
        <h1 class="hero-title">
            Pendakian Aman,<br>
            <span>Pengalaman Tak Terlupakan</span>
        </h1>
        <p class="hero-sub">
            Bersama guide lokal berpengalaman, nikmati petualangan terbaik di setiap puncak Sembalun.
        </p>
        <div class="hero-actions">
            <a href="paket.php" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Lihat Paket
            </a>
            <a href="tentang.php" class="btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Tentang Kami
            </a>
        </div>
    </div>
</section>

<!-- ===== STATS BAR ===== -->
<div class="stats-bar">
    <?php foreach ($features as $f): ?>
    <div class="stat-item">
        <div class="stat-icon">
            <?php
            $icons = [
                'guide'     => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
                'route'     => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>',
                'equipment' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="15" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>',
                'price'     => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>',
            ];
            echo $icons[$f['icon']] ?? '';
            ?>
        </div>
        <div class="stat-text">
            <div class="stat-title"><?= htmlspecialchars($f['title']) ?></div>
            <div class="stat-desc"><?= htmlspecialchars($f['desc']) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ===== DESTINATIONS ===== -->
<section class="destinations" id="destinations">
    <div class="section-header reveal">
        <div class="section-label">Pilih Destinasi</div>
        <h2 class="section-title">Destinasi Populer</h2>
        <p class="section-sub">Pilih destinasi pendakian favoritmu</p>
    </div>

    <div class="dest-grid">
        <?php foreach ($destinations as $i => $d): ?>
        <div class="dest-card reveal reveal-delay-<?= ($i % 4) + 1 ?>">
            <div class="dest-img-wrap">
                <?php if (file_exists($d['image'])): ?>
                    <img src="<?= htmlspecialchars($d['image']) ?>" alt="<?= htmlspecialchars($d['name']) ?>" loading="lazy">
                <?php else: ?>
                    <div class="img-fallback">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5">
                            <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
                        </svg>
                    </div>
                <?php endif; ?>
                <div class="dest-overlay">
                    <div class="dest-name"><?= htmlspecialchars($d['name']) ?></div>
                    <div class="dest-alt"><?= htmlspecialchars($d['altitude']) ?></div>
                    <span class="badge <?= htmlspecialchars($d['difficulty_class']) ?>">
                        Tingkat <?= htmlspecialchars($d['difficulty']) ?>
                    </span>
                </div>
            </div>
            <div class="dest-meta">
                <div class="dest-meta-row">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?= htmlspecialchars($d['duration']) ?>
                </div>
                <div class="dest-meta-row">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    <?= htmlspecialchars($d['price']) ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="dest-actions reveal">
        <a href="paket.php" class="btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            Lihat Semua Destinasi
        </a>
    </div>
</section>

<!-- ===== WHY US ===== -->
<section class="why-us" id="why-us">
    <div class="section-header reveal">
        <div class="section-label">Keunggulan Kami</div>
        <h2 class="section-title">Mengapa Memilih Kami?</h2>
        <p class="section-sub">Kami hadir untuk memastikan setiap langkah pendakianmu aman dan berkesan</p>
    </div>

    <div class="why-grid">
        <?php
        $why_icons = [
            'guide-exp'   => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#52b788" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
            'safety'      => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#52b788" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
            'transparent' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#52b788" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>',
            'service'     => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#52b788" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.52 12 19.79 19.79 0 01.47 3.4 2 2 0 012.44 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 9.4a16 16 0 006.72 6.72l.8-.8a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>',
        ];
        foreach ($why_us as $i => $w):
        ?>
        <div class="why-card reveal reveal-delay-<?= ($i % 4) + 1 ?>">
            <div class="why-icon">
                <?= $why_icons[$w['icon']] ?? '' ?>
            </div>
            <div class="why-title"><?= htmlspecialchars($w['title']) ?></div>
            <div class="why-desc"><?= htmlspecialchars($w['desc']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="testimonials" id="testimonials">
    <div class="section-header reveal">
        <div class="section-label">Testimoni</div>
        <h2 class="section-title">Kata Mereka</h2>
        <p class="section-sub">Ribuan pendaki telah mempercayakan perjalanan mereka kepada kami</p>
    </div>
 
    <div class="testi-grid">
        <?php
        $testimonials = [
            [
                "text"   => "Pengalaman yang luar biasa! Guide-nya sangat profesional dan ramah. Jalur yang dipilih aman dan pemandangannya memukau. Pasti akan kembali lagi!",
                "name"   => "Andi Susanto",
                "role"   => "Pendaki dari Jawa Tengah",
                "init"   => "AP",
                "stars"  => 5,
            ],
            [
                "text"   => "Paket premiumnya worth it banget. Semua fasilitas lengkap, makanan enak, dan guidenya berpengalaman. Dokumentasi foto juga keren! Highly recommended.",
                "name"   => "Lalu Arya",
                "role"   => "Pendaki dari Sumbawa",
                "init"   => "SD",
                "stars"  => 5,
            ],
            [
                "text"   => "Ini pertama kali saya mendaki dan saya pilih Rinjani Guide. Keputusan terbaik! Guidenya sabar banget nemenin saya sampai puncak. Terima kasih!",
                "name"   => "Neza Khulaifia",
                "role"   => "Pendaki Pemula dari Lombok Utara",
                "init"   => "BS",
                "stars"  => 5,
            ],
        ];
        foreach ($testimonials as $i => $t):
        ?>
        <div class="testi-card reveal reveal-delay-<?= $i + 1 ?>">
            <div class="testi-quote">"</div>
            <p class="testi-text"><?= htmlspecialchars($t['text']) ?></p>
            <div class="testi-author">
                <div class="testi-avatar"><?= htmlspecialchars($t['init']) ?></div>
                <div>
                    <div class="testi-stars"><?= str_repeat('★', $t['stars']) ?></div>
                    <div class="testi-name"><?= htmlspecialchars($t['name']) ?></div>
                    <div class="testi-role"><?= htmlspecialchars($t['role']) ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer id="contact">
    <div class="footer-grid">
        <!-- Brand -->
        <div class="footer-brand">
            <div class="logo-wrap">
                <?php if (file_exists('../upload/logo.png')): ?>
                    <img src="../upload/logo.png" alt="Rinjani Guide">
                <?php else: ?>
                    <div class="logo-placeholder">LOGO HERE</div>
                <?php endif; ?>
            </div>
            <p>Guide lokal terpercaya untuk pendakian aman dan berkesan di kawasan Sembalun, Lombok.</p>
            <div class="footer-social">
                <a href="#" class="social-btn" title="Instagram">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                    </svg>
                </a>
                <a href="#" class="social-btn" title="Facebook">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                    </svg>
                </a>
                <a href="https://wa.me/6281234567890" class="social-btn" title="WhatsApp">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                    </svg>
                </a>
                <a href="#" class="social-btn" title="YouTube">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58z"/>
                        <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Links -->
        <div>
            <div class="footer-heading">Navigasi</div>
            <ul class="footer-links">
                <li><a href="beranda.php">Beranda</a></li>
                <li><a href="paket.php">Paket Pendakian</a></li>
                <li><a href="tentang.php">Tentang Kami</a></li>
                <li><a href="#testimonials">Testimoni</a></li>
            </ul>
        </div>

        <!-- Destinations -->
        <div>
            <div class="footer-heading">Destinasi</div>
            <ul class="footer-links">
                <?php foreach ($destinations as $d): ?>
                <li><a href="#"><?= htmlspecialchars($d['name']) ?></a></li>
                <?php endforeach; ?>
                <li><a href="tentang.php">Semua Destinasi →</a></li>
            </ul>
        </div>

        <!-- Contact -->
        <div>
            <div class="footer-heading">Kontak</div>
            <div class="footer-contact">
                <div class="footer-contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>Sembalun, Lombok Timur,<br>Nusa Tenggara Barat</span>
                </div>
                <div class="footer-contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.52 12 19.79 19.79 0 01.47 3.4 2 2 0 012.44 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 9.4a16 16 0 006.72 6.72l.8-.8a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                    <a href="https://wa.me/6283129650994" style="color:inherit;text-decoration:none;">+62 831-2965-0994</a>
                </div>
                <div class="footer-contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <a href="mailto:info@rinjaniguide.com" style="color:inherit;text-decoration:none;">info@rinjaniguide.com</a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© <?= date('Y') ?> Rinjani Guide. All rights reserved.</p>
        <div class="footer-bottom-links">
            <a href="#">Privasi</a>
            <a href="#">Syarat & Ketentuan</a>
            <a href="#">Kebijakan Refund</a>
        </div>
    </div>
</footer>

<!-- ===== SCROLL TOP ===== -->
<button class="scroll-top" id="scrollTop" aria-label="Kembali ke atas">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="18 15 12 9 6 15"/>
    </svg>
</button>

<!-- ===== JAVASCRIPT ===== -->
<script>
(function () {
    'use strict';

    /* --- Navbar scroll --- */
    const navbar = document.getElementById('navbar');
    const scrollTopBtn = document.getElementById('scrollTop');

    window.addEventListener('scroll', () => {
        const y = window.scrollY;
        navbar.classList.toggle('scrolled', y > 60);
        scrollTopBtn.classList.toggle('visible', y > 400);
    }, { passive: true });

    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    /* --- Hamburger / mobile menu --- */
    const ham  = document.getElementById('hamburger');
    const mMenu = document.getElementById('mobileMenu');

    ham.addEventListener('click', () => {
        ham.classList.toggle('open');
        mMenu.classList.toggle('open');
    });

    mMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            ham.classList.remove('open');
            mMenu.classList.remove('open');
        });
    });

    /* --- Smooth scroll for anchors --- */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const id = a.getAttribute('href');
            if (id === '#') return;
            const el = document.querySelector(id);
            if (el) {
                e.preventDefault();
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* --- Intersection Observer for reveal animations --- */
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    reveals.forEach(el => observer.observe(el));

    /* --- Hero bg parallax (mild) --- */
    const heroBg = document.getElementById('heroBg');
    if (heroBg) {
        setTimeout(() => heroBg.classList.add('loaded'), 100);

        window.addEventListener('scroll', () => {
            const y = window.scrollY;
            if (y < window.innerHeight) {
                heroBg.style.transform = `scale(1) translateY(${y * 0.25}px)`;
            }
        }, { passive: true });
    }

    /* --- Destination card: image error fallback --- */
    document.querySelectorAll('.dest-img-wrap img').forEach(img => {
        img.addEventListener('error', () => {
            img.parentElement.style.display = 'none';
            const fallback = document.createElement('div');
            fallback.className = 'img-fallback';
            fallback.innerHTML = '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5"><path d="M8 3l4 8 5-5 5 15H2L8 3z"/></svg>';
            img.parentElement.parentElement.querySelector('.dest-img-wrap').appendChild(fallback);
        });
    });

    /* --- Stat counter animation --- */
    function animateCount(el, target, suffix) {
        let start = 0;
        const step = target / 50;
        const timer = setInterval(() => {
            start += step;
            if (start >= target) { start = target; clearInterval(timer); }
            el.textContent = Math.round(start).toLocaleString('id-ID') + suffix;
        }, 30);
    }

    /* --- Active nav link on scroll --- */
    const sections = document.querySelectorAll('section[id], div[id]');
    const navLinks = document.querySelectorAll('.navbar-menu a');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(s => {
            if (window.scrollY >= s.offsetTop - 120) current = s.id;
        });
        navLinks.forEach(a => {
            a.classList.remove('active');
            if (a.getAttribute('href') === '#' + current) a.classList.add('active');
        });
    }, { passive: true });

})();
</script>
</body>
</html>