<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Voyage — SkyVoyage</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php
// session already started by editVoyageController.php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); exit;
}
$errors  = $errors  ?? [];
$message = $message ?? '';
?>

<?php require "partials/navabar.php"; ?>

<div class="container">
    <div class="form-container" style="max-width:700px;">
        <h2 class="form-title">✏️ Modifier le Voyage</h2>

        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php foreach ($errors as $e): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
        

        <form action="../controller/editVoyageController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id"     value="<?= $voyage->id ?>">

            <div class="form-group">
                <label>Titre *</label>
                <input type="text" name="titre" value="<?= htmlspecialchars($voyage->titre) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Destination *</label>
                    <input type="text" name="destination" value="<?= htmlspecialchars($voyage->destination) ?>" required>
                </div>
                <div class="form-group">
                    <label>Pays *</label>
                    <input type="text" name="pays" value="<?= htmlspecialchars($voyage->pays) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Prix (TND)</label>
                    <input type="number" step="0.01" name="prix" value="<?= $voyage->prix ?>" min="0" required>
                </div>
                <div class="form-group">
                    <label>Durée (jours)</label>
                    <input type="number" name="duree" value="<?= $voyage->duree ?>" min="1" required>
                </div>
                <div class="form-group">
                    <label>Places</label>
                    <input type="number" name="places" value="<?= $voyage->places ?>" min="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Date départ</label>
                    <input type="date" name="date_depart" value="<?= htmlspecialchars($voyage->date_depart) ?>" required>
                </div>
                <div class="form-group">
                    <label>Date retour</label>
                    <input type="date" name="date_retour" value="<?= htmlspecialchars($voyage->date_retour) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Catégorie</label>
                <select name="categorie">
                    <?php foreach (['Découverte','Aventure','Plage','Culture','Luxe','Famille','Romantique'] as $cat): ?>
                        <option value="<?= $cat ?>" <?= $voyage->categorie === $cat ? 'selected' : '' ?>>
                            <?= $cat ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($voyage->description) ?></textarea>
            </div>

            <div class="form-group">
                <label>Nouvelle image (optionnel)</label>
                <input type="file" name="image_file" accept="image/*">
                <small style="color:#888;">Laisser vide pour garder l'image actuelle</small>
            </div>

            <div style="background:var(--light);padding:16px;border-radius:10px;margin-bottom:16px;">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:600;">
                    <input type="checkbox" name="en_promo" id="promoCheck"
                           style="width:auto;" <?= $voyage->en_promo ? 'checked' : '' ?>>
                    🔥 En promotion
                </label>
                <div id="promoField" style="display:<?= $voyage->en_promo ? 'block' : 'none' ?>;margin-top:12px;">
                    <label>Ancien prix (TND)</label>
                    <input type="number" step="0.01" name="ancien_prix"
                           value="<?= $voyage->ancien_prix ?>" min="0">
                </div>
            </div>

            <div style="display:flex;gap:12px;">
                <button type="submit" class="btn btn-warning" style="flex:1;">💾 Enregistrer</button>
                <a href="adminVoyages.php" class="btn btn-outline" style="flex:1;text-align:center;">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('promoCheck').addEventListener('change', function() {
        document.getElementById('promoField').style.display = this.checked ? 'block' : 'none';
    });
</script>

<footer><p>© <?= date('Y') ?> SkyVoyage</p></footer>
</body>
</html>