<?php
/**
 * authController.php — Gestion de l'authentification
 */
session_start();
require "../config/db_connect.php";
require "../model/user.php";
require "../model/usermaneger.php";

$manager = new UserManager($db);
$action  = $_POST['action'] ?? $_GET['action'] ?? '';
$errors  = [];

// Code secret requis pour créer un compte admin (à changer selon votre sécurité)
define('ADMIN_SECRET_CODE', 'SkyAdmin2025');

// ── CONNEXION ─────────────────────────────────────────────────────────────────
if ($action === 'login') {
    $email       = trim($_POST['email']       ?? '');
    $pwd         = $_POST['password']         ?? '';
    $role_choisi = trim($_POST['role_choisi'] ?? 'client');

    if (empty($email) || empty($pwd)) {
        $errors[] = "Email et mot de passe obligatoires.";
    } else {
        $user = $manager->findByEmail($email);

        if ($user && password_verify($pwd, $user->password)) {

            if ($user->role !== $role_choisi) {
                if ($role_choisi === 'admin') {
                    $errors[] = "Ce compte n'a pas les droits administrateur.";
                } else {
                    $errors[] = "Veuillez vous connecter en tant qu'Administrateur.";
                }
            } else {
                $_SESSION['user_id']   = $user->id;
                $_SESSION['user_nom']  = $user->getNomComplet();
                $_SESSION['user_role'] = $user->role;

                if ($user->role === 'admin') {
                    header("Location: ../view/adminVoyage.php");
                } else {
                    header("Location: ../view/catalogue.php");
                }
                exit;
            }

        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }

    $_SESSION['auth_errors'] = $errors;
    header("Location: ../view/login.php");
    exit;
}

// ── INSCRIPTION ───────────────────────────────────────────────────────────────
if ($action === 'register') {
    $nom        = trim($_POST['nom']        ?? '');
    $prenom     = trim($_POST['prenom']     ?? '');
    $email      = trim($_POST['email']      ?? '');
    $pwd        = $_POST['password']        ?? '';
    $pwd2       = $_POST['password2']       ?? '';
    $telephone  = trim($_POST['telephone']  ?? '');
    $role       = trim($_POST['role']       ?? 'client');
    $admin_code = trim($_POST['admin_code'] ?? '');

    // Sécurité : seuls 'client' et 'admin' sont acceptés
    if (!in_array($role, ['client', 'admin'])) {
        $role = 'client';
    }

    // Validations communes
    if (empty($nom) || empty($prenom) || empty($email) || empty($pwd)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }
    if (strlen($pwd) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }
    if ($pwd !== $pwd2) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if ($manager->emailExists($email)) {
        $errors[] = "Cet email est déjà utilisé.";
    }

    // Validation spécifique admin : vérification du code secret
    if ($role === 'admin') {
        if (empty($admin_code)) {
            $errors[] = "Le code administrateur est requis pour créer un compte admin.";
        } elseif ($admin_code !== ADMIN_SECRET_CODE) {
            $errors[] = "Code administrateur incorrect.";
        }
    }

    if (empty($errors)) {
        $user = new User(0, $nom, $prenom, $email,
                         password_hash($pwd, PASSWORD_DEFAULT), $role, $telephone);

        if ($manager->insert($user)) {
            $newUser = $manager->findByEmail($email);
            $_SESSION['user_id']   = $newUser->id;
            $_SESSION['user_nom']  = $newUser->getNomComplet();
            $_SESSION['user_role'] = $role;

            // Redirection selon le rôle
            if ($role === 'admin') {
                header("Location: ../view/adminVoyage.php");
            } else {
                header("Location: ../view/catalogue.php");
            }
            exit;
        }
        $errors[] = "Erreur lors de l'inscription, réessayez.";
    }

    $_SESSION['auth_errors'] = $errors;
    header("Location: ../view/register.php");
    exit;
}

// ── DÉCONNEXION ───────────────────────────────────────────────────────────────
if ($action === 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

header("Location: ../view/login.php");
exit;