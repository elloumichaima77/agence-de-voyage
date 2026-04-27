<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations — SkyVoyage</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .card-img-wrapper {
            position: relative;
            overflow: hidden !important;
            width: 100% !important;
            height: 220px !important;
            flex-shrink: 0;
            background: #e0e0e0;
        }
        .card-img-wrapper img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block;
        }
        .statut-badge {
            position: absolute;
            top: 12px; right: 12px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .82rem;
            font-weight: 700;
        }
        .statut-en_attente { background: #fff3cd; color: #856404; }
        .statut-confirmee  { background: #d4edda; color: #155724; }
        .statut-annulee    { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit;
}
require "../config/db_connect.php";
require "../model/Reservation.php";
require "../model/ReservationManager.php";

$manager      = new ReservationManager($db);
$reservations = $manager->getByUser($_SESSION['user_id']);
// getByUser retourne FETCH_ASSOC donc $res est un tableau

$message = $_SESSION['res_message'] ?? '';
$errors  = $_SESSION['res_errors']  ?? [];
unset($_SESSION['res_message'], $_SESSION['res_errors']);

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
$uploadPath = __DIR__ . '/../public/uploads/voyages/'; // chemin réel pour file_exists
?>
<?php require "partials/navabar.php"; ?>

<div class="container">
    <h2 class="section-title">🎫 Mes Réservations</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <?php if (empty($reservations)): ?>
        <div class="empty-state">
            <div class="icon">🧳</div>
            <p>Vous n'avez aucune réservation pour le moment.</p>
            <a href="catalogue.php" class="btn btn-primary" style="margin-top:16px;">Explorer les voyages ✈️</a>
        </div>
    <?php else: ?>
        <div class="grid-3">
            <?php foreach ($reservations as $res): ?>
            <?php
            // Choisir l'image
            $pays  = $res['pays'] ?? '';
            $image = $res['image'] ?? 'voyage_default.jpg';

            if (isset($imageParPays[$pays]) && file_exists($uploadPath . $imageParPays[$pays])) {
                $img = $uploadDir . $imageParPays[$pays];
            } elseif (!empty($image) && $image !== 'voyage_default.jpg' && file_exists($uploadPath . $image)) {
                $img = $uploadDir . $image;
            } else {
                $img = $uploadDir . 'voyage_default.jpg';
            }

            // Statut badge
            $statut = $res['statut'] ?? 'en_attente';
            $statutLabel = match($statut) {
                'confirmee'  => '✅ Confirmée',
                'annulee'    => '✕ Annulée',
                default      => '⏳ En attente',
            };
            ?>
            <div class="card">

                <!-- IMAGE -->
                <div class="card-img-wrapper">
                    <img src="<?= htmlspecialchars($img) ?>"
                         alt="<?= htmlspecialchars($res['voyage_titre']) ?>"
                         onerror="this.style.display='none'">
                    <span class="statut-badge statut-<?= $statut ?>">
                        <?= $statutLabel ?>
                    </span>
                </div>

                <!-- INFOS -->
                <div class="card-body">
                    <div class="card-title"><?= htmlspecialchars($res['voyage_titre']) ?></div>
                    <div class="card-destination">
                        📍 <?= htmlspecialchars($res['destination']) ?>
                        <?= $pays ? ', ' . htmlspecialchars($pays) : '' ?>
                    </div>

                    <div style="font-size:.88rem;color:#666;display:flex;flex-direction:column;gap:6px;margin-top:8px;">
                        <span>📅 Départ :
                            <strong>
                                <?= !empty($res['date_depart']) ? date('d/m/Y', strtotime($res['date_depart'])) : '—' ?>
                            </strong>
                        </span>
                        <span>👥 Personnes : <strong><?= $res['nb_personnes'] ?></strong></span>
                        <span>💰 Total :
                            <strong style="color:var(--accent);">
                                <?= number_format($res['prix_total'], 2) ?> TND
                            </strong>
                        </span>
                        <span>🗓️ Réservé le : <?= date('d/m/Y', strtotime($res['date_reservation'])) ?></span>
                    </div>
                </div>

                <!-- BOUTON -->
                <?php if ($statut === 'en_attente'): ?>
                <div class="card-footer">
                    <a href="../controller/reservationController.php?action=annuler&id=<?= $res['id'] ?>"
                       class="btn btn-danger btn-sm btn-block"
                       onclick="return confirm('Annuler cette réservation ?')">
                        ✕ Annuler la réservation
                    </a>
                </div>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer><p>© <?= date('Y') ?> SkyVoyage</p></footer>
</body>
</html>