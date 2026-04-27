<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Voyages — SkyVoyage</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); exit;
}
require "../config/db_connect.php";
require "../model/Voyage.php";
require "../model/VoyageManager.php";

$manager = new VoyageManager($db);
$voyages = isset($voyages) ? $voyages : $manager->getAll();

$adminMsg = $_SESSION['admin_msg'] ?? '';
unset($_SESSION['admin_msg']);
?>
<?php require "partials/navabar.php"; ?>

<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <h2 class="section-title" style="margin:0;">⚙️ Gestion des Voyages</h2>
        <div style="display:flex;gap:10px;">
            <a href="adminReservations.php" class="btn btn-outline">🎫 Réservations</a>
            <a href="addVoyage.php"         class="btn btn-success">➕ Ajouter un voyage</a>
        </div>
    </div>

    <?php if ($adminMsg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($adminMsg) ?></div>
    <?php endif; ?>

    <!-- Statistiques rapides -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;margin-bottom:28px;">
        <?php
        $total   = count($voyages);
        $promos  = count(array_filter($voyages, fn($v) => $v->en_promo));
        $complets= count(array_filter($voyages, fn($v) => $v->places == 0));
        $cats    = count($manager->getAllCategories());
        ?>
        <?php foreach ([
            ['🗺️','Voyages',       $total,   'var(--primary)'],
            ['🔥','Promotions',    $promos,  'var(--danger)'],
            ['🔒','Complets',      $complets,'var(--warning)'],
            ['🏷️','Catégories',   $cats,    'var(--success)'],
        ] as [$icon, $label, $val, $color]): ?>
        <div style="background:var(--white);border-radius:12px;padding:18px;box-shadow:var(--shadow);text-align:center;">
            <div style="font-size:1.8rem;"><?= $icon ?></div>
            <div style="font-size:1.6rem;font-weight:800;color:<?= $color ?>;"><?= $val ?></div>
            <div style="font-size:.82rem;color:#888;"><?= $label ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Tableau -->
    <?php
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
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Destination</th>
                    <th>Catégorie</th>
                    <th>Prix (TND)</th>
                    <th>Places</th>
                    <th>Départ</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($voyages)): ?>
                    <tr><td colspan="9" style="text-align:center;color:#aaa;padding:30px;">
                        Aucun voyage enregistré.
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($voyages as $v): ?>
                    <tr>
                        <td>
                            <?php
                            if (isset($imageParPays[$v->pays])) {
                                $imgSrc = $uploadDir . $imageParPays[$v->pays];
                            } elseif ($v->image !== 'voyage_default.jpg') {
                                $imgSrc = $uploadDir . $v->image;
                            } else {
                                $imgSrc = $uploadDir . 'voyage_default.jpg';
                            }
                        ?>
                        <img src="<?= htmlspecialchars($imgSrc) ?>"
                                 alt="" width="60" height="45"
                                 style="object-fit:cover;border-radius:6px;"
                                 onerror="this.src='../public/uploads/voyages/voyage_default.jpg'">
                        </td>
                        <td style="font-weight:600;"><?= htmlspecialchars($v->titre) ?></td>
                        <td>📍 <?= htmlspecialchars($v->destination) ?></td>
                        <td>
                            <span style="background:var(--light);padding:3px 8px;border-radius:20px;font-size:.8rem;">
                                <?= htmlspecialchars($v->categorie) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($v->en_promo): ?>
                                <span class="badge badge-danger">PROMO</span><br>
                            <?php endif; ?>
                            <strong><?= number_format($v->prix, 2) ?></strong>
                        </td>
                        <td style="color:<?= $v->places > 0 ? 'var(--success)' : 'var(--danger)' ?>;font-weight:600;">
                            <?= $v->places ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($v->date_depart)) ?></td>
                        <td>
                            <?php if ($v->places == 0): ?>
                                <span class="badge badge-danger">Complet</span>
                            <?php elseif ($v->places <= 5): ?>
                                <span class="badge badge-warning">Presque complet</span>
                            <?php else: ?>
                                <span class="badge badge-success">Disponible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="detailVoyage.php?id=<?= $v->id ?>"
                                   class="btn btn-outline btn-sm">👁️</a>
                                <a href="../controller/editVoyageController.php?id=<?= $v->id ?>"
                                   class="btn btn-warning btn-sm">✏️</a>
                                <a href="../controller/editVoyageController.php?action=delete&id=<?= $v->id ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Supprimer ce voyage ?')">🗑️</a>
                            </div>
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