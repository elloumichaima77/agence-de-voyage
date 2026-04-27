<?php
/**
 * editVoyageController.php — Modification et suppression d'un voyage (admin)
 */
session_start();
require "../config/db_connect.php";
require "../model/voyage.php";
require "../model/voyageManager.php";
require "../model/fileUploder.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../view/login.php");
    exit;
}

$manager = new VoyageManager($db);
$action  = $_POST['action'] ?? $_GET['action'] ?? '';
$errors  = [];
$message = '';

// ── SUPPRESSION ───────────────────────────────────────────────────────────────
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($manager->delete($id)) {
        $_SESSION['admin_msg'] = "Voyage supprimé avec succès.";
    }
    header("Location: ../view/adminVoyage.php");
    exit;
}

// ── MISE À JOUR ───────────────────────────────────────────────────────────────
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = (int)($_POST['id'] ?? 0);
    $voyage     = $manager->getById($id);

    if (!$voyage) { header("Location: ../view/adminVoyage.php"); exit; }

    $voyage->titre       = trim($_POST['titre']       ?? '');
    $voyage->destination = trim($_POST['destination']  ?? '');
    $voyage->pays        = trim($_POST['pays']         ?? '');
    $voyage->prix        = (float)($_POST['prix']      ?? 0);
    $voyage->duree       = (int)($_POST['duree']       ?? 1);
    $voyage->places      = (int)($_POST['places']      ?? 0);
    $voyage->date_depart = trim($_POST['date_depart']  ?? '');
    $voyage->date_retour = trim($_POST['date_retour']  ?? '');
    $voyage->categorie   = trim($_POST['categorie']    ?? '');
    $voyage->description = trim($_POST['description']  ?? '');
    $voyage->en_promo    = isset($_POST['en_promo']);
    $voyage->ancien_prix = $voyage->en_promo ? (float)($_POST['ancien_prix'] ?? 0) : null;

    // Image optionnelle
    if (!empty($_FILES['image_file']['name'])) {
        $uploader = new FileUploader('../public/uploads/voyages/');
        $result   = $uploader->upload($_FILES['image_file']);
        if ($result) {
            $voyage->image = $result;
            $manager->updateImage($id, $result);
        } else {
            $errors = array_merge($errors, $uploader->getErrors());
        }
    }

    if (empty($errors) && $manager->update($voyage)) {
        $message = "Voyage mis à jour avec succès.";
    } else {
        $errors[] = "Erreur lors de la mise à jour.";
    }

    $voyages = $manager->getAll();
    require "../view/adminVoyage.php";
    exit;
}

// ── AFFICHAGE FORMULAIRE ÉDITION ─────────────────────────────────────────────
if (isset($_GET['id'])) {
    $voyage = $manager->getById((int)$_GET['id']);
    require "../view/editVoyage.php";
    exit;
}

header("Location: ../view/adminVoyage.php");