<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — SkyVoyage</title>
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
        .role-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
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
        .submit-client { background: var(--primary) !important; }
        .submit-admin  { background: var(--accent)  !important; }
    </style>
</head>
<body>
<?php
session_start();
$errors = $_SESSION['auth_errors'] ?? [];
unset($_SESSION['auth_errors']);
?>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">✈️</div>
        <h2 class="auth-title">Bienvenue sur SkyVoyage</h2>

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
            <input type="hidden" name="action" value="login">
            <!-- Le rôle choisi est envoyé avec le formulaire -->
            <input type="hidden" name="role_choisi" id="role_choisi" value="client">

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email"
                       placeholder="exemple@mail.com" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" id="submit-btn"
                    class="btn btn-primary btn-block submit-client"
                    style="margin-top:8px;">
                🔐 Se connecter en tant que <span id="role-label">Client</span>
            </button>
        </form>

        <p style="text-align:center; margin-top:20px; font-size:.9rem; color:#666;">
            Pas encore de compte ?
            <a href="register.php" style="color:var(--primary); font-weight:600;">Créer un compte</a>
        </p>

        <hr style="margin:20px 0; border-color:#eee;">
        <p style="text-align:center; font-size:.82rem; color:#aaa;">
            Demo admin : admin@skyvoyage.tn / admin123
        </p>
    </div>
</div>

<script>
function selectRole(role) {
    const btnClient = document.getElementById('btn-client');
    const btnAdmin  = document.getElementById('btn-admin');
    const submitBtn = document.getElementById('submit-btn');
    const roleLabel = document.getElementById('role-label');
    const roleInput = document.getElementById('role_choisi');

    // Reset les deux boutons
    btnClient.className = 'role-btn';
    btnAdmin.className  = 'role-btn';

    if (role === 'client') {
        btnClient.classList.add('active-client');
        submitBtn.className = 'btn btn-block submit-client';
        submitBtn.style.color = 'white';
        roleLabel.textContent = 'Client';
        roleInput.value = 'client';
    } else {
        btnAdmin.classList.add('active-admin');
        submitBtn.className = 'btn btn-block submit-admin';
        submitBtn.style.color = 'white';
        roleLabel.textContent = 'Administrateur';
        roleInput.value = 'admin';
    }
}
</script>
</body>
</html>