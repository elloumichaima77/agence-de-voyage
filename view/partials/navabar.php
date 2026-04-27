<?php
// view/partials/navbar.php — Barre de navigation commune
if (session_status() === PHP_SESSION_NONE) session_start();
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$isLogged = isset($_SESSION['user_id']);
?>
<nav class="navbar">
    <a href="../index.php" class="navbar-brand">
        <span>✈️</span> SkyVoyage
    </a>
    <ul class="navbar-nav">
        <li><a href="../view/catalogue.php">🗺️ Destinations</a></li>
        <?php if ($isLogged): ?>
            <li><a href="../view/mesReservations.php">🎫 Mes Réservations</a></li>
            <?php if ($isAdmin): ?>
                <li><a href="../view/adminVoyage.php">⚙️ Admin</a></li>
                <li><a href="../view/addVoyage.php">➕ Ajouter</a></li>
            <?php endif; ?>
            <li><span style="color:rgba(255,255,255,.7);padding:0 8px;">👤 <?= htmlspecialchars($_SESSION['user_nom']) ?></span></li>
            <li><a href="../controller/authController.php?action=logout" class="btn-logout">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="../view/login.php">Connexion</a></li>
            <li><a href="../view/register.php">Inscription</a></li>
        <?php endif; ?>
    </ul>
</nav>
