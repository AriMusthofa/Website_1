<?php
// =====================================================================
//  Rinjani Guide — Halaman Tentang Kami
// =====================================================================
$page_title = "Tentang Kami — Rinjani Guide";

$stats = [
    [
        "icon"  => "mountain",
        "value" => "5+",
        "label" => "Tahun Pengalaman",
        "desc"  => "Melayani pendaki sejak 2013",
    ],
    [
        "icon"  => "users",
        "value" => "100+",
        "label" => "Pendaki Puas",
        "desc"  => "Telah mendaki bersama kami",
    ],
    [
        "icon"  => "guide",
        "value" => "10+",
        "label" => "Guide Profesional",
        "desc"  => "Berpengalaman & bersertifikat",
    ],
    [
        "icon"  => "shield",
        "value" => "100%",
        "label" => "Keselamatan",
        "desc"  => "Prioritas utama kami",
    ],
];

$misi = [
    "Memberikan layanan pendakian yang aman dan profesional",
    "Mengutamakan kepuasan dan kenyamanan pendaki",
    "Memberdayakan masyarakat lokal sekitar Rinjani",
    "Menjaga kelestarian alam dan lingkungan sekitar",
];

$why_us = [
    [
        "icon"  => "award",
        "title" => "Guide Lokal Berpengalaman",
        "desc"  => "Tim guide profesional, ramah, dan sangat berpengalaman.",
    ],
    [
        "icon"  => "shield-plus",
        "title" => "Keamanan Terjamin",
        "desc"  => "Peralatan standar, SOP jelas, dan asuransi pendakian.",
    ],
    [
        "icon"  => "tag",
        "title" => "Harga Transparan",
        "desc"  => "Harga jelas tanpa biaya tersembunyi, sesuai dengan layanan.",
    ],
    [
        "icon"  => "smile",
        "title" => "Layanan Ramah",
        "desc"  => "Siap membantu sebelum, selama, dan setelah pendakian.",
    ],
];

