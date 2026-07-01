<?php
// =====================================================================
//  Rinjani Guide — Halaman Kontak
// =====================================================================
$page_title = "Kontak Kami — Rinjani Guide";

// ---- Data kontak ----
$contacts = [
    [
        "type"    => "phone",
        "title"   => "Telepon",
        "desc"    => "Hubungi kami melalui nomor telepon",
        "value"   => "+62 831-2965-0994",
        "href"    => "tel:+6283129650994",
        "color"   => "#25a85a",
        "bg"      => "#e8f8f0",
    ],
    [
        "type"    => "whatsapp",
        "title"   => "WhatsApp",
        "desc"    => "Chat kami langsung melalui WhatsApp",
        "value"   => "+62 831-2965-0994",
        "href"    => "https://wa.me/qr/OBEVYAVI5YMFM1",
        "color"   => "#25d366",
        "bg"      => "#e8faf0",
    ],
    [
        "type"    => "instagram",
        "title"   => "Instagram",
        "desc"    => "Follow dan pantau kami di Instagram",
        "value"   => "@rinjaniguide",
        "href"    => "https://www.instagram.com/btn_gn_rinjani?igsh=cW1nMWkzc3Zxa2Qw",
        "color"   => "#e1306c",
        "bg"      => "#fce8f0",
    ],
    [
        "type"    => "facebook",
        "title"   => "Facebook",
        "desc"    => "Like & follow halaman facebook kami",
        "value"   => "Rinjani Guide",
        "href"    => "https://www.facebook.com/share/1Awdjmqzyv/",
        "color"   => "#1877f2",
        "bg"      => "#e8f0fe",
    ],
    [
        "type"    => "tiktok",
        "title"   => "TikTok",
        "desc"    => "Follow dan tonton konten kami di TikTok",
        "value"   => "@rinjaniguide",
        "href"    => "https://www.tiktok.com/@btn_gn_rinjani?_r=1&_t=ZS-96rwnoZfONx",
        "color"   => "#010101",
        "bg"      => "#f0f0f0",
    ],
    [
        "type"    => "email",
        "title"   => "Email",
        "desc"    => "Kirim melalui email kami",
        "value"   => "info@rinjaniguide.com",
        "href"    => "mailto:info@rinjaniguide.com",
        "color"   => "#3a8c5c",
        "bg"      => "#e8f5ee",
    ],
];

$address = "Jl. Pelor Mas Raya No. III, Kekalik Jaya, Kec. Sekarbela, Kota Mataram, Nusa Tenggara Barat 83126";
$map_src = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3945.0387580465713!2d116.089543274336!3d-8.592272087231626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcdbf7ed05603ab%3A0x6b5771dd5cbe0d20!2sUniversitas%20Teknologi%20Mataram!5e0!3m2!1sid!2sid!4v1780202172532!5m2!1sid!2sid";

// ---- Handle form submission ----
$form_success = false;
$form_error   = '';
$form_data    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    // CSRF
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
        $form_error = 'Permintaan tidak valid.';
    } else {
        $nama    = trim(strip_tags($_POST['nama']    ?? ''));
        $email   = trim(strip_tags($_POST['email']   ?? ''));
        $telp    = trim(strip_tags($_POST['telp']    ?? ''));
        $subjek  = trim(strip_tags($_POST['subjek']  ?? ''));
        $pesan   = trim(strip_tags($_POST['pesan']   ?? ''));

        $form_data = compact('nama','email','telp','subjek','pesan');

        if (empty($nama) || empty($email) || empty($pesan)) {
            $form_error = 'Nama, email, dan pesan wajib diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_error = 'Format email tidak valid.';
        } else {
            $form_success = true;
            $form_data    = [];
        }
    }
}

