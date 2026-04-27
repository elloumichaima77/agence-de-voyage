<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Voyage — SkyVoyage</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); exit;
}
$errors  = $errors  ?? [];
$message = $message ?? '';
?>
<?php require "partials/navabar.php"; ?>

<div class="container">
    <div class="form-container" style="max-width:700px;">
        <h2 class="form-title">✈️ Ajouter un Voyage</h2>

        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php foreach ($errors as $e): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>

        <form action="../controller/addVoyageController.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Titre du voyage *</label>
                <input type="text" name="titre" placeholder="ex: Escapade Romantique à Paris" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Destination *</label>
                    <input type="text" name="destination" placeholder="Paris" required>
                </div>
                <div class="form-group">
                    <label>Pays *</label>
                    <input type="text" name="pays" placeholder="France" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Prix (TND) *</label>
                    <input type="number" step="0.01" name="prix" placeholder="1200.00" min="0" required>
                </div>
                <div class="form-group">
                    <label>Durée (jours) *</label>
                    <input type="number" name="duree" placeholder="7" min="1" required>
                </div>
                <div class="form-group">
                    <label>Places disponibles *</label>
                    <input type="number" name="places" placeholder="20" min="0" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Date de départ *</label>
                    <input type="date" name="date_depart" required>
                </div>
                <div class="form-group">
                    <label>Date de retour</label>
                    <input type="date" name="date_retour">
                </div>
            </div>

            <div class="form-group">
                <label>Catégorie</label>
                <select name="categorie">
                    <option value="Découverte">Découverte</option>
                    <option value="Aventure">Aventure</option>
                    <option value="Plage">Plage</option>
                    <option value="Culture">Culture</option>
                    <option value="Luxe">Luxe</option>
                    <option value="Famille">Famille</option>
                    <option value="Romantique">Romantique</option>
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"
                          placeholder="Décrivez ce voyage en quelques lignes..."></textarea>
            </div>

            <div class="form-group">
                <label>Image du voyage</label>
                <input type="file" name="image_file" accept="image/*">
                <small style="color:#888;">JPG, PNG, WEBP — Max 3 Mo</small>
            </div>

            <!-- PROMO -->
            <div style="background:var(--light);padding:16px;border-radius:10px;margin-bottom:16px;">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:600;">
                    <input type="checkbox" name="en_promo" id="promoCheck" style="width:auto;">
                    🔥 Ce voyage est en promotion
                </label>
                <div id="promoField" style="display:none;margin-top:12px;">
                    <label>Ancien prix (TND)</label>
                    <input type="number" step="0.01" name="ancien_prix" placeholder="1800.00" min="0">
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="submit" class="btn btn-success" style="flex:1;">
                    ✅ Ajouter le voyage
                </button>
                <a href="adminVoyage.php" class="btn btn-outline" style="flex:1;text-align:center;">
                    Annuler
                </a>
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