$team = [
    ["name" => "Sulaiman",    "role" => "Lead Guide & Founder",      "exp" => "5 tahun pengalaman", "img" => "../upload/Sulaiman.jpg"],
    ["name" => "Putra Jaya",   "role" => "Senior Mountain Guide",        "exp" => "3 tahun pengalaman",  "img" => "../upload/Putra.jpg"],
    ["name" => "Sataruddin",    "role" => "Operations Manage",     "exp" => "2 tahun pengalaman", "img" => "../upload/Satar.jpg"],
    ["name" => "Ari Musthofa", "role" => "Safety & Equipment Officer","exp" => "2 tahun pengalaman",  "img" => "../upload/Ari.jpg"],
    ["name" => "Zahid Faruqi", "role" => "Trail & Logistics Guide",   "exp" => "1 tahun pengalaman",  "img" => "../upload/Faruq.jpg"],
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
        /* ===== RESET & VARIABLES ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-dark:  #1a3a2a;
            --green-mid:   #2d6a4f;
            --green-main:  #3a8c5c;
            --green-light: #52b788;
            --green-pale:  #d8f3dc;
            --cream:       #f4f5f0;
            --white:       #ffffff;
            --text-dark:   #161d12;
            --text-mid:    #3d4838;
            --text-light:  #8a9180;
            --border:      #e4e8df;
            --shadow-xs:   0 1px 4px rgba(0,0,0,.05);
            --shadow-sm:   0 3px 14px rgba(0,0,0,.08);
            --shadow-md:   0 8px 32px rgba(0,0,0,.11);
            --shadow-lg:   0 20px 60px rgba(0,0,0,.14);
            --radius-sm:   10px;
            --radius-md:   16px;
            --radius-lg:   24px;
            --nav-h:       72px;
            --tr:          .28s cubic-bezier(.25,.8,.25,1);
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text-dark);
            overflow-x: hidden;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--cream); }
        ::-webkit-scrollbar-thumb { background: var(--green-light); border-radius: 3px; }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            height: var(--nav-h);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-xs);
            transition: box-shadow var(--tr);
        }
        .navbar.scrolled { box-shadow: var(--shadow-sm); }

        .nav-logo img {
            height: 44px; width: auto; object-fit: contain; display: block;
        }
        .nav-logo .logo-ph {
            height: 44px; width: 148px;
            border: 1.5px dashed var(--border);
            border-radius: 6px; background: var(--cream);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-light); font-size: 11px; letter-spacing: .5px;
        }

        .nav-menu {
            display: flex; align-items: center; gap: 36px; list-style: none;
        }
        .nav-menu a {
            color: var(--text-mid); text-decoration: none;
            font-size: 14px; font-weight: 500; letter-spacing: .2px;
            position: relative; transition: color var(--tr);
        }
        .nav-menu a:hover,
        .nav-menu a.active { color: var(--green-main); }
        .nav-menu a.active::after {
            content: ''; position: absolute;
            bottom: -4px; left: 0; right: 0;
            height: 2px; background: var(--green-main); border-radius: 1px;
        }

        .btn-nav {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--green-main); color: var(--white) !important;
            padding: 10px 20px; border-radius: 8px;
            font-size: 14px; font-weight: 600; letter-spacing: .2px;
            transition: background var(--tr), transform var(--tr), box-shadow var(--tr);
            box-shadow: 0 4px 16px rgba(58,140,92,.32);
        }
        .btn-nav:hover {
            background: var(--green-dark) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26,58,42,.3);
        }
        .btn-nav::after { display: none !important; }

        /* Hamburger */
        .hamburger {
            display: none; flex-direction: column; gap: 5px;
            cursor: pointer; background: none; border: none; padding: 4px;
        }
        .hamburger span {
            display: block; width: 24px; height: 2px;
            background: var(--text-dark); border-radius: 2px;
            transition: transform .3s, opacity .3s;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display: none; position: fixed;
            top: var(--nav-h); left: 0; right: 0;
            background: var(--white); border-bottom: 1px solid var(--border);
            padding: 20px 5% 28px; flex-direction: column;
            z-index: 999; box-shadow: var(--shadow-md);
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            color: var(--text-mid); text-decoration: none;
            padding: 13px 0; font-size: 15px; font-weight: 500;
            border-bottom: 1px solid var(--border); transition: color .2s;
        }
        .mobile-menu a:hover { color: var(--green-main); }
        .mobile-menu a:last-child { border-bottom: none; margin-top: 12px; }
        .mobile-menu .btn-nav-m {
            display: inline-block; background: var(--green-main);
            color: var(--white) !important; padding: 12px 24px;
            border-radius: 8px; text-align: center; font-weight: 600;
        }

        /* ===== HERO ===== */
        .hero {
            position: relative;
            margin-top: var(--nav-h);
            height: 420px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background:
                linear-gradient(to bottom, rgba(10,22,14,.38) 0%, rgba(10,22,14,.62) 100%),
                url('../upload/rinjani2.jpg') center 35% / cover no-repeat;
            transform: scale(1.04);
            transition: transform 6s ease-out;
        }
        .hero-bg.loaded { transform: scale(1); }

        .hero-content {
            position: relative; z-index: 2;
            text-align: center; padding: 0 20px;
            animation: fadeUp .8s ease-out both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 6vw, 64px);
            font-weight: 800; color: var(--white);
            line-height: 1.1; margin-bottom: 16px;
            text-shadow: 0 4px 24px rgba(0,0,0,.4);
        }
        .hero-sub {
            color: rgba(255,255,255,.82);
            font-size: clamp(15px, 2vw, 18px);
            max-width: 520px; margin: 0 auto 28px;
            line-height: 1.65;
        }
        /* Decorative divider */
        .hero-divider {
            display: flex; align-items: center; justify-content: center; gap: 14px;
        }
        .hero-divider::before,
        .hero-divider::after {
            content: '';
            width: 64px; height: 1px;
            background: rgba(255,255,255,.5);
        }
        .hero-divider svg { color: rgba(255,255,255,.7); }

        /* ===== SECTION BASE ===== */
        section { padding: 88px 5%; }

        /* ===== ABOUT STORY ===== */
        .story { background: var(--white); }
        .story-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 64px; align-items: center;
            max-width: 1200px; margin: 0 auto;
        }
        .story-label {
            display: inline-block;
            color: var(--green-main); font-size: 12px; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase;
            margin-bottom: 14px;
        }
        .story-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 700; color: var(--text-dark);
            line-height: 1.2; margin-bottom: 22px;
        }
        .story-body p {
            color: var(--text-mid); font-size: 15px; line-height: 1.8;
            margin-bottom: 16px;
        }
        .story-body p:last-of-type { margin-bottom: 28px; }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 9px;
            background: var(--green-main); color: var(--white);
            padding: 13px 26px; border-radius: 10px;
            font-size: 15px; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer;
            transition: background var(--tr), transform var(--tr), box-shadow var(--tr);
            box-shadow: 0 6px 22px rgba(58,140,92,.35);
        }
        .btn-primary:hover {
            background: var(--green-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(26,58,42,.32);
        }

        /* Story image */
        .story-img-wrap {
            position: relative;
        }
        .story-img {
            width: 100%; border-radius: var(--radius-lg);
            object-fit: cover; display: block;
            aspect-ratio: 4/3;
            box-shadow: var(--shadow-lg);
        }
        .story-img-placeholder {
            width: 100%; aspect-ratio: 4/3;
            border-radius: var(--radius-lg);
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 12px; box-shadow: var(--shadow-lg);
        }
        .story-img-placeholder svg { opacity: .35; }
        .story-img-placeholder span { color: rgba(255,255,255,.4); font-size: 13px; }

        /* Badge on image */
        .img-badge {
            position: absolute; bottom: -18px; left: 28px;
            background: var(--white);
            border-radius: var(--radius-sm);
            padding: 14px 20px;
            box-shadow: var(--shadow-md);
            display: flex; align-items: center; gap: 12px;
        }
        .img-badge-icon {
            width: 46px; height: 46px;
            background: var(--green-pale);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .img-badge-text .num {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 800;
            color: var(--green-main); line-height: 1;
        }
        .img-badge-text .lbl { font-size: 12px; color: var(--text-light); }

        /* ===== STATS STRIP ===== */
        .stats-section {
            background: var(--cream);
            padding: 56px 5%;
        }
        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            max-width: 1100px; margin: 0 auto;
        }
        .stat-box {
            padding: 36px 28px;
            border-right: 1px solid var(--border);
            display: flex; align-items: center; gap: 18px;
            transition: background var(--tr);
        }
        .stat-box:last-child { border-right: none; }
        .stat-box:hover { background: var(--green-pale); }

        .stat-icon-wrap {
            width: 52px; height: 52px; flex-shrink: 0;
            background: var(--green-pale);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            transition: background var(--tr);
        }
        .stat-box:hover .stat-icon-wrap { background: rgba(58,140,92,.18); }
        .stat-icon-wrap svg { color: var(--green-main); }

        .stat-info .stat-val {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-weight: 800;
            color: var(--text-dark); line-height: 1;
            margin-bottom: 3px;
        }
        .stat-info .stat-lbl {
            font-size: 14px; font-weight: 600; color: var(--text-dark);
            margin-bottom: 2px;
        }
        .stat-info .stat-desc { font-size: 12px; color: var(--text-light); }

        /* ===== VISI & MISI ===== */
        .visi-misi { background: var(--white); }
        .section-header { text-align: center; margin-bottom: 52px; }
        .section-tag {
            display: inline-block;
            background: var(--green-pale); color: var(--green-mid);
            font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; padding: 5px 14px;
            border-radius: 50px; margin-bottom: 12px;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(26px, 4vw, 42px);
            font-weight: 700; color: var(--text-dark); line-height: 1.2;
        }
        .section-sub { font-size: 16px; color: var(--text-light); margin-top: 8px; }

        .vm-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 24px; max-width: 1000px; margin: 0 auto;
        }
        .vm-card {
            background: var(--cream);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 36px 32px;
            transition: transform var(--tr), box-shadow var(--tr), border-color var(--tr);
        }
        .vm-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--green-light);
        }
        .vm-icon-wrap {
            width: 60px; height: 60px;
            background: var(--green-main);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 22px;
            box-shadow: 0 6px 20px rgba(58,140,92,.35);
        }
        .vm-label {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700;
            color: var(--text-dark); margin-bottom: 14px;
        }
        .vm-underline {
            width: 36px; height: 3px;
            background: var(--green-main);
            border-radius: 2px; margin-bottom: 16px;
        }
        .vm-text {
            font-size: 15px; color: var(--text-mid);
            line-height: 1.75;
        }
        .vm-list { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .vm-list li {
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 15px; color: var(--text-mid); line-height: 1.55;
        }
        .vm-list li::before {
            content: '';
            width: 20px; height: 20px; flex-shrink: 0; margin-top: 1px;
            background: var(--green-pale)
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232d6a4f' stroke-width='2.8'%3E%3Cpath d='M20 6L9 17l-5-5'/%3E%3C/svg%3E")
                center / 11px no-repeat;
            border-radius: 50%;
        }

        /* ===== WHY US ===== */
        .why-us {
            background: var(--cream);
        }
        .why-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 24px; max-width: 1200px; margin: 0 auto;
        }
        .why-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            padding: 32px 24px 28px;
            text-align: center;
            transition: transform var(--tr), box-shadow var(--tr), border-color var(--tr);
        }
        .why-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: var(--green-light);
        }
        .why-icon {
            width: 64px; height: 64px;
            background: var(--green-pale);
            border-radius: 18px; margin: 0 auto 18px;
            display: flex; align-items: center; justify-content: center;
            transition: background var(--tr);
        }
        .why-card:hover .why-icon { background: var(--green-pale); }
        .why-icon svg { color: var(--green-main); }
        .why-title {
            font-weight: 700; font-size: 16px;
            color: var(--text-dark); margin-bottom: 8px;
        }
        .why-desc { font-size: 14px; color: var(--text-light); line-height: 1.65; }

        /* ===== TEAM ===== */
        .team-section { background: var(--white); padding-left: 3%; padding-right: 3%; }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-top: 50px;
            width: 100%;
        }
        .team-card {
            background: var(--cream);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            overflow: hidden;
            transition: transform var(--tr), box-shadow var(--tr);
            text-align: center;
            min-width: 0;
        }
        .team-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
        .team-photo-wrap {
            position: relative; overflow: hidden;
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
        }
        .team-photo {
            width: 100%; aspect-ratio: 4/5;
            object-fit: cover; display: block;
            transition: transform .5s ease;
        }
        .team-card:hover .team-photo { transform: scale(1.06); }
        .team-photo-placeholder {
            width: 100%; aspect-ratio: 4/5;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
        }
        .team-photo-placeholder svg { opacity: .3; width: 36px; height: 36px; }
        .team-info { padding: 12px 10px 14px; }
        .team-name {
            font-family: 'Playfair Display', serif;
            font-size: 14px; font-weight: 700;
            color: var(--text-dark); margin-bottom: 3px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .team-role {
            font-size: 11px; color: var(--green-main); font-weight: 500;
            margin-bottom: 6px; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .team-exp {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 11px; color: var(--text-light);
            background: var(--white); border: 1px solid var(--border);
            padding: 2px 8px; border-radius: 50px;
        }
        .team-exp svg { color: var(--green-main); width: 10px; height: 10px; }

        /* ===== CTA ===== */
        .cta-strip {
            background: linear-gradient(135deg, var(--green-main) 0%, var(--green-dark) 100%);
            padding: 72px 5%;
            text-align: center; position: relative; overflow: hidden;
        }
        .cta-strip::before {
            content: '';
            position: absolute; bottom: -80px; left: -80px;
            width: 320px; height: 320px;
            background: rgba(255,255,255,.04); border-radius: 50%;
        }
        .cta-strip::after {
            content: '';
            position: absolute; top: -60px; right: -60px;
            width: 240px; height: 240px;
            background: rgba(255,255,255,.04); border-radius: 50%;
        }
        .cta-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(26px, 4vw, 42px);
            font-weight: 800; color: var(--white);
            margin-bottom: 12px; position: relative; z-index: 1;
        }
        .cta-sub {
            color: rgba(255,255,255,.7); font-size: 16px;
            margin-bottom: 36px; position: relative; z-index: 1;
        }
        .cta-actions {
            display: flex; gap: 14px; justify-content: center;
            flex-wrap: wrap; position: relative; z-index: 1;
        }
        .btn-cta-white {
            background: var(--white); color: var(--green-dark);
            padding: 14px 32px; border-radius: 10px;
            font-size: 15px; font-weight: 700; text-decoration: none;
            border: none; cursor: pointer;
            transition: background var(--tr), transform var(--tr);
            box-shadow: 0 6px 24px rgba(0,0,0,.2);
        }
        .btn-cta-white:hover { background: var(--green-pale); transform: translateY(-3px); }
        .btn-cta-border {
            background: transparent; color: var(--white);
            padding: 14px 32px; border-radius: 10px;
            font-size: 15px; font-weight: 600; text-decoration: none;
            border: 2px solid rgba(255,255,255,.45); cursor: pointer;
            transition: all var(--tr);
        }
        .btn-cta-border:hover { border-color: var(--white); background: rgba(255,255,255,.1); transform: translateY(-3px); }

        /* ===== FOOTER ===== */
        footer {
            background: var(--green-dark); color: rgba(255,255,255,.65);
            padding: 56px 5% 32px;
        }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr;
            gap: 48px; margin-bottom: 40px;
        }
        .footer-logo img { height: 40px; width: auto; margin-bottom: 14px; }
        .footer-logo .logo-ph {
            height: 40px; width: 120px;
            border: 1.5px dashed rgba(255,255,255,.2); border-radius: 6px;
            background: rgba(255,255,255,.04); margin-bottom: 14px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.3); font-size: 11px;
        }
        .footer-logo p { font-size: 14px; line-height: 1.7; max-width: 260px; }
        .footer-h { font-size: 13px; font-weight: 700; color: var(--white); letter-spacing: .5px; margin-bottom: 16px; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-links a { color: rgba(255,255,255,.5); text-decoration: none; font-size: 14px; transition: color .2s; }
        .footer-links a:hover { color: var(--green-light); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.07); padding-top: 24px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px; font-size: 13px;
        }

        /* ===== SCROLL TOP ===== */
        .scroll-top {
            position: fixed; bottom: 28px; right: 28px; z-index: 900;
            width: 44px; height: 44px;
            background: var(--green-main); color: var(--white);
            border-radius: 10px; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 20px rgba(58,140,92,.4);
            transition: all var(--tr);
            opacity: 0; transform: translateY(10px); pointer-events: none;
        }
        .scroll-top.show { opacity: 1; transform: none; pointer-events: all; }
        .scroll-top:hover { background: var(--green-dark); transform: translateY(-3px); }

        /* ===== REVEAL ===== */
        .reveal {
            opacity: 0; transform: translateY(28px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal.visible { opacity: 1; transform: none; }
        .rd1 { transition-delay: .06s; }
        .rd2 { transition-delay: .12s; }
        .rd3 { transition-delay: .18s; }
        .rd4 { transition-delay: .24s; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1100px) {
            .why-grid  { grid-template-columns: repeat(2, 1fr); }
            .team-grid { grid-template-columns: repeat(5, 1fr); gap: 10px; }
            .team-name { font-size: 12px; }
            .team-info { padding: 10px 8px 12px; }
            .stats-grid{ grid-template-columns: repeat(2, 1fr); }
            .stat-box:nth-child(2) { border-right: none; }
            .stat-box:nth-child(3) { border-top: 1px solid var(--border); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 860px) {
            .story-grid { grid-template-columns: 1fr; gap: 40px; }
            .vm-grid    { grid-template-columns: 1fr; }
            .img-badge  { left: 16px; }
        }
        @media (max-width: 768px) {
            .nav-menu   { display: none; }
            .hamburger  { display: flex; }
            section     { padding: 64px 5%; }
            .hero       { height: 340px; }
        }
        @media (max-width: 600px) {
            .why-grid   { grid-template-columns: 1fr; }
            .team-grid  { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .team-name  { font-size: 13px; white-space: normal; }
            .team-role  { font-size: 11px; }
            .stats-grid { grid-template-columns: 1fr; }
            .stat-box   { border-right: none !important; border-top: 1px solid var(--border); }
            .stat-box:first-child { border-top: none; }
            .footer-grid{ grid-template-columns: 1fr; gap: 28px; }
        }
        @media (max-width: 420px) {
            .team-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" id="navbar">
    <a class="nav-logo" href="beranda.php">
        <?php if (file_exists('../upload/logohitam.png')): ?>
            <img src="../upload/logohitam.png" alt="Rinjani Guide">
        <?php else: ?>
            <div class="logo-ph">LOGO HERE</div>
        <?php endif; ?>
    </a>

    <ul class="nav-menu">
        <li><a href="beranda.php">Beranda</a></li>
        <li><a href="paket.php">Paket Pendakian</a></li>
        <li><a href="tentang.php" class="active">Tentang Kami</a></li>
        <li><a href="kontak.php">Kontak</a></li>
        <li>
            <a href="booking.php" class="btn-nav">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Booking Sekarang
            </a>
        </li>
    </ul>

    <button class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
</nav>

<!-- Mobile menu -->
<div class="mobile-menu" id="mobileMenu">
    <a href="beranda.php">Beranda</a>
    <a href="paket.php">Paket Pendakian</a>
    <a href="tentang.php">Tentang Kami</a>
    <a href="kontak.php">Kontak</a>
    <a href="booking.php" class="btn-nav-m">Booking Sekarang</a>
</div>

<!-- ===== HERO ===== -->
<section class="hero" id="hero">
    <div class="hero-bg" id="heroBg"></div>
    <div class="hero-content">
        <h1 class="hero-title">Tentang Kami</h1>
        <p class="hero-sub">Rinjani Guide hadir untuk menemani setiap langkah petualanganmu di alam yang luar biasa.</p>
        <div class="hero-divider">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
            </svg>
        </div>
    </div>
</section>

<!-- ===== STORY ===== -->
<section class="story" id="story">
    <div class="story-grid">
        <!-- Text -->
        <div class="story-body reveal">
            <span class="story-label">Tentang Rinjani Guide</span>
            <h2 class="story-title">Pendakian Aman,<br>Pengalaman Tak Terlupakan</h2>
            <p>Rinjani Guide adalah tim guide lokal berpengalaman yang telah menemani ribuan pendaki menjelajahi keindahan Gunung Rinjani. Kami berkomitmen memberikan layanan terbaik dengan mengutamakan keselamatan, kenyamanan, dan pengalaman berkesan di setiap perjalanan.</p>
            <p>Sebagai putra daerah, kami tidak hanya ingin berbagi keindahan alam Rinjani, tetapi juga budaya, keramahan, dan cerita lokal yang menjadikan setiap pendakian lebih bermakna.</p>
            <a href="#team" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                    <path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
                Kenali Tim Kami
            </a>
        </div>

        <!-- Image -->
        <div class="story-img-wrap reveal rd2">
            <?php if (file_exists('../upload/rinjani3.jpg')): ?>
                <img class="story-img" src="../upload/rinjani3.jpg" alt="Tim Rinjani Guide">
            <?php else: ?>
                <div class="story-img-placeholder">
                    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    <span>Foto Tim</span>
                </div>
            <?php endif; ?>
            <!-- Badge -->
            <div class="img-badge">
                <div class="img-badge-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3a8c5c" stroke-width="2">
                        <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
                    </svg>
                </div>
                <div class="img-badge-text">
                    <div class="num">3.726</div>
                    <div class="lbl">MDPL — Puncak Rinjani</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<div class="stats-section">
    <div class="stats-grid">
        <?php
        $stat_icons = [
            'mountain' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 3l4 8 5-5 5 15H2L8 3z"/></svg>',
            'users'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>',
            'guide'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M6 20v-1a6 6 0 0112 0v1"/><line x1="12" y1="12" x2="12" y2="16"/></svg>',
            'shield'   => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>',
        ];
        foreach ($stats as $i => $s):
            $d = 'rd' . ($i + 1);
        ?>
        <div class="stat-box reveal <?= $d ?>">
            <div class="stat-icon-wrap">
                <?= $stat_icons[$s['icon']] ?? '' ?>
            </div>
            <div class="stat-info">
                <div class="stat-val"><?= htmlspecialchars($s['value']) ?></div>
                <div class="stat-lbl"><?= htmlspecialchars($s['label']) ?></div>
                <div class="stat-desc"><?= htmlspecialchars($s['desc']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ===== VISI & MISI ===== -->
<section class="visi-misi" id="visi-misi">
    <div class="section-header reveal">
        <h2 class="section-title">Visi &amp; Misi</h2>
    </div>
    <div class="vm-grid">
        <!-- Visi -->
        <div class="vm-card reveal rd1">
            <div class="vm-icon-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
            <div class="vm-label">Visi</div>
            <div class="vm-underline"></div>
            <p class="vm-text">Menjadi penyedia layanan pendakian terbaik di Indonesia dengan standar keselamatan tinggi dan pengalaman yang berkesan, serta berkontribusi dalam pelestarian alam dan budaya lokal.</p>
        </div>

        <!-- Misi -->
        <div class="vm-card reveal rd2">
            <div class="vm-icon-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <polyline points="12 16 12.01 16"/>
                    <path d="M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    <path d="M15 9l-3 3-3-3"/>
                </svg>
            </div>
            <div class="vm-label">Misi</div>
            <div class="vm-underline"></div>
            <ul class="vm-list">
                <?php foreach ($misi as $m): ?>
                <li><?= htmlspecialchars($m) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<!-- ===== WHY US ===== -->
<section class="why-us" id="why-us">
    <div class="section-header reveal">
        <h2 class="section-title">Kenapa Memilih Rinjani Guide?</h2>
    </div>
    <div class="why-grid">
        <?php
        $why_icons = [
            'award'       => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>',
            'shield-plus' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="9" x2="12" y2="15"/><line x1="9" y1="12" x2="15" y2="12"/></svg>',
            'tag'         => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
            'smile'       => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>',
        ];
        foreach ($why_us as $i => $w):
            $d = 'rd' . ($i + 1);
        ?>
        <div class="why-card reveal <?= $d ?>">
            <div class="why-icon">
                <?= $why_icons[$w['icon']] ?? '' ?>
            </div>
            <div class="why-title"><?= htmlspecialchars($w['title']) ?></div>
            <div class="why-desc"><?= htmlspecialchars($w['desc']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ===== TEAM ===== -->
<section class="team-section" id="team">
    <div class="section-header reveal">
        <div class="section-tag">Tim Kami</div>
        <h2 class="section-title">Kenali Para Guide Kami</h2>
        <p class="section-sub">Profesional, ramah, dan berpengalaman di setiap jalur</p>
    </div>
    <div class="team-grid">
        <?php foreach ($team as $i => $m):
            $d = 'rd' . (($i % 4) + 1);
        ?>
        <div class="team-card reveal <?= $d ?>">
            <div class="team-photo-wrap">
                <?php if (file_exists($m['img'])): ?>
                    <img class="team-photo" src="<?= htmlspecialchars($m['img']) ?>" alt="<?= htmlspecialchars($m['name']) ?>">
                <?php else: ?>
                    <div class="team-photo-placeholder">
                        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.3">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
            <div class="team-info">
                <div class="team-name"><?= htmlspecialchars($m['name']) ?></div>
                <div class="team-role"><?= htmlspecialchars($m['role']) ?></div>
                <span class="team-exp">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <?= htmlspecialchars($m['exp']) ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer id="contact">
    <div class="footer-grid">
        <div class="footer-logo">
            <?php if (file_exists('../upload/logo.png')): ?>
                <img src="../upload/logo.png" alt="Rinjani Guide" style="filter:brightness(0) invert(1)">
            <?php else: ?>
                <div class="logo-ph">LOGO HERE</div>
            <?php endif; ?>
            <p>Guide lokal terpercaya untuk pendakian aman dan berkesan di kawasan Sembalun, Lombok.</p>
        </div>
        <div>
            <div class="footer-h">Navigasi</div>
            <ul class="footer-links">
                <li><a href="beranda.php">Beranda</a></li>
                <li><a href="paket.php">Paket Pendakian</a></li>
                <li><a href="tentang.php">Tentang Kami</a></li>
                <li><a href="kontak.php">Kontak</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-h">Kontak</div>
            <ul class="footer-links">
                <li><a href="https://wa.me/6283129650994">+62 831-2965-0994</a></li>
                <li><a href="mailto:info@rinjaniguide.com">info@rinjaniguide.com</a></li>
                <li><a href="kontak.php">Sembalun, Lombok Timur</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© <?= date('Y') ?> Rinjani Guide. All rights reserved.</p>
        <p>Made with ❤ for Rinjani</p>
    </div>
</footer>

<!-- ===== SCROLL TOP ===== -->
<button class="scroll-top" id="scrollTop" aria-label="Ke atas">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="18 15 12 9 6 15"/>
    </svg>
</button>

<!-- ===== JS ===== -->
<script>
(function(){
    'use strict';

    /* Navbar scroll */
    const navbar = document.getElementById('navbar');
    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 30);
        scrollTopBtn.classList.toggle('show', window.scrollY > 400);
    }, { passive: true });
    scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    /* Hamburger */
    const ham   = document.getElementById('hamburger');
    const mMenu = document.getElementById('mobileMenu');
    ham.addEventListener('click', () => {
        ham.classList.toggle('open');
        mMenu.classList.toggle('open');
    });
    mMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        ham.classList.remove('open'); mMenu.classList.remove('open');
    }));

    /* Smooth scroll anchors */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const id = a.getAttribute('href');
            if (id === '#') return;
            const el = document.querySelector(id);
            if (el) { e.preventDefault(); el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });

    /* Reveal on scroll */
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    /* Hero bg parallax */
    const heroBg = document.getElementById('heroBg');
    if (heroBg) {
        setTimeout(() => heroBg.classList.add('loaded'), 80);
        window.addEventListener('scroll', () => {
            if (window.scrollY < window.innerHeight) {
                heroBg.style.transform = `scale(1) translateY(${window.scrollY * 0.22}px)`;
            }
        }, { passive: true });
    }

    /* Counter animation on stat numbers */
    function animateVal(el, target, suffix) {
        let cur = 0, step = target / 55;
        const t = setInterval(() => {
            cur += step;
            if (cur >= target) { cur = target; clearInterval(t); }
            el.textContent = Math.round(cur).toLocaleString('id-ID') + suffix;
        }, 28);
    }

    const statEls = document.querySelectorAll('.stat-val');
    const statObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target;
            const raw = el.textContent.trim();
            const num = parseInt(raw.replace(/\D/g, ''), 10);
            const suffix = raw.replace(/[\d.,]/g, '');
            if (!isNaN(num) && num > 0) animateVal(el, num, suffix);
            statObs.unobserve(el);
        });
    }, { threshold: 0.5 });
    statEls.forEach(el => statObs.observe(el));

})();
</script>
</body>
</html>