// CSRF token
session_start();
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];
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
        /* ===== RESET & VARS ===== */
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
            --tr:          .26s cubic-bezier(.25,.8,.25,1);
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

        .nav-logo a { display: flex; align-items: center; text-decoration: none; }
        .nav-logo img { height: 44px; width: auto; object-fit: contain; display: block; }
        .nav-logo .logo-ph {
            height: 44px; width: 150px;
            border: 1.5px dashed var(--border); border-radius: 6px;
            background: var(--cream);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-light); font-size: 11px; letter-spacing: .5px;
        }

        .nav-menu { display: flex; align-items: center; gap: 34px; list-style: none; }
        .nav-menu a {
            color: var(--text-mid); text-decoration: none;
            font-size: 14px; font-weight: 500; letter-spacing: .2px;
            position: relative; transition: color var(--tr);
        }
        .nav-menu a:hover,
        .nav-menu a.active { color: var(--green-main); }
        .nav-menu a.active::after {
            content: ''; position: absolute; bottom: -4px; left: 0; right: 0;
            height: 2px; background: var(--green-main); border-radius: 1px;
        }

        .btn-nav {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--green-main); color: var(--white) !important;
            padding: 10px 20px; border-radius: 8px;
            font-size: 14px; font-weight: 600;
            transition: background var(--tr), transform var(--tr), box-shadow var(--tr);
            box-shadow: 0 4px 16px rgba(58,140,92,.32);
        }
        .btn-nav:hover { background: var(--green-dark) !important; transform: translateY(-2px); }
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
        .mobile-menu a:last-child { border-bottom: none; margin-top: 10px; }
        .mobile-menu .btn-m {
            display: block; background: var(--green-main);
            color: var(--white) !important; padding: 12px 22px;
            border-radius: 8px; text-align: center; font-weight: 600;
        }

        /* ===== HERO ===== */
        .hero {
            margin-top: var(--nav-h);
            position: relative; height: 400px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background:
                linear-gradient(to bottom, rgba(8,18,12,.4) 0%, rgba(8,18,12,.65) 100%),
                url('../upload/rinjani1.jpg') center 40% / cover no-repeat;
            transform: scale(1.04);
            transition: transform 6s ease-out;
        }
        .hero-bg.loaded { transform: scale(1); }
        .hero-content {
            position: relative; z-index: 2;
            text-align: center; padding: 0 20px;
            animation: fadeUp .75s ease-out both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(36px, 6vw, 62px);
            font-weight: 800; color: var(--white);
            line-height: 1.1; margin-bottom: 16px;
            text-shadow: 0 4px 24px rgba(0,0,0,.35);
        }
        .hero-sub {
            color: rgba(255,255,255,.82);
            font-size: clamp(14px, 1.8vw, 17px);
            max-width: 540px; margin: 0 auto 24px; line-height: 1.7;
        }
        .hero-divider {
            display: flex; align-items: center; justify-content: center; gap: 14px;
        }
        .hero-divider::before,
        .hero-divider::after {
            content: ''; width: 60px; height: 1px; background: rgba(255,255,255,.45);
        }

        /* ===== SECTION WRAPPERS ===== */
        section { padding: 80px 5%; }
        .section-header { text-align: center; margin-bottom: 50px; }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 700; color: var(--text-dark); line-height: 1.2;
        }
        .section-sub { font-size: 16px; color: var(--text-light); margin-top: 8px; }
        .inner { max-width: 1100px; margin: 0 auto; }

        /* ===== CONTACT CARDS ===== */
        .contacts-section { background: var(--white); }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .contact-card {
            display: flex; align-items: center; gap: 20px;
            padding: 24px 26px;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: transform var(--tr), box-shadow var(--tr), border-color var(--tr);
            cursor: pointer;
        }
        .contact-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: var(--green-light);
        }

        .contact-icon {
            width: 60px; height: 60px; flex-shrink: 0;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .contact-icon img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; }
        .contact-icon svg { display: block; }

        .contact-info .c-title {
            font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 3px;
        }
        .contact-info .c-desc { font-size: 13px; color: var(--text-light); margin-bottom: 5px; }
        .contact-info .c-val {
            font-size: 16px; font-weight: 700; color: var(--text-dark);
        };

        /* ===== MAP SECTION ===== */
        .map-section { background: var(--cream); padding: 80px 5%; }

        .map-address {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            font-size: 15px; color: var(--text-mid);
            margin-bottom: 28px;
        }
        .map-address svg { color: var(--green-main); flex-shrink: 0; }

        .map-wrap {
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1.5px solid var(--border);
            position: relative;
            height: 460px;
        }
        .map-wrap iframe {
            width: 100%; height: 100%;
            border: none; display: block;
        }
        /* Fallback if no iframe */
        .map-fallback {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #e8f0e4 0%, #d8e8d0 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 14px;
        }
        .map-fallback svg { color: var(--green-main); opacity: .45; }
        .map-fallback p { font-size: 15px; color: var(--text-light); }
        .map-fallback a {
            color: var(--green-main); font-weight: 600; text-decoration: none; font-size: 14px;
        }
        .map-fallback a:hover { text-decoration: underline; }

        /* ===== CONTACT FORM ===== */
        .form-section { background: var(--white); }

        .form-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 48px; align-items: start;
        }

        .form-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-weight: 700;
            color: var(--text-dark); margin-bottom: 12px;
        }
        .form-info p {
            font-size: 15px; color: var(--text-mid); line-height: 1.75; margin-bottom: 28px;
        }

        .info-items { display: flex; flex-direction: column; gap: 16px; }
        .info-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 14px 16px;
            background: var(--cream); border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }
        .info-item-icon {
            width: 38px; height: 38px; flex-shrink: 0;
            background: var(--green-pale); border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .info-item-icon svg { color: var(--green-main); }
        .info-item-text .lbl { font-size: 12px; color: var(--text-light); margin-bottom: 2px; }
        .info-item-text .val { font-size: 14px; font-weight: 600; color: var(--text-dark); }

        /* Form */
        .contact-form { display: flex; flex-direction: column; gap: 16px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label {
            font-size: 13px; font-weight: 600; color: var(--text-mid);
            letter-spacing: .2px;
        }
        .form-group label span { color: #e63946; margin-left: 2px; }

        .form-input, .form-select, .form-textarea {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; color: var(--text-dark);
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 11px 14px;
            outline: none;
            transition: border-color var(--tr), box-shadow var(--tr);
            width: 100%;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(58,140,92,.1);
        }
        .form-input.err, .form-textarea.err { border-color: #e63946; }
        .form-select { appearance: none; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a9180' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;
        }
        .form-textarea { resize: vertical; min-height: 120px; line-height: 1.6; }
        .form-input::placeholder, .form-textarea::placeholder { color: #b8c0b4; }

        .char-count { font-size: 12px; color: var(--text-light); text-align: right; margin-top: -4px; }

        .btn-submit {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            background: var(--green-main); color: var(--white);
            padding: 13px 28px; border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px; font-weight: 600;
            border: none; cursor: pointer;
            transition: background var(--tr), transform var(--tr), box-shadow var(--tr);
            box-shadow: 0 6px 22px rgba(58,140,92,.35);
            width: 100%;
        }
        .btn-submit:hover { background: var(--green-dark); transform: translateY(-2px); box-shadow: 0 10px 30px rgba(26,58,42,.3); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit.loading { pointer-events: none; opacity: .75; }
        .btn-submit.loading::after {
            content: ''; width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,.3); border-top-color: #fff;
            border-radius: 50%; animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Alert */
        .alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 13px 16px; border-radius: var(--radius-sm);
            font-size: 14px; margin-bottom: 4px;
            animation: alertIn .3s ease-out;
        }
        @keyframes alertIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: #e8faf0; border: 1px solid #b7e4c7; color: #1a6e3c; }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert svg { flex-shrink: 0; margin-top: 1px; }

        /* ===== STICKY CTA BAR ===== */
        .cta-bar {
            background: var(--green-dark);
            padding: 28px 5%;
        }
        .cta-bar-inner {
            max-width: 1100px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            gap: 20px; flex-wrap: wrap;
        }
        .cta-bar-left { display: flex; align-items: center; gap: 18px; }
        .cta-bar-icon {
            width: 56px; height: 56px; flex-shrink: 0;
            background: rgba(255,255,255,.08);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }
        .cta-bar-text h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 700; color: var(--white);
        }
        .cta-bar-text p { font-size: 13px; color: rgba(255,255,255,.55); margin-top: 2px; }
        .btn-wa {
            display: inline-flex; align-items: center; gap: 9px;
            background: #25d366; color: var(--white);
            padding: 13px 28px; border-radius: 10px;
            font-size: 15px; font-weight: 700; text-decoration: none;
            border: none; cursor: pointer; flex-shrink: 0;
            transition: background var(--tr), transform var(--tr), box-shadow var(--tr);
            box-shadow: 0 6px 22px rgba(37,211,102,.4);
        }
        .btn-wa:hover { background: #1da851; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(37,211,102,.45); }

        /* ===== FOOTER ===== */
        footer {
            background: var(--green-dark);
            color: rgba(255,255,255,.6);
            padding: 48px 5% 28px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr;
            gap: 48px; margin-bottom: 36px;
        }
        .footer-logo img { height: 40px; width: auto; margin-bottom: 14px; filter: brightness(0) invert(1); }
        .footer-logo .logo-ph {
            height: 40px; width: 120px; margin-bottom: 14px;
            border: 1.5px dashed rgba(255,255,255,.2); border-radius: 6px;
            background: rgba(255,255,255,.04);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.3); font-size: 11px;
        }
        .footer-logo p { font-size: 14px; line-height: 1.7; max-width: 260px; }
        .footer-h { font-size: 13px; font-weight: 700; color: var(--white); letter-spacing: .5px; margin-bottom: 16px; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-links a { color: rgba(255,255,255,.5); text-decoration: none; font-size: 14px; transition: color .2s; }
        .footer-links a:hover { color: var(--green-light); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.07); padding-top: 22px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 8px; font-size: 13px;
        }

        /* ===== SCROLL TOP ===== */
        .scroll-top {
            position: fixed; bottom: 28px; right: 28px; z-index: 900;
            width: 44px; height: 44px;
            background: var(--green-main); color: var(--white);
            border-radius: 10px; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 20px rgba(58,140,92,.4);
            opacity: 0; transform: translateY(10px); pointer-events: none;
            transition: all var(--tr);
        }
        .scroll-top.show { opacity: 1; transform: none; pointer-events: all; }
        .scroll-top:hover { background: var(--green-dark); transform: translateY(-3px); }

        /* ===== REVEAL ===== */
        .reveal {
            opacity: 0; transform: translateY(24px);
            transition: opacity .55s ease, transform .55s ease;
        }
        .reveal.visible { opacity: 1; transform: none; }
        .rd1 { transition-delay: .06s; }
        .rd2 { transition-delay: .13s; }
        .rd3 { transition-delay: .20s; }
        .rd4 { transition-delay: .27s; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 860px) {
            .contact-grid { grid-template-columns: 1fr; }
            .contact-card.full { max-width: 100%; grid-column: auto; }
            .form-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .nav-menu { display: none; }
            .hamburger { display: flex; }
            section { padding: 60px 5%; }
            .hero { height: 320px; }
            .form-row { grid-template-columns: 1fr; }
        }
        @media (max-width: 540px) {
            .cta-bar-inner { flex-direction: column; align-items: flex-start; }
            .btn-wa { width: 100%; justify-content: center; }
            .footer-grid { grid-template-columns: 1fr; gap: 28px; }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" id="navbar">
    <div class="nav-logo">
        <a href="index.php">
            <?php if (file_exists('../upload/logohitam.png')): ?>
                <img src="../upload/logohitam.png" alt="Rinjani Guide">
            <?php else: ?>
                <div class="logo-ph">LOGO HERE</div>
            <?php endif; ?>
        </a>
    </div>

    <ul class="nav-menu">
        <li><a href="beranda.php">Beranda</a></li>
        <li><a href="paket.php">Paket Pendakian</a></li>
        <li><a href="tentang.php">Tentang Kami</a></li>
        <li><a href="kontak.php" class="active">Kontak</a></li>
        <li>
            <a href="booking.php" class="btn-nav">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Booking Sekarang
            </a>
        </li>
    </ul>

    <button class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <a href="beranda.php">Beranda</a>
    <a href="paket.php">Paket Pendakian</a>
    <a href="tentang.php">Tentang Kami</a>
    <a href="kontak.php">Kontak</a>
    <a href="booking.php" class="btn-m">Booking Sekarang</a>
</div>

<!-- ===== HERO ===== -->
<div class="hero">
    <div class="hero-bg" id="heroBg"></div>
    <div class="hero-content">
        <h1 class="hero-title">Kontak Kami</h1>
        <p class="hero-sub">Punya pertanyaan atau butuh bantuan? Hubungi kami melalui salah satu kontak berikut. Kami siap membantu!</p>
        <div class="hero-divider">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M8 3l4 8 5-5 5 15H2L8 3z"/>
            </svg>
        </div>
    </div>
</div>

<!-- ===== KONTAK CARDS ===== -->
<section class="contacts-section" id="kontak">
    <div class="inner">
        <div class="section-header reveal">
            <h2 class="section-title">Hubungi Kami</h2>
            <p class="section-sub">Kami akan merespon secepat mungkin.</p>
        </div>

        <div class="contact-grid">
            <?php
            // SVG icons untuk tiap tipe
            $icons = [
                'phone' => '
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                    </svg>',
                'whatsapp' => '
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M11.993 2C6.465 2 2 6.477 2 12.018c0 1.76.459 3.412 1.265 4.845L2 22l5.272-1.38A9.938 9.938 0 0011.993 22C17.522 22 22 17.523 22 11.982 22 6.477 17.522 2 11.993 2zm0 18.15a8.265 8.265 0 01-4.21-1.15l-.302-.18-3.128.82.835-3.047-.197-.314a8.282 8.282 0 01-1.27-4.46c0-4.579 3.728-8.307 8.307-8.307 4.578 0 8.306 3.728 8.306 8.307-.001 4.579-3.729 8.33-8.341 8.33z"/>
                    </svg>',
                'instagram' => '
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>',
                'facebook' => '
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>',
                'tiktok' => '
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="white">
                        <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.29 6.29 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.52V6.76a4.85 4.85 0 01-1.02-.07z"/>
                    </svg>',
                'email' => '
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>',
            ];

            // Gradient backgrounds per type
            $bgs = [
                'phone'     => 'background: #25a85a;',
                'whatsapp'  => 'background: linear-gradient(135deg, #25d366, #128c7e);',
                'instagram' => 'background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);',
                'facebook'  => 'background: #1877f2;',
                'tiktok'    => 'background: #010101;',
                'email'     => 'background: linear-gradient(135deg, #3a8c5c, #2d6a4f);',
            ];

            $i = 0;
            foreach ($contacts as $c):
                $is_tiktok = $c['type'] === 'tiktok';
                $i++;
                $delay = 'rd' . (($i - 1) % 4 + 1);
            ?>
            <a href="<?= htmlspecialchars($c['href']) ?>"
               target="<?= in_array($c['type'], ['whatsapp','instagram','facebook','tiktok']) ? '_blank' : '_self' ?>"
               rel="noopener noreferrer"
               class="contact-card reveal <?= $delay ?> <?= $is_tiktok ? 'full' : '' ?>">
                <div class="contact-icon" style="<?= $bgs[$c['type']] ?>">
                    <?= $icons[$c['type']] ?? '' ?>
                </div>
                <div class="contact-info">
                    <div class="c-title"><?= htmlspecialchars($c['title']) ?></div>
                    <div class="c-desc"><?= htmlspecialchars($c['desc']) ?></div>
                    <div class="c-val"><?= htmlspecialchars($c['value']) ?></div>
                </div>
                <svg style="margin-left:auto;flex-shrink:0;color:#c8d5c0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===== MAP ===== -->
<section class="map-section" id="lokasi">
    <div class="inner">
        <div class="section-header reveal">
            <h2 class="section-title">Lokasi Kami</h2>
            <div class="map-address">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <?= htmlspecialchars($address) ?>
            </div>
        </div>

        <div class="map-wrap reveal">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3945.0387580465713!2d116.089543274336!3d-8.592272087231626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcdbf7ed05603ab%3A0x6b5771dd5cbe0d20!2sUniversitas%20Teknologi%20Mataram!5e0!3m2!1sid!2sid!4v1780202172532!5m2!1sid!2sid"
                allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Lokasi Rinjani Guide">
            </iframe>
        </div>

        <div style="text-align:center;margin-top:16px">
            <a href="https://goo.gl/maps/mataram" target="_blank" rel="noopener"
               style="color:var(--green-main);font-size:14px;font-weight:600;text-decoration:none;">
                📍 Buka di Google Maps →
            </a>
        </div>
    </div>
</section>

<!-- ===== CONTACT FORM ===== -->
<section class="form-section" id="form">
    <div class="inner">
        <div class="section-header reveal">
            <div class="section-title">Kirim Pesan</div>
            <p class="section-sub">Isi formulir di bawah dan kami akan menghubungi Anda segera.</p>
        </div>

        <div class="form-grid">
            <!-- Info sidebar -->
            <div class="form-info reveal rd1">
                <h3>Kami Siap Membantu Anda</h3>
                <p>Apapun pertanyaan Anda tentang pendakian Rinjani, paket wisata, atau kebutuhan khusus, tim kami akan dengan senang hati membantu.</p>

                <div class="info-items">
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div class="info-item-text">
                            <div class="lbl">Jam Operasional</div>
                            <div class="val">Setiap hari, 07.00 – 20.00 WITA</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.52 12 19.79 19.79 0 01.47 3.4 2 2 0 012.44 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 9.4a16 16 0 006.72 6.72l.8-.8a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                        </div>
                        <div class="info-item-text">
                            <div class="lbl">Telepon / WhatsApp</div>
                            <div class="val">+62 831-2965-0994</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <div class="info-item-text">
                            <div class="lbl">Email</div>
                            <div class="val">info@rinjaniguide.com</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="info-item-text">
                            <div class="lbl">Alamat</div>
                            <div class="val">Jl.Pelor Mas Raya No.III, Kekalik Jaya, Sekarbela, Mataram</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="reveal rd2">
                <?php if ($form_success): ?>
                <div class="alert alert-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    Pesan Anda berhasil dikirim! Kami akan menghubungi Anda segera.
                </div>
                <?php endif; ?>

                <?php if (!empty($form_error)): ?>
                <div class="alert alert-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <?= htmlspecialchars($form_error) ?>
                </div>
                <?php endif; ?>

                <form class="contact-form" id="contactForm" method="POST" action="#form" novalidate>
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                    <input type="hidden" name="send_message" value="1">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap<span>*</span></label>
                            <input class="form-input" type="text" id="nama" name="nama"
                                placeholder="Masukkan nama Anda"
                                value="<?= htmlspecialchars($form_data['nama'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email<span>*</span></label>
                            <input class="form-input" type="email" id="email" name="email"
                                placeholder="nama@email.com"
                                value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telp">No.Telepon/WhatsApp</label>
                            <input class="form-input" type="tel" id="telp" name="telp"
                                placeholder="+62 xxx-xxxx-xxxx"
                                value="<?= htmlspecialchars($form_data['telp'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="subjek">Subjek</label>
                            <select class="form-select" id="subjek" name="subjek">
                                <option value="">Pilih subjek...</option>
                                <?php
                                $subjects = [
                                    'Booking Pendakian','Informasi Paket','Harga & Pembayaran',
                                    'Jadwal & Ketersediaan','Pertanyaan Umum','Lainnya'
                                ];
                                foreach ($subjects as $s):
                                    $sel = isset($form_data['subjek']) && $form_data['subjek'] === $s ? 'selected' : '';
                                ?>
                                <option value="<?= htmlspecialchars($s) ?>" <?= $sel ?>>
                                    <?= htmlspecialchars($s) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pesan">Pesan<span>*</span></label>
                        <textarea class="form-textarea" id="pesan" name="pesan"
                            placeholder="Tuliskan pesan atau pertanyaan Anda di sini..."
                            maxlength="1000" required><?= htmlspecialchars($form_data['pesan'] ?? '') ?></textarea>
                        <span class="char-count"><span id="charCount">0</span>/1000</span>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        <span class="btn-text">Kirim Pesan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA BAR ===== -->
<div class="cta-bar">
    <div class="cta-bar-inner">
        <div class="cta-bar-left">
            <div class="cta-bar-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.7)" stroke-width="1.8">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.52 12 19.79 19.79 0 01.47 3.4 2 2 0 012.44 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 9.4a16 16 0 006.72 6.72l.8-.8a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                </svg>
            </div>
            <div class="cta-bar-text">
                <h3>Butuh Bantuan?</h3>
                <p>Tim kami siap membantu 24/7 untuk semua kebutuhan pendakian Anda.</p>
            </div>
        </div>
        <a href="https://wa.me/qr/OBEVYAVI5YMFM1"
           target="_blank" rel="noopener" class="btn-wa">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M11.993 2C6.465 2 2 6.477 2 12.018c0 1.76.459 3.412 1.265 4.845L2 22l5.272-1.38A9.938 9.938 0 0011.993 22C17.522 22 17.522 17.523 22 11.982 22 6.477 17.522 2 11.993 2zm0 18.15a8.265 8.265 0 01-4.21-1.15l-.302-.18-3.128.82.835-3.047-.197-.314a8.282 8.282 0 01-1.27-4.46c0-4.579 3.728-8.307 8.307-8.307 4.578 0 8.306 3.728 8.306 8.307-.001 4.579-3.729 8.33-8.341 8.33z"/>
            </svg>
            Chat via WhatsApp
        </a>
    </div>
</div>

<!-- ===== FOOTER ===== -->
<footer>
    <div class="footer-grid">
        <div class="footer-logo">
            <?php if (file_exists('../upload/logo.png')): ?>
                <img src="../upload/logo.png" alt="Rinjani Guide">
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
            <div class="footer-h">Sosial Media</div>
            <ul class="footer-links">
                <li><a href="https://wa.me/qr/OBEVYAVI5YMFM1" target="_blank">WhatsApp</a></li>
                <li><a href="https://www.instagram.com/btn_gn_rinjani?igsh=cW1nMWkzc3Zxa2Qw" target="_blank">Instagram</a></li>
                <li><a href="https://www.facebook.com/share/1Awdjmqzyv/" target="_blank">Facebook</a></li>
                <li><a href="https://www.tiktok.com/@btn_gn_rinjani?_r=1&_t=ZS-96rwnoZfONx" target="_blank">TikTok</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© <?= date('Y') ?> Rinjani Guide. All rights reserved.</p>
        <p>Made with ❤ for Rinjani</p>
    </div>
</footer>

<!-- Scroll top -->
<button class="scroll-top" id="scrollTop" aria-label="Ke atas">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="18 15 12 9 6 15"/>
    </svg>
</button>

<!-- ===== JS ===== -->
<script>
(function(){
    'use strict';

    /* Navbar & scroll top */
    const navbar   = document.getElementById('navbar');
    const scrollBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 30);
        scrollBtn.classList.toggle('show', window.scrollY > 400);
    }, { passive: true });
    scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    /* Hamburger */
    const ham   = document.getElementById('hamburger');
    const mmenu = document.getElementById('mobileMenu');
    ham.addEventListener('click', () => {
        ham.classList.toggle('open');
        mmenu.classList.toggle('open');
    });
    mmenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        ham.classList.remove('open'); mmenu.classList.remove('open');
    }));

    /* Smooth scroll */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const id = a.getAttribute('href');
            if (id === '#') return;
            const el = document.querySelector(id);
            if (el) { e.preventDefault(); el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });

    /* Reveal */
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

    /* Hero parallax */
    const heroBg = document.getElementById('heroBg');
    if (heroBg) {
        setTimeout(() => heroBg.classList.add('loaded'), 80);
        window.addEventListener('scroll', () => {
            if (window.scrollY < window.innerHeight) {
                heroBg.style.transform = `scale(1) translateY(${window.scrollY * 0.2}px)`;
            }
        }, { passive: true });
    }

    /* Char counter */
    const pesan     = document.getElementById('pesan');
    const charCount = document.getElementById('charCount');
    if (pesan && charCount) {
        const update = () => charCount.textContent = pesan.value.length;
        pesan.addEventListener('input', update);
        update();
    }

    /* Form validation */
    const form    = document.getElementById('contactForm');
    const btnSub  = document.getElementById('btnSubmit');
    if (form) {
        form.addEventListener('submit', e => {
            const nama  = document.getElementById('nama');
            const email = document.getElementById('email');
            const msg   = document.getElementById('pesan');
            let ok = true;

            [nama, email, msg].forEach(el => el.classList.remove('err'));

            if (!nama.value.trim())  { nama.classList.add('err');  ok = false; }
            if (!email.value.trim() || !email.value.includes('@')) { email.classList.add('err'); ok = false; }
            if (!msg.value.trim())   { msg.classList.add('err');   ok = false; }

            if (!ok) { e.preventDefault(); return; }
            btnSub.classList.add('loading');
            document.querySelector('.btn-submit .btn-text').textContent = 'Mengirim...';
        });

        /* Remove err on input */
        form.querySelectorAll('.form-input, .form-textarea').forEach(el => {
            el.addEventListener('input', () => el.classList.remove('err'));
        });
    }

})();
</script>
</body>
</html>