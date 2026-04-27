<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Voyage — SkyVoyage</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .detail-img-wrapper {
            overflow: hidden;
            height: 420px;
            width: 100%;
            flex-shrink: 0;
        }
        .detail-img-wrapper img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block;
        }
    </style>
</head>
<body>
<?php
session_start();
require "../config/db_connect.php";
require "../model/voyage.php";
require "../model/voyagemanager.php";

$manager = new VoyageManager($db);
$id      = (int)($_GET['id'] ?? 0);
$voyage  = $manager->getById($id);

if (!$voyage) {
    header("Location: catalogue.php");
    exit;
}

$message = $_SESSION['res_message'] ?? '';
$errors  = $_SESSION['res_errors']  ?? [];
unset($_SESSION['res_message'], $_SESSION['res_errors']);

// Même tableau que catalogue et mesReservations
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

$uploadDir  = '../public/uploads/voyages/';
$uploadPath = __DIR__ . '/../public/uploads/voyages/';

// Choisir l'image
if (isset($imageParPays[$voyage->pays]) && file_exists($uploadPath . $imageParPays[$voyage->pays])) {
    $img = $uploadDir . $imageParPays[$voyage->pays];
} elseif (!empty($voyage->image) && $voyage->image !== 'voyage_default.jpg' && file_exists($uploadPath . $voyage->image)) {
    $img = $uploadDir . $voyage->image;
} else {
    $img = $uploadDir . 'voyage_default.jpg';
}
?>
<?php require "partials/navabar.php"; ?>

<div class="container" style="max-width:1000px;">

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <div class="voyage-detail" style="margin-top:30px;">

        <!-- IMAGE -->
        <div class="detail-img-wrapper">
            <img src="<?= htmlspecialchars($img) ?>"
                 alt="<?= htmlspecialchars($voyage->titre) ?>"
                 onerror="this.src='<?= $uploadDir ?>voyage_default.jpg'">
        </div>

        <!-- INFOS -->
        <div class="voyage-detail-info">
            <?php if ($voyage->en_promo): ?>
                <span class="badge badge-danger" style="margin-bottom:10px;">🔥 PROMOTION</span>
            <?php endif; ?>

            <h1 style="font-size:1.8rem;font-weight:800;color:var(--dark);margin-bottom:6px;">
                <?= htmlspecialchars($voyage->titre) ?>
            </h1>

            <p style="color:var(--primary);font-size:1rem;margin-bottom:4px;">
                📍 <?= htmlspecialchars($voyage->destination) ?>, <?= htmlspecialchars($voyage->pays) ?>
            </p>

            <div class="etoiles" style="font-size:1.2rem;margin-bottom:16px;">
                <?= $voyage->getEtoiles() ?>
                <span style="font-size:.85rem;color:#888;">(<?= $voyage->note ?>/5)</span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="label">📅 Départ</div>
                    <div class="value"><?= date('d/m/Y', strtotime($voyage->date_depart)) ?></div>
                </div>
                <div class="info-item">
                    <div class="label">🏁 Retour</div>
                    <div class="value">
                        <?= $voyage->date_retour ? date('d/m/Y', strtotime($voyage->date_retour)) : '—' ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">🕐 Durée</div>
                    <div class="value"><?= $voyage->duree ?> jours</div>
                </div>
                <div class="info-item">
                    <div class="label">👥 Places restantes</div>
                    <div class="value" style="color:<?= $voyage->places > 0 ? 'var(--success)' : 'var(--danger)' ?>">
                        <?= $voyage->places > 0 ? $voyage->places . ' disponibles' : 'Complet' ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">🏷️ Catégorie</div>
                    <div class="value"><?= htmlspecialchars($voyage->categorie) ?></div>
                </div>
            </div>

            <!-- PRIX -->
            <div style="margin:16px 0;">
                <?php if ($voyage->en_promo && $voyage->ancien_prix): ?>
                    <span class="prix-ancien" style="font-size:1rem;">
                        <?= number_format($voyage->ancien_prix, 2) ?> TND
                    </span><br>
                <?php endif; ?>
                <span class="detail-price"><?= number_format($voyage->prix, 2) ?> TND</span>
                <span style="color:#888;font-size:.9rem;"> / personne</span>
            </div>

            <!-- DESCRIPTION -->
            <?php if ($voyage->description): ?>
                <p style="color:#555;line-height:1.7;margin-bottom:20px;">
                    <?= nl2br(htmlspecialchars($voyage->description)) ?>
                </p>
            <?php endif; ?>

            <!-- FORMULAIRE RÉSERVATION -->
            <div id="reserver">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="alert alert-info">
                        <a href="login.php" style="color:var(--primary);font-weight:600;">Connectez-vous</a>
                        pour réserver ce voyage.
                    </div>
                <?php elseif ($voyage->isDisponible()): ?>
                    <form action="../controller/reservationController.php" method="POST"
                          style="background:var(--light);padding:20px;border-radius:12px;">
                        <input type="hidden" name="action"    value="reserver">
                        <input type="hidden" name="voyage_id" value="<?= $voyage->id ?>">

                        <div class="form-group">
                            <label>Nombre de personnes (max <?= $voyage->places ?>)</label>
                            <input type="number" name="nb_personnes" value="1"
                                   min="1" max="<?= $voyage->places ?>" required id="nbPers">
                        </div>

                        <p style="margin-bottom:12px;font-size:.9rem;color:#555;">
                            Total estimé :
                            <strong id="totalPrix"><?= number_format($voyage->prix, 2) ?> TND</strong>
                        </p>

                        <button type="submit" class="btn btn-primary btn-block">
                            ✈️ Confirmer la réservation
                        </button>
                    </form>

                    <script>
                        const prix = <?= $voyage->prix ?>;
                        document.getElementById('nbPers').addEventListener('input', function() {
                            document.getElementById('totalPrix').textContent =
                                (this.value * prix).toFixed(2) + ' TND';
                        });
                    </script>
                <?php else: ?>
                    <div class="alert alert-warning">Ce voyage est complet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;">
        <a href="catalogue.php" class="btn btn-outline">← Retour au catalogue</a>
    </div>
</div>

<footer><p>© <?= date('Y') ?> SkyVoyage</p></footer>
</body>
</html>