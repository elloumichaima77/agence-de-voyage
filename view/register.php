<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — SkyVoyage</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .role-selector {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }
        .role-btn {
            flex: 1;
            padding: 14px 10px;
            border: 2px solid var(--border);
            border-radius: 10px;
            background: var(--white);
            cursor: pointer;
            text-align: center;
            transition: all .2s;
            font-size: .95rem;
            font-weight: 600;
            color: #555;
        }
        .role-btn:hover { border-color: var(--primary); color: var(--primary); }
        .role-btn.active-client {
            border-color: var(--primary);
            background: #e8f4fd;
            color: var(--primary);
        }
        .role-btn.active-admin {
            border-color: var(--accent);
            background: #fff3e0;
            color: var(--accent);
        }
        .role-btn .role-icon { font-size: 1.6rem; display: block; margin-bottom: 4px; }

        /* Champ code admin masqué par défaut */
        #adminCodeField { display: none; margin-top: 16px; }

        .submit-client { background: var(--primary) !important; color: #fff !important; }
        .submit-admin  { background: var(--accent)  !important; color: #fff !important; }
    </style>
</head>
<body>
<?php
session_start();
$errors = $_SESSION['auth_errors'] ?? [];
unset($_SESSION['auth_errors']);
?>
<div class="auth-page">
    <div class="auth-card" style="max-width:500px;">
        <div class="auth-logo">🌍</div>
        <h2 class="auth-title">Créer un compte</h2>

        <?php foreach ($errors as $err): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>

        <!-- Sélecteur de rôle -->
        <div class="role-selector">
            <div class="role-btn active-client" id="btn-client" onclick="selectRole('client')">
                <span class="role-icon">🧳</span>
                Client
            </div>
            <div class="role-btn" id="btn-admin" onclick="selectRole('admin')">
                <span class="role-icon">⚙️</span>
                Administrateur
            </div>
        </div>

        <form action="../controller/authController.php" method="POST">
            <input type="hidden" name="action" value="register">
            <input type="hidden" name="role"   id="role_input" value="client">

            <div class="form-row">
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" placeholder="Mohamed" required>
                </div>
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" placeholder="Ben Ali" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@exemple.com" required>
            </div>

            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" placeholder="+216 XX XXX XXX">
            </div>

            <div class="form-group">
                <label>Mot de passe <small style="color:#aaa;">(min. 6 caractères)</small></label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="password2" required>
            </div>

            <!-- Code secret admin (visible uniquement si rôle admin sélectionné) -->
            <div id="adminCodeField">
                <div class="form-group">
                    <label>🔑 Code administrateur secret</label>
                    <input type="password" name="admin_code"
                           placeholder="Entrez le code secret admin"
                           id="admin_code_input">
                    <small style="color:#888;">Requis pour créer un compte administrateur.(SkyAdmin2025 par défaut)</small>
                </div>
            </div>

            <button type="submit" id="submit-btn"
                    class="btn btn-block submit-client" style="margin-top:8px;">
                ✅ Créer mon compte <span id="role-label">Client</span>
            </button>
        </form>

        <p style="text-align:center; margin-top:20px; font-size:.9rem; color:#666;">
            Déjà inscrit ?
            <a href="login.php" style="color:var(--primary); font-weight:600;">Se connecter</a>
        </p>
    </div>
</div>

<script>
function selectRole(role) {
    const btnClient   = document.getElementById('btn-client');
    const btnAdmin    = document.getElementById('btn-admin');
    const submitBtn   = document.getElementById('submit-btn');
    const roleLabel   = document.getElementById('role-label');
    const roleInput   = document.getElementById('role_input');
    const adminField  = document.getElementById('adminCodeField');
    const adminInput  = document.getElementById('admin_code_input');

    btnClient.className = 'role-btn';
    btnAdmin.className  = 'role-btn';

    if (role === 'client') {
        btnClient.classList.add('active-client');
        submitBtn.className   = 'btn btn-block submit-client';
        roleLabel.textContent = 'Client';
        roleInput.value       = 'client';
        adminField.style.display = 'none';
        adminInput.required   = false;
    } else {
        btnAdmin.classList.add('active-admin');
        submitBtn.className   = 'btn btn-block submit-admin';
        roleLabel.textContent = 'Administrateur';
        roleInput.value       = 'admin';
        adminField.style.display = 'block';
        adminInput.required   = true;
    }
}
</script>
</body>
</html>