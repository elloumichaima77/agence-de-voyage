<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Voyages — SkyVoyage</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php require "../controller/catalogueController.php"; ?>
<?php require "partials/navabar.php"; ?>

<div class="hero">
    <h1>🌍 Explorez le Monde</h1>
    <p>Des voyages inoubliables à portée de clic</p>
    <form method="GET" action="catalogue.php">
        <div class="search-bar">
            <div class="form-group">
                <label>Destination</label>
                <input type="text" name="destination" placeholder="Paris, Rome, Bali..."
                       value="<?= htmlspecialchars($_GET['destination'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Catégorie</label>
                <select name="categorie">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"
                            <?= ($_GET['categorie'] ?? '') === $cat ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Prix max (TND)</label>
                <input type="number" name="prix_max" placeholder="5000"
                       value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>" min="0">
            </div>
            <div class="form-group">
                <label>Départ après</label>
                <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
            </div>
            <div>
                <button type="submit" class="btn btn-accent">🔍 Rechercher</button>
                <a href="catalogue.php" class="btn btn-outline" style="margin-left:6px;">✕</a>
            </div>
        </div>
    </form>
</div>

<div class="container">

    <?php
    // ── IMAGE PAR PAYS ─────────────────────────────────────────────────────
    // Tu télécharges tes images manuellement dans /public/uploads/voyages/
    // et tu les associes ici avec le nom exact du pays dans la BDD
    $imageParPays = [
        'France'      => 'france.jpg',
        'Italie'      => 'italie.jpg',
        'Espagne'     => 'espagne.jpg',
        'Japon'       => 'japon.jpg',
        'Indonésie'   => 'indonesie.jpg',
        'Tanzanie'    => 'tanzanie.jpg',
        'Chili'       => 'chili.jpg',
        'Maldives'    => 'maldives.jpg',
        'Maroc'       => 'maroc.jpg',
        'Tunisie'     => 'tunisie.jpg',
        'Grèce'       => 'grece.jpg',
        'Thaïlande'   => 'thailande.jpg',
        'Portugal'    => 'portugal.jpg',
        'Turquie'     => 'turquie.jpg',
    ];
    $uploadDir = '../public/uploads/voyages/';
    ?>

    <?php if (!empty($_GET['destination']) || !empty($_GET['categorie']) || !empty($_GET['prix_max'])): ?>
        <p style="margin-bottom:16px; color:#555;">
            <strong><?= count($voyages) ?></strong> voyage(s) trouvé(s)
        </p>
    <?php else: ?>
        <h2 class="section-title">✈️ Nos Voyages</h2>
    <?php endif; ?>

    <?php if (empty($voyages)): ?>
        <div class="empty-state">
            <div class="icon">🧳</div>
            <p>Aucun voyage trouvé. Essayez d'autres critères.</p>
            <a href="catalogue.php" class="btn btn-primary" style="margin-top:16px;">Voir tous les voyages</a>
        </div>
    <?php else: ?>
        <div class="grid-3">
            <?php foreach ($voyages as $voyage): ?>
            <div class="card">
                <div class="card-img-wrapper">
                    <?php
                    // Choix de l'image : pays > image uploadée > défaut
                    if (isset($imageParPays[$voyage->pays])) {
                        $img = $uploadDir . $imageParPays[$voyage->pays];
                    } elseif ($voyage->image !== 'voyage_default.jpg') {
                        $img = $uploadDir . $voyage->image;
                    } else {
                        $img = $uploadDir . 'voyage_default.jpg';
                    }
                    ?>
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($voyage->titre) ?>"
                         onerror="this.src='<?= $uploadDir ?>voyage_default.jpg'">

                    <?php if ($voyage->en_promo): ?>
                        <span class="badge-promo">🔥 PROMO</span>
                    <?php endif; ?>
                    <?php if (!$voyage->isDisponible()): ?>
                        <span class="badge-complet">Complet</span>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <div class="card-title"><?= htmlspecialchars($voyage->titre) ?></div>
                    <div class="card-destination">
                        📍 <?= htmlspecialchars($voyage->destination) ?>, <?= htmlspecialchars($voyage->pays) ?>
                    </div>
                    <div class="etoiles"><?= $voyage->getEtoiles() ?></div>
                    <div class="card-meta">
                        <span>📅 <?= date('d/m/Y', strtotime($voyage->date_depart)) ?></span>
                        <span>🕐 <?= $voyage->duree ?> jours</span>
                        <span>👥 <?= $voyage->places ?> places</span>
                    </div>
                    <span style="display:inline-block;background:var(--light);color:var(--secondary);padding:3px 10px;border-radius:20px;font-size:.8rem;">
                        <?= htmlspecialchars($voyage->categorie) ?>
                    </span>
                    <div class="card-price">
                        <?php if ($voyage->en_promo && $voyage->ancien_prix): ?>
                            <span class="prix-ancien"><?= number_format($voyage->ancien_prix, 2) ?> TND</span><br>
                        <?php endif; ?>
                        <span class="prix-actuel"><?= number_format($voyage->prix, 2) ?> TND</span>
                        <span style="font-size:.8rem;color:#999;">/pers.</span>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="detailVoyage.php?id=<?= $voyage->id ?>" class="btn btn-outline btn-sm" style="flex:1;">Détails</a>
                    <?php if ($voyage->isDisponible()): ?>
                        <a href="detailVoyage.php?id=<?= $voyage->id ?>#reserver" class="btn btn-primary btn-sm" style="flex:1;">Réserver ✈️</a>
                    <?php else: ?>
                        <button class="btn btn-sm" style="flex:1;background:#eee;color:#999;" disabled>Complet</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer><p>© <?= date('Y') ?> SkyVoyage — Votre agence de voyage en ligne</p></footer>
</body>
</html>