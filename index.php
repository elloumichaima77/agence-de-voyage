<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyVoyage — Partez à l'aventure</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:   #0077b6;
            --secondary: #023e8a;
            --accent:    #f77f00;
            --success:   #27ae60;
            --danger:    #e74c3c;
            --light:     #f0f4f8;
            --dark:      #0d1b2a;
            --white:     #ffffff;
            --border:    #dde3ea;
            --shadow:    0 4px 20px rgba(0,0,0,.10);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--dark);
            color: var(--white);
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 20px 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background .4s, padding .4s;
        }
        .navbar.scrolled {
            background: rgba(13,27,42,.95);
            backdrop-filter: blur(12px);
            padding: 14px 60px;
            box-shadow: 0 2px 20px rgba(0,0,0,.4);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -.02em;
        }
        .navbar-brand .logo-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
        .navbar-nav {
            display: flex;
            gap: 6px;
            list-style: none;
            align-items: center;
        }
        .navbar-nav a {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: .9rem;
            font-weight: 500;
            transition: background .2s, color .2s;
        }
        .navbar-nav a:hover { background: rgba(255,255,255,.12); color: var(--white); }
        .navbar-nav .btn-nav {
            background: var(--accent);
            color: var(--white) !important;
            font-weight: 600;
        }
        .navbar-nav .btn-nav:hover { background: #e06e00; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 100%, rgba(0,119,182,.35) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 80% 20%, rgba(247,127,0,.2) 0%, transparent 60%),
                linear-gradient(170deg, #0d1b2a 0%, #023e8a 50%, #0d1b2a 100%);
        }

        /* animated particles */
        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(255,255,255,.15) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: drift 20s linear infinite;
            opacity: .4;
        }

        @keyframes drift {
            0%   { transform: translateY(0); }
            100% { transform: translateY(-60px); }
        }

        /* floating plane */
        .plane-anim {
            position: absolute;
            top: 22%;
            left: -100px;
            font-size: 2.5rem;
            animation: flyAcross 18s linear infinite;
            opacity: .4;
        }
        @keyframes flyAcross {
            0%   { left: -120px; top: 22%; }
            50%  { top: 18%; }
            100% { left: calc(100% + 120px); top: 22%; }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 0 20px;
            animation: fadeUp .9s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(247,127,0,.18);
            border: 1px solid rgba(247,127,0,.4);
            color: #ffa94d;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(3rem, 8vw, 6.5rem);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -.03em;
            margin-bottom: 24px;
        }
        .hero h1 em {
            font-style: italic;
            color: var(--accent);
        }

        .hero p {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            color: rgba(255,255,255,.7);
            max-width: 540px;
            margin: 0 auto 40px;
            line-height: 1.7;
            font-weight: 300;
        }

        .hero-cta {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            padding: 16px 36px;
            background: var(--accent);
            color: var(--white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .02em;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 8px 30px rgba(247,127,0,.4);
        }
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 40px rgba(247,127,0,.5);
        }

        .btn-hero-outline {
            padding: 16px 36px;
            background: rgba(255,255,255,.08);
            color: var(--white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            border: 1px solid rgba(255,255,255,.25);
            transition: background .2s, transform .2s;
            backdrop-filter: blur(6px);
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,.15);
            transform: translateY(-3px);
        }

        /* hero stats */
        .hero-stats {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin-top: 64px;
            flex-wrap: wrap;
            animation: fadeUp .9s .3s ease both;
        }
        .stat-item { text-align: center; }
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--accent);
            display: block;
        }
        .stat-label {
            font-size: .8rem;
            color: rgba(255,255,255,.5);
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 500;
        }
        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,.15);
            align-self: stretch;
        }

        /* scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,.4);
            font-size: .75rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            animation: fadeUp 1s .8s ease both;
        }
        .scroll-line {
            width: 1px;
            height: 50px;
            background: linear-gradient(to bottom, rgba(255,255,255,.4), transparent);
            animation: scrollPulse 2s ease-in-out infinite;
        }
        @keyframes scrollPulse {
            0%, 100% { opacity: .4; transform: scaleY(1); }
            50%       { opacity: 1;  transform: scaleY(.7); }
        }

        /* ── DESTINATIONS SECTION ── */
        .section {
            padding: 100px 60px;
        }

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 56px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .section-tag {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 900;
            letter-spacing: -.02em;
            line-height: 1.1;
        }

        .section-link {
            color: rgba(255,255,255,.5);
            text-decoration: none;
            font-size: .88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color .2s;
            white-space: nowrap;
        }
        .section-link:hover { color: var(--white); }
        .section-link .arrow { transition: transform .2s; }
        .section-link:hover .arrow { transform: translateX(4px); }

        /* Destination cards */
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: auto;
            gap: 20px;
        }

        .dest-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
            text-decoration: none;
            display: block;
            background: #1a2a3a;
        }
        .dest-card:nth-child(1) { grid-column: span 2; height: 380px; }
        .dest-card:nth-child(2) { height: 380px; }
        .dest-card:nth-child(3) { height: 260px; }
        .dest-card:nth-child(4) { height: 260px; }
        .dest-card:nth-child(5) { grid-column: span 2; height: 260px; }

        .dest-img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .6s ease;
            display: block;
        }
        .dest-card:hover .dest-img { transform: scale(1.07); }

        .dest-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(13,27,42,.9) 0%, rgba(13,27,42,.1) 60%, transparent 100%);
            transition: opacity .3s;
        }
        .dest-card:hover .dest-overlay { opacity: .7; }

        .dest-info {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 24px;
        }
        .dest-category {
            display: inline-block;
            background: rgba(247,127,0,.85);
            color: var(--white);
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 8px;
        }
        .dest-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 4px;
        }
        .dest-card:nth-child(1) .dest-name { font-size: 2rem; }
        .dest-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: .82rem;
            color: rgba(255,255,255,.7);
        }
        .dest-price {
            color: #ffa94d;
            font-weight: 700;
        }

        /* placeholder image fallback */
        .dest-placeholder {
            width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0d2137, #023e8a);
        }

        /* ── FEATURES ── */
        .features-section {
            background: #111d2b;
            padding: 100px 60px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 24px;
            margin-top: 56px;
        }

        .feature-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 16px;
            padding: 32px;
            transition: background .3s, border-color .3s, transform .3s;
        }
        .feature-card:hover {
            background: rgba(255,255,255,.07);
            border-color: rgba(247,127,0,.3);
            transform: translateY(-4px);
        }
        .feature-icon {
            width: 54px; height: 54px;
            background: rgba(247,127,0,.15);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 20px;
        }
        .feature-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .feature-desc {
            font-size: .88rem;
            color: rgba(255,255,255,.5);
            line-height: 1.7;
            font-weight: 300;
        }

        /* ── PROMO BANNER ── */
        .promo-banner {
            margin: 0 60px;
            border-radius: 24px;
            background: linear-gradient(135deg, var(--accent) 0%, #c0392b 100%);
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }
        .promo-banner::before {
            content: '🔥';
            position: absolute;
            right: -20px; top: -30px;
            font-size: 12rem;
            opacity: .08;
        }
        .promo-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 10px;
        }
        .promo-text p {
            color: rgba(255,255,255,.8);
            font-size: .95rem;
            font-weight: 300;
        }
        .btn-promo {
            padding: 14px 32px;
            background: var(--white);
            color: var(--accent);
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            font-size: .95rem;
            white-space: nowrap;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 6px 20px rgba(0,0,0,.2);
        }
        .btn-promo:hover { transform: scale(1.04); }

        /* ── CATEGORIES ── */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-top: 56px;
        }
        .cat-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
            padding: 28px 20px;
            text-align: center;
            text-decoration: none;
            color: var(--white);
            transition: background .3s, border-color .3s, transform .3s;
        }
        .cat-card:hover {
            background: rgba(0,119,182,.15);
            border-color: rgba(0,119,182,.4);
            transform: translateY(-4px);
        }
        .cat-emoji { font-size: 2.2rem; margin-bottom: 12px; display: block; }
        .cat-label {
            font-weight: 600;
            font-size: .95rem;
            margin-bottom: 4px;
        }
        .cat-count {
            font-size: .8rem;
            color: rgba(255,255,255,.4);
        }

        /* ── FOOTER ── */
        footer {
            background: #080f18;
            padding: 60px;
            margin-top: 100px;
        }
        .footer-inner {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 48px;
        }
        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 900;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-brand .logo-icon {
            width: 30px; height: 30px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
        }
        .footer-desc {
            color: rgba(255,255,255,.4);
            font-size: .88rem;
            line-height: 1.7;
            font-weight: 300;
        }
        .footer-col-title {
            font-weight: 700;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.5);
            margin-bottom: 18px;
        }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-links a {
            color: rgba(255,255,255,.5);
            text-decoration: none;
            font-size: .9rem;
            transition: color .2s;
        }
        .footer-links a:hover { color: var(--white); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.07);
            padding-top: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-copy { color: rgba(255,255,255,.3); font-size: .82rem; }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .navbar { padding: 16px 24px; }
            .navbar.scrolled { padding: 12px 24px; }
            .navbar-nav { display: none; }
            .section, .features-section { padding: 70px 24px; }
            .destinations-grid {
                grid-template-columns: 1fr 1fr;
            }
            .dest-card:nth-child(1),
            .dest-card:nth-child(5) { grid-column: span 2; }
            .categories-grid { grid-template-columns: repeat(2, 1fr); }
            .promo-banner { margin: 0 24px; padding: 36px; }
            .footer-inner { grid-template-columns: 1fr 1fr; }
            footer { padding: 40px 24px; }
        }

        @media (max-width: 560px) {
            .destinations-grid { grid-template-columns: 1fr; }
            .dest-card:nth-child(n) { grid-column: span 1; height: 250px; }
            .hero-stats { gap: 20px; }
            .stat-divider { display: none; }
            .footer-inner { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav class="navbar" id="navbar">
    <a href="index.php" class="navbar-brand">
        <span class="logo-icon">✈️</span>
        SkyVoyage
    </a>
    <ul class="navbar-nav">
        <li><a href="view/catalogue.php">Catalogue</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="view/mesReservations.php">Mes réservations</a></li>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <li><a href="view/adminVoyage.php">Admin</a></li>
            <?php endif; ?>
            <li>
                <a href="controller/authController.php?action=logout" class="btn-nav">
                    Déconnexion
                </a>
            </li>
        <?php else: ?>
            <li><a href="view/login.php">Connexion</a></li>
            <li><a href="view/register.php" class="btn-nav">Commencer ✈️</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="plane-anim">✈</div>

    <div class="hero-content">
        <div class="hero-eyebrow">
            🌍 L'aventure commence ici
        </div>
        <h1>
            Vivez des voyages<br>
            <em>inoubliables</em>
        </h1>
        <p>
            Découvrez des destinations d'exception, des offres exclusives
            et réservez en quelques clics. Le monde vous attend.
        </p>
        <div class="hero-cta">
            <a href="view/catalogue.php" class="btn-hero-primary">
                Explorer les voyages ✈️
            </a>
            <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="view/register.php" class="btn-hero-outline">
                Créer un compte gratuit
            </a>
            <?php else: ?>
            <a href="view/mesReservations.php" class="btn-hero-outline">
                Mes réservations 🎫
            </a>
            <?php endif; ?>
        </div>

        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-num">50+</span>
                <span class="stat-label">Destinations</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="stat-num">1K+</span>
                <span class="stat-label">Voyageurs</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="stat-num">7</span>
                <span class="stat-label">Catégories</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="stat-num">4.8★</span>
                <span class="stat-label">Note moyenne</span>
            </div>
        </div>
    </div>

    <div class="scroll-indicator">
        <span>Défiler</span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- ── DESTINATIONS ── -->
<section class="section">
    <div class="section-header">
        <div>
            <p class="section-tag">✨ Nos meilleures offres</p>
            <h2 class="section-title">Destinations phares</h2>
        </div>
        <a href="view/catalogue.php" class="section-link">
            Voir tout le catalogue <span class="arrow">→</span>
        </a>
    </div>

    <?php
    require_once "config/db_connect.php";
    require_once "model/voyage.php";
    require_once "model/voyagemanager.php";
    $manager = new VoyageManager($db);
    $voyages = $manager->getAll();
    $promos  = $manager->getPromos();

    // Images par pays
    $imageParPays = [
        'France'    => 'france.jpg',   'Italie'    => 'italie.jpg',
        'Espagne'   => 'espagne.jpg',  'Japon'     => 'japon.jpg',
        'Indonésie' => 'indonesie.jpg','Tanzanie'  => 'tanzanie.jpg',
        'Chili'     => 'chili.jpg',    'Maldives'  => 'maldives.jpg',
        'Maroc'     => 'maroc.jpg',    'Tunisie'   => 'tunisie.jpg',
        'Grèce'     => 'grece.jpg',    'Thaïlande' => 'thailande.jpg',
        'Portugal'  => 'portugal.jpg', 'Turquie'   => 'turquie.jpg',
    ];
    $uploadDir = 'public/uploads/voyages/';

    // Emoji fallback par catégorie
    $emojiCat = [
        'Découverte' => '🗺️', 'Aventure' => '🏔️', 'Plage' => '🏖️',
        'Culture'    => '🏛️', 'Luxe'    => '💎', 'Famille' => '👨‍👩‍👧',
        'Romantique' => '🌹',
    ];

    // Top 5 voyages
    $top = array_slice($voyages, 0, 5);
    ?>

    <div class="destinations-grid">
        <?php foreach ($top as $v): ?>
        <?php
        $pays = $v->pays;
        if (isset($imageParPays[$pays])) {
            $img = $uploadDir . $imageParPays[$pays];
        } elseif ($v->image !== 'voyage_default.jpg') {
            $img = $uploadDir . $v->image;
        } else {
            $img = null;
        }
        $emoji = $emojiCat[$v->categorie] ?? '✈️';
        ?>
        <a href="view/detailVoyage.php?id=<?= $v->id ?>" class="dest-card">
            <?php if ($img): ?>
                <img class="dest-img"
                     src="<?= htmlspecialchars($img) ?>"
                     alt="<?= htmlspecialchars($v->titre) ?>"
                     onerror="this.style.display='none'">
            <?php else: ?>
                <div class="dest-placeholder"><?= $emoji ?></div>
            <?php endif; ?>

            <div class="dest-overlay"></div>

            <div class="dest-info">
                <?php if ($v->en_promo): ?>
                    <span class="dest-category">🔥 Promo</span>
                <?php else: ?>
                    <span class="dest-category"><?= htmlspecialchars($v->categorie) ?></span>
                <?php endif; ?>
                <div class="dest-name"><?= htmlspecialchars($v->destination) ?>, <?= htmlspecialchars($v->pays) ?></div>
                <div class="dest-meta">
                    <span>🕐 <?= $v->duree ?> jours</span>
                    <span>•</span>
                    <span class="dest-price">À partir de <?= number_format($v->prix, 0, ',', ' ') ?> TND</span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>



<!-- ── CATÉGORIES ── -->
<section class="section">
    <div class="section-header">
        <div>
            <p class="section-tag">🏷️ Partez selon vos envies</p>
            <h2 class="section-title">Explorez par catégorie</h2>
        </div>
    </div>
    <div class="categories-grid">
        <?php
        $cats = [
            ['Découverte', '🗺️', 'Partez explorer'],
            ['Aventure',   '🏔️', 'Dépassez vos limites'],
            ['Plage',      '🏖️', 'Soleil & sable fin'],
            ['Culture',    '🏛️', 'Art & histoire'],
            ['Luxe',       '💎', 'Prestige & raffinement'],
            ['Famille',    '👨‍👩‍👧', 'Souvenirs en famille'],
            ['Romantique', '🌹', 'Pour les amoureux'],
        ];
        // Count par catégorie
        $catCounts = [];
        foreach ($voyages as $v) {
            $catCounts[$v->categorie] = ($catCounts[$v->categorie] ?? 0) + 1;
        }
        ?>
        <?php foreach ($cats as [$cat, $emoji, $sub]): ?>
        <a href="view/catalogue.php?categorie=<?= urlencode($cat) ?>" class="cat-card">
            <span class="cat-emoji"><?= $emoji ?></span>
            <div class="cat-label"><?= $cat ?></div>
            <div class="cat-count">
                <?= $catCounts[$cat] ?? 0 ?> voyage<?= ($catCounts[$cat] ?? 0) > 1 ? 's' : '' ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ── FEATURES ── -->
<section class="features-section">
    <div style="text-align:center;margin-bottom:0;">
        <p class="section-tag" style="text-align:center;">💡 Pourquoi nous choisir</p>
        <h2 class="section-title" style="text-align:center;">Votre voyage, notre priorité</h2>
    </div>
    <div class="features-grid">
        <?php foreach ([
            ['🛡️', 'Réservation sécurisée',   'Paiement et données protégés. Votre sérénité avant tout.'],
            ['⚡', 'Confirmation instantanée', 'Votre réservation confirmée en quelques secondes.'],
            ['🎯', 'Offres personnalisées',    'Des voyages sélectionnés selon vos envies et votre budget.'],
            ['📞', 'Support 24/7',             'Notre équipe est disponible pour vous accompagner à tout moment.'],
            ['💸', 'Meilleur prix garanti',    'Nous vous offrons les meilleures tarifs du marché, sans frais cachés.'],
            ['🌍', 'Destinations exclusives',  'Accédez à des expériences uniques dans le monde entier.'],
        ] as [$icon, $title, $desc]): ?>
        <div class="feature-card">
            <div class="feature-icon"><?= $icon ?></div>
            <div class="feature-title"><?= $title ?></div>
            <p class="feature-desc"><?= $desc ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ── FOOTER ── -->
<footer>
    <div class="footer-inner">
        <div>
            <div class="footer-brand">
                <span class="logo-icon">✈️</span>
                SkyVoyage
            </div>
            <p class="footer-desc">
                Votre agence de voyage en ligne. Des escapades inoubliables,
                des prix transparents, une expérience irréprochable.
            </p>
        </div>
        <div>
            <p class="footer-col-title">Navigation</p>
            <ul class="footer-links">
                <li><a href="view/catalogue.php">Catalogue</a></li>
                <li><a href="view/catalogue.php?categorie=Promo">Promotions</a></li>
                <li><a href="view/mesReservations.php">Mes réservations</a></li>
            </ul>
        </div>
        <div>
            <p class="footer-col-title">Compte</p>
            <ul class="footer-links">
                <li><a href="view/login.php">Connexion</a></li>
                <li><a href="view/register.php">Inscription</a></li>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <li><a href="view/adminVoyage.php">Administration</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div>
            <p class="footer-col-title">Contact</p>
            <ul class="footer-links">
                <li><a href="#">📧 contact@skyvoyage.tn</a></li>
                <li><a href="#">📞 +216 71 000 000</a></li>
                <li><a href="#">📍 Tunis, Tunisie</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p class="footer-copy">© <?= date('Y') ?> SkyVoyage — Tous droits réservés.</p>
        <p class="footer-copy">Fait avec ❤️ en Tunisie</p>
    </div>
</footer>

<script>
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    });
</script>

</body>
</html>
