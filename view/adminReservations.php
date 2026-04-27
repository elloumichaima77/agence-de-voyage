<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Réservations — SkyVoyage</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); exit;
}
require "../config/db_connect.php";
require "../model/reservation.php";
require "../model/reservationmanager.php";

$manager      = new ReservationManager($db);
$reservations = $manager->getAll();

// Filtrage par statut
$filtreStatut = $_GET['statut'] ?? '';
if ($filtreStatut) {
    $reservations = array_filter($reservations, fn($r) => $r->statut === $filtreStatut);
}
?>
<?php require "partials/navabar.php"; ?>

<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <h2 class="section-title" style="margin:0;">🎫 Gestion des Réservations</h2>
        <a href="adminVoyage.php" class="btn btn-outline">← Voyages</a>
    </div>

    <!-- Filtre statut -->
    <div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
        <?php foreach ([
            [''          , '📋 Toutes'       , ''],
            ['en_attente', '⏳ En attente'   , 'var(--warning)'],
            ['confirmee' , '✅ Confirmées'   , 'var(--success)'],
            ['annulee'   , '✕ Annulées'     , 'var(--danger)'],
        ] as [$val, $label, $color]): ?>
            <a href="adminReservations.php<?= $val ? '?statut='.$val : '' ?>"
               class="btn btn-sm <?= $filtreStatut === $val ? 'btn-primary' : 'btn-outline' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Voyage</th>
                    <th>Destination</th>
                    <th>Pers.</th>
                    <th>Total (TND)</th>
                    <th>Date rés.</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reservations)): ?>
                    <tr><td colspan="9" style="text-align:center;color:#aaa;padding:30px;">
                        Aucune réservation trouvée.
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($reservations as $res): ?>
                    <tr>
                        <td style="color:#aaa;font-size:.85rem;">#<?= $res->id ?></td>
                        <td>
                            <div style="font-weight:600;">
                                <?= htmlspecialchars($res->user_prenom . ' ' . $res->user_nom) ?>
                            </div>
                            <div style="font-size:.82rem;color:#888;">
                                <?= htmlspecialchars($res->user_email) ?>
                            </div>
                        </td>
                        <td style="font-weight:600;"><?= htmlspecialchars($res->voyage_titre) ?></td>
                        <td>📍 <?= htmlspecialchars($res->destination) ?></td>
                        <td style="text-align:center;"><?= $res->nb_personnes ?></td>
                        <td><strong><?= number_format($res->prix_total, 2) ?></strong></td>
                        <td style="font-size:.85rem;">
                            <?= date('d/m/Y H:i', strtotime($res->date_reservation)) ?>
                        </td>
                        <td><?= $res->getBadgeStatut() ?></td>
                        <td>
                            <!-- Changement de statut -->
                            <form action="../controller/reservationController.php"
                                  method="POST" style="display:flex;gap:4px;align-items:center;">
                                <input type="hidden" name="action" value="statut">
                                <input type="hidden" name="id"     value="<?= $res->id ?>">
                                <select name="statut" style="padding:4px 6px;border-radius:6px;border:1px solid var(--border);font-size:.82rem;">
                                    <?php foreach (Reservation::STATUTS as $s): ?>
                                        <option value="<?= $s ?>" <?= $res->statut === $s ? 'selected' : '' ?>>
                                            <?= ucfirst(str_replace('_', ' ', $s)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">OK</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<footer><p>© <?= date('Y') ?> SkyVoyage</p></footer>
</body>
</